/* $Id: list.js,v 1.10 2007/05/07 14:34:22 iwamoto Exp $ */

var class_list = Class.create();

class_list.prototype = {
    tr_name: "",
    table_name: "",
    form_name: "",
    prev_update_id: null,

    class_btn: {
        'allon': {
            'normal': "icon_item_allon",
            'dim': "icon_item_allon_dim",
            'push': "icon_item_allon_push",
            'hover': "icon_item_allon_hover"
        },

        'alloff': {
            'normal': "icon_item_alloff",
            'dim': "icon_item_alloff_dim",
            'push': "icon_item_alloff_push",
            'hover': "icon_item_alloff_hover"
        },
            
        'reload': {
            'normal': "icon_item_reload",
            'dim': "icon_item_reload_dim",
            'push': "icon_item_reload_push",
            'hover': "icon_item_reload_hover"
        }
    },

    class_selected: "list_body2_selected",
    class_normal: "list_body2",
    class_hover: "list_body2_hover",
    is_disabled_btns: false,
    non_target_class: "",

    initialize: function(tr_name, table_name, form_name) {
        this.tr_name = tr_name;
        this.table_name = table_name;
        this.form_name = form_name;
    },

    _getElementsByName_iefix: function(tag, name) {
         var elem = document.getElementsByTagName(tag);
         var arr = new Array();
         var iarr;
         for(i = 0,iarr = 0; i < elem.length; i++) {
              att = elem[i].getAttribute("name");
              if(att == name) {
                   arr[iarr++] = elem[i];
              }
         }
         return arr;
    },

    _get_first_tag_elem: function(elem, tag) {
        var tgt = null;

        if (!elem) return null;

        for (var i = 0; i < elem.childNodes.length; ++i) {
            if (tag == elem.childNodes[i].tagName) {
                tgt = elem.childNodes[i];
                break;
            }
        }
        return tgt;
    },

    _change_all_tr: function(action) {
        if (this.is_disabled_btns) return true;
        var se_name = this.tr_name.replace('_tr', '_se');
        if (!document[this.form_name]) return false;
        var es_se = document[this.form_name][se_name];
        if (!es_se) return false;
        var len = es_se.length;
        if (len) {
            for (var i = 0; i < len; ++i) {
                es_se[i].checked = (action == "select") ? true : false;
            }
        } else {
            es_se.checked = (action == "select") ? true : false;
        }
        return true;
    },

    _view_update_tr_update_elem: function(e_se) {
        if (!e_se || !e_se.parentNode) return false;
        var e_tr = e_se.parentNode.parentNode;
        if (e_tr && e_se.checked) {
            if (e_tr.className != this.class_selected) {
                e_tr.className = this.class_selected;
            }
        } else {
            if (e_tr.className != this.class_normal) {
                e_tr.className = this.class_normal;
            }
        }
        return true;
    },

    _view_update_tr: function() {
        var se_name = this.tr_name.replace('_tr', '_se');
        if (!document[this.form_name]) return false;
        var es_se = document[this.form_name][se_name];
        if (!es_se) return false;
        var len = es_se.length;
        if (len) {
            for (var i = 0; i < len; ++i) {
                this._view_update_tr_update_elem(es_se[i]);
            }
        } else {
            this._view_update_tr_update_elem(es_se);
        }
        return true;
    },

    _get_tr_elements: function() {
        var e_tb = this._get_first_tag_elem($(this.table_name), "TBODY");
        if (!e_tb) return null;

        var arr = new Array();
        for (var i = 0; i < e_tb.childNodes.length; ++i) {
            var e_tr = e_tb.childNodes[i];
            if ("TR" == e_tr.tagName && e_tr.getAttribute("name") == this.tr_name) {
                arr.push(e_tr);
            }
        }

        return arr;
    },

    _get_nselected: function() {
        var nselected = 0;
        var len = 0;
        var se_name = this.tr_name.replace('_tr', '_se');
        if (!document[this.form_name]) return 0;
        var es_se = document[this.form_name][se_name];
        if (es_se) {
            len = es_se.length;
            if (len) {
                for (var i = 0; i < len; ++i) {
                    if (es_se[i].checked) ++nselected;
                }
            } else {
                len = 1;
                nselected = es_se.checked ? 1 : 0;
            }
        }
        return {'nselected': nselected, 'nelem': len};
    },

    _view_update_btn: function() {
        var h = this._get_nselected();
        var nelem = h.nelem;
        var nselected = h.nselected;

        var class_name_all;
        var class_name_none;
        var class_name_reload;
        if (this.is_disabled_btns) {
            class_name_all = this.class_btn.allon.dim;
            class_name_none = this.class_btn.alloff.dim;
            class_name_reload = this.class_btn.reload.dim;
        }  else {
            class_name_reload = this.class_btn.reload.normal;
            if (nelem == 0) {
                class_name_all = this.class_btn.allon.dim;
                class_name_none = this.class_btn.alloff.dim;
            } else if (nelem == nselected) {
                class_name_all = this.class_btn.allon.dim;
                class_name_none = this.class_btn.alloff.normal;
            } else if (nselected == 0) {
                class_name_all = this.class_btn.allon.normal;
                class_name_none = this.class_btn.alloff.dim;
            } else {
                class_name_all = this.class_btn.allon.normal;
                class_name_none = this.class_btn.alloff.normal;
            }
        }

        var e_btn_all = $(this.tr_name.toString().replace("_tr", "_btn_all"));
        var e_btn_none = $(this.tr_name.toString().replace("_tr", "_btn_none"));
        var e_btn_reload = $(this.tr_name.toString().replace("_tr", "_btn_reload"));

        if (e_btn_all.className != class_name_all) {
            e_btn_all.className = class_name_all;
        }
        if (e_btn_none.className != class_name_none) {
            e_btn_none.className = class_name_none;
        }
        if (e_btn_reload.className != class_name_reload) {
            e_btn_reload.className = class_name_reload;
        }
    },

    _change_btn: function(id, type, action) {
        var e_btn = $(id);
        if (!e_btn) return false;

        if (e_btn.className == this.class_btn[type].dim) return true;

        if (action == 'over') {
            if (e_btn.className != this.class_btn[type].hover) {
                e_btn.className = this.class_btn[type].hover;
            }
        } else if (action == 'out') {
            if (e_btn.className != this.class_btn[type].normal) {
                e_btn.className = this.class_btn[type].normal;
            }
        } else if (action == 'down') {
            if (e_btn.className != this.class_btn[type].push) {
                e_btn.className = this.class_btn[type].push;
            }
        } else if (action == 'up') {
            if (e_btn.className != this.class_btn[type].normal) {
                e_btn.className = this.class_btn[type].normal;
            }
        } else {
            return false;
        }

        return true;
    },

    _change_tr: function(id, action) {
        var e_tr = $(id);
        var e_se = $(id.replace('tr', 'se'));
        var prev_id = this.prev_update_id;
        this.prev_update_id = id;

        if (action == 'over') {
            if (prev_id == id) return;
            this.view_update_tr();
            if (this.non_target_class != "" && e_tr.className == this.non_target_class) return;
            if (e_se && !e_se.checked) { 
                if (e_tr.className != this.class_hover) {
                    e_tr.className = this.class_hover; 
                }
            }
        } else if (action == 'out'){
            this.prev_update_id = null;
            this.view_update_tr();
        } else if (action == 'down') {
            var es_se = this._getElementsByName_iefix("input", this.tr_name.replace('tr', 'se'));
            if (!e_se) return;
            var check = e_se && !e_se.checked;
            var nselected_except_me = 0;
            this.view_update_tr();
            for (var i = 0; i < es_se.length; ++i) { 
                var e_tr_tgt = $(es_se[i].id.toString().replace('_se_', '_tr_'));
                if (this.non_target_class != "" && e_tr_tgt.className == this.non_target_class) continue;
                if (e_tr_tgt.className != this.class_normal) {
                    e_tr_tgt.className = this.class_normal;
                }
                if (es_se[i].checked && (es_se[i] != e_se)) ++nselected_except_me;
                es_se[i].checked = false; 
            }
            if (check || nselected_except_me) {
                e_se.checked = true;
            }
            this.view_update_tr();
            this.view_update_btn();
        } else if (action == 'down_non_reversal') {
            if (!e_se.checked) {
                this._change_tr(id, 'down');
                this.view_update_btn();
            }
        } else if (action == 'add') {
            if (this.non_target_class != "" && e_tr.className == this.non_target_class) return;
            if (e_se.checked) {
                if (e_tr.className != this.class_hover) {
                    e_tr.className = this.class_hover;
                }
            } else {
                if (e_tr.className != this.class_selected) {
                    e_tr.className = this.class_selected;
                }
            }
            this.view_update_btn();
        } else if (action == 'select') {
            e_se.checked = true;
            this.view_update_btn();
        } else if (action == 'unselect') {
            e_se.checked = false;
            this.view_update_btn();
        }
    },

    _get_selected_tr_ids: function() {
        var arr = new Array();
        var se_name = this.tr_name.replace('_tr', '_se');
        if (!document[this.form_name]) return arr;
        var es_se = document[this.form_name][se_name];
        if (!es_se) return arr;

        len = es_se.length;
        if (len) {
            for (var i = 0; i < len; ++i) {
                if (es_se[i].checked) {
                    arr.push(es_se[i].parentNode.parentNode.id);
                }
            }
        } else {
            if (es_se.checked) {
                arr.push(es_se.parentNode.parentNode.id);
            }
        }
        return arr;
    },

    _disable_btns: function(disable) {
        this.is_disabled_btns = disable;
    }
};


