<?php 
	Header("content-type: application/x-javascript");

	require_once("../multilang/multilang_api.php");
	
	// language information by url start
		$t_lang_from_url = explode('/', trim($_SERVER['REQUEST_URI']), 3);

		lang_set_active_language($t_lang_from_url[1]);
  // language information by url end
?>

<!--
var store = {
	mode : 'store_data',
	status : 'init',
	init_php : '../php/bd_init_task.php',
	init : function(){
		this.status = 'init';
		sendRequest(on_init,'&mode='+this.mode,'post',this.init_php,true,true);
		
		opener.document.getElementById('id_btn_copy').visibility = "hidden";
		opener.document.getElementById('id_btn_copy_img').disabled = true;
		
		function on_init(oj){
			var res=decodeURIComponent(oj.responseText);
			debug('on init : return => '+res);
			eval('var ret = '+res+';');
			
			if(ret.result){
				debug('init : '+ret.message);
				store.copy();
				store.start_read_prog();
			}else{
				debug('init : fail');
				//to do
				return;
			}
			return;
		}
	},
	//copy_php : '../php/bd_do_store_data.php',
	copy_php : '../php/bd_do_task.php',
	copyOj : '',
	copy : function(){
		if(!self.opener){
			debug('parent window was changed');
			//to do
			return;
		}
		var _path = self.opener.document.getElementById("idInDataPath").value
		var _cmd = '&op_mode='+this.mode+'&path=/mnt/fs'+_path;
		this.copyOj = sendRequest(on_copy,_cmd,'post',this.copy_php,true,true);		
		this.status = 'copy';
		
		function on_copy(oj){
			
			
			var res=decodeURIComponent(oj.responseText);
			debug('on copy : return => '+res);
			
			var tmp = res.split("\n");
			var ret = tmp[0].split(":");
			switch(ret[0])
			{
				case 'OK':
					document.getElementById('idButtonBurnNext').style.visibility = 'visible';
					break;
				case 'NG':
					var msg = ret[1];
					if(msg=='cancel'){
						var _msg = "<?php echo lang_get('storing_msg_18')?>";
						
					}else if(msg == 'NOT DATA DISC'){
						var _msg = "<?php echo lang_get('storing_msg_6')?>";
					}else if(msg == 'No volume'){
						var _msg = msg = "<?php echo lang_get('storing_msg_20')?>";
					}
					store.end(_msg);
					return;
					break;
				case 'WARNING':
					var msg = res;
					break;
				case 'ERROR':
					var msg = ret[1];
					if(msg == 'TRAY OPENED'){
						msg = "<?php echo lang_get('schedule_msg_17')?>";
					}else if(msg == 'BD IS BUSY'){
						msg = "<?php echo lang_get('storing_msg_2')?>";
					}else if(msg == 'NO DISC'){
						msg = "<?php echo lang_get('storing_msg_4')?>";
					}else{
						msg = 'Error : '+msg;
					}
					store.end(msg);
					return;
					break;
				case 'EXCEPTION':
					// complete or canceled
					debug('D : Exception');
					break;
				default:
					// Timeout or cancel
					debug('D : No return (Timeout/Cancel)');
					break;
			}
		}
	},
	read_timer : '',
	start_read_prog : function(){
		//document.getElementById('prog').width = 1;
		resize_bar(1);
		
		function resize_bar(prog){
			var w_max = 370;
			if(prog==100){
				var w = w_max;
			}else{
				var w = parseInt(prog * w_max / 100, 10);
			}
			if(w>0){
				document.getElementById('prog').width = w;
				document.getElementById('idProg_bar').style.visibility = "visible";
				document.getElementById('progValue').innerHTML = "<strong>"+prog+" %</strong>";
			}
			if(w == w_max){
				store.end('complete');
			}
		}
		//document.getElementById('idProg_bar').style.visibility = "visible";
		this.read_timer = setInterval('store.read_prog()',1000);
	},
	read_php : '../php/storing_data_get_prog.php',
	prog : 0,
	read_prog : function(){
		var _cmd = '';
		sendRequest(on_read_prog,_cmd,'post',this.read_php,true,true);
		
		function on_read_prog(oj){
			var res=decodeURIComponent(oj.responseText);
			//debug('on read prog : return => '+res);
			var _prog = parseInt(res,10);
			if(isNaN(_prog) || _prog > 100){
				return;
			}
			if(_prog < 0){
				var _err_code = {
					'-90' : "<?php echo lang_get('esata_msg_23')?>",
					'-92' : "<?php echo lang_get('esata_msg_21')?>",
					'-94' : "<?php echo lang_get('esata_msg_24')?>",
					'-96' : "<?php echo lang_get('esata_msg_22')?>",
					'-98' : "<?php echo lang_get('esata_msg_25')?>"
				}
				store.end(_err_code[_prog.toString()]);
				return;
			}
			if(_prog > store.prog){
				resize_bar(_prog);
			}
			/* Old version
			eval('var ret = '+res+';');
			if(ret.result){
				if(ret.message=='complete'){
					if(store.status!='complete'){
						clearInterval(store.read_timer);
						store.status = 'complete';
					}else{
						//to do
					}
				}else if(ret.message=='cancel'){
					store.finish_cancel();
					return;
				}
				var _prog = parseInt(ret.progress,10);
				resize_bar(_prog);
			}else{
				debug(ret.message);
				//to do
			}
			*/
			function resize_bar(prog){
				var w_max = 370;
				if(prog==100){
					var w = w_max;
				}else{
					var w = parseInt(prog * w_max / 100, 10);
				}
				if(w>0){
					document.getElementById('prog').width = w;
					document.getElementById('idProg_bar').style.visibility = "visible";
					document.getElementById('progValue').innerHTML = "<strong>"+prog+" %</strong>";
				}
				if(w == w_max){
					store.end('complete');
				}
			}
		}
	},
	cancel_php : '../php/storing_data_cancel.php',
	cancel : function(){
		//document.getElementById('progValue').style.left = 120;
		document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_11')?>";
		sendRequest(on_cancel,'&cancel_mode='+this.mode,'post',this.cancel_php,true,true);
		//clearInterval(this.read_timer);
		//alert('cancel');
		//alert(this.copyOj);
		//this.copyOj.abort();
		//alert(this.copyOj.getAllResponseHeaders());
		//var _path = self.opener.document.getElementById("idInDataPath").value
		//var _cmd = '&path=/mnt/fs'+_path;
		//this.copyOj = sendRequest(on_copy,_cmd,'post',this.copy_php,true,true);
		
		
		function on_cancel(oj){
			document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_18')?>";
			var res=decodeURIComponent(oj.responseText);
			debug('on cancel : return => '+res);
			eval('var ret = '+res+';');
			if(ret.result){
				debug(ret.message);
				store.end('cancel');
			}else{
				debug(ret.message);
			}
		}
	},
	end_php : '../php/bd_lcd_msg.php',
	'end' : function(msg){
		if(msg){
			if(msg == 'cancel'){
				//alert("<?php echo lang_get('storing_msg_18')?>");
				var _msg = "<?php echo lang_get('storing_msg_18')?>";
			}else if(msg == 'complete'){
				//alert("<?php echo lang_get('storing_msg_19')?>");
				var _msg = "<?php echo lang_get('storing_msg_19')?>";
			}else if(msg == 'Disc size over'){
				// Disc capacity is larger than free capacity of NAS
				var _msg = msg;
				document.getElementById('popup_msg').innerHTML = "<?php echo lang_get('storing_msg_18')?>";
				document.getElementById('popup_msg').style.color = '#7E2217';
			}else{
				document.getElementById('popup_msg').innerHTML = msg;
				document.getElementById('popup_msg').style.color = '#7E2217';
			}
		}
		
		if(this.read_timer) clearInterval(this.read_timer);
		document.getElementById('idButtonBurnNext').innerHTML = "<input type='image' onclick='close_task();' src='../images/btn/btn_confirm.gif'/>";
		document.getElementById('idButtonBurnNext').style.visibility = 'visible';
		if(!_msg) var _msg = msg.replace('<BR />',"\n");
		alert(_msg);
	}
}
function close_task(){
	if(opener.document.getElementById('id_btn_copy'))	opener.document.getElementById('id_btn_copy').visibility = "visible";
	if(opener.document.getElementById('id_btn_copy_img')) opener.document.getElementById('id_btn_copy_img').disabled = false;
	
	this.close();
}

//-->