/* $Id: mstweb_page_mimetype.js,v 1.22 2007/02/08 13:50:30 iwamoto Exp $ */

var class_mst_ajax_mimetype_get_page = class_mst_ajax.extend({
    action: "get_page_mimetype",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {$ERR("m1");}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_setting')) {$ERR("m2");}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_mimetype_get_mimetype_list = class_mst_ajax.extend({
    action: "get_mimetype_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, this.owner.mimetype_list_id)) {alert("1-1");}
                list('mime', 'show_add_box', false);
                list('mime', 'show_edit_box', false);
                list('mime', 'view_update_btn');
            } else {
                if (!mstweb_util.update_html(xml, this.owner.mimetype_list_id)) {}
            }
        } catch (e) {
            return this.request();
        }
        list('mime', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.mimetype_list_id, msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    request: function(disable_loading_msg, request_if_failed) {
        if (request_if_failed && !this.failed) return true;
        if (!disable_loading_msg) {
            mstweb_util.update_innerhtml(this.owner.mimetype_list_id, msg('MIMETYPE_LOADING'));
        }
        if (!this.parent()) return false;
        list('mime', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_mimetype_register = class_mst_ajax.extend({
    action: "register",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (tut_xml.get_text(xml, 'prev_extention')) {
            }
            if (!this.owner.ajax.ajax.get_mimetype_list.request(true)) {}
        } else {
            alert(mstweb_util.get_html(xml));
        }
        list('mime', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('MIMETYPE_FAILED_REGISTER'));
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    request: function(edit, prev_extention, id_ext, id_mime) {
        var hash = {
            extention: $(id_ext).value,
            mimetype: $(id_mime).value
        };
        if (edit) {
            hash.prev_extention = prev_extention;
            this.action = "edit";
        } else {
            this.action = "register";
        }

        if (!this.parent({'hash': hash})) return false;
        list('mime', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_mimetype_unregister = class_mst_ajax.extend({
    action: "unregister",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (tut_browser.is_ie()) {
                    this.owner.ajax.ajax.get_mimetype_list.request(true);
                } else {
                    this.owner.reload();
                }
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_mimetype')) {}
            }
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('MIMETYPE_FAILED_UNREGISTER'));
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    request: function() {
        var form = Form.serialize($('mstweb_page_mimetype'));

        if (!this.parent({serial: form})) return false;
        list('mime', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_mimetype_reset = class_mst_ajax.extend({
    action: "reset",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!this.owner.ajax.ajax.get_mimetype_list.request(true)) {}
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_mimetype')) {}
            }
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('MIMETYPE_FAILED_RESET'));
        } catch (e) {}
        list('mime', 'disable_btns', false);
    },

    request: function() {
        if (!this.parent()) return false;
        list('mime', 'disable_btns', true);
        return true;
    }
});

var class_mstweb_page_mimetype = class_mst_page.extend({
    controller: "mstweb_page_mimetype",

    mimetype_list_id: "mstweb_page_mimetype_mimetype_list",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_mimetype_get_page(),
        get_mimetype_list: new class_mst_ajax_mimetype_get_mimetype_list(),
        register: new class_mst_ajax_mimetype_register(),
        unregister: new class_mst_ajax_mimetype_unregister(),
        reset: new class_mst_ajax_mimetype_reset()
    }),

    initialize: function() {
        this.parent('mimetype', 'mst_page_mimetype');
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
        this.ajax.ajax.get_mimetype_list.failed = true;
        this.on_load();
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        this.ajax.ajax.get_mimetype_list.request(false, true);
    },

    terminate: function() {
        this.ajax.ajax.get_mimetype_list.failed = true;
    },

    browser_dep_table_row: function() {
        // XXX:
        var str_table_row;

        if (tut_browser.is_ie()) { 
            str_table_row = "block";
        } else {
            str_table_row = "table-row";
        }
        return str_table_row;
    },

// etc.
    on_add: function() {
        if ($('mime_tr_add').style.display == "none" || $('mime_tr_add').style.display == "") {
            $('mime_tr_add').style.display = this.browser_dep_table_row();
            list('mime', 'show_add_box', true);
        } else {
            $('mime_tr_add').style.display = "none";
            list('mime', 'show_add_box', false);
        }
        list('mime', 'view_update_btn');
        cntl('mime_a', 'clear_text');
        cntl('mime_a', 'update_view');
        return true;
    },

    on_edit: function(selected_tr_id) {
        var edit_tr_id;
        if (selected_tr_id) {
            // return normal mode
            edit_tr_id = selected_tr_id;
            var edit_mime_id = edit_tr_id.replace('_tr_', '_se_') + "_edit_extention";
            var edit_ext_id = edit_tr_id.replace('_tr_', '_se_') + "_edit_mimetype";
            if ($(edit_mime_id)) $(edit_mime_id).value = $(edit_mime_id).defaultValue;
            if ($(edit_ext_id)) $(edit_ext_id).value = $(edit_ext_id).defaultValue;
            list('mime', 'show_edit_box', false);
        } else {
            // change edit mode
            var selected_tr_ids = list('mime', 'get_selected_tr_ids');
            if (selected_tr_ids.length != 1) {alert('selected_tr_ids.length != 1');}
            edit_tr_id = selected_tr_ids[0];
            list('mime', 'change_all_tr', 'unselect');
            list('mime', 'show_edit_box', true);
        }
        $(edit_tr_id).style.display = selected_tr_id ? this.browser_dep_table_row() : "none";
        $(edit_tr_id + "_edit").style.display = selected_tr_id ? "none" : this.browser_dep_table_row();
        return true;
    }
});

