using System;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Xml;
using JHSoft.Base;

namespace JHSoft.Web.ProjectManage.UserControl;

public class TaskRoleDesgin : Page
{
	private XmlDocument xmlDocument = new XmlDocument();

	public string XmlStr;

	protected HtmlForm Form1;

	protected void Page_Load(object sender, EventArgs e)
	{
		xmlDocument.Load(((Page)this).Request.InputStream);
		XmlNode xmlNode = xmlDocument.SelectSingleNode("//root//Page//PageName");
		_ = xmlNode.InnerText;
		XmlStr = xmlDocument.DocumentElement.OuterXml;
	}

	protected override void OnInit(EventArgs e)
	{
		InitializeComponent();
		((Page)this).OnInit(e);
	}

	private void InitializeComponent()
	{
	}

	private string GetResult(string strUserID, string strTaskID, string strProjectRole)
	{
		string result = "0|0|0|0|0";
		switch (strProjectRole)
		{
		default:
			_ = strProjectRole == "3";
			break;
		case null:
		case "1":
		case "2":
			break;
		}
		return result;
	}
}