/*** SHARE ***/

var class_list_share = Class.create();
class_list_share.prototype = Object.extend(new class_list(), {
    id_btn_add : "mst_con_share_add",
    id_btn_release : "mst_con_share_del",
    id_btn_reload : "share_btn_reload",
    id_btn_repair : "mst_con_share_repair",
    _enable_repairing: true,

    initialize: function(tr_name, table_name, form_name) {
        this.table_name = table_name;
        this.tr_name = tr_name;
        this.form_name = form_name;
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        this._view_update_btn();
        var h = this._get_nselected();
        var e_rel = $(this.id_btn_release);
        var e_add = $(this.id_btn_add);
        var e_reload = $(this.id_btn_reload);
        var e_repair = $(this.id_btn_repair);

        if (this.is_disabled_btns) {
            e_rel.disabled = true;
            e_reload.disabled = true;
            e_add.disabled = true;
            e_repair.disabled = true;
        } else {
            e_rel.disabled = (0 < h.nselected) ? false: true;
            e_reload.disabled = false;
            e_add.disabled = false;
            if (this._enable_repairing) {
                e_repair.disabled = (0 < h.nselected) ? false: true;
            } else {
                e_repair.disabled = true;
            }
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },

    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    },

/*** original ***/
    enable_repairing: function(enable) {
        this._enable_repairing = enable;
        this.view_update();
    }
});


