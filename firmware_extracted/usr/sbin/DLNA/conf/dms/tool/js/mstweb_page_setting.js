/* $Id: mstweb_page_setting.js,v 1.11 2007/01/26 09:54:58 iwamoto Exp $ */

var class_mst_ajax_setting_get_page = class_mst_ajax.extend({
    action: "get_page_setting",

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


var class_mstweb_page_setting = class_mst_page.extend({
    controller: "mstweb_page_setting",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_setting_get_page()
    }),

    initialize: function() {
        this.parent('setting', 'mst_page_setting');
        this.ajax.init(this, this.controller);
    }, 

    enter: function() {
        if (!this.parent()) return false;
        if (!this.load_completed) return true;
        mst_tab_setting.open(mst_tab_setting.state.active_page_name);
        return true;
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
        return true;
    },

    on_load_complete: function() {
        mst_tab_setting.init();
    },

    on_leave: function() {
        this.ajax.abort_all_connection();
    }
});

var class_mst_tab_setting = class_mst_tab.extend({
    completed_rounding: null,

    initialize: function(pages, initial_page_name, tab_ids, class_tab) {
        this.parent(pages, initial_page_name, tab_ids, class_tab);
    },

    init: function() {
        this.rounding();
        this.parent();
    },

    rounding: function() {
        $("mst_setting_content_block1").makeRounded(false);
        $S('#setting_menu_list li').each(function(el) {
            el.makeRounded(false, "top left, bottom left", {radius: 8});
        });
        this.completed_rounding = true;
    },

    on_class_changed : function(el) {
        if (!this.completed_rounding) return false;
        if (el && el.makeRounded) el.makeRounded(true, "top left, bottom left");
    }
});
