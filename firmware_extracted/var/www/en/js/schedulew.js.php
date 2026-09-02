<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>

//////////////////////////////////////////
//      Scheduling Backup Wizard        //    
//////////////////////////////////////////

var gPhp = new Array("../php/browser_get_info.php");

//////////////////////////////////////////
//              Table ID                //    
//////////////////////////////////////////
var gIdTable=new Array('browser_table','detail_table');
var cIdTable=new Array('monthly_table','weekly_table','daily_table');


/////////////////////
// Show table area //
/////////////////////

function showTable(id){	
	//debug(id);

	if(id =="detail_table"){
		if($('cms_source').value == "none"){
			
			alert("<?php echo lang_get('wizard_msg_6')?>");
			return false;
		}	
	}	
	document.getElementById(gIdTable[0]).style.display = "none";
	document.getElementById(gIdTable[1]).style.display = "none";
	

	if(id!=""){
		document.getElementById(id).style.display = "block";
	}
}

function showCalTable(id){
		
	document.getElementById(cIdTable[0]).style.display = "none";
	document.getElementById(cIdTable[1]).style.display = "none";
	document.getElementById(cIdTable[2]).style.display = "none";	

	if(id!=""){
		document.getElementById(id).style.display = "block";
	}
	
	if(id == 'daily_table') document.getElementById('backupOccur').innerHTML = "<?php echo lang_get('mail_edit_8')?>"; 
	else if(id == 'weekly_table') document.getElementById('backupOccur').innerHTML = "<span style='color:red'><-" + "<?php echo lang_get('wizard_msg_5')?>"+"</span>";  
	else if(id == 'monthly_table') document.getElementById('backupOccur').innerHTML = "<span style='color:red'><-" + "<?php echo lang_get('wizard_msg_4')?>"+"</span>";  
		
	document.getElementById('cms_sch_week').value = '';
	document.getElementById('cms_sch_date').value = '';
	
}


function setDay(backupDay){
// View
if(backupDay == 'sun') tempDay = "<?php echo lang_get('common_day_7')?>";
else if(backupDay == 'mon') tempDay = "<?php echo lang_get('common_day_1')?>";
else if(backupDay == 'tue') tempDay = "<?php echo lang_get('common_day_2')?>";
else if(backupDay == 'wed') tempDay = "<?php echo lang_get('common_day_3')?>";
else if(backupDay == 'thu') tempDay = "<?php echo lang_get('common_day_4')?>";
else if(backupDay == 'fri') tempDay = "<?php echo lang_get('common_day_5')?>";
else if(backupDay == 'sat') tempDay = "<?php echo lang_get('common_day_6')?>";

document.getElementById('backupOccur').innerHTML = "<?php echo lang_get('common_weekly')?> : " + tempDay;



// Result Process
document.getElementById('cms_sch_week').value = backupDay;


}

