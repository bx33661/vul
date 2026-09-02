/* $Id: mstweb_page_security.js,v 1.31 2007/03/19 13:34:22 iwamoto Exp $ */

var class_mst_ajax_security_get_page = class_mst_ajax.extend({
    action: "get_page_security",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html_list(xml, this.owner.page_id)) {return false;}
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

var class_mst_ajax_security_set_default = class_mst_ajax.extend({
    action: "set_default",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, 'mst_state_security')) {return false;}
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_security')) {return false;}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert(msg('SECURITY_FAILED_CHANGE_DEFAULT'));
    },

    request: function() {
        if (this.owner.security_dialog_enable()) {
            var e_a = 'mst_sec_default_allow';
            var e_d = 'mst_sec_default_deny';

            if (!$(e_a) || !$(e_d)) return false;
            if ($(e_a).checked) {
                if (!confirm(msg('SECURITY_SECURITY_DIALOG_DEFAULT_ALLOW'))) {
                    $(e_a).checked = false;
                    $(e_d).checked = true;
                    return false;
                }
            }
        }

        var form = Form.serialize($('mstweb_page_security_default'));
        if (!this.parent({serial: form})) return false;
        return true;
    }
});

var class_mst_ajax_security_get_default = class_mst_ajax.extend({
    action: "get_default",
    load_client_lists: null,

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
        this.load_client_list = false;
    },

    _load_client_lists: function(request_if_failed) {
        if (!this.load_client_lists) return true;
        this.load_client_lists = false;
//        this.owner.ajax.ajax.get_deny_list.request(false, request_if_failed);
//        this.owner.ajax.ajax.get_allow_list.request(false, request_if_failed);
        return true;
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.default_id)) {}
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_security')) {}
        }
        this._load_client_lists();
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.default_id, msg('SECURITY_FAILED_GET_DEFAULT'));
        this._load_client_lists();
    },

    request: function(load_client_lists, request_if_failed) {
        if (load_client_lists) this.load_client_lists = true;

        if (request_if_failed && !this.failed) {
            if (load_client_lists) {
                this._load_client_lists(request_if_failed);
            }
            return true;
        }

        if (!this.parent()) return false;
        return true;
    }
});


var class_mst_ajax_security_remove = class_mst_ajax.extend({
    action: "remove",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (tut_browser.is_ie()) {
                    this.owner.ajax.ajax.get_deny_list.request(true);
                    this.owner.ajax.ajax.get_allow_list.request(true);
                } else {
                    this.owner.reload();
                }
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_security')) {}
            }
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('SECURITY_FAILED_UNREGISTER'));
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    request: function(is_allow) {
        var form_id = is_allow ? 'mstweb_page_security_allow' : 'mstweb_page_security_deny';
        var form = Form.serialize($(form_id));
        if (!this.parent({serial: form})) return false;
        return true;
    }
});

var class_mst_ajax_security_to_deny = class_mst_ajax.extend({
    action: "to_deny",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (tut_browser.is_ie()) {
                    this.owner.ajax.ajax.get_deny_list.request(true);
                    this.owner.ajax.ajax.get_allow_list.request(true);
                } else {
                    this.owner.reload();
                }
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_security')) {}
            }
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('SECURITY_FAILED_CHANGE_SECURITY'));
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    request: function() {
        var form = Form.serialize($('mstweb_page_security_allow'));

        if (!this.parent({serial: form})) return false;
        list('sec_a', 'disable_btns', true);
        list('sec_d', 'disable_btns', true);
        return true;
    }
});


