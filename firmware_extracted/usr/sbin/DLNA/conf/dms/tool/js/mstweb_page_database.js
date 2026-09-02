/* $Id: mstweb_page_database.js,v 1.1 2007/01/27 08:42:34 iwamoto Exp $ */

var class_mst_ajax_database_get_page = class_mst_ajax.extend({
    action: "get_page_database",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, this.owner.page_id)) {$ERR("d1");}
            this.owner.load_complete();
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_setting')) {$ERR("d2");}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        mstweb_util.update_innerhtml(this.owner.page_id, msg('COMMON_FAILED_LOADING_PAGE'));
    }
});

var class_mst_ajax_database_rebuild_database = class_mst_ajax.extend({
    action: "rebuild_database",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_database')) {$ERR("d5");}
            }
        } catch (e) {}
        cntl('database', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('DATABASE_FAILED_REPAIR_DATABASE'));
        } catch (e) {}
        cntl('database', 'disable_controls', false);
    },

    request: function() {
        if (!this.parent()) return false;
        cntl('database', 'disable_controls', true);
        return true;
    }
});

var class_mstweb_page_database = class_mst_page.extend({
    controller: "mstweb_page_database",

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_database_get_page(),
        rebuild_database: new class_mst_ajax_database_rebuild_database()
    }),

    initialize: function() {
        this.parent('name', 'mst_page_database');
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
    },

    view_update_btn: function() {
        return true;
    }
});


