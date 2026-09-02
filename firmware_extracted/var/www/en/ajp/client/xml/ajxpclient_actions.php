<?php
    $ajxpclient_actions["switch_root_dir"]["XML"] = '<action name="switch_root_dir">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["switch_root_dir"]["callback"] = 'switchAction';
    
    $ajxpclient_actions["get_template"]["XML"] = '<action name="get_template">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["get_template"]["callback"] = 'switchAction';
    
    $ajxpclient_actions["get_i18n_messages"]["XML"] = '<action name="get_i18n_messages">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["get_i18n_messages"]["callback"] = 'switchAction';
   
    $ajxpclient_actions["display_doc"]["XML"] = '<action name="display_doc">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["display_doc"]["callback"] = 'switchAction';
    
    $ajxpclient_actions["up_dir"]["XML"] = '<action name="up_dir">
			<gui text="148" title="24" src="up.png" hasAccessKey="true"
				accessKey="parent_access_key">
				<context selection="false" dir="false" recycle="false"
					actionBar="true" contextMenu="false" infoPanel="false" actionBarGroup="default">
				</context>
			</gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>			
			<processing>
				<clientCallback prepareModal="false"><![CDATA[
					url = ajaxplorer.actionBar.getLocationBarValue();
					currentParentUrl = url.substr(0, url.lastIndexOf(\'/\'));
					if(currentParentUrl == "") currentParentUrl = "/";
					ajaxplorer.getFoldersTree().goToParentNode();
					var anchor = ajaxplorer.getFoldersTree().getCurrentNodeProperty("pagination_anchor");
					if(anchor) currentParentUrl = currentParentUrl + "#" + anchor;
					ajaxplorer.getFilesList().loadXmlList(currentParentUrl);
					ajaxplorer.actionBar.updateLocationBar(currentParentUrl);
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["up_dir"]["callback"] = 'switchAction';
    $ajxpclient_actions["up_dir"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
                                              
    $ajxpclient_actions["refresh"]["XML"] = '<action name="refresh">
			<gui text="149" title="149" src="reload.png" hasAccessKey="true"
				accessKey="refresh_access_key">
				<context selection="false" dir="true" recycle="false"
					actionBar="true" contextMenu="true" infoPanel="false">
				</context>
			</gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="false"><![CDATA[
					ajaxplorer.getFilesList().reload();
					ajaxplorer.getFoldersTree().reloadCurrentNode();					
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["refresh"]["callback"] = 'switchAction';
    $ajxpclient_actions["refresh"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
                                 
    $ajxpclient_actions["thumb_display"]["XML"] = '<action name="thumb_display">
			<gui text="228" title="229" src="view_icon.png" hasAccessKey="true"
				accessKey="thumbs_access_key">
				<context selection="false" dir="" recycle="false"
					actionBar="true" contextMenu="false" infoPanel="false"
					actionBarGroup="default">
				</context>				
			</gui>
			<processing>
				<clientCallback prepareModal="false" displayModeButton="thumb"><![CDATA[
					ajaxplorer.filesList.switchDisplayMode(\'thumb\');
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["thumb_display"]["callback"] = 'switchAction';
    
    $ajxpclient_actions["list_display"]["XML"] = '<action name="list_display">
			<gui text="226" title="227" src="view_text.png" hasAccessKey="true"
				accessKey="list_access_key">
				<context selection="false" dir="" recycle="false"
					actionBar="true" contextMenu="false" infoPanel="false"
					actionBarGroup="default">
				</context>
			</gui>
			<processing>
				<clientCallback prepareModal="false" displayModeButton="list"><![CDATA[
					ajaxplorer.filesList.switchDisplayMode(\'list\');
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["list_display"]["callback"] = 'switchAction';
    
    $ajxpclient_actions["splash"]["XML"] = '<action name="splash">
			<gui text="166" title="167" src="info.png" hasAccessKey="true"
				accessKey="about_access_key">
				<context selection="false" dir="" recycle="false"
					actionBar="true" contextMenu="false" infoPanel="false"
					actionBarGroup="user">
				</context>
			</gui>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
				modal.showDialogForm(
					\'Ajaxplorer\', 
					\'splash_form\', 
					null, 
					function(){hideLightBox();return false;}, 
					null, 
					true);		
					]]></clientCallback>
				<clientForm id="splash_form"><![CDATA[
					<div id="splash_form" box_width="455">
						<div id="splashScreen">
							<div align="center" style="font-size:50px;font-family:Trebuchet MS, sans-serif; font-weight:bold;color:#79f;">
								<img src="AJXP_CLIENT_RESOURCES_FOLDER/images/ajxp_logo_w_64.gif" width="64" height="64" border="0"> AjaXplorer
							</div>
							<iframe frameborder="0" src="AJXP_SERVER_ACCESS?get_action=display_doc&doc_file=CREDITS" id="docFileIframe"></iframe>
						</div>
					</div>				
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $ajxpclient_actions["splash"]["callback"] = 'switchAction';
?>