/* $Id: mstweb_page_control.js,v 1.6 2007/01/16 11:44:34 iwamoto Exp $ */

var class_mst_ajax_control_get_page = class_mst_ajax.extend({
    action: "get_page_control",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
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
    }
});

var class_mstweb_page_control = class_mst_page.extend({
    controller: "mstweb_page_control",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_control_get_page()
    }),

    initialize: function() {
        this.parent('control', 'mst_page_control');
        this.ajax.init(this, this.controller);
    }, 

    on_load: function() {
        this.ajax.ajax.get_page.request();
        return true;
    },

    on_load_complete: function() {
        var text = {
            start_server: "start server",
            stop_server: "stop server",
            service_stopped: "service stopped",
            server_stopping: "server stopping",
            server_stopped: "server stopped",
            server_starting: "server starting",
            server_started: "server started"
        };
        cntl('cntl', 'set_text', text);
    }
});

