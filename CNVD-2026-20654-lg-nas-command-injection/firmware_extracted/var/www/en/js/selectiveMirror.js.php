<?php 
	Header("content-type: application/x-javascript");

	/*******************************************************************
	Multi Languate Support
	*******************************************************************/
	require_once("../multilang/multilang_api.php");
	$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);
	lang_set_active_language($t_lang_from_url[1]);
  
?>


// When DOM ready
jQuery(document).ready(function(){
	 
	// For AJAX loading
	jQuery("#page_loading").ajaxStart(function(){
  	jQuery(this).show();
  }).ajaxStop(function(){
  	jQuery(this).hide();
  });
  
	// Radio button => enable
	jQuery("#smEnable").click(function(){
		jQuery.post('../php/selectiveMirror.php',{'mode' : 'enable'}, function(data){
			alert("<?php echo lang_get('sm_enabled')?>");
			page.showList();
		});
	});

	// Radio button => disable
	jQuery("#smDisable").click(function(){
		jQuery.post('../php/selectiveMirror.php',{'mode' : 'disable'}, function(data){
			alert("<?php echo lang_get('sm_disabled')?>");
			page.showDisable();
		});
	});
	
	// Bind function to Buttons
	// 1. List Div		
			// 1-1. Add			
			jQuery("#btnAddDiv").click(function(){
				page.showAddDiv();
			});
			
			// 1-2. Edit			 
			jQuery("#btnEditDiv").click(function(){
				page.showEditDiv();
			});
			
			// 1-3. Delete
			jQuery("#btnDelDiv").click(function(){
				page.doDelete();
			});
	
	// 2. Add Div
			// 2-1. Save
			jQuery("#btnSaveApply").click(function(){
				page.doAdd();
			});
	
			// 2-2. Cancel		
			jQuery("#btnCancelApply").click(function(){
				jQuery("#listDiv").show();
				jQuery("#addDiv").hide();
			});
			
			// 2-3. Source Select			
			jQuery("#btnSrc").click(function(){
					popupFileBrowser('srcPath');
			});
			
			// 2-4. Destination Select
			jQuery("#btnDes").click(function(){
				popupFileBrowser('desPath');
			});

	// 3. Edit Div
			// 3-1. Save
			jQuery("#btnEditSaveApply").click(function(){
				page.doEdit();
			});
			
			// 3-2. Cancel
			jQuery("#btnEditCancelApply").click(function(){
				jQuery("#listDiv").show();
				jQuery("#editDiv").hide();
			});

			// 3-3. Source Select for Edit Div			
			jQuery("#btnSrcEdit").click(function(){
				popupFileBrowser('srcPathEdit');
			});

			// 3-4. Destination Select for Edit Div
			jQuery("#btnDesEdit").click(function(){
				popupFileBrowser('desPathEdit');
			});
			
	// Get status when page loaded => Radio button
  jQuery.post('../php/selectiveMirror.php',{'mode' : 'getStatus'}, function(result){
		
		if(result == 1){
		jQuery("#smEnable").attr({'checked' : true});

			//get Selective Mirror List			
			page.showList();
		}
		else if(result == 0){
			jQuery("#smDisable").attr({'checked' : true});
			
			page.showDisable();
		}
	});
	
	// Check/Uncheck All checkboxes
	jQuery("#smChk").click(function(){
		var $checkBoxes = jQuery("#smList").find(':checkbox');
		
		
			if(this.checked){
				$checkBoxes.attr({'checked' : true});
			}else{
				
				$checkBoxes.attr({'checked' : false});
			}
		});
});

/****************************************************************
Object name       : page
 * Method
    - showList    : get List from Server and show
    - showDisable : Indicates Selective Mirror is diabled
    - showAddDiv  : show Add div
    - showEditDiv : show Edit div & set the checked value
    - doAdd       : Send value to the Server to Add list
    - doEdit      : Send value to the Server to Edit list
    - doDelete    : Send values to the Server to delete from list
*****************************************************************/


