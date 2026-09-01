# 金和 OA C6 XML 外部实体注入漏洞分析

## 漏洞概述

金和 OA C6 的 ProjectManage 模块存在多处 XML 解析入口。相关页面直接将 HTTP 请求体传入 `System.Xml.XmlDocument.Load(Stream)`，解析前没有显式禁止 DTD，也没有将 `XmlResolver` 置为 `null`。

在允许 DTD 和外部实体解析的运行环境中，攻击者可以构造包含外部实体声明的 XML 请求，诱使服务器访问外部或内网资源。结合应用池账户权限及网络条件，该问题还可能被用于本地文件读取、盲 XXE 数据外带或拒绝服务。

## 漏洞信息

| 项目 | 内容 |
| --- | --- |
| 漏洞名称 | 金和 OA C6 XML 外部实体注入漏洞 |
| 漏洞类型 | XML External Entity Injection（XXE） |
| CWE | CWE-611：Improper Restriction of XML External Entity Reference |
| 受影响模块 | ProjectManage XML 请求处理功能 |
| 潜在影响 | SSRF、本地文件读取、信息外带、拒绝服务 |
| 远程代码执行 | 现有代码不支持这一结论 |

## 漏洞入口

问题出现在以下三个页面类中：

| 页面功能 | 对应代码文件 |
| --- | --- |
| ProjectManage XML 处理 | [XmlHttp.cs](src/JHSoft/Web/ProjectManage/ProjectManage/XmlHttp.cs) |
| TaskManage 任务处理 | [AddTask.cs](src/JHSoft/Web/ProjectManage/TaskManage/AddTask.cs) |
| UserControl 任务角色处理 | [TaskRoleDesgin.cs](src/JHSoft/Web/ProjectManage/UserControl/TaskRoleDesgin.cs) |

`XmlHttp` 对应的页面路径为：

```text
/c6/Jhsoft.Web.projectmanage/ProjectManage/XmlHttp.aspx/
```

部分资料会将 `SetXmlHttp` 写成服务端方法名，但在 `TaskRoleDesgin` 类中没有发现该方法。若实际请求路径中出现 `SetXmlHttp`，更可能与 IIS/ASP.NET 的页面映射或 PATH_INFO 有关，不能直接当作代码中已经确认的方法名。

## 漏洞分析

三个页面的处理方式基本相同：

```text
HTTP Request.InputStream
    ↓
XmlDocument.Load(Stream)
    ↓
XML DOM
    ↓
XPath 查询及业务处理
```

### ProjectManage/XmlHttp

```csharp
private XmlDocument xmlDocument = new XmlDocument();

protected void Page_Load(object sender, EventArgs e)
{
    xmlDocument.Load(((Page)this).Request.InputStream);
    XmlNode xmlNode =
        xmlDocument.SelectSingleNode("//root//Page//PageName");
    string pageName = xmlNode.InnerText;
    XmlStr = xmlDocument.DocumentElement.OuterXml;
    Xml(pageName);
}
```

请求体在 `Page_Load` 中直接进入 `XmlDocument.Load`。XML 文档构造完成后，程序才读取 `PageName` 并进入业务分支。`XmlHttp` 后续会根据该字段执行资源删除、项目暂停、项目结束、资源新增、合同删除或聊天内容写入等操作。

### TaskManage/AddTask

```csharp
private XmlDocument xmlDocument = new XmlDocument();

protected void Page_Load(object sender, EventArgs e)
{
    xmlDocument.Load(((Page)this).Request.InputStream);
    XmlNode xmlNode =
        xmlDocument.SelectSingleNode("//root//Page//PageName");
    string pageName = xmlNode.InnerText;
    XmlStr = xmlDocument.DocumentElement.OuterXml;
    Xml(pageName);
}
```

该页面还会从 XML 中读取任务执行人、开始时间和结束时间。攻击者提交的 XML 会先被完整解析，再进入这些字段的读取过程。

### UserControl/TaskRoleDesgin

```csharp
private XmlDocument xmlDocument = new XmlDocument();

protected void Page_Load(object sender, EventArgs e)
{
    xmlDocument.Load(((Page)this).Request.InputStream);
    XmlNode xmlNode =
        xmlDocument.SelectSingleNode("//root//Page//PageName");
    _ = xmlNode.InnerText;
    XmlStr = xmlDocument.DocumentElement.OuterXml;
}
```

