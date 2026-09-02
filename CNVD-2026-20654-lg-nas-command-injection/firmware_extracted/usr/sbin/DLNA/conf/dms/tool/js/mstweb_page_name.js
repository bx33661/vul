/* $Id: mstweb_page_name.js,v 1.12 2007/02/26 10:25:53 iwamoto Exp $ */

var class_mst_ajax_name_get_page = class_mst_ajax.extend({
    action: "get_page_name",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {$ERR("n1");}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_setting')) {$ERR("n2");}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_name_change_name = class_mst_ajax.extend({
    action: "change_name",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                var new_server_name = mstweb_util.get_child_node(xml, "new_server_name");
                cntl('name', 'update_server_name', new_server_name);
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_name')) {$ERR("n4");}
            }
        } catch (e) {}
        cntl('name', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('NAME_FAILED_SET_SERVER_NAME'));
        } catch (e) {}
        cntl('name', 'disable_controls', false);
    },

    request: function() {
        if (!$(this.owner.btn_apply_id) || $(this.owner.btn_apply_id).disabled) return true;
        var form = Form.serialize($('mstweb_page_name_form'));

        if (!this.parent({serial:form})) return false;
        cntl('name', 'disable_controls', true);
        return true;
    }
});

var class_mst_ajax_name_get_name = class_mst_ajax.extend({
    action: "get_name",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                var server_name = mstweb_util.get_child_node(xml, "server_name");
                cntl('name', 'update_server_name', server_name);
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_name')) {$ERR("n4");}
            }
        } catch (e) {}
        cntl('name', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('NAME_FAILED_GET_SERVER_NAME'));
        } catch (e) {}
        cntl('name', 'disable_controls', false);
    },

    request: function(request_if_failed) {
        if (request_if_failed && !this.failed) return true;
        if (!this.parent()) return false;
        cntl('name', 'disable_controls', true);
        return true;
    }
});

var class_mstweb_page_name = class_mst_page.extend({
    controller: "mstweb_page_name",
    btn_apply_id: "page_name_btn_apply",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_name_get_page(),
        get_name: new class_mst_ajax_name_get_name(),
        change_name: new class_mst_ajax_name_change_name()
    }),

    initialize: function() {
        this.parent('name', 'mst_page_name');
        this.ajax.init(this, this.controller);
    }, 

    enter: function() {
        if (!this.parent()) return false;
        if (!this.load_completed) return true;
        this.on_load_complete();
        return true;
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        cntl('name', 'update_view');
    },

    view_update_btn: function() {
        return true;
    },

    update_server_name: function(new_server_name) {
        return true;
    }
});