function setTime(){
	
	var sch_hour = $('cms_sch_hour').value;
	var sch_min = $('cms_sch_min').value;
	
	if(sch_hour < 10) sch_hour = "0"+sch_hour;
	if(sch_min < 10) sch_min = "0"+sch_min;
$('backupTime').innerHTML = " @ " + sch_hour+":"+sch_min;
	
}
///////////// Show Calendar ////////////// 
function showCal(year, month)
{    
  // Get Date Information
  var date = new Date(); 
  var curYear = year || date.getFullYear();
  var curMonth = month || date.getMonth()+1;
  var curDate = date.getDate();
 
  
  // Check a leap year & Get days of Each Month
  var february = ((0 == curYear % 4) && (0 != (curYear % 100))) || (0 == curYear % 400) ? 29 : 28;
  var daysOfMonth = new Array(31, february, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  
  
  // Define Link Button : Prev month & next month
  var prev_year = (curMonth == 1)? curYear - 1:curYear;
  var prev_month = (curMonth == 1)? 12 : curMonth - 1;
  var next_year = (curMonth == 12)? curYear+1:curYear;
  var next_month = (curMonth == 12)? 1 : curMonth + 1;


  var link_month_prev = "<img src=\"../images/wizard/sch_arrow_01.gif\" width=\"20\" height=\"11\" border=\"0\" onClick='showCal("+prev_year+","+prev_month+")' style='cursor:pointer;cursor:hand;'>";
  var link_month_next = "<img src=\"../images/wizard/sch_arrow_02.gif\" width=\"20\" height=\"11\" border=\"0\" onClick='showCal("+next_year+","+next_month+")' style='cursor:pointer;cursor:hand;'>";

	
  // Calendar_head : Start
	var calendar_head = "<table width=\"100\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
		calendar_head += "<tr>";
    calendar_head = calendar_head + "<td width=\"20\">"+link_month_prev+"</td>";
    calendar_head = calendar_head + "<td align=\"center\" class=\"m_gray_15\">"+ curYear +" / "+ curMonth +"</td>" ;                                                 	
		calendar_head = calendar_head + "<td width=\"20\">"+link_month_next+"</td>";		
		calendar_head += "<tr>";
		calendar_head += "</table>";
  // Calendar_head : End


  // Calendar_body : Start
  var firstDay = new Date(curYear,curMonth-1,1).getDay();
  var lastDay = new Date(curYear,curMonth-1,daysOfMonth[curMonth-1]).getDay();
  
  var dates = new Array();
	var firstDate = 1;

	for (var i = 0; i < firstDay; i++) dates[i] = '';
	for (var i = firstDay; i < daysOfMonth[curMonth-1] + firstDay ; i++) {
		dates[i] = "<a href='javascript:void(0)' onClick='showDate("+firstDate+")'>"+firstDate+"</a>";
		firstDate ++;
	}

	var len = dates.length;
	for (var i = 0; i < (6-lastDay); i++) {	dates[ len + i] = '';	}
	
  
  var calendar_body = "<table width=\"165\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
  
    for(var i =0; i<dates.length;i+=7)
  {      
      calendar_body += "<TR height='15' align='center'>";
      calendar_body += "<TD align=\"center\" class=\"sunday\">"+dates[i]  +"</TD>";
      calendar_body += "<TD align=\"center\" class=\"m_gray_16\">"+dates[i+1]+"</TD>";
      calendar_body += "<TD align=\"center\" class=\"m_gray_16\">"+dates[i+2]+"</TD>";
      calendar_body += "<TD align=\"center\" class=\"m_gray_16\">"+dates[i+3]+"</TD>";
      calendar_body += "<TD align=\"center\" class=\"m_gray_16\">"+dates[i+4]+"</TD>";
      calendar_body += "<TD align=\"center\" class=\"m_gray_16\">"+dates[i+5]+"</TD>";
      calendar_body += "<TD align=\"center\" class=\"saturday\">"+dates[i+6]+"</TD>";
      calendar_body += "</TR>"
    
  }   
  
 calendar_body += "</table>";
 // Calendar_body : End

 // Show Calendar Info
 document.getElementById('calendar_head').innerHTML = calendar_head;
 document.getElementById('calendar_body').innerHTML = calendar_body;
}

///////////// Show Date //////////////
function showDate(backupDay){

document.getElementById('backupOccur').innerHTML = "<?php echo lang_get('common_monthly')?> : "+backupDay+" <?php echo lang_get('common_day')?>	";
	$('cms_sch_date').value = backupDay;
}


///////////// Show Time input box //////////////

function init_select()
{
    var strsel = "<select class='selectbox03' id='cms_sch_hour' style='WIDTH: 50px; HEIGHT: 20px' onChange='setTime()'>\n";
    for(var i=0; i<24; i++){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>\n"
    document.getElementById("cms_time_hour").innerHTML=strsel;
    
    strsel = "<select class='selectbox03' id='cms_sch_min' style='WIDTH: 50px; HEIGHT: 20px' onChange='setTime()'>\n";
    for(var i=0; i<60; i +=10){
        strsel += "<option value='";
        strsel += i;
        strsel += "'>";
        strsel += i;
        strsel += "</option>\n";
    }
    strsel += "</select>\n"
    document.getElementById("cms_time_min").innerHTML=strsel;
} 






//////////////////////////////////////////
//         Set Schedule Info            //    
//////////////////////////////////////////





function setSchedule()
{		
		var temp_cycle;
		var temp_date;
		var temp_week;
		var cmd="";
		var obj;
		
    var obj=document.cms_sch.cms_sch_cycle;
		// Get Important Information for Error Check		
		for (var i=0;i<obj.length;i++) { 
			if (obj[i].checked == true) { 
 				 cmd += "&cycle="+obj[i].value;
  				temp_cycle = obj[i].value;
  		}
		}
	
    obj=document.getElementById("cms_sch_date");
    cmd += "&date="+obj.value;
		temp_date = obj.value;
		
    obj=document.getElementById("cms_sch_week");
    cmd += "&week="+obj.value;
    temp_week = obj.value;

		// Error Check 
		
		if(temp_cycle == 'weekly' && temp_week == ''){
			alert("<?php echo lang_get('wizard_msg_5')?>");
			return;
		}
		else if(temp_cycle == 'monthly' && temp_date == ''){
			alert("<?php echo lang_get('wizard_msg_4')?>");
			return;
		}
		

		// Make default tastname by Using date()	
		Number.prototype.to2 = function() { return (this > 9 ? "" : "0")+this; };
		
	  var now = new Date();
	  var thisYear = now.getFullYear();
	  var thisMonth = (now.getMonth()+1).to2();
	  var thisDate = now.getDate().to2();
	  var thisHour = now.getHours().to2();
	  var thisMinute = now.getMinutes().to2();
	  var thisSecond = now.getSeconds().to2();
	 
	  var taskname = thisYear +""+ thisMonth +""+ thisDate +""+ thisHour +""+ thisMinute +""+ thisSecond;
    var srcdef="/mnt/fs";
    
    
    cmd += "&act=new&task="+taskname;
    
    cmd += "&name="+taskname;
    //cmd += "&desc="+document.getElementById("cms_description").value;
    cmd += "&srcdef="+srcdef;
    cmd += "&srcpath="+document.getElementById("cms_source").value;
    
 
    
    
    obj=document.getElementById("cms_sch_hour");
    cmd += "&time="+obj.options[obj.selectedIndex].value;

    obj=document.getElementById("cms_sch_min");
    cmd += ":"+obj.options[obj.selectedIndex].value;

    var obj=document.cms_sch.cms_direc;
    
    for (var i=0;i<obj.length;i++) { 
			if (obj[i].checked == true) { 
 				 cmd += "&direc="+obj[i].value;
  		}
		}
    //alert(cmd);
    //fStat=gStat[1];
    //alert(cmd);
    sendRequest(on_3, cmd, "post", "../php/comnso_schedule_xml.php",true,true);
}
  
function on_3(oj)
{
	var res=decodeURIComponent(oj.responseText);
	//debug(res);
	//open_task_list();
	//fStat=gStat[0];
	location.href="../blu_ray/schedule.php";
}




function showLoadingImage(){
	
	$('file_box').style.display = "none";
	$('file_box_loading').style.display = "block";
	
}

function showFileBox(){
	$('file_box_loading').style.display = "none";
	$('file_box').style.display = "block";
}



//////////////////////////////////////////
//           File Browser               //
//    Last Modified 2008 / 11 / 24      //    
//////////////////////////////////////////

var file_or_dir_only = 'directory';
var remote_php = '../php/bd_pop_brows_remote.php';
var display_mode = 'list';
var sort_cond = 'time';

//페이지가 처음 로딩될때 실행되는 함수 //
// path_mode : rip/store/burn/schedule //
function startLoad(path_mode){
	
	showLoadingImage();
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	refresh_file_box(path_mode);
	
}
//현재 디렉토리내의 디렉토리 및 파일을 표시해주는 함수
function refresh_file_box(path_mode){
	if(path_mode=="")
	{
		path_mode="none";
	}
	//debug("refresh file box : path mode : "+path_mode);
	var strResponseURL = remote_php+'?action=show_me_files&mode='+display_mode+'&sort_cond='+sort_cond+'&file_or_dir_only='+file_or_dir_only+'&path_mode='+path_mode;
	//getCurrentDirectoryPath();
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
				method:'get',
				asynchronous: true,
				onSuccess:function (responseHttpObj) {
					display_files_list_mode(responseHttpObj.responseText);
				},
				onFailure:function (){
					displayError();
					showFileBox();
					//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
					//is_loading = false;
				}
			}
		)
}



