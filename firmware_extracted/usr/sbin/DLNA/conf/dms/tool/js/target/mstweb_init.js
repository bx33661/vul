mst_tab_main.pages.initial_page_name = "security";

/*  XXX: for fixed window
mst_tab_main.rounding = function() {
    $S('#menu_list li').each(function(el) {
        el.makeRounded(false, "top", {radius: 8});
    });
    this.completed_rounding = true;
};

mst_tab_main.on_class_changed = function(el) {
    if (!this.completed_rounding) return false;
    el.makeRounded(true, "top");
};

mst_tab_setting.rounding = function() {
    $("mst_setting_content_block1").makeRounded(false);
    $S('#setting_menu_list li').each(function(el) {
        el.makeRounded(false, "top left, bottom left", {radius: 8});
    });
    this.completed_rounding = true;
};

mst_tab_setting.on_class_changed = function(el) {
    if (!this.completed_rounding) return false;
    if (el && el.makeRounded) el.makeRounded(true, "top left, bottom left");
};
*/

/*
mstweb_util.update_innerhtml = function(id, msg) {
    if (!$(id)) return false;

    if ($(id).mst_original && $(id).mst_original == msg) return true;
    $(id).mst_original = msg;
    $(id).innerHTML = msg;

    return true;
};
*/
