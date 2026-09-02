/* $Id: mstweb_init.js,v 1.20 2007/03/20 05:13:44 iwamoto Exp $ */

/*** util ***/
var mstweb_util = new class_mstweb_util();
mstweb_util._show_error_dialog = false;

var tut_browser = new class_tut_browser();
var tut_xml = new class_tut_xml();

function $ERR(msg) { return mstweb_util.show_error_dialog(msg); }
function $INF(msg) { return mstweb_util.show_info_dialog(msg); }
function $DBG(msg) { return mstweb_util.show_debug_dialog(msg); }

/*** mst ***/
class_mst_ajax.url = "window.location";

var mst_message = new class_mst_message();

function msg(id) {
    var str = mst_message.get_message(id);
    if (!str) {$ERR("msg:1"); return "";}
    str = str.replace("\\n", "\n");
    str = str.replace("\\t", "\t");
    return str;
}

function msg_add(hash) {
    return mst_message.add_messages(hash);
}


/*** mstweb ***/
var mstweb = new class_mstweb();

function mstweb_logout(lost_session) {
    mstweb_page_security.terminate();
    mstweb_page_backup.terminate();
    mstweb_page_control.terminate();
    mstweb_page_mimetype.terminate();
    mstweb_page_name.terminate();
    mstweb_page_database.terminate();
    mstweb_page_activation.terminate();
    mstweb_page_password.terminate();
    mstweb_page_security.terminate();
    mstweb_page_setting.terminate();
    mstweb_page_share.terminate();
    mstweb_page_view.terminate();
    mstweb_page_login.terminate();
    return mstweb_page_login.ajax.ajax.get_page.request(true);
}

/*** mstweb_page_backup ***/
var mstweb_page_backup = new class_mstweb_page_backup();

function mstweb_page_backup_get_task_list(udn, control_url_cd, friendly_name, icon_url, force) {
    mstweb_page_backup.ajax.ajax.get_task_list.request(udn, control_url_cd, friendly_name, icon_url, force);
}

function mstweb_page_backup_get_server_list() {
    mstweb_page_backup.ajax.ajax.get_server_list.request();
}

function mstweb_page_backup_register_task() {
    if (true == confirm(msg('BACKUP_ASK_REGISTER_TASK'))) {
        mstweb_page_backup.ajax.ajax.register_task.request();
    }
}

function mstweb_page_backup_unregister_task(udn, control_url_cd, friendly_name, icon_url) {
    if (true == confirm(msg('BACKUP_ASK_UNREGISTER_TASK'))) {
        mstweb_page_backup.ajax.ajax.unregister_task.request(udn, control_url_cd, friendly_name, icon_url);
    }
}

function mstweb_page_backup_page_browse_container(udn, control_url_cd, friendly_name, icon_url) {
    mstweb_page_backup.ajax.ajax.get_page_browse_container.request(udn, control_url_cd, friendly_name, icon_url);
}

function mstweb_page_backup_get_page_server_list() {
    mstweb_page_backup.ajax.ajax.get_page_server_list.request();
}

function mstweb_page_backup_init_container_tree() {
    if (!mstweb_page_backup.init_tree()) {return false;}
}


/*** mstweb_page_control ***/
var mstweb_page_control = new class_mstweb_page_control();


/*** mstweb_page_login ***/
var mstweb_page_login = new class_mstweb_page_login();


/*** mstweb_page_mimetype ***/
var mstweb_page_mimetype = new class_mstweb_page_mimetype();

function mstweb_page_mimetype_get_mimetype_list() {
    mstweb_page_mimetype.ajax.ajax.get_mimetype_list.request();
}

function mstweb_page_mimetype_register(edit, prev_extention, id_ext, id_mime) {
    mstweb_page_mimetype.ajax.ajax.register.request(edit, prev_extention, id_ext, id_mime);
}

function mstweb_page_mimetype_unregister() {
    if (true == confirm(msg('MIMETYPE_ASK_UNREGISTER'))) {
        mstweb_page_mimetype.ajax.ajax.unregister.request();
    }
}

function mstweb_page_mimetype_reset() {
    if (true == confirm(msg('MIMETYPE_ASK_RESET'))) {
        mstweb_page_mimetype.ajax.ajax.reset.request();
    }
}

function mstweb_page_mimetype_on_add() {
    if (!mstweb_page_mimetype.on_add()) {return false;}
}

function mstweb_page_mimetype_on_edit(tr_id) {
    if (!mstweb_page_mimetype.on_edit(tr_id)) {return false;}
}


/*** mstweb_page_name ***/
var mstweb_page_name = new class_mstweb_page_name();

function mstweb_page_name_change_name() {
    mstweb_page_name.ajax.ajax.change_name.request();
}

function mstweb_page_name_view_update_btn() {
    cntl('name', 'update_view');
}

function mstweb_page_name_undo_name() {
    cntl('name', 'undo_server_name');
}

/*** mstweb_page_database ***/
var mstweb_page_database = new class_mstweb_page_database();

function mstweb_page_database_rebuild_database() {
    mstweb_page_database.ajax.ajax.rebuild_database.request();
}

/*** mstweb_page_activation ***/
var mstweb_page_activation = new class_mstweb_page_activation();

/*** mstweb_page_password ***/
var mstweb_page_password = new class_mstweb_page_password();

function mstweb_page_password_change_password() {
    mstweb_page_password.ajax.ajax.change_password.request();
}