var page = {
	showList : function(){
		jQuery.post('../php/selectiveMirror.php',{'mode' : 'getList'}, function(result){
			eval('var list = '+result);
			
			var table = "<table width='650px' border='0' cellspacing='0' cellpadding='0'>";
			if(list.rows.length > 0){
				for(i=0;i<list.rows.length;i++){
					table +="<tr>";
					table +="<td class='otherCol_420'  style='padding-left:10px;width:30px'><input type='checkbox' value='"+list.rows[i].id+"'></td>";
					table +="<td class='firstCol_250'  style='width:310px' id='srcPath_"+list.rows[i].id+"'>"+list.rows[i].cell[0]+"</td>";
					table +="<td class='otherCol_420'  style='width:310px' id='desPath_"+list.rows[i].id+"'>"+list.rows[i].cell[1]+"</td>";
					table +="</tr>";
				}
			}
			else if(list.rows.length == 0){
				table += "<tr><td colspan='2' style='height:300px' align='center' valign='middle'>"+"<?php echo lang_get('no_settings')?>"+"</td></tr>";
			}
			table += "</table>"; 
			document.getElementById('smList').innerHTML = table;
			jQuery("#listButtons").show();
			jQuery("#smChk").attr({'checked' : false});
		});
		
		
	},
	
		showDisable : function(){
			
			var table = "<table width='650px' border='0' cellspacing='0' cellpadding='0'>";
					table += "<tr><td colspan='2' align='center' style='height:300px' valign='middle'>"+"<?php echo lang_get('sm_disabled')?>"+"</td></tr>";
					table += "</table>"; 
			
			document.getElementById('smList').innerHTML = table;
			jQuery("#listButtons").hide();
		
	},
	
	showAddDiv : function(){
				//Clear Previous Value
				jQuery("#srcPath").val('');
				jQuery("#desPath").val('');
				
				jQuery("#listDiv").hide();
				jQuery("#addDiv").show();	
	},
	
	showEditDiv : function(){

			var checkedCount = jQuery("#smList :checkbox:checked").length;
			if( checkedCount < 1 || checkedCount > 1){
					alert("<?php echo lang_get('select_one_setting_to_edit')?>");
					return false;
				}
			else{
				jQuery("#smList :checkbox:checked").each(function(){
	        		
	        		// Get Row Index from Table
	        		rowIndex = jQuery(this).val();
	        		
	        		jQuery('#srcPathEdit').val(jQuery("#srcPath_"+rowIndex).text());
	        		jQuery('#desPathEdit').val(jQuery("#desPath_"+rowIndex).text());
	        	
	        		jQuery('#srcPathEditOld').val(jQuery("#srcPath_"+rowIndex).text());
	        		jQuery('#desPathEditOld').val(jQuery("#desPath_"+rowIndex).text());
	        	});
			
		}
		jQuery("#listDiv").hide();
		jQuery("#editDiv").show();
},
	
	doAdd : function(){
					var srcPath = jQuery("#srcPath").val();
					var desPath = jQuery("#desPath").val();
					
					if(srcPath == '' || desPath == ''){
						alert("<?php echo lang_get('please_input_src_des_path')?>");
						return false;
					}
				jQuery.post('../php/selectiveMirror.php',{'mode' : 'addList','srcPath' : srcPath, 'desPath' : desPath}, function(response){
						
						eval("var result="+response);
						alert(result.ml_string);
						if(result.number > 0){
							jQuery("#addDiv").hide();
							jQuery("#listDiv").show();
					  
							page.showList();
						}
						
						
					});
	},
	
	doEdit : function(){
		
		
		var srcPath = jQuery("#srcPathEdit").val();
		var desPath = jQuery("#desPathEdit").val();
		
		var srcPathOld = jQuery("#srcPathEditOld").val();
		var desPathOld = jQuery("#desPathEditOld").val();
		
		if(srcPath == srcPathOld && desPath == desPathOld){
			alert("<?php echo lang_get('network_msg_2')?>");
			return false;
		}
		else{
			
			srcPath = srcPath+"|"+srcPathOld;
			desPath = desPath+"|"+desPathOld;
	
			jQuery.post('../php/selectiveMirror.php',{'mode' : 'editList','srcPath' : srcPath, 'desPath' : desPath},function(response){
								eval("var result="+response);
								alert(result.ml_string);
								
								if(result.number > 0){
									jQuery("#editDiv").hide();
									jQuery("#listDiv").show();
							  
									page.showList();
								}
			});
			
		}
	},
		doDelete : function(){
			
			var checkedCount = jQuery("#smList :checkbox:checked").length;
			if( checkedCount < 1){
					alert("<?php echo lang_get('select_settings_to_delete')?>");
					return false;
				}
				
				msg = "<?php echo lang_get("delete_x_itmes")?>";
				msg = msg.replace("#COUNT#",checkedCount);
				
				if(confirm(msg)){
        	
        	var srcPath ='';
        	var desPath ='';
        	
        	var flag = false;
        	
        	jQuery("#smList :checkbox:checked").each(function(){
        		
        		if(flag) {
        			srcPath += "|";
        			desPath += "|";
        		}
        		
        		// Get Row Index from Table
        		rowIndex = jQuery(this).val();
        		
        		srcPath += jQuery("#srcPath_"+rowIndex).text();
        		desPath += jQuery("#desPath_"+rowIndex).text();
        		
        		flag = true;
        	});
   				  
        	jQuery.post('../php/selectiveMirror.php',{'mode' : 'delList','srcPath' : srcPath, 'desPath' : desPath}, function(data){
						alert("<?php echo lang_get('selected_setting_deleted')?>");
						page.showList();
					});
				}
				else {
					return false;
				}
			}	
}

// Show Folder select Window
function popupFileBrowser(id)
{
	document.getElementById("idInputFieldId").value=id;
	var win = window.open('../blu_ray/bd_pop_brows.php','SCHEDULE_BROWSER','scrollbars=no,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=420px,height=490px'); 
	win.focus(); 
}


//========================================================//
// show_help
//========================================================//
function show_help()
{
	var _win = window.open('../help/system/help_selective.html','Help_ddns','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
  _win.focus();
	hPopWin = _win;
}
