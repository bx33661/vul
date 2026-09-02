/* $Id: mstweb_page_login.js,v 1.8 2007/01/27 14:21:04 iwamoto Exp $ */

var class_mst_ajax_login_get_page = class_mst_ajax.extend({
    action: "get_page_login",

    initialize: function(loginler, owner) {
        this.parent(loginler, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {return false;}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    },

    request: function(loose_session) {
        var arg = {
//            hash : { 'lose_session': lose_session ? 'y' : 'n' }
            hash : { 'lose_session': 'n' }
        };
        if (!this.parent(arg)) return false;
    }
});

var class_mst_ajax_login_login = class_mst_ajax.extend({
    action: "login",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_state(xml, 'container', 'err')) {}
            if (!mstweb_util.update_session_id(xml)) {$(X)}
            mst_tab_main.init();
        } else {
            if (!mstweb_util.update_state(xml, 'mst_state_login', 'err')) {}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert("login failure");
    },

    request: function() {
        var form = Form.serialize($('mst_login_form'));
        if (!form) {return false;}
        if (!this.parent({serial: form})) return false;
        return true;
    }
});

var class_mstweb_page_login = class_mst_page.extend({
    controller: "mstweb_page_login",
    id_pass: "mstweb_page_login_password",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_login_get_page(),
        login: new class_mst_ajax_login_login()
    }),

    initialize: function() {
        this.parent('login', 'container');
        this.ajax.init(this, this.controller);
        if (!mstweb_util.update_state(null, 'mst_state_login', 'err')) {return false;}
    }, 

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        $('mst_login_form').onsubmit = function() {
            this.ajax.ajax.login.request(); 
            return false;
        }.bindAsEventListener(this);

        $(this.id_pass).focus();
    }
});