/*** SECURITY - ALLOW ***/

var class_list_security_allow = Class.create();
class_list_security_allow.prototype = Object.extend(new class_list(), {
    id_btn_remove : "sec_a_btn_remove",
    id_btn_to_deny : "sec_a_btn_to_deny",
    id_btn_reload : "sec_a_btn_reload",

    initialize: function(tr_name, table_name, form_name) {
        this.tr_name = tr_name;
        this.table_name = table_name;
        this.form_name = form_name;
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        this._view_update_btn();
        var h = this._get_nselected();
        var e_remove = $(this.id_btn_remove);
        var e_reload = $(this.id_btn_reload);
        var e_to_deny = $(this.id_btn_to_deny);

        if (this.is_disabled_btns) {
            e_reload.disabled = true;
            e_remove.disabled = true;
            e_to_deny.disabled = true;
        } else {
            e_reload.disabled = false;
            e_remove.disabled = (0 < h.nselected) ? false: true;
            e_to_deny.disabled = (0 < h.nselected) ? false: true;
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },

    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    }
});


/*** SECURITY - DENY ***/

var class_list_security_deny = Class.create();
class_list_security_deny.prototype = Object.extend(new class_list(), {
    id_btn_remove : "sec_d_btn_remove",
    id_btn_to_allow : "sec_d_btn_to_allow",
    id_btn_reload : "sec_d_btn_reload",

    initialize: function(tr_name, table_name, form_name) {
        this.tr_name = tr_name;
        this.table_name = table_name;
        this.form_name = form_name;
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        this._view_update_btn();
        var h = this._get_nselected();
        var e_remove = $(this.id_btn_remove);
        var e_reload = $(this.id_btn_reload);
        var e_to_allow = $(this.id_btn_to_allow);

        if (this.is_disabled_btns) {
            e_reload.disabled = true;
            e_remove.disabled = true;
            e_to_allow.disabled = true;
        } else {
            e_reload.disabled = false;
            e_remove.disabled = (0 < h.nselected) ? false: true;
            e_to_allow.disabled = (0 < h.nselected) ? false: true;
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },

    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
        this.view_update_btn();
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    }
});