//파일 및 디렉토리를 리스트로 표시
function display_files_list_mode(response){
	//debug(response);	//**//
	
	var info = eval('(' + response + ')');
	var total_size = info.total_size;
	var i=0;
	var data = null;
	var link = '';
	var checkbox_html = '';
	var rename_html = '';
	var body_row_total = '';
	var body_row = '';
	var action_html = '';
	var obj_cnt = 0;
	 
	var table_frame_html = "<table cellpadding='0' cellspacing='0' border='0' width='650px'>"
							+"<tbody>"
							+"<tr><td colspan=3  style=\"padding-left:25px;\"><a href='' onClick=\"move_up();return false;\"><img src=\"../images/comnso/cms_folder_up.gif\">&nbsp;..</a><td></tr>"
							+"#body_row#"
							+"</tbody>"
							+"</table>";
							
	var body_row_html = '<tr style="height:25px">'
							//+"<td>#checkbox#</td>"
							+'<td width=\"70%\" style="border-bottom:1px solid #E5E5E5">#name#</td>'
							+'<td width=\"10%\" style="border-bottom:1px solid #E5E5E5">#size#</td>'
							+'<td width=\"20%\" style="border-bottom:1px solid #E5E5E5">#time#</td>'
							//+'<td>#action#</td>'
						+'</tr>';
	//alert(response);
	
	//	Directories
	for(i=0;i<info.dirs.length;i++){
		data = info.dirs[i];
		body_row = body_row_html;
		var _file_name = data.file_name.replace(/\s/g,'&nbsp;');
		
		link = "<input type='checkbox' name='listCheck' id='"+_file_name+"' onClick=\"setTarget('" + _file_name+"')\">&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		link += "<a href='' onclick=\"move_dir('"+data.encoded_file_name+"');return false;\">"+_file_name+"</a>";
		//rename_html = getRenameButton('directory',data.file_name);
		
		//alert("A");
		/*
		if(data.selected.length>0){
			body_row = body_row.replace('<tr>','<tr class="tr_selected">');
			checkbox_html = '<input type="radio" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'" checked>'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}else{
			checkbox_html = '<input type="radio" name="directory[]" id="chk_'+obj_cnt+'" value="'+data.file_name+'">'+"&nbsp;<img src='../images/comnso/cms_folder.gif' />&nbsp";
		}
		*/
		//body_row = body_row.replace('#checkbox#',checkbox_html);
		body_row = body_row.replace('#name#',link);
		body_row = body_row.replace('#size#','&nbsp;');
		body_row = body_row.replace('#time#',data.date+' '+data.time);
		//action_html = getDeleteButton(data.file_name);
		//body_row = body_row.replace('#action#',action_html+rename_html);
		//body_row = body_row.replace('#action#',action_html);
		
		body_row_total += body_row;
		obj_cnt++;
		
	}
	
	$('file_box').innerHTML = table_frame_html.replace('#body_row#',body_row_total);
	
	
	showFileBox();
	//$('file_box').innerHTML ="why?";
	getCurrentDirectoryPath();
	//$('file_box_loading').style.display = "none";
	//$('file_box').style.display = "block";
	//$('files_slc_fm').style.visibility = "visible";
}





