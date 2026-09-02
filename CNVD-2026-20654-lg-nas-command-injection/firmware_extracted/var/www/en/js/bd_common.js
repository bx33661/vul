//=======================================================//
// BD busy check
//=======================================================//
var bdBusy = {
	"userId" : "",
	"appId" : "",
	"init" : function(){
		this.userId = "";
		this.appId = "";
	},
	"get_msg" : function( arr ){
		this.init();
		for( var i=1;arr[i];i++ ){
			var _tmp = arr[i].split(":");
			if( _tmp[0]=="USER ID" ) this.userId = _tmp[1];
			if( _tmp[0]=="APPLICATION ID" ) this.appId = _tmp[1];
		}
		switch( this.userId ){
			case "web":
				var _msg = do_web( this.appId );
				break;
			case "myself":
				if( this.appId == "internal" ) var _msg = "BD IS BUSY<br>Tray is working.<br>Wait until it finishes.";
				break;
			case "root":
				if( this.appId == "mosilt" ) var _msg = "BD IS BUSY<br>Disc drive is burning.<br>Wait until it finishes.";
				if( this.appId == "mopilt" ) var _msg = "BD IS BUSY<br>Disc drive is backuping.<br>Wait until it finishes.";
				break;
			case "cms":
				var _msg = "BD IS BUSY<br>Disc drive is doing schedule backup.<br>Wait until it finishes.";
			default:
				break;
		}
		return _msg;
		
		function do_web(app_id){
			switch( app_id ){
				case "rip":
					var _msg = "BD IS BUSY<br>Disc drive is extracting.<br>Wait until it finishes.";
					break;
				case "store":
					var _msg = "BD IS BUSY<br>Disc drive is storing.<br>Wait until it finishes.";
					break;
				case "burn":
					var _msg = "BD IS BUSY<br>Disc drive is burning.<br>Wait until it finishes.";
					break;
				default:
					var _msg = "Error!";
					break;
			}
			return _msg;
		}
	}
}