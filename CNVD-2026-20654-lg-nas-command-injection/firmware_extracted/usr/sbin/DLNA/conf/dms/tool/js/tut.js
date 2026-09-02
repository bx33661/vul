/* $Id: tut.js,v 1.10 2007/01/22 15:43:56 iwamoto Exp $ */

/*** xml ***/
var class_tut_xml = new Class({
    get_text_node_val: function(node) {
        return node.firstChild ? node.firstChild.nodeValue : "";
    },
    
    get_text: function(xml, tag_name) {
        var elem = xml.getElementsByTagName(tag_name);
        if (!elem || 0 == elem.length) return null;
        return elem.item(0).firstChild.nodeValue;
    }
});

/*** mutex ***/
var class_tut_mutex = new Class({
    locked: false,
    nspin: null,
    
    lock: function() {
        var i;
        for(i=0; this.locked; ++i) {
            if (this.nspin < i) { 
                $ERR("spun 100000 times"); i=0; 
            }
        }
        this.locked = true;
    },
    
    unlock: function() {
        this.locked = false;
    }
});

/*** browser ***/
var class_tut_browser = new Class({
    _is_ie: "",

    initialize: function() {
        this._is_ie = false;
        var name = navigator.appName;

        if (name == "Microsoft Internet Explorer") {
            this._is_ie = true;
        }
    },

    is_ie: function() {
        return this._is_ie;
    }
});

/*** ajax ***/
var class_tut_ajax = new Class({
    url: null,
    default_timeout: null,
    connection_objs: [],

    initialize: function(url, timeout) {
        this.url = url;
        this.default_timeout = timeout ? timeout : 300000;
    },

    abort_all_connection: function() {
        // using yui
        this.connection_objs.each(function(o){
            if (YAHOO.util.Connect.isCallInProgress(o)) {
//                YAHOO.util.Connect.abort(o);
            }
        });
        this.connection_objs.clear();
        return true;
    },

    onsuccess: function(o) {
        return true;
    },

    onfailure: function(o) {
        return true;
    },

    request_by_yui: function(query_string) {
        var options = {
            success: this.onsuccess.bind(this),
            failure: this.onfailure.bind(this),
            timeout: this.default_timeout
        };
        var cObj = YAHOO.util.Connect.asyncRequest('POST', this.url, options, query_string);
//        this.connection_objs.push(cObj);  // XXX:
        return cObj;
    },

    request: function(serial) {
        return this.request_by_yui(serial);
    }
});