var class_mst_ajax_security_to_allow = class_mst_ajax.extend({
    action: "to_allow",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (tut_browser.is_ie()) {
                    this.owner.ajax.ajax.get_deny_list.request(true);
                    this.owner.ajax.ajax.get_allow_list.request(true);
                } else {
                    this.owner.reload();
                }
            } else {
                alert(mstweb_util.get_html(xml));
            }
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('SECURITY_FAILED_CHANGE_SECURITY'));
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
        list('sec_d', 'disable_btns', false);
    },

    request: function() {
        if (this.owner.security_dialog_enable()) {
            if (!confirm(msg('SECURITY_SECURITY_DIALOG_TOALLOW'))) return false;
        }

        var form = Form.serialize($('mstweb_page_security_deny'));

        if (!this.parent({serial: form})) return false;
        list('sec_a', 'disable_btns', true);
        list('sec_d', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_security_get_deny_list = class_mst_ajax.extend({
    action: "get_deny_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, this.owner.deny_list_id)) {}
                this.owner.update_host();
                list('sec_d', 'view_update');
            } else {
                if (!mstweb_util.update_html(xml, this.owner.deny_list_id)) {}
            }
        } catch (e) {
            return this.request(true);
        }
        list('sec_d', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.deny_list_id, msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('sec_d', 'disable_btns', false);
    },

    request: function(disable_loading_msg, request_if_failed) {
        if (request_if_failed && !this.failed) return true;
        if (!disable_loading_msg) {
            mstweb_util.update_innerhtml(this.owner.deny_list_id, msg('SECURITY_LOADING'));
        }
        if (!this.parent()) return false;
        list('sec_d', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_security_get_allow_list = class_mst_ajax.extend({
    action: "get_allow_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, this.owner.allow_list_id)) {}
                this.owner.update_host();
                list('sec_a', 'view_update');
            } else {
                if (!mstweb_util.update_html(xml, this.owner.allow_list_id)) {}
            }
        } catch (e) {
            return this.request(true);
        }
        list('sec_a', 'disable_btns', false);

    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.allow_list_id, msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('sec_a', 'disable_btns', false);
    },

    request: function(disable_loading_msg, request_if_failed) {
        if (request_if_failed && !this.failed) return true;
        if (!disable_loading_msg) {
            mstweb_util.update_innerhtml(this.owner.allow_list_id, msg('SECURITY_LOADING'));
        }
        if (!this.parent()) return false;
        list('sec_a', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_security_set_security_dialog_visibility = class_mst_ajax.extend({
    action: "set_security_dialog_visibility",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
            } else {
                alert(msg('SECURITY_FAILED_CHANGE_SECURITY_DIALOG_VISIBILITY'));
                if ($(this.owner.security_dialog_visibility_id)) {
                    $(this.owner.security_dialog_visibility_id).checked = !$(this.owner.security_dialog_visibility_id).checked;
                }
            }
        } catch (e) {
            return this.request(true);
        }
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('SECURITY_FAILED_CHANGE_SECURITY_DIALOG_VISIBILITY'));
            if ($(this.owner.security_dialog_visibility_id)) {
                $(this.owner.security_dialog_visibility_id).checked = !$(this.owner.security_dialog_visibility_id).checked;
            }
        } catch (e) {}
    },

    request: function(request_if_failed) {
        var checkbox_id = this.owner.security_dialog_visibility_id;
        var visibility;

        if (request_if_failed && !this.failed) return true;
        if (!$(checkbox_id)) return false;

        visibility = $(checkbox_id).checked ? "true" : "false";

        if (!this.parent({serial: "visibility=" + visibility})) return false;

        return true;
    }
});


var class_mstweb_page_security = class_mst_page.extend({
    controller: "mstweb_page_security",
    cookie_hide_security_dialog: "mst_security_hide_security_dialog",

    allow_list_id: "page_security_allow",
    deny_list_id: "page_security_deny",
    default_id: "page_security_default",
    security_dialog_visibility_id: "mst_sec_setting_security_dialog_visibility",
    forcibly_show_security_dialog: null,

    timer_id: null,
    timer_cnt: 0,

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_security_get_page(),
        set_default: new class_mst_ajax_security_set_default(),
        get_default: new class_mst_ajax_security_get_default(),
        remove: new class_mst_ajax_security_remove(),
        to_deny: new class_mst_ajax_security_to_deny(),
        to_allow: new class_mst_ajax_security_to_allow(),
        get_deny_list: new class_mst_ajax_security_get_deny_list(),
        get_allow_list: new class_mst_ajax_security_get_allow_list(),
        set_security_dialog_visibility: new class_mst_ajax_security_set_security_dialog_visibility()
    }),

    initialize: function() { 
        this.parent('security', 'mst_page_security');
        this.ajax.init(this, this.controller);
    }, 

    enter: function() {
        if (!this.parent()) return false;
        this.timer_cnt = 0;
        if (!this.load_completed) return true;
        this.on_load_complete();
        return true;
    },

    leave: function() {
        if (this.timer_id) {
            clearInterval(this.timer_id);
            this.timer_id = null;
        }
        return this.parent();
    },

    terminate: function() {
        this.ajax.ajax.get_allow_list.failed = true;
        this.ajax.ajax.get_deny_list.failed = true;
        this.ajax.ajax.get_default.failed = true;
    },

    reload: function() {
        this.load_completed = false;
        this.ajax.ajax.get_allow_list.failed = true;
        this.ajax.ajax.get_deny_list.failed = true;
        this.ajax.ajax.get_default.failed = true;
        this.on_load();
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
        return true;
    },

    on_load_complete: function() {
        this.ajax.ajax.get_default.request(true, true);

        if (this.security_dialog_enable()) {
            if ($(this.security_dialog_visibility_id)) {
                $(this.security_dialog_visibility_id).checked = true;
            }
        }

        this.start_timer(1000);

        return true;
    },

    start_timer: function(period) {
        if (this.timer_id) {
            clearInterval(this.timer_id);
            this.timer_id = null;
        }
        this.timer_id = setInterval(this.on_timer.bind(this), period);
    },

    on_timer: function() {
        if (this.timer_id && $(this.default_id)) {
            switch (this.timer_cnt++ % 3) {
            case 0: this.ajax.ajax.get_deny_list.request(true); break;
            case 1: this.ajax.ajax.get_allow_list.request(true); break;
            case 2: this.ajax.ajax.get_default.request(false, false); break;
            default: break;
            }
            if (this.timer_cnt == 3) this.start_timer(10000);
        }
    },

    security_dialog_enable: function() {
        if (this.forcibly_show_security_dialog) return true;
        if (!$(this.security_dialog_visibility_id)) return true;
        return $(this.security_dialog_visibility_id).checked;
    },

    update_host: function() {
    },

    change_security_dialog_visibility: function() {
        if (!this.ajax.ajax.set_security_dialog_visibility.request(false)) {
            if ($(this.security_dialog_visibility_id)) {
                $(this.security_dialog_visibility_id).checked = !$(this.security_dialog_visibility_id).checked;
            }
        }
    }
});
