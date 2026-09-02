/* $Id: mstweb.js,v 1.10 2007/01/27 14:21:04 iwamoto Exp $ */

var class_mst_ajax_root_get_menu = class_mst_ajax.extend({
    action: "get_menu",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.tgt_id)) {$ERR("1");}
            if (!mstweb_util.update_session_id(xml)) {$ERR("2");}
            mst_tab_main.init();
        } else {
            mstweb_page_login.ajax.ajax.get_page.request();
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.tgt_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mstweb = new Class({
    controller: "mstweb_body",
    tgt_id: "container",

    ajax: new class_mst_ajaxes({
        get_menu: new class_mst_ajax_root_get_menu()
    }),

    initialize: function() {
    }, 

    on_load: function() {
        this.ajax.init(this, this.controller);

        this.ajax.ajax.get_menu.request();
    }
});

