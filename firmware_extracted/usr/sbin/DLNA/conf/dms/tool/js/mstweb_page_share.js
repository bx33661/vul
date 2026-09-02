/* $Id: mstweb_page_share.js,v 1.25 2007/01/27 14:37:34 iwamoto Exp $ */

var class_mst_ajax_share_get_page_share = class_mst_ajax.extend({
    action: "get_page_share",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {return false;}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {return false;}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_share_get_share_list = class_mst_ajax.extend({
    action: 'get_share_list',

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, this.owner.share_list_id)) {}
            } else {
                if (!mstweb_util.update_html(xml, this.owner.share_list_id)) {}
            }
        } catch (e) {
            return this.request();
        }
        list('share', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.share_list_id, msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('share', 'disable_btns', false);
    },

    request: function(disable_loading_msg, request_if_failed) {
        if (request_if_failed && !this.failed) return true;
        if (!disable_loading_msg) {
            mstweb_util.update_innerhtml(this.owner.share_list_id, msg('SHARE_LOADING'));
        }
        if (!this.parent()) return false;
        list('share', 'disable_btns', true);
        return true;
    }
});


var class_mst_ajax_share_unregister_share_list = class_mst_ajax.extend({
    action: "unregister_share_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                this.owner.ajax.ajax.get_share_list.request(true);
            } else {
                alert(mstweb_util.get_html(xml));
                this.owner.ajax.ajax.get_share_list.request(true);
            }
        } catch (e) {}
        list('share', 'disable_btns', false);
    },

    onfailure: function(o) {
        list('share', 'disable_btns', false);
        this.parent(o);
        alert(msg('SHARE_FAILED_UNSHARE'));
    },

    request: function() {
        try {
            var form = Form.serialize($('mstweb_page_share'));
            if (!form) {return false;}

            if (!confirm(msg('SHARE_ASK_UNSHARE'))) { return false; }
            if (!this.parent({serial: form})) return false;
        } catch (e) {}
        list('share', 'disable_btns', true);
        return true;
    }
});


var class_mst_ajax_share_repair_database = class_mst_ajax.extend({
    action: "repair_database",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
            } else {
                alert(msg('SHARE_FAILED_REBUILD_DATABASE'));
            }
        } catch (e) {}
        list('share', 'enable_repairing', true);
        list('share', 'disable_btns', false);
    },

    onfailure: function(o) {
        list('share', 'enable_repairing', true);
        list('share', 'disable_btns', false);
        this.parent(o);
        alert(msg('SHARE_FAILED_REBUILD_DATABASE'));
    },

    request: function() {
        try {
            var form = Form.serialize($('mstweb_page_share'));
            if (!form) {return false;}
            if (!this.parent({serial: form})) return false;
        } catch (e) {}
        list('share', 'enable_repairing', false);
        list('share', 'disable_btns', true);
    }
});


var class_mstweb_page_share = class_mst_page.extend({
    controller: "mstweb_page_share",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_share_get_page_share(),
        get_share_list: new class_mst_ajax_share_get_share_list(),
        unregister_share_list: new class_mst_ajax_share_unregister_share_list(),
        repair_database: new class_mst_ajax_share_repair_database()
    }),

    share_list_id: "page_share_list",

    initialize: function() {
        this.parent('share', 'mst_page_share');
        this.ajax.init(this, this.controller);
    }, 

    enter: function() {
        if (!this.parent()) return false;
        if (!this.load_completed) return true;
        this.on_load_complete();
        return true;
    },

    reload: function() {
        this.load_completed = false;
        this.ajax.ajax.get_share_list.failed = true;
        this.on_load();
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
        return true;
    },

    on_load_complete: function() {
        this.ajax.ajax.get_share_list.request(false, true);
        return true;
    },

    terminate: function() {
        this.ajax.ajax.get_share_list.failed = true;
    }
});

