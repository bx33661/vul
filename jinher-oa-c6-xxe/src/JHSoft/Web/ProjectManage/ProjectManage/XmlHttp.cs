using System;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Xml;
using JHSoft.Base;
using JHSoft.ProjectManage;

namespace JHSoft.Web.ProjectManage.ProjectManage;

public class XmlHttp : Page
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

	protected override void OnInit(EventArgs e)
	{
		InitializeComponent();
		((Page)this).OnInit(e);
	}

	private void InitializeComponent()
	{
	}

	private void Xml(string strPageName)
	{
		string text = string.Empty;
		string userCode = ((Page)this).Session["UserCode"].ToString();
		string userName = ((Page)this).Session["UserName"].ToString();
		_ = string.Empty;
		string empty = string.Empty;
		switch (strPageName.ToLower())
		{
		case "resourcedel":
			empty = xmlDocument.SelectSingleNode("//root//ID").InnerText;
			text = new Project().ResourceDel(int.Parse(empty));
			break;
		case "projectpause":
			empty = xmlDocument.SelectSingleNode("//root//ID").InnerText;
			text = new Project().ProjectPause(int.Parse(empty));
			break;
		case "projectpauseexit":
			empty = xmlDocument.SelectSingleNode("//root//ID").InnerText;
			text = new Project().ProjectPauseExit(int.Parse(empty));
			break;
		case "projectend":
		{
			empty = xmlDocument.SelectSingleNode("//root//ID").InnerText;
			string innerText8 = xmlDocument.SelectSingleNode("//root//projectid").InnerText;
			text = new Project().Projectend(int.Parse(empty), int.Parse(innerText8));
			break;
		}
		case "selectend":
			empty = xmlDocument.SelectSingleNode("//root//projectid").InnerText;
			text = new Project().Selectend(int.Parse(empty));
			break;
		case "resourceadd":
		{
			empty = xmlDocument.SelectSingleNode("//root//projectid").InnerText;
			string innerText5 = xmlDocument.SelectSingleNode("//root//ResID").InnerText;
			string innerText6 = xmlDocument.SelectSingleNode("//root//Quantity").InnerText;
			string innerText7 = xmlDocument.SelectSingleNode("//root//name").InnerText;
			text = new Project().ResourceAdd(int.Parse(empty), int.Parse(innerText5), int.Parse(innerText6), innerText7);
			break;
		}
		case "compactdel":
			empty = xmlDocument.SelectSingleNode("//root//ID").InnerText;
			text = new Project().CompactDel(int.Parse(empty));
			break;
		case "chatinsert":
		{
			string innerText = xmlDocument.SelectSingleNode("//root//projectid").InnerText;
			string innerText2 = xmlDocument.SelectSingleNode("//root//title").InnerText;
			string innerText3 = xmlDocument.SelectSingleNode("//root//content").InnerText;
			string innerText4 = xmlDocument.SelectSingleNode("//root//sort").InnerText;
			text = new Project().ChatInsert(innerText, userCode, userName, innerText2, innerText3, innerText4);
			break;
		}
		}
		((Control)this).Page.Response.Write(text);
		((Page)this).Response.End();
	}
}
