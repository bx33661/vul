/* $Id: mstweb_menu.js,v 1.9 2007/01/26 09:54:58 iwamoto Exp $ */

var class_mst_tab_main = class_mst_tab.extend({
    completed_rounding: null,

    initialize: function(pages, initial_page_name, tab_ids, class_tab) {
        this.parent(pages, initial_page_name, tab_ids, class_tab);
    },

    init: function() {
        this.parent();
        this.rounding();
    },

    rounding: function() {
        $S('#menu_list li').each(function(el) {
            el.makeRounded(false, "top", {radius: 8});
        });
        $('contents2').makeRounded(false, "bottom, top right", {radius: 8});
        this.completed_rounding = true;
    },

    on_class_changed : function(el) {
        if (!this.completed_rounding) return false;
        el.makeRounded(true, "top");
    }
});
