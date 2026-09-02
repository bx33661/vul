<?php
    $messages = ConfService::getMessages();
    
    $smb_actions["logout"]["callback"] = 'switchAction';
    $smb_actions["logout"]["rights"] = array("NOUSER" => "false", "USERLOGGED" => "only", "GUESTLOGGED" => "hidden", "READ" => "false", "WRITE" => "false", "ADMINONLY" => "");
    $smb_actions["logout"]["XML"] = '<action name="logout">
			<gui text="164" title="169" src="decrypted.png"
				accessKey="" hasAccessKey="false">
				<context selection="false" dir="" recycle="false"
					actionBar="true" contextMenu="false" infoPanel="false"
					actionBarGroup="user">
				</context>
			</gui>
			<rightsContext noUser="false" userLogged="only" guestLogged="hidden" read="false" write="false" adminOnly=""></rightsContext>			
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					clearRememberData();
					var connexion = new Connexion();
					connexion.addParameter(\'get_action\', \'logout\');
					connexion.onComplete = function(transport){
						ajaxplorer.actionBar.parseXmlMessage(transport.responseXML);
						};
					connexion.sendSync();
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
		
   $smb_actions["switch_root_dir"]["XML"] = '<action name="switch_root_dir">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $smb_actions["switch_root_dir"]["callback"] = 'switchAction';
    
    $smb_actions["get_template"]["XML"] = '<action name="get_template">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $smb_actions["get_template"]["callback"] = 'switchAction';
    
    $smb_actions["get_i18n_messages"]["XML"] = '<action name="get_i18n_messages">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $smb_actions["get_i18n_messages"]["callback"] = 'switchAction';
   
    $smb_actions["display_doc"]["XML"] = '<action name="display_doc">
			<processing>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $smb_actions["display_doc"]["callback"] = 'switchAction';
    
    $smb_actions["up_dir"]["XML"] = '<action name="up_dir">
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
    $smb_actions["up_dir"]["callback"] = 'switchAction';
    $smb_actions["up_dir"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
                                              
    $smb_actions["refresh"]["XML"] = '<action name="refresh">
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
    $smb_actions["refresh"]["callback"] = 'switchAction';
    $smb_actions["refresh"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
                                 
    $smb_actions["thumb_display"]["XML"] = '<action name="thumb_display">
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
    $smb_actions["thumb_display"]["callback"] = 'switchAction';
    
    $smb_actions["list_display"]["XML"] = '<action name="list_display">
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
    $smb_actions["list_display"]["callback"] = 'switchAction';
    
    $smb_actions["splash"]["XML"] = '<action name="splash">
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
								<img src="client/images/ajxp_logo_w_64.gif" width="64" height="64" border="0"> AjaXplorer
							</div>
							<iframe frameborder="0" src="content.php?get_action=display_doc&doc_file=CREDITS" id="docFileIframe"></iframe>
						</div>
					</div>				
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
    $smb_actions["splash"]["callback"] = 'switchAction';

    $smb_actions["ls"]["callback"] = 'switchAction';
    $smb_actions["ls"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "false");
    $smb_actions["ls"]["XML"] = '<action name="ls" dirDefault="true">			
			<displayDefinitions>
				<display mode="list">
					<column messageId="1" attributeName="ajxp_label"/>
					<column messageId="2" attributeName="filesize"/>
					<column messageId="3" attributeName="mimestring"/>
					<column messageId="4" attributeName="ajxp_modiftime"/>
				</display>
			</displayDefinitions>
			<gui text="32" title="32" src="fileopen.png"
				accessKey="folder_access_key">
				<context selection="true" dir="" recycle="false"
					actionBar="false" actionBarGroup="get" contextMenu="true" infoPanel="true">
				</context>
				<selectionContext dir="true" file="true" recycle="false"
					unique="true" allowedMimes="zip">
				</selectionContext>
			</gui>
			<rightsContext noUser="true" userLogged="only" read="true"
				write="false" adminOnly="false">
			</rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var path;
					if(window.actionArguments && window.actionArguments.length>0){
						path = window.actionArguments[0];
					}else{
						userSelection = ajaxplorer.getFilesList().getUserSelection();
						if(userSelection && userSelection.isUnique() && (userSelection.hasDir() || userSelection.hasMime(["zip"]))){
							path = userSelection.getUniqueFileName();
						}
					}
					if(path){
						ajaxplorer.getFoldersTree().goToDeepPath(path);
						ajaxplorer.filesList.loadXmlList(path);
						ajaxplorer.getActionBar().updateLocationBar(path);
					}
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
			</processing>
		</action>';
	
	$smb_actions["upload"]["callback"] = 'switchAction';
	$smb_actions["upload"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["upload"]["XML"] = '<action name="upload">
			<gui text="27" title="27" src="yast_backup.png" accessKey="upload_access_key" hasAccessKey="true">
				<context selection="false" dir="true" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="put" inZip="false">
				</context></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
				if ( ajaxplorer.actionBar.getFlashVersion() >= 8 && (navigator.appVersion.indexOf("Win") > -1 ) && (document.location.href.substring(0,5)!=\'https\' || Prototype.Browser.IE) )
				{
					modal.showDialogForm(\'Upload\', 
										\'flash_upload_form\', 
										null, 
										function(){
											hideLightBox();
											return false;
										}, 
										null, 
										true, true);				
				}
				else
				{
					$(\'hidden_frames\').innerHTML = \'<iframe name="hidden_iframe" id="hidden_iframe"></iframe>\';
					var max = \'<?php ConfService::getConf("UPLOAD_MAX_NUMBER")?>\';			
					var onLoadFunction = function(oForm){
						this.multi_selector = new MultiSelector(oForm, oForm.getElementsBySelector(\'div.uploadFilesList\')[0],max);
						this.multi_selector.addElement(oForm.getElementsBySelector(\'.dialogFocus\')[0]);
						var rep = document.createElement(\'input\');
						rep.setAttribute(\'type\', \'hidden\');
						rep.setAttribute(\'name\', \'dir\');
						rep.setAttribute(\'value\', ajaxplorer.getFilesList().getCurrentRep());
						oForm.appendChild(rep);
					}.bind(ajaxplorer.actionBar);
					
					modal.setCloseAction(function(){
						ajaxplorer.filesList.reload();
					});
					modal.showDialogForm(\'Upload\', \'originalUploadForm\', onLoadFunction, function(){ajaxplorer.actionBar.multi_selector.submitMainForm();return false;}, function(){$(\'hidden_frames\').innerHTML = \'<iframe name="hidden_iframe" id="hidden_iframe"></iframe>\';});
				}				
					]]></clientCallback>
				<clientForm id="flash_upload_form"><![CDATA[
					<!-- UPLOAD FORM -->
					<form action="content.php" target="hidden_iframe" enctype="multipart/form-data" method="POST" id="originalUploadForm" style="text-align:left; display:none;" box_width="640">
					<legend>AJXP_MESSAGE[25]<b class="replace_rep"></b></legend>
					<br><br>AJXP_MESSAGE[171] &nbsp; <b><font color="red">AJXP_MESSAGE[512]</font></b><br>
					<input type="file" id="userfile_1" name="userfile_1" class="dialogFocus" size="30">
					<input type="hidden" name="get_action" value="upload">
					
					<br><br>AJXP_MESSAGE[172]
					<div id="upload_files_list" class="uploadFilesList"></div>
					</form>
									
					<!-- FLEX UPLOAD FORM -->
					<div id="flash_upload_form" box_width="470">
					<div id="flashscreen">
					<iframe id="flashframe" frameborder="0" src="content.php?get_action=get_template&template_name=flash_tpl.html&encode=false"></iframe>
					</div>
					</div>
					<script language="javascript">$(\'originalUploadForm\').hide();</script>
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["upload"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["upload"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["upload"]["XML"]);
		}
	}
		
	$smb_actions["mkdir"]["callback"] = 'switchAction';
	$smb_actions["mkdir"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["mkdir"]["XML"] = '<action name="mkdir">			
			<gui text="154" title="155" src="folder_new.png" accessKey="folder_access_key" hasAccessKey="true">
				<context selection="false" dir="true" recycle="hidden" actionBar="true" contextMenu="true" infoPanel="false" actionBarGroup="put" inZip="false"></context>
			</gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[					
					modal.showDialogForm(\'Create\', \'mkdir_form\', null, function(){
						var oForm = $(modal.getForm());	
						var elementToCheck=(oForm[\'dirname\']);
						ajaxplorer.actionBar.submitForm(oForm);				
						hideLightBox(true);
						return false;
					});
					]]></clientCallback>
				<clientForm id="mkdir_form"><![CDATA[
				<div id="mkdir_form" action="mkdir" box_width="280">
				AJXP_MESSAGE[173]<br/>
				<input type="text" name="dirname" size="30" maxlength="217" class="dialogFocus">
				</div>
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["mkdir"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["mkdir"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["mkdir"]["XML"]);
		}
	}
	
	$smb_actions["mkfile"]["callback"] = 'switchAction';
	$smb_actions["mkfile"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["mkfile"]["XML"] = '<action name="mkfile">
			<gui text="156" title="157" src="filenew.png" accessKey="file_access_key" hasAccessKey="true">
				<context selection="false" dir="true" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="put" inZip="false">
				</context></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					modal.showDialogForm(\'Create\', \'mkfile_form\', null, function(){
						var oForm = $(modal.getForm());	
						var elementToCheck=(oForm[\'filename\']);
						ajaxplorer.actionBar.submitForm(oForm);				
						hideLightBox(true);
						return false;
					});
					]]></clientCallback>
				<clientForm id="mkfile_form"><![CDATA[
				<div id="mkfile_form" action="mkfile" box_width="280">
				AJXP_MESSAGE[174]<br/>
				<input type="text" name="filename" size="30" maxlength="217" class="dialogFocus">
				</div>				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["mkfile"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["mkfile"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["mkfile"]["XML"]);
		}
	}
	
	$smb_actions["download"]["callback"] = 'switchAction';
	$smb_actions["download"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
	$smb_actions["download"]["XML"] = '<action name="download" fileDefault="true">
			<gui text="88" title="88" src="download_manager.png" accessKey="download_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="false"
					actionBar="true" contextMenu="true" infoPanel="true"
					actionBarGroup="get">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="false"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var userSelection = ajaxplorer.getFilesList().getUserSelection();
					if((userSelection.isUnique() && !userSelection.hasDir()) || zipEnabled)
					{
						var ie6_download = false;
						if ( Prototype.Browser.IE )
						{
							var ua = navigator.userAgent;
							if ( ua.indexOf(\'MSIE 8.0\') === -1 && ua.indexOf(\'MSIE 7.0\') === -1 && ua.indexOf(\'MSIE 6.0\') > -1)
							{
								ie6_download = true;
							}
						}
						var userSelection = ajaxplorer.getFilesList().getUserSelection();
						if ( ie6_download == false )
						{
							$(\'download_form\').setAttribute("target", "download_iframe");
						}
						$(\'download_form\').select("input").each(function(input){
							if(input.name!=\'get_action\') input.remove();
						});
						userSelection.updateFormOrUrl($(\'download_form\'));
						$(\'download_form\').submit();
					}
					else
					{
						var loadFunc = function(oForm){
							var dObject = oForm.getElementsBySelector(\'div[id="multiple_download_container"]\')[0];
							var downloader = new MultiDownloader(dObject, ajxpServerAccessPath+\'?action=download&file=\');
							downloader.triggerEnd = function(){hideLightBox()};
							fileNames = userSelection.getFileNames();
							for(var i=0; i<fileNames.length;i++)
							{
								downloader.addListRow(fileNames[i]);
							}				
						};
						var closeFunc = function(){
							hideLightBox();
							return false;
						};
						modal.showDialogForm(\'Download Multiple\', \'multi_download_form\', loadFunc, closeFunc, null, true);
					}
					]]></clientCallback>
					<clientForm id="multi_download_form"><![CDATA[
					<div id="multi_download_form" title="AJXP_MESSAGE[118]"  box_width="410">
					<div class="dialogLegend">AJXP_MESSAGE[119]</div><br/>
					<div id="multiple_download_container"></div>
					<form style="display:inline;" action="content.php" method="POST" id="download_form">
						<input type="hidden" name="get_action" value="download">
					</form>
					<iframe id="download_iframe" name="download_iframe" style="display:none"></iframe>					
					</div>]]>
					</clientForm>
					<clientListener name="selectionChange"><![CDATA[
					if(ajaxplorer){
						var userSelection = ajaxplorer.getFilesList().getUserSelection();
						var action = ajaxplorer.getActionBar().getActionByName("download");
						if(zipEnabled){
							if(action){
								if((userSelection.isUnique() && !userSelection.hasDir()) || userSelection.isEmpty()){
									action.setIconSrc(\'download_manager.png\');
								}else{
									action.setIconSrc(\'accessories-archiver.png\');
								}
							}
						}else{
							if(userSelection.hasDir() && action){
								action.selectionContext.dir = false;
							}
						}
					}
					]]></clientListener>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["download"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["download"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["download"]["XML"]);
		}
	}
		
	$smb_actions["public_url"]["callback"] = 'switchAction';
	$smb_actions["public_url"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
	$smb_actions["public_url"]["XML"] = '<action name="public_url" fileDefault="false">
			<gui text="292" title="292" src="public_url.png" hasAccessKey="false">
				<context selection="true" dir="" recycle="false"
					actionBar="true" contextMenu="true" infoPanel="true"
					actionBarGroup="get">
				</context>
				<selectionContext dir="false" file="true" recycle="false" unique="true"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[   
					if(!window.ajxpPublicUrlCallback){
						window.ajxpPublicUrlCallback = function(){
							var userSelection = ajaxplorer.getFilesList().getUserSelection();
							if(!userSelection.isUnique() || userSelection.hasDir()) return;
							var userSelection = ajaxplorer.getFilesList().getUserSelection();
							var publicUrl = ajxpServerAccessPath+\'?get_action=public_url\';
							publicUrl = userSelection.updateFormOrUrl(null,publicUrl);
	                        var oForm = $(modal.getForm());	                        
	                        var conn = new Connexion(publicUrl);
							if ( Prototype.Browser.IE || Prototype.Browser.WebKit )
							{
								document.onselectstart = null;
							}
	                        conn.setParameters(oForm.serialize(true));
	                        conn.addParameter(\'get_action\',\'public_url\');
	                        conn.onComplete = function(transport){
	                        	var cont = oForm.select(\'input[id="public_url_container"]\')[0];
	                        	if(cont){
	                        		cont.value = transport.responseText;
						if(transport.responseText.substr(0,4) != \'http\')
						{
							alert(transport.responseText);
							
							exit(1);
						}
						else
						{
							cont.select();
						}
	                        		
	                        	}
	                        	modal.refreshDialogAppearance();
	                        };
	                        conn.sendSync();
	                    };
                    }
		    
		    CopyPublicUrlCallback = function(){
			var oForm = $(modal.getForm());	 
			var cont = oForm.select(\'input[id="public_url_container"]\')[0];
			var browserName = navigator.appName;

			if ( browserName.search("Explorer") > 0 ) {
			  document.execCommand(\'Copy\');
			  cont.select();
			}
			else {
			  var flashcopier = \'flashcopier\';
			  if(!document.getElementById(flashcopier)) {
			    var divholder = document.createElement(\'div\');
			    divholder.id = flashcopier;
			    document.body.appendChild(divholder);
			  }
			  document.getElementById(flashcopier).innerHTML = \'\';
			  var divinfo = \'<embed src="client/flash/_clipboard.swf" FlashVars="clipboard=\'+encodeURIComponent(cont.value)+\'" width="0" height="0" type="application/x-shockwave-flash"></embed>\';
			  document.getElementById(flashcopier).innerHTML = divinfo;
			  cont.select();
			}
		    };
                    modal.showDialogForm(\'Get\', \'public_url_form\', null, function(){
                        hideLightBox(true);
                        return false;
                    }, null, true);
					]]></clientCallback>
					<clientForm id="public_url"><![CDATA[
					<div id="public_url_form" title="AJXP_MESSAGE[293]"  box_width="280" action="public_url">
						<fieldset>
							<legend>1 : AJXP_MESSAGE[307]</legend>
							<div class="dialogLegend">
								<input type="text" style="width: 25px; float: right;" value="1" maxlength="3" name="expiration"/>
								<span message_id="294">AJXP_MESSAGE[294]</span> 
							</div>
							<div class="dialogLegend" style="clear: both; margin-top: 10px;">
								<input type="password" value="" maxlength="20" name="password" style="float: right; width: 100px;"/>
								<span message_id="295">AJXP_MESSAGE[295]</span>
							</div>
						</fieldset>
						<fieldset>
							<legend>2 : AJXP_MESSAGE[308]</legend>
							<div align="center" style="color: white; margin-top:5px; font-size:10px;">
								<div align="center" style="text-align:center; cursor: pointer; width:50px; border: 1px solid #aaa;background-color: #bbb;padding:5 10 3 10;" onclick="window.ajxpPublicUrlCallback(); return false;"><img src="client/images/crystal/actions/22/public_url.png" height="22" width="22"/><br/>AJXP_MESSAGE[309]</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>3 : AJXP_MESSAGE[296]</legend>
							<input type="text" style="width:230px;" id="public_url_container"/><br/>
							<div class="dialogButtons"><input id="copy_btn" type="button" name="copy_btn" value="URL AJXP_MESSAGE[66]" class="dialogButton" onClick="CopyPublicUrlCallback(); return false;"/></div>
							<script language="javascript"> if( !(navigator.appVersion.indexOf("Win") > -1 ) )$(\'copy_btn\').hide();</script>
						</fieldset>
					</div>]]>
					</clientForm>					
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["public_url"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["public_url"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["public_url"]["XML"]);
		}
	}
		
	$smb_actions["view"]["callback"] = 'switchAction';
	$smb_actions["view"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
	$smb_actions["view"]["XML"] = '<action name="view">
			<gui text="129" title="136" src="frame_image.png" accessKey="view_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="true"
					actionBarGroup="get">
				</context>
				<selectionContext dir="false" file="true" recycle="false" unique="true" allowedMimes="png,bmp,jpg,jpeg,gif" behaviour="hidden"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var userSelection =  ajaxplorer.getFilesList().getUserSelection();
						var loadFunc = function(oForm){
							ajaxplorer.actionBar.diaporama = new Diaporama($(oForm));
							ajaxplorer.actionBar.diaporama.open(ajaxplorer.getFilesList().getItems(), userSelection.getUniqueFileName());
						};
						var closeFunc = function(){
							ajaxplorer.actionBar.diaporama.close();
							hideLightBox();
							return false;
						};
						modal.showDialogForm(\'Diaporama\', \'diaporama_box\', loadFunc, closeFunc, null, true, true);
					]]></clientCallback>
				<clientForm id="diaporama_box"><![CDATA[
				<div id="diaporama_box" action="view_diapo" box_width="90%" box_height="90%">
					<div class="action_bar" style="width: 500px;">
						<a href="#" onclick="return false;" id="closeButton"><img src="client/images/crystal/actions/22/fileclose.png"  width="22" height="22" alt="" border="0"><br><span message_id="86">AJXP_MESSAGE[86]</span></a>
						<a href="#" onclick="return false;" id="fsButton"><img src="AJXP_CLIENT_RESOclientURCES_FOLDER/images/crystal/actions/22/window_fullscreen.png"  width="22" height="22" alt="" border="0"><br><span message_id="235">AJXP_MESSAGE[235]</span></a>
						<a href="#" onclick="return false;" id="nofsButton" style="display:none;"><img src="client/images/crystal/actions/22/window_nofullscreen.png"  width="22" height="22" alt="" border="0"><br><span message_id="236">AJXP_MESSAGE[236]</span></a>
						<div class="separator"></div>
						<a href="#" id="prevButton" onclick="return false;"><img  width="22" height="22" src="client/images/crystal/actions/22/back_22.png" alt="" border="0"><br><span message_id="178">AJXP_MESSAGE[178]</span></a>
						<a href="#" id="nextButton" onclick="return false;"><img width="22" height="22" src="client/images/crystal/actions/22/forward_22.png" alt="" border="0"><br><span message_id="179">AJXP_MESSAGE[179]</span></a>
						<div class="separator"></div>
						<a href="#" id="stopButton" onclick="return false;"><img width="22" height="22" src="client/images/crystal/actions/22/player_stop.png" alt="AJXP_MESSAGE[233]" border="0"><br><span message_id="232">AJXP_MESSAGE[232]</span></a>
						<a href="#" id="playButton" onclick="return false;"><img width="22" height="22" src="client/images/crystal/actions/22/player_play.png" alt="AJXP_MESSAGE[231]" border="0"><br><span message_id="230">AJXP_MESSAGE[230]</span></a>
						<div class="separator"></div>
						<a href="#" id="downloadDiapoButton" onclick="return false;"><img width="22" height="22" src="client/images/crystal/actions/22/download_manager.png" alt="" border="0"><br><span message_id="88">AJXP_MESSAGE[88]</span></a>
					</div>
					<div align="right" style="background-image: url(client/images/header_bg_plain.png); padding-top: 1px; padding-right: 5px; border:1px solid #333; border-bottom:none; height: 21px;">
					<table cellpadding="0" cellspacing="0" style="font-size:11px;"><tr>
					<td><img class="diaporamaButton" width="16" height="16" src="client/images/crystal/actions/16/viewmag1.png" id="actualSizeButton" hspace="5" alt="Actual Size(100%)" title="Actual Size(100%)" style="cursor:pointer;"><img class="diaporamaButton" src="client/images/crystal/actions/16/window_nofullscreen.png" width="16" height="16" hspace="5" id="fitToScreenButton" alt="Fit To Screen" title="Fit To Screen" style="cursor:pointer;"></td>
					<td><div class="slider" id="slider-2"><input class="slider-input" id="slider-input-2" name="slider-input-2"/></div></td>
					<td><input id="zoomValue" type="text" style="text-align: right; width: 25px;height: 16px;padding:0px;border: none; padding-right: 1px;"/> %</td>
					<td style="padding-left: 10px;"><input id="time" type="text" value="3" style="text-align: right; width: 25px;height: 16px;padding:0px;border: none; padding-right: 1px;"/> s</td></tr></table>
					</div>
					<div style="width:100%; text-align:center; vertical-align:center;overflow:auto; background-color:#333; border:1px solid black;" id="imageContainer">
					<img id="mainImage" src="" style="border: 4px solid white;">
					</div>
				</div>				
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["view"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["view"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["view"]["XML"]);
		}
	}
		
	$smb_actions["chmod"]["callback"] = 'switchAction';
	$smb_actions["chmod"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["chmod"]["XML"] = '<action name="chmod">
			<gui text="287" title="287" src="ksysv.png" accessKey="" hasAccessKey="false">
				<context selection="true" dir="true" recycle="hidden"  behaviour="hidden"
					actionBar="false" contextMenu="true" infoPanel="true"
					actionBarGroup="put" inZip="false">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="true" allowedMimes="" behaviour="hidden"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var userSelection =  ajaxplorer.getFilesList().getUserSelection();					
					var loadFunc = function(oForm){
						ajaxplorer.actionBar.propertyPane = new PropertyPanel(userSelection, oForm);
					};
					var completeFunc = function(){
						
						userSelection.updateFormOrUrl(modal.getForm());
						ajaxplorer.actionBar.submitForm(modal.getForm());
						hideLightBox();
						return false;
					};
					modal.showDialogForm(\'Edit Online\', \'properties_box\', loadFunc, completeFunc);
					]]></clientCallback>
				<clientForm id="properties_box"><![CDATA[
					<div id="properties_box" action="chmod" box_width="220"></div>				
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
		
	$smb_actions["rename"]["callback"] = 'switchAction';
	$smb_actions["rename"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["rename"]["XML"] = '<action name="rename">
			<gui text="6" title="158" src="applix.png" accessKey="rename_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="change" inZip="false">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="true" image="false" mp3="false" editable="false"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var callback = function(item, newValue){						
						var filename = item.getAttribute(\'filename\');
						var conn = new Connexion();
						conn.addParameter(\'get_action\', \'rename\');
						conn.addParameter(\'file\', filename);
						conn.addParameter(\'filename_new\', newValue);
						conn.onComplete = function(transport){
							ajaxplorer.actionBar.parseXmlMessage(transport.responseXML);
						};
						conn.sendSync();
					};
					ajaxplorer.filesList.switchCurrentLabelToEdition(callback);
					]]></clientCallback>
				<clientForm id="rename_form"><![CDATA[
				<div id="rename_form" action="rename" box_width="280">
				AJXP_MESSAGE[6] <b class="replace_file"></b> &nbsp;AJXP_MESSAGE[42]<br/>
				<input type="text" name="filename_new" maxlength="217" value="" id="filename_new" class="dialogFocus initFicName">
				</div>
				]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["rename"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["rename"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["rename"]["XML"]);
		}
	}
		
	$smb_actions["copy"]["callback"] = 'switchAction';
	$smb_actions["copy"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["copy"]["XML"] = '<action name="copy" ctrlDragndropDefault="true">
			<gui text="66" title="159" src="editcopy.png" accessKey="copy_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="change">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="false" image="false" mp3="false" editable="false"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					if(ajaxplorer.user){
						var activeRepository = ajaxplorer.user.getActiveRepository();
					}
					var onLoad = function(oForm){
						var getAction = oForm.getElementsBySelector(\'input[name="get_action"]\')[0];
						getAction.value = \'copy\';
						var container = oForm.getElementsBySelector(".treeCopyContainer")[0];
						var eDestLabel = oForm.getElementsBySelector(\'input[name="dest"]\')[0];
						var eDestNodeHidden = oForm.getElementsBySelector(\'input[name="dest_node_temp"]\')[0];
						this.treeCopyActive = true;
						if(!this.treeCopy){
							this.treeCopy = new WebFXLoadTree(\'/\', 
											ajxpServerAccessPath+\'?get_action=ls\', 
											"javascript:ajaxplorer.foldersTree.clickNode(CURRENT_ID)", 
											\'explorer\',null,null,\'&skipZip=true\');
						}
						else{
							this.treeCopy.src = ajxpServerAccessPath+\'?get_action=ls\';
							this.treeCopy.queryParameter = \'&skipZip=true\';
							window.setTimeout(\'ajaxplorer.actionBar.treeCopy.reload()\', 100);
						}				
						this.treeCopyActionDest = $A([eDestLabel]);
						this.treeCopyActionDestNode = $A([eDestNodeHidden]);
						container.innerHTML = this.treeCopy.toString();
						$(this.treeCopy.id).observe("click", function(e){
							ajaxplorer.foldersTree.clickNode(this.treeCopy.id);
							Event.stop(e);
						}.bind(this));
						this.treeCopy.focus();
						
						if(ajaxplorer.user){
							var repoList = ajaxplorer.user.getRepositoriesList();	
							var index=-1;
							repoList.each(function(pair){
								var repoObject = pair.value;
								repoObject.allowCrossRepositoryCopy = true;
								if(repoObject.allowCrossRepositoryCopy){
									index++;
									if(index) $(\'external_repository\').show();							
									if(repoObject.getId() == activeRepository) return;
									$(\'external_repository\').insert(\'<option value="\'+repoObject.getId()+\'">\'+repoObject.getLabel()+\'</option>\');
								}
							});							
							if(!index) return;
							
							$(\'external_repository\').insert(\'<option value="\'+activeRepository+\'">\'+activeRepository+\'&nbsp;&lt;Current Folder&gt;</option>\');
							
							externalRepo = activeRepository;
							this.treeCopy.src += \'&tmp_repository_id=\'+externalRepo;
							this.treeCopy.queryParameters += \'&tmp_repository_id=\'+externalRepo;
							
							$(\'external_repository\').observe("change", function(e){
								externalRepo = $(\'external_repository\').getValue();
								this.treeCopy.src += \'&tmp_repository_id=\'+externalRepo;
								this.treeCopy.queryParameters += \'&tmp_repository_id=\'+externalRepo;
								this.treeCopy.reload();
							}.bind(this));
						}
						
					}.bind(ajaxplorer.actionBar);
					var onCancel = function(){				
						ajaxplorer.cancelCopyOrMove();
					};
					var onSubmit = function(){
						var oForm = modal.getForm();
						
							var eDestLabel = oForm.getElementsBySelector(\'input[name="dest"]\')[0];
							
							if(($(\'external_repository\').getValue() == activeRepository) && (eDestLabel.value == ajaxplorer.filesList.getCurrentRep()))
							{
								alert(MessageHash[183]);
								return false;
							}
						
						ajaxplorer.filesList.getUserSelection().updateFormOrUrl(oForm);				
						this.submitForm(oForm, true);
						ajaxplorer.cancelCopyOrMove();
						return false;
					}.bind(ajaxplorer.actionBar);
					modal.showDialogForm(\'Move/Copy\', \'copymove_form\', onLoad, onSubmit, onCancel);				
					]]></clientCallback>
				<clientForm id="copymove_form"><![CDATA[
					<div id="copymove_form" action="copy" box_width="272">
					<input type="hidden" name="dest_node_temp" value="">
					<span id="copymove_text_span"></span>
					<div class="dialogLegend">AJXP_MESSAGE[175]</div>
					<select id="external_repository" name="dest_repository_id" style="width: 248px; font-size: 12px; height: 21px; margin-bottom: 0px; border: 1px solid #ccc;"></select>
					<div style="overflow:auto; height:250px; width:246px; padding: 0px 0px; border: 1px solid #ccc;">
					<input type="text" name="dest" value="/" readonly  style="width:100%; border: none; border-bottom: 1px solid #ccc;"/>
					<div id="treeCopy" style="height:222px; padding: 3px 2px; text-align:left;" class="treeCopyContainer"></div>
					</div>
					</div>
				]]></clientForm>
				<clientListener name="contextChange"><![CDATA[
				if(ajaxplorer){
					var action = ajaxplorer.getActionBar().getActionByName("copy");
					if(action && ajaxplorer.foldersTree){
						if(ajaxplorer.foldersTree.currentInZip()){
							action.setLabel(247, 248);
							action.setIconSrc(\'ark_extract.png\');
						}else{
							action.setLabel(66, 159);
							action.setIconSrc(\'editcopy.png\');
						}
					}
				}
				]]></clientListener>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	if(preg_match_all("/AJXP_MESSAGE(\[.*?\])/", $smb_actions["copy"]["XML"], $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			$messId = str_replace("]", "", str_replace("[", "", $match[1]));
			$smb_actions["copy"]["XML"] = str_replace("AJXP_MESSAGE[$messId]", $messages[$messId], $smb_actions["copy"]["XML"]);
		}
	}
		
	$smb_actions["move"]["callback"] = 'switchAction';
	$smb_actions["move"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["move"]["XML"] = '<action name="move" dragndropDefault="true">
			<gui text="70" title="160" src="editpaste.png" accessKey="move_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="hidden"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="change" inZip="false">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="false" image="false" mp3="false" editable="false"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
				if(ajaxplorer.user){
						var activeRepository = ajaxplorer.user.getActiveRepository();
					}
					var onLoad = function(oForm){
						var getAction = oForm.getElementsBySelector(\'input[name="get_action"]\')[0];
						getAction.value = \'move\';
						var container = oForm.getElementsBySelector(".treeCopyContainer")[0];
						var eDestLabel = oForm.getElementsBySelector(\'input[name="dest"]\')[0];
						var eDestNodeHidden = oForm.getElementsBySelector(\'input[name="dest_node_temp"]\')[0];
						this.treeCopyActive = true;
						if(!this.treeCopy){
							this.treeCopy = new WebFXLoadTree(\'/\', 
																ajxpServerAccessPath+\'?get_action=ls\', 
																"javascript:ajaxplorer.foldersTree.clickNode(CURRENT_ID)", 
																\'explorer\',null,null,\'&skipZip=true\');
						}
						else{
							this.treeCopy.src = ajxpServerAccessPath+\'?get_action=ls\';
							this.treeCopy.queryParameter = \'&skipZip=true\';
							window.setTimeout(\'ajaxplorer.actionBar.treeCopy.reload()\', 100);
						}						
						this.treeCopyActionDest = $A([eDestLabel]);
						this.treeCopyActionDestNode = $A([eDestNodeHidden]);
						container.innerHTML = this.treeCopy.toString();
						$(this.treeCopy.id).observe("click", function(e){
							ajaxplorer.foldersTree.clickNode(this.treeCopy.id);
							Event.stop(e);
						}.bind(this));						
						this.treeCopy.focus();
						
						if(ajaxplorer.user){
							var repoList = ajaxplorer.user.getRepositoriesList();	
							var index=-1;
							repoList.each(function(pair){
								var repoObject = pair.value;
								repoObject.allowCrossRepositoryCopy = true;
								if(repoObject.allowCrossRepositoryCopy){
									index++;
									if(index) $(\'external_repository\').show();
									if(repoObject.getId() == activeRepository) return;
									$(\'external_repository\').insert(\'<option value="\'+repoObject.getId()+\'">\'+repoObject.getLabel()+\'</option>\');
								}
							});							
							if(!index) return;
							
							$(\'external_repository\').insert(\'<option value="\'+activeRepository+\'">\'+activeRepository+\'&nbsp;&lt;Current Repository&gt;</option>\');
							
							externalRepo = activeRepository;
							this.treeCopy.src += \'&tmp_repository_id=\'+externalRepo;
							this.treeCopy.queryParameters += \'&tmp_repository_id=\'+externalRepo;
							
							$(\'external_repository\').observe("change", function(e){
								var externalRepo_mv = $(\'external_repository\').getValue();
								this.treeCopy.src += \'&tmp_repository_id=\'+externalRepo_mv;
								this.treeCopy.queryParameters += \'&tmp_repository_id=\'+externalRepo_mv;
								this.treeCopy.reload();
							}.bind(this));
						}
												
					}.bind(ajaxplorer.actionBar);
					var onCancel = function(){				
						ajaxplorer.cancelCopyOrMove();
					};
					var onSubmit = function(){
						var oForm = modal.getForm();
						var eDestLabel = oForm.getElementsBySelector(\'input[name="dest"]\')[0];
						
						if(($(\'external_repository\').getValue() == activeRepository) && (eDestLabel.value == ajaxplorer.filesList.getCurrentRep()))
						{
							alert(MessageHash[183]);
							return false;
						}
				
						ajaxplorer.filesList.getUserSelection().updateFormOrUrl(oForm);				
						this.submitForm(oForm, true);
						ajaxplorer.cancelCopyOrMove();
						
						return false;
					}.bind(ajaxplorer.actionBar);
					modal.showDialogForm(\'Move/Copy\', \'copymove_form\', onLoad, onSubmit, onCancel);
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
		
	$smb_actions["delete"]["callback"] = 'switchAction';
	$smb_actions["delete"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "true", "ADMINONLY" => "");
	$smb_actions["delete"]["XML"] = '<action name="delete">			
			<gui text="7" title="161" src="editdelete.png" accessKey="delete_access_key" hasAccessKey="true">
				<context selection="true" dir="" recycle="false"
					actionBar="true" contextMenu="true" infoPanel="false"
					actionBarGroup="change" inZip="false">
				</context>
				<selectionContext dir="true" file="true" recycle="false" unique="false" image="false" mp3="false" editable="false"></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="true" adminOnly=""></rightsContext>
			<processing>
				<clientCallback prepareModal="true"><![CDATA[
					var onLoad = function(oForm){
				    	var message = MessageHash[177];
				    	if(ajaxplorer.foldersTree.recycleEnabled() && !ajaxplorer.foldersTree.currentIsRecycle()){
				    		message = MessageHash[176];
				    	}
		   		    	$(oForm).getElementsBySelector(\'span[id="delete_message"]\')[0].innerHTML = message;
					};
					modal.showDialogForm(\'Delete\', \'delete_form\', onLoad, function(){
						var oForm = modal.getForm();
						ajaxplorer.filesList.getUserSelection().updateFormOrUrl(oForm);
						ajaxplorer.actionBar.submitForm(oForm, true);
						hideLightBox(true);
						return false;
					});
					]]></clientCallback>
				<clientForm id="delete_form"><![CDATA[
				<div id="delete_form" action="delete" box_width="280">
					<span id="delete_message"></span>
				</div>]]></clientForm>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	
	$smb_actions["image_proxy"]["callback"] = 'switchAction';
	$smb_actions["image_proxy"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
	$smb_actions["image_proxy"]["XML"] = '<action name="image_proxy">
			<gui text="6" title="158" src="applix.png" accessKey="rename_access_key"><context selection="true" dir="" recycle="false" actionBar="false" contextMenu="false" infoPanel="false"></context><selectionContext dir="true" file="true" recycle="false" unique="true" image="" mp3="" editable=""></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback><![CDATA[
					// PUT HERE CODE TO EXECUTE IN JAVASCRIPT
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
	
	$smb_actions["mp3_proxy"]["callback"] = 'switchAction';
	$smb_actions["mp3_proxy"]["rights"] = array("NOUSER" => "true", "USERLOGGED" => "only", "READ" => "true", "WRITE" => "false", "ADMINONLY" => "");
	$smb_actions["mp3_proxy"]["XML"] = '<action name="mp3_proxy">
			<gui text="6" title="158" src="applix.png" accessKey="rename_access_key"><context selection="true" dir="" recycle="false" actionBar="false" contextMenu="false" infoPanel="false"></context><selectionContext dir="true" file="true" recycle="false" unique="true" image="" mp3="" editable=""></selectionContext></gui>
			<rightsContext noUser="true" userLogged="only" read="true" write="false" adminOnly=""></rightsContext>
			<processing>
				<clientCallback><![CDATA[
					// PUT HERE CODE TO EXECUTE IN JAVASCRIPT
					]]></clientCallback>
				<serverCallback methodName="switchAction"></serverCallback>
				</processing>
		</action>';
?>