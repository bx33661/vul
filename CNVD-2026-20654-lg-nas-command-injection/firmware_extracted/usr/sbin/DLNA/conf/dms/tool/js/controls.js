/* $Id: controls.js,v 1.7 2007/02/08 13:50:30 iwamoto Exp $ */

/*** CONTROL ***/

var class_control = Class.create();

class_control.prototype = {
    id: null,

    initialize: function(id) {
        this.id = id;
    },

    _change_state: function(action) {
                 
    }
};


var class_control_reload = Class.create();
class_control_reload.prototype = Object.extend(new class_control(), {
    class_name: {
        'normal': "icon_item_reload",
        'dim': "icon_item_reload_dim",
        'hover': "icon_item_reload_hover",
        'push': "icon_item_reload_push"
    },

    initialize: function(id) {
        this.id = id;
    },

/* virtual */
    change_state: function(action) {
        var elem = $(this.id);
        if (!elem) return false;
        if (action == 'disable') {
            if (elem.className != this.class_name.dim) {
                elem.className = this.class_name.dim;
            }
        } else if (action == 'normal') {
            if (elem.className != this.class_name.normal) {
                elem.className = this.class_name.normal;
            }
        } else if (action == 'hover') {
            if (elem.className != this.class_name.hover) {
                elem.className = this.class_name.hover;
            }
        } else if (action == 'push') {
            if (elem.className != this.class_name.push) {
                elem.className = this.class_name.push;
            }
        } else {
            return false;
        }
        return true;
    }

/* original */
});


/*** CONTROLS ***/

var class_controls = Class.create();

class_controls.prototype = {
    controls: new Object(),
    controls_is_disalbe: false,

    initialize: function() {
    },

    _add_control: function(name, id) {
        this.controls[name] = id;
    },

    _get_control: function(name) {
        return this.controls[name];
    },

    _disable_controls: function(disable) {
        this.controls_is_disalbe = disable;
        this.update_view();
    },

    _disable_control: function(disable, name) {
        $(this.controls[name]).disabled = disable;
    },

    _update_view: function() {
                           
    }
};


var class_controls_password = Class.create();
class_controls_password.prototype = Object.extend(new class_controls(), {
    id_btn_apply: "mstweb_page_password_btn_apply",
    id_btn_clear: "mstweb_page_password_btn_clear",
    id_txt_pass1: "mstweb_page_password_txt_pass1",
    id_txt_pass2: "mstweb_page_password_txt_pass2",
    id_warn_pass: "mstweb_page_password_warn_pass",

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_txt_pass1 = $(this.id_txt_pass1);
        var e_txt_pass2 = $(this.id_txt_pass2);
        var e_btn_apply = $(this.id_btn_apply);
        var e_btn_clear = $(this.id_btn_clear);
        var e_warn_pass = $(this.id_warn_pass);
        var d_btn_apply = true;
        var d_btn_clear = true;
        var d_warn_pass = true;
        var d_txt_pass1 = true;
        var d_txt_pass2 = true;

        if (!this.controls_is_disalbe) {
            d_txt_pass1 = false;
            d_txt_pass2 = false;
            if (e_txt_pass1.value.length || e_txt_pass2.value.length) {
                d_btn_clear = false;
                if (e_txt_pass1.value == e_txt_pass2.value) {
                    d_btn_apply = false;
                } else {
                    if (e_txt_pass1.value.length <= e_txt_pass2.value.length) {
                        d_warn_pass = false;
                    }
                }
            }
        }

        e_btn_apply.disabled = d_btn_apply;
        e_btn_clear.disabled = d_btn_clear;
        e_txt_pass1.disabled = d_txt_pass1;
        e_txt_pass2.disabled = d_txt_pass2;
        e_warn_pass.style.display = d_warn_pass ? "none" : "inline";
    },

/* original */
    clear_text_box: function() {
        var e_txt_pass1 = $(this.id_txt_pass1);
        var e_txt_pass2 = $(this.id_txt_pass2);

        $(this.id_txt_pass1).value = "";
        $(this.id_txt_pass2).value = "";
    }
});


var class_controls_name = Class.create();
class_controls_name.prototype = Object.extend(new class_controls(), {
    id_btn_apply: "page_name_btn_apply",
    id_btn_undo: "page_name_btn_undo",
    id_txt_server: "page_name_server_name",

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_btn_apply = $(this.id_btn_apply);
        var e_btn_undo = $(this.id_btn_undo);
        var e_txt_server = $(this.id_txt_server);

        var d_btn_apply = true;
        var d_btn_undo = true;
        var d_txt_server = true;

        if (!this.controls_is_disalbe) {
            d_txt_server = false;
            if (e_txt_server.defaultValue != e_txt_server.value) {
                d_btn_apply = false;
                d_btn_undo = false;
            }
        }

        e_btn_apply.disabled = d_btn_apply;
        e_btn_undo.disabled = d_btn_undo;
        e_txt_server.disabled = d_txt_server;
    },

/* original */
    update_server_name: function(new_server_name) {
        $(this.id_txt_server).value = new_server_name;
        $(this.id_txt_server).defaultValue = new_server_name;
    },

    undo_server_name: function() {
        $(this.id_txt_server).value = $(this.id_txt_server).defaultValue;
        this.update_view();
    }
});

var class_controls_database = Class.create();
class_controls_database.prototype = Object.extend(new class_controls(), {
    id_btn_exec: "mst_con_database_rebuild",

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_btn_exec = $(this.id_btn_exec);
        var d_btn_exec = true;

        if (!this.controls_is_disalbe) {
            d_btn_exec = false;
        }

        e_btn_exec.disabled = d_btn_exec;
    }
});

