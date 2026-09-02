/* $Id: mstweb_page_view.js,v 1.10 2007/01/16 15:57:04 iwamoto Exp $ */

var class_mst_ajax_view_get_page = class_mst_ajax.extend({
    action: "get_page_view",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {$ERR("v1");}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_setting')) {$ERR("v2");}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_view_change_language = class_mst_ajax.extend({
    action: "change_language",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, 'mst_state_view')) {}
            location.reload();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_view')) {}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert(msg('VIEW_FAILED_CHANGE_LANGUAGE'));
    },

    request: function(lang) {
        var form;
        if (lang) {
            form = "language=" + lang;
        } else {
            form = Form.serialize($('form_setting_view_language'));
        }
        if (!this.parent({serial:form})) return false;
        return true;
    }
});

var class_mstweb_page_view = class_mst_page.extend({
    controller: "mstweb_page_view",

    ajax: new class_mst_ajaxes ({
        get_page: new class_mst_ajax_view_get_page(),
        change_language: new class_mst_ajax_view_change_language()
    }),

    initialize: function() {
        this.parent('view', 'mst_page_view');
        this.ajax.init(this, this.controller);
    }, 

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        var style_name = ss_get_style();
        if (style_name) {
            var elem = $('setting_view_skin_' + style_name);
            if (elem) {
                elem.selected = "selected";
            }
        }
    },

    change_stylesheet: function() {
        var selected = Form.Element.Serializers.select($("select_setting_view_skin"));
        if (selected.length != 2) {
            alert(msg('VIEW_FAILED_CHANGE_STYLESHEET'));
            return;
        }
        ss_switch_style(selected[1]);
    },

    on_apply: function() {
        this.ajax.ajax.change_language.request();
        this.change_stylesheet();
        return true;
    }
});

