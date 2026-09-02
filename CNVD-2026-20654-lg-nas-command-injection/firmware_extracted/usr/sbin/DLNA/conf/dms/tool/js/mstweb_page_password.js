/* $Id: mstweb_page_password.js,v 1.9 2007/01/16 15:57:04 iwamoto Exp $ */

var class_mst_ajax_passowrd_get_page = class_mst_ajax.extend({
    action: "get_page_password",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {$ERR("p1");}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_setting')) {$ERR("p2");}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_passowrd_change_password = class_mst_ajax.extend({
    action: "change_password",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html(xml, 'mst_state_password')) {$ERR("p3");}
                this.owner.on_clear();
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_password')) {$ERR("p4");}
            }
        } catch (e) {}
        cntl('pass', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('PASSWORD_FAILED_CHANGE_PASSWORD'));
        } catch (e) {}
        cntl('pass', 'disable_controls', false);
    },

    request: function() {
        var form = Form.serialize($('mstweb_page_password_form'));
        if (!this.parent({serial:form})) return false;
        cntl('pass', 'disable_controls', true);
        return true;
    }
});

var class_mstweb_page_password = class_mst_page.extend({
    controller: "mstweb_page_password",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_passowrd_get_page(),
        change_password: new class_mst_ajax_passowrd_change_password()
    }),

    initialize: function() {
        this.parent('password', 'mst_page_password');
        this.ajax.init(this, this.controller);
    }, 

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        cntl('pass', 'update_view');
    },

    on_clear: function() {
        cntl('pass', 'clear_text_box');
        cntl('pass', 'update_view');

        return true;
    },

    view_update_btn: function() {
        cntl('pass', 'update_view');
        return true;
    }
});

