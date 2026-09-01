using System;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Xml;
using JHSoft.Base;
using JHSoft.ResourceDetect;

namespace JHSoft.Web.ProjectManage.TaskManage;

public class AddTask : Page
{
	protected HtmlForm Form1;

	private XmlDocument xmlDocument = new XmlDocument();

	public string XmlStr;

	protected void Page_Load(object sender, EventArgs e)
	{
		xmlDocument.Load(((Page)this).Request.InputStream);
		XmlNode xmlNode = xmlDocument.SelectSingleNode("//root//Page//PageName");
		string innerText = xmlNode.InnerText;
		XmlStr = xmlDocument.DocumentElement.OuterXml;
		Xml(innerText);
	}

	private void Xml(string strPageName)
	{
		string text = string.Empty;
		_ = string.Empty;
		_ = string.Empty;
		_ = string.Empty;
		switch (strPageName)
		{
		case "TaskDetect":
		{
			string innerText = xmlDocument.SelectSingleNode("//root//TaskExecutorID").InnerText;
			string innerText2 = xmlDocument.SelectSingleNode("//root//StartTime").InnerText;
			string innerText3 = xmlDocument.SelectSingleNode("//root//EndTime").InnerText;
			DetectCls detectCls = new DetectCls();
			text = (detectCls.DetectResource("Calendar", "1", innerText, innerText2, innerText3) ? "0" : "1");
			break;
		}
		}
		((Control)this).Page.Response.Write(text);
		((Page)this).Response.End();
	}

	protected override void OnInit(EventArgs e)
	{
		InitializeComponent();
		((Page)this).OnInit(e);
	}

	private void InitializeComponent()
	{
	}
}