这里同样没有为 `XmlDocument` 设置安全解析选项。实体引用若被解析，其内容会进入 XML DOM，并可能出现在 `PageName` 或 `OuterXml` 等后续处理结果中。

## 形成原因

问题集中在 XML 解析边界。三个页面都缺少以下配置：

- `XmlReaderSettings.DtdProcessing = DtdProcessing.Prohibit`；
- `XmlReaderSettings.XmlResolver = null`；
- `XmlDocument.XmlResolver = null`；
- XML 文档大小和实体扩展限制。

`PageName`、`ID`、`projectid` 等字段校验发生在 XML 解析之后，`switch` 分支、Session 状态和业务层参数校验也无法阻止解析器处理 DTD 或外部实体。

`XmlDocument` 的默认解析行为会受到 .NET Framework 版本、应用信任级别及兼容性配置影响。微软文档说明，`XmlResolver` 负责定位外部 DTD、实体和其他资源；将其设置为 `null` 可以阻止外部资源解析。具体行为可参考 [`XmlDocument.XmlResolver`](https://learn.microsoft.com/en-us/dotnet/api/system.xml.xmldocument.xmlresolver?view=netframework-4.8.1) 和 [Resolving External Resources](https://learn.microsoft.com/en-us/dotnet/standard/data/xml/resolving-external-resources)。

因此，这段代码是否能在某一部署中完成外部请求或文件读取，仍与目标 CLR、应用配置、运行账户权限和网络策略有关。源码层面的问题在于应用没有主动关闭这些能力，而是依赖运行环境的默认设置。

## 漏洞影响

### SSRF

如果服务器允许解析外部 HTTP 实体，攻击者可以让应用访问指定地址，包括仅对服务器可见的内网服务。可访问范围还会受到出网策略、代理配置和目标网络 ACL 的限制。

### 本地文件读取

外部实体也可以引用本地 `file` 资源。实际读取结果取决于应用池账户权限、文件内容及解析器行为。代码没有直接把 `XmlStr` 返回给客户端，因此从响应中直接取得文件内容并不是必然结果，盲 XXE 外带还需要额外的实体组合和可用出网通道。

### 拒绝服务

代码没有设置文档大小或实体扩展上限。运行环境若接受 DTD 和实体扩展，恶意 XML 可能消耗大量 CPU 或内存。除此之外，未经限制的大型 XML 请求本身也会增加服务端资源压力。

当前分析不支持远程代码执行结论，不应将 XXE、文件读取与 RCE 混为一谈。

## 修复建议

建议统一封装安全 XML 读取逻辑，禁止各页面直接调用 `XmlDocument.Load(Stream)`。业务不需要 DTD 时，应明确禁止 DTD、关闭外部资源解析，并限制 XML 文档大小：

```csharp
var settings = new XmlReaderSettings
{
    DtdProcessing = DtdProcessing.Prohibit,
    XmlResolver = null,
    MaxCharactersInDocument = 100_000
};

using (XmlReader reader = XmlReader.Create(Request.InputStream, settings))
{
    var document = new XmlDocument
    {
        XmlResolver = null
    };

    document.Load(reader);
}
```

`MaxCharactersInDocument` 应按正常业务请求大小设置，并在 IIS/ASP.NET 层同步限制请求体。修复范围也应覆盖其他使用 `XmlDocument.Load`、`LoadXml` 或不安全 `XmlReader` 配置处理外部输入的页面。

修复完成后，可使用正常业务 XML 和含 `DOCTYPE` 的测试请求做回归验证。正常请求应保持兼容，包含 DTD、外部 HTTP 实体或本地文件实体的请求应被稳定拒绝，且不能产生任何外部网络访问。

## 总结

金和 OA C6 ProjectManage 模块的多个页面直接使用 `XmlDocument.Load(Stream)` 解析 HTTP 请求体，代码中没有显式禁止 DTD 或外部实体解析。解析动作位于 XPath 查询和业务分支之前，后续字段校验不能消除这一风险。

在允许外部实体解析的运行环境中，该问题可能造成 SSRF、本地文件读取、盲 XXE 数据外带或拒绝服务。修复时应从解析器入口关闭 DTD 和外部资源访问，并为 XML 请求设置合理的资源限制。
