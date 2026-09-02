/*
<head>
    <link rel="stylesheet" href="none.css" type="text/css" title="default" />
    <link rel="alternate stylesheet" href="blue.css" type="text/css" title="blue" />
    <script type="text/javascript" src="prototype.js"></script>
    <script type="text/javascript" src="style_switcher.js"></script>
</head>

<body>
    <a href="javascript:ss_switch_style('default')">none</a>
</body>

window.onload = function(e) { ss_load_style(); }
window.onunload = function(e) { ss_save_style(); } 
*/


var class_style_switcher = Class.create();

class_style_switcher.prototype = {
    cookie_name: "style",
    cookie_days: 365,

    initialize: function() {
    },

    setActiveStyleSheet: function(title) {
      var i, a, main;
      for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
          a.disabled = true;
          if(a.getAttribute("title") == title) a.disabled = false;
        }
      }
    },

    getActiveStyleSheet: function() {
      var i, a;
      for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) return a.getAttribute("title");
      }
      return null;
    },

    getPreferredStyleSheet: function() {
      var i, a;
      for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
        if(a.getAttribute("rel").indexOf("style") != -1
           && a.getAttribute("rel").indexOf("alt") == -1
           && a.getAttribute("title")
           ) return a.getAttribute("title");
      }
      return null;
    },
    
    createCookie: function(value) {
      if (this.cookie_days) {
        var date = new Date();
        date.setTime(date.getTime()+(this.cookie_days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
      }
      else expires = "";
      document.cookie = this.cookie_name + "=" + value + expires + "; path=/";
    },
    
    readCookie: function() {
      var nameEQ = this.cookie_name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
    },

    loadStyle: function() {
      var cookie = this.readCookie();
      var title = cookie ? cookie : this.getPreferredStyleSheet();
      this.setActiveStyleSheet(title);
    },

    saveStyle: function() {
      var title = this.getActiveStyleSheet();
      this.createCookie(title);
    }
};

var style_switcher = new class_style_switcher();

function ss_switch_style(title) { style_switcher.setActiveStyleSheet(title); }
function ss_load_style() { style_switcher.loadStyle(); }
function ss_save_style() { style_switcher.saveStyle(); }
function ss_get_style() { return style_switcher.getActiveStyleSheet(); }