function mstweb_page_password_view_update_btn() {
    if (!mstweb_page_password.view_update_btn()) {$ERR("5");}
}

function mstweb_page_password_on_clear() {
    if (!mstweb_page_password.on_clear()) {$ERR("6");}
}


/*** mstweb_page_security ***/
var mstweb_page_security = new class_mstweb_page_security();

function mstweb_page_security_get_allow_list() {
    mstweb_page_security.ajax.ajax.get_allow_list.request();
}

function mstweb_page_security_get_deny_list() {
    mstweb_page_security.ajax.ajax.get_deny_list.request();
}

function mstweb_page_security_to_allow() {
    mstweb_page_security.ajax.ajax.to_allow.request();
}

function mstweb_page_security_to_deny() {
    mstweb_page_security.ajax.ajax.to_deny.request();
}

function mstweb_page_security_remove(is_allow) {
    mstweb_page_security.ajax.ajax.remove.request(is_allow);
}

function mstweb_page_security_get_default() {
    mstweb_page_security.ajax.ajax.get_default.request();
}

function mstweb_page_security_set_default(to_allow) {
    mstweb_page_security.ajax.ajax.set_default.request();
}

function mstweb_page_security_change_security_dialog_visibility() {
    mstweb_page_security.change_security_dialog_visibility();
}

function mstweb_page_security_get_page() {
    mstweb_page_security.ajax.ajax.get_page.request();
}

/*** mstweb_page_setting ***/
var mstweb_page_setting = new class_mstweb_page_setting();


/*** mstweb_page_share ***/
var mstweb_page_share = new class_mstweb_page_share();

function mstweb_page_share_get_share_list(disable_loading_msg) {
    return mstweb_page_share.ajax.ajax.get_share_list.request(disable_loading_msg);
}

function mstweb_page_share_unregister_share_list() {
    return mstweb_page_share.ajax.ajax.unregister_share_list.request();
}

function mstweb_page_share_repair_database() {
    return mstweb_page_share.ajax.ajax.repair_database.request();
}

/*** mstweb_page_view ***/
var mstweb_page_view = new class_mstweb_page_view();

function mstweb_page_view_on_apply() {
    if (!mstweb_page_view.on_apply()) {return false;}
}

function mstweb_page_view_change_language(lang) {
    var ajax = new class_mst_ajax('mstweb_page_view', 'change_language');
    ajax.init();
    return ajax.request({serial: 'language=' + lang});
}

function mstweb_page_view_get_page() {
    return mstweb_page_view.ajax.ajax.get_page.request();
}

/*** list ***/
var mst_list = {
    share : new class_list_share('share_tr', 'share_list_body','mstweb_page_share'),
    sec_a : new class_list_security_allow('sec_a_tr', 'sec_a_list_body', 'mstweb_page_security_allow'),
    sec_d : new class_list_security_deny('sec_d_tr', 'sec_d_list_body', 'mstweb_page_security_deny'),
    mime : new class_list_mime('mime_tr', 'mime_list_body', 'mstweb_page_mimetype'),
    bu_s : new class_list_bu_s('bu_s_tr', 'bu_s_list_body', 'mstweb_page_backup'),
    bu_t : new class_list_bu_t('bu_t_tr', 'bu_t_list_body', 'mstweb_page_backup'),
    root : new class_list()
};

function list(type, action, arg1, arg2, arg3) {
    return mst_list[type][action](arg1, arg2, arg3);
}

/*** controls ***/
var mst_controls = {
    pass : new class_controls_password(),
    name : new class_controls_name(),
    database : new class_controls_database(),
    mime_a : new class_controls_mimetype_add(),
    bu_t : new class_controls_register_task(),
    cntl : new class_controls_control()
};

function cntl(type, action, arg1, arg2, arg3) {
    return mst_controls[type][action](arg1, arg2, arg3);
}

/*** tab_main ***/
var mst_tab_main = new class_mst_tab_main(
    {
        security: mstweb_page_security,
        share: mstweb_page_share,
        control: mstweb_page_control,
        backup: mstweb_page_backup,
        setting: mstweb_page_setting
    }, 
    'security',
    {
        security: 'mst_tab_main_security',
        share: 'mst_tab_main_share',
        control: 'mst_tab_main_control',
        backup: 'mst_tab_main_backup',
        setting: 'mst_tab_main_setting'
    },
    {
        active: 'tab_active',
        hover: 'tab_hover',
        normal: ''
    }
);

/*** tab_setting ***/
var mst_tab_setting = new class_mst_tab_setting(
    {
        name: mstweb_page_name,
        mimetype: mstweb_page_mimetype,
        database: mstweb_page_database,
        view: mstweb_page_view,
        activation: mstweb_page_activation,
        password: mstweb_page_password
    }, 
    'name',
    {
        name: 'mst_tab_setting_name',
        mimetype: 'mst_tab_setting_mimetype',
        database: 'mst_tab_setting_database',
        view: 'mst_tab_setting_view',
        activation: 'mst_tab_setting_activation',
        password: 'mst_tab_setting_password'
    },
    {
        active: 'tab_active',
        hover: 'tab_hover',
        normal: ''
    }
);

/*** window ***/
window.onload = function() {
    document.body.oncontextmenu = function() {
        return ("input" == event.srcElement.tagName.toLowerCase());
    };
    document.body.onselectstart = function() {
        return ("input" == event.srcElement.tagName.toLowerCase());
    };
    ss_load_style();
    mstweb.on_load();
};

window.onunload = function() {
    ss_save_style();
};