var class_controls_mimetype_add = Class.create();
class_controls_mimetype_add.prototype = Object.extend(new class_controls(), {
    id_btn_apply: "page_mimetype_add_btn_apply",
    id_btn_cancel: "page_mimetype_add_btn_cancel",
    id_txt_ext: "page_mimetype_add_txt_extention",
    id_txt_mime: "page_mimetype_add_txt_mimetype",

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_btn_apply = $(this.id_btn_apply);
        var e_btn_cancel = $(this.id_btn_cancel);
        var e_txt_ext = $(this.id_txt_ext);
        var e_txt_mime = $(this.id_txt_mime);

        var d_btn_apply = true;
        var d_btn_cancel = true;
        var d_txt_ext = true;
        var d_txt_mime = true;

        if (!this.controls_is_disalbe) {
            d_btn_apply = (e_txt_ext.value != "" && e_txt_mime.value != "") ? false : true;

            d_btn_cancel = false;
            d_txt_ext = false;
            d_txt_mime = false;
        }

        e_btn_apply.disabled = d_btn_apply;
        e_btn_cancel.disabled = d_btn_cancel;
        e_txt_ext.disabled = d_txt_ext;
        e_txt_mime.disabled = d_txt_mime;
    },

/* original */
    clear_text: function() {
        $(this.id_txt_ext).value = "";
        $(this.id_txt_mime).value = "";
    }
});


var class_controls_register_task = Class.create();
class_controls_register_task.prototype = Object.extend(new class_controls(), {
    id_btn_apply: "page_backup_register_task_btn_apply",
    id_txt_container: "page_backup_register_task_txt_container",
    control_reload: new class_control_reload('page_backup_register_task_btn_reload'),

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_btn_apply = $(this.id_btn_apply);
        var e_txt_container = $(this.id_txt_container);

        var d_btn_apply = true;
        var d_btn_reload = true;
        var d_txt_container = true;

        if (!this.controls_is_disalbe) {
            d_btn_apply = (e_txt_container.value) == "" ? true : false;
            d_btn_reload = false;
            d_txt_container = false;
        }

        e_btn_apply.disabled = d_btn_apply;
        e_txt_container.disabled = d_txt_container;
        this.control_reload.change_state(d_btn_reload ? 'disable' : 'normal');
    },

/* original */
    update_container_name: function(container_name) {
        $(this.id_txt_container).value = container_name;
        this.update_view();
    },

    change_state: function(item, state) {
        if (this.controls_is_disalbe) return true;

        if (item == 'reload') {
            if (state == 'hover') {
                this.control_reload.change_state('hover');
            } else if (state == 'normal') {
                this.control_reload.change_state('normal');
            } else if (state == 'push') {
                this.control_reload.change_state('push');
            } else {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
});


var class_controls_control = Class.create();
class_controls_control.prototype = Object.extend(new class_controls(), {
    id_btn_control: "mst_control_change_btn",
    id_txt_state: "mst_control_state_tbl_td_state",
    id_chk_autorun: "mst_control_autorun_chk",

    msg: {
        start_server: null,
        stop_server: null,
        service_stopped: null,
        server_stopping: null,
        server_stopped: null,
        server_starting: null,
        server_started: null
    },

    initialize: function() {
    },

/* virtual */
    add_control: function(name, id) {
        return this._add_control(name, id);
    },

    get_control: function(name) {
        return this._get_control(name);
    },

    disable_controls: function(disable) {
        this._disable_controls(disable);
    },

    disable_control: function(disable, name) {
        return this._disable_control(disable, name);
    },

    update_view: function() {
        var e_btn_control = $(this.id_btn_control);
        var e_txt_state = $(this.id_txt_state);

        var d_btn_control = true;

        if (!this.controls_is_disalbe) {
            d_btn_control = false;
        }
        e_btn_control.disabled = d_btn_control;
    },

/* original */
    set_text: function(text) {
        for (var i in text) {
            this.msg[i] = text[i];
        }
    },

    _change_ui: function(state, btn) {
        mstweb_util.update_innerhtml(this.id_txt_state, state);
        $(this.id_btn_control).value = btn;
    },

    _change_ui_chk: function(checked) {
        var elem = $(this.id_chk_autorun);
        if (!elem) return false;
        $(this.id_chk_autorun).checked = checked;
        return true;
    },

    change_state: function(state) {
        switch (state) {
        case 'service_stopped' :
            this.disable_controls(true);
            this._change_ui(this.msg.service_stopped, this.msg.start_server);
            break;
        case 'server_stopping' :
            this.disable_controls(true);
            this._change_ui(this.msg.server_stopping, this.msg.stop_server);
            break;
        case 'server_stopped' :
            this.disable_controls(false);
            this._change_ui(this.msg.server_stopped, this.msg.start_server);
            break;
        case 'server_starting' :
            this.disable_controls(true);
            this._change_ui(this.msg.server_starting, this.msg.start_server);
            break;
        case 'server_started' :
            this.disable_controls(false);
            this._change_ui(this.msg.server_started, this.msg.stop_server);
            break;
        case 'autorun_enabled' :
            this.disable_controls(false);
            this._change_ui_chk(true);
            break;
        case 'autorun_disabled' :
            this.disable_controls(false);
            this._change_ui_chk(false);
            break;
        }
        this.update_view();
        return true;
    }
});