//현재 디렉토리 위치를 브라우저의 Address Bar에 표시
function getCurrentDirectoryPath(){
	//debug("get current directory");	//
	
	var strResponseURL = remote_php+'?action=get_curr_dir_path';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			asynchronous: true,
			onSuccess:function (responseHttpObj) {
				var path_info = eval('(' + responseHttpObj.responseText + ')');
				current_path = path_info.curr_url;
				$('idPath').innerHTML = path_info.curr_url;
				is_loading = false;
			},
			onFailure:displayError
			}
		)
}

// 파일 및 디렉토리를 표시할때 각 개체마다 표시할 삭제버튼의 HTML을 리턴
function getDeleteButton(name){
	return '<a href="javascript:void(0)" onclick="one_delete(\''+sg_quote_escape(name)+'\');return false;">[Delete]</a>';
}
//파일 및 디렉토리를 표시할때 각 개체마다 표시할 이름 수정버튼의 HTML을 리턴
function getRenameButton(type,name){
	return '<a href="javascript:void(0)" onclick="pop_rename_box(this,\''+type+'\',\''+sg_quote_escape(name)+'\');return false;">'
			+'[Rename]';
			+'</a>';
}

function sg_quote_escape(str){
	return str.replace('\'','\\\'');
}

function displayError(){
	alert('Request was failed.');
	is_loading = false;
}