/*** MIMETYPE ***/

var class_list_mime = Class.create();
class_list_mime.prototype = Object.extend(new class_list(), {
    id_btn_add : "mime_btn_add",
    id_btn_reload : "mime_btn_reload",
    id_btn_del : "mime_btn_del",
    id_btn_edit : "mime_btn_edit",
    id_btn_reset : "mime_btn_reset",
    showing_edit_box: false,
    showing_add_box: false,


    initialize: function(tr_name, table_name, form_name) {
        this.table_name = table_name;
        this.tr_name = tr_name;
        this.form_name = form_name;
        this.showing_edit_box = false;
        this.showing_add_box = false;
        
        // override
        this.non_target_class = "list_body2_edit";
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        this._view_update_btn();
        var h = this._get_nselected();
        var e_add = $(this.id_btn_add);
        var e_reload = $(this.id_btn_reload);
        var e_del = $(this.id_btn_del);
        var e_reset = $(this.id_btn_reset);
        var e_edit = $(this.id_btn_edit);

        if (this.is_disabled_btns) {
            e_reload.disabled = true;
            e_add.disabled = true;
            e_del.disabled = true;
            e_edit.disabled = true;
            e_reset.disabled = true;
        } else {
            e_reload.disabled = false;
            e_add.disabled = this.showing_add_box ? true : false;
            e_del.disabled = (0 < h.nselected) ? false : true;
            e_edit.disabled = (!this.showing_edit_box && 1 == h.nselected) ? false : true;
            e_reset.disabled = false;
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },


    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    },

    get_selected_tr_ids: function() {
        return this._get_selected_tr_ids();
    },

/* original */
    show_edit_box: function(show) {
        this.showing_edit_box = show;
    },

    show_add_box: function(show) {
        this.showing_add_box = show;
    }
});


/*** BACKUP_SERVER_LIST ***/

var class_list_bu_s = Class.create();
class_list_bu_s.prototype = Object.extend(new class_list(), {
    id_btn_reload : "bu_s_btn_reload",

    initialize: function(tr_name, table_name, form_name) {
        this.table_name = table_name;
        this.tr_name = tr_name;
        this.form_name = form_name;
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        var h = this._get_nselected();
        var e_reload = $(this.id_btn_reload);
        if (!e_reload) return false;

        if (this.is_disabled_btns) {
            e_reload.disabled = true;
        } else {
            e_reload.disabled = false;
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },

    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    },

    get_selected_tr_ids: function() {
        return this._get_selected_tr_ids();
    }
});


/*** BACKUP_TASK_LIST ***/

var class_list_bu_t = Class.create();
class_list_bu_t.prototype = Object.extend(new class_list(), {
    id_btn_add : "bu_t_btn_add",
    id_btn_reload : "bu_t_btn_reload",
    id_btn_del : "bu_t_btn_del",

    initialize: function(tr_name, table_name, form_name) {
        this.table_name = table_name;
        this.tr_name = tr_name;
        this.form_name = form_name;
    },

    change_all_tr: function(action) {
        this._change_all_tr(action);
        this.view_update();
    },

    view_update_btn: function() {
        this._view_update_btn();
        var h = this._get_nselected();
        var e_add = $(this.id_btn_add);
        var e_reload = $(this.id_btn_reload);
        var e_del = $(this.id_btn_del);

        if (this.is_disabled_btns) {
            e_reload.disabled = true;
            e_add.disabled = true;
            e_del.disabled = true;
        } else {
            e_reload.disabled = false;
            e_add.disabled = false;
            e_del.disabled = (0 < h.nselected) ? false : true;
        }
    },

    change_tr: function(id, action) {
        this._change_tr(id, action);
    },

    disable_btns: function(disable) {
        this._disable_btns(disable);
        this.view_update_btn();
    },

    view_update_tr: function() {
        this._view_update_tr();
    },

    change_btn: function(id, type, action) {
        this._change_btn(id, type, action);
    },

    view_update: function() {
        this.view_update_tr();
        this.view_update_btn();
    },

    get_selected_tr_ids: function() {
        return this._get_selected_tr_ids();
    }
});

