/* $Id$ */

var class_mst_ajax_passowrd_get_page = class_mst_ajax.extend({
    action: "get_page_activation",

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

var class_mstweb_page_activation = class_mst_page.extend({
    controller: "mstweb_page_activation",
    timer_id: null,
    active: false,

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_passowrd_get_page()
    }),

    initialize: function() {
        this.parent('activation', 'mst_page_activation');
        this.ajax.init(this, this.controller);
    }, 

    enter: function() {
        this.active = true;
        if (!this.parent()) return false;
        if (!this.load_completed) return true;
        this.on_load_complete();
        return true;
    },

    leave: function() {
        this.active = false;
        return this.parent();
    },

    start_timer: function(period) {
        if (!this.timer_id) {
            this.timer_id = setInterval(this.on_timer.bind(this), period);
        }
    },

    on_timer: function() {
        if (this.active) {
            this.ajax.ajax.get_page.request();
        }
    },

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        this.start_timer(5000);
    }
});