function setTarget(target_folder){
	
	var allListCheck = document.getElementsByName('listCheck');
	
	
	if($(target_folder).checked == true) {
		
		for(i=0;i<allListCheck.length;i++){
			allListCheck[i].checked = false;
		}
		$(target_folder).checked = true;
		$('target_folder').innerHTML = current_path + target_folder;
		$('target_folder2').innerHTML = current_path + target_folder;
		$('cms_source').value = current_path + target_folder;
		
		
		//alert(target_url);
		//menu.get(target_url);
		
		
	}
	else{
		
		for(i=0;i<allListCheck.length;i++){
			allListCheck[i].checked = false;
		}
		$('target_folder').innerHTML = "none";
		$('target_folder2').innerHTML = "none";
		$('cms_source').value = "none";
		
	
		
	}
}


//=======================================================//
// Move to other folder
//=======================================================//
function move_dir(dir_name){
	/*
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	*/
	//is_loading = true;
	showLoadingImage();
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	//debug("move dir : "+dir_name);
	var strResponseURL = remote_php+'?action=move_dir&dir_name='+dir_name;
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				//debug(responseHttpObj.responseText);
				var info = eval('(' + responseHttpObj.responseText + ')');
				//debug(info);
				if(parseInt(info.result) == 1){
					//debug("ok");
					refresh_file_box();
				}else{
					alert(info.error_msg);
					showFileBox();
					//getCurrentDirectoryPath();
					$('idPath').innerHTML = old_cur_dir;
					is_loading = false;
				}
				//hideLoadingImage();
				},
			onFailure:function (){
				//hideLoadingImage();
				displayError();
				//$('file_box_loading').style.display = "none";
				$('file_box').style.display = "block";
				//getCurrentDirectoryPath();
				$('idPath').innerHTML = old_cur_dir;
				//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
				}
			}
		)
}


//=======================================================//
// Move up
//=======================================================//
function move_up(){
	/*
	if(is_loading){
		alert("<?php echo lang_get('common_loading')?>");
		$('file_box_loading').style.display = "none";
		$('file_box').style.display = "block";
		return false;
	}
	is_loading = true;
	*/

	if(current_path == '/'){
		alert('Here is root path.');
		//$('idPath').innerHTML = '/';
		//is_loading = false;
		return false;
	}
	
	old_cur_dir = $('idPath').innerHTML;
	$('idPath').innerHTML = "<?php echo lang_get('common_loading')?>";
	
	
	showLoadingImage();
	//is_loading = true;
	var strResponseURL = remote_php+'?action=move_up';
	var httpObj = new Ajax.Request   (
		    strResponseURL, {
			method:'get',
			onSuccess:function (responseHttpObj) {
				var info = eval('(' + responseHttpObj.responseText + ')');
				if(parseInt(info.result) == 1){
					refresh_file_box();
				}else{
					alert(info.error_msg);
					showFileBox();
					//getCurrentDirectoryPath();
					$('idPath').innerHTML = old_cur_dir;
					//is_loading = false;
				}
				//hideLoadingImage();
				},

			onFailure:function (){
				//hideLoadingImage();
				displayError();
				showFileBox();
				//getCurrentDirectoryPath();
				$('idPath').innerHTML = old_cur_dir;
				//if($('files_slc_fm')) $('files_slc_fm').style.visibility = "visible";
				}
			}
		)
}

//========================================================//
// show_help
//========================================================//

function show_help()
{

		var _win = window.open('../help/wizard/help_sch_wizard.html','Help_System_wizard','titlebar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes,location=no,menu=no,width=600px,height=500px,left=540,top=240');
    _win.focus();
		hPopWin = _win;

	}