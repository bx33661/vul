/* $Id: mstweb_page_backup.js,v 1.13 2007/01/16 15:57:04 iwamoto Exp $ */

var class_mst_ajax_backup_get_page = class_mst_ajax.extend({
    action: "get_page_backup",

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

var class_mst_ajax_backup_get_server_list = class_mst_ajax.extend({
    action: "get_server_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, this.owner.server_list_id)) {}
                this.owner.cur_task_list_id = null;
            } else {
                if (!mstweb_util.update_html(xml, this.owner.server_list_id)) {}
            }
        } catch (e) {}
        list('bu_s', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.server_list_id, msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('bu_s', 'disable_btns', false);
    },

    request: function() {
        mstweb_util.update_innerhtml(this.owner.server_list_id, msg('BACKUP_LOADING_SERVER_LIST'));
        if (!this.parent()) return false;
        list('bu_s', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_backup_get_task_list = class_mst_ajax.extend({
    action: "get_task_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            var udn = tut_xml.get_text(xml, 'udn');
            var tag = 'bu_s_td_' + udn;
            if (this.parent(o)) {
                if (!mstweb_util.update_html_list(xml, tag)) {}
                this.owner.clear_prev_task_list(tag);
                list('bu_t', 'view_update');
                mstweb_util.update_innerhtml('ntask_' + udn, tut_xml.get_text(xml, 'ntask'));
            } else {
                if (!mstweb_util.update_html(xml, 'ntask_' + udn)) {}
            }
        } catch (e) {}
        list('bu_s', 'disable_btns', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('COMMON_FAILED_LOADING_LIST'));
        } catch (e) {}
        list('bu_s', 'disable_btns', false);
        // XXX:
        this.owner.ajax.ajax.get_server_list.request();
    },

    request: function(udn, control_url_cd, friendly_name, icon_url, force) {
        if (!force && this.owner.cur_task_list_id && this.owner.cur_task_list_id.indexOf(udn) != -1) { return true; }

        var hash = {
            'udn': udn,
            'control_url_cd': control_url_cd,
            'friendly_name': friendly_name,
            'icon_url': icon_url
        };
        mstweb_util.update_innerhtml('bu_s_td_' + udn, msg('BACKUP_LOADING_TASK_LIST'));
        if (!this.parent({'hash': hash})) return false;
        list('bu_s', 'disable_btns', true);
        return true;
    }
});

var class_mst_ajax_backup_get_page_server_list = class_mst_ajax.extend({
    action: "get_page_backup_server_list",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, "mstweb_page_backup_contents")) {}
            this.owner.ajax.ajax.get_server_list.request();
        } else {
            if (!mstweb_util.update_html(xml, "mstweb_page_backup_contents")) {}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert(msg('COMMON_FAILED_LOADING_PAGE'));
    },

    request: function() {
        if (!this.parent()) return false;
        return true;
    }
});

var class_mst_ajax_backup_get_page_browse_container = class_mst_ajax.extend({
    action: "get_page_browse_container",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, "mstweb_page_backup_contents")) {return false;}
            if (!this.owner.init_tree()) {return false;}
        } else {
            if (!mstweb_util.update_html(xml, "mstweb_page_backup_contents")) {return false;}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert(msg('COMMON_FAILED_LOADING_PAGE'));
    },

    request: function(udn, control_url_cd, friendly_name, icon_url) {
        var hash = {
            'udn': udn,
            'control_url_cd': control_url_cd,
            'friendly_name': friendly_name,
            'icon_url': icon_url
        };
        if (!this.parent({'hash': hash})) return false;
        return true;
    }
});

var class_mst_ajax_backup_browse_request = class_mst_ajax.extend({
    action: "browse_container",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!this.owner.append_container(xml)) {}
            } else {
                if (!mstweb_util.update_html(xml)) {}
            }
            this.owner.returned_http_request();
        } catch (e) {}
        cntl('bu_t', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            mstweb_util.update_innerhtml(this.owner.browsing_container_id, msg('BACKUP_FAILED_BROWSE_CONTAINER'));
        } catch (e) {}
        cntl('bu_t', 'disable_controls', false);
        this.owner.returned_http_request();
    },

    request: function(container_id) {
        var hash = {
            'container_id_for_browse': container_id,
            'udn': $('mstweb_page_backup_page_browse_container_udn').value,
            'control_url_cd': $('mstweb_page_backup_page_browse_container_control_url_cd').value
        };
        if (!this.parent({'hash': hash})) return false;
        cntl('bu_t', 'disable_controls', true);
        return true;
    }
});

var class_mst_ajax_backup_register_task = class_mst_ajax.extend({
    action: "register_task",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        try {
            var xml = o.responseXML;
            if (this.parent(o)) {
                if (!mstweb_util.update_html(xml, 'mst_state_backup')) {}
                if (!this.owner.init_tree()) {}
            } else {
                if (!mstweb_util.update_html(xml, 'mst_state_backup')) {}
            }
        } catch (e) {}
        cntl('bu_t', 'disable_controls', false);
    },

    onfailure: function(o) {
        try {
            this.parent(o);
            alert(msg('BACKUP_FAILED_REGISTER_TASK'));
        } catch (e) {}
        cntl('bu_t', 'disable_controls', false);
    },

    request: function() {
        if (-1 == this.owner.container_id) {alert("couldn't register task"); return false;}

        var hash = {
            'container_id': this.owner.container_id,
            'udn': $('mstweb_page_backup_page_browse_container_udn').value
        };
        if (!this.parent({'hash': hash})) return false;
        cntl('bu_t', 'disable_controls', true);
        return true;
    }
});

var class_mst_ajax_backup_unregister_task = class_mst_ajax.extend({
    action: "unregister_task",

    initialize: function(controller, owner) {
        this.parent(controller, this.action, owner);
    },

    onsuccess: function(o) {
        var xml = o.responseXML;
        if (this.parent(o)) {
            if (!mstweb_util.update_html(xml, 'mst_state_backup')) {}
            {
                var udn = tut_xml.get_text(xml, 'udn');
                var control_url_cd = tut_xml.get_text(xml, 'control_url_cd');
                var friendly_name = tut_xml.get_text(xml, 'friendly_name');
                var icon_url = tut_xml.get_text(xml, 'icon_url');
                if (!udn || !control_url_cd || !icon_url || !friendly_name) {return false;}
                this.owner.ajax.ajax.get_task_list.request(udn, control_url_cd, friendly_name, icon_url, true);
            }
        } else {
            if (!mstweb_util.update_html(xml, 'mst_state_backup')) {return false;}
        }
    },

    onfailure: function(o) {
        this.parent(o);
        alert(msg('BACKUP_FAILED_UNREGISTER_TASK'));
    },

    request: function(udn, control_url_cd, friendly_name, icon_url) {
        var form = Form.serialize($("mstweb_page_backup"));
        var hash = {
            'udn': udn,
            'control_url_cd': control_url_cd,
            'friendly_name': friendly_name,
            'icon_url': icon_url
        };
        if (!this.parent({'hash': hash, 'serial': form})) return false;
        return true;
    }
});

var class_mstweb_page_backup = class_mst_page.extend({
    controller: "mstweb_page_backup",
    cur_task_list_id: null,

    server_list_id: "page_backup_server_list",
    browsing_container_id : 'mstweb_page_backup_page_browse_container_browse',

    ajax: new class_mst_ajaxes({
        get_page: new class_mst_ajax_backup_get_page(),
        get_server_list: new class_mst_ajax_backup_get_server_list(),
        get_task_list: new class_mst_ajax_backup_get_task_list(),
        get_page_server_list: new class_mst_ajax_backup_get_page_server_list(),
        get_page_browse_container: new class_mst_ajax_backup_get_page_browse_container(),
        browse_request: new class_mst_ajax_backup_browse_request(),
        register_task: new class_mst_ajax_backup_register_task(),
        unregister_task: new class_mst_ajax_backup_unregister_task()
    }),

    initialize: function() {
        this.parent('backup', 'mst_page_backup');
        this.ajax.init(this, this.controller);
    }, 

    on_load: function() {
        this.ajax.ajax.get_page.request();
    },

    on_load_complete: function() {
        this.ajax.ajax.get_page_server_list.request();
    },

    clear_prev_task_list: function(cur_task_list_id) {
        if (this.cur_task_list_id && (this.cur_task_list_id != cur_task_list_id)) { 
            mstweb_util.update_innerhtml(this.cur_task_list_id, "");
        }
        this.cur_task_list_id = cur_task_list_id;

    },

    display_task_list_message: function(udn, type) {
        var elem_retrieving = $('div_' + udn + '_retrieving');
        var elem_failed = $('div_' + udn + '_failed');
        var elem_retrieving_style;
        var elem_failed_style;
        
        if (!elem_retrieving || !elem_failed) { return false; }

        switch (type) {
        case 0:
            elem_retrieving_style = 'block';
            elem_failed_style = 'none';
            break;
        case 1:
            elem_retrieving_style = 'none';
            elem_failed_style = 'none';
            break;
        case 2:
            elem_retrieving_style = 'none';
            elem_failed_style = 'block';
            break;
        }

        elem_retrieving.style.display = elem_retrieving_style;
        elem_failed.style.display = elem_failed_style;

        return true;
    },

// container browsing
    tree: null,
    tree_index: -1,
    fn_load_comp: 0,
    container_id: -1,

    init_tree: function() {
        this.tree_index = -1;
        this.fn_load_comp = 0;
        this.tree = new YAHOO.widget.TreeView(this.browsing_container_id);
        this.tree.setDynamicLoad(this.on_toggle.bind(this), 1);
        this.tree.setLabelClickHandler(this.on_label_click.bind(this));

        cntl('bu_t', 'update_container_name', '');
        mstweb_util.update_innerhtml(this.browsing_container_id, msg('BACKUP_LOADING_PAGE_BROWSE'));

        this.ajax.ajax.browse_request.request(0);

        return true;
    },

    returned_http_request: function() {
        if (this.fn_load_comp) {
            this.fn_load_comp();
            this.fn_load_comp = 0;
        }
    },

    on_label_click: function(node) {
        this.container_id = node.data['container_id'];
        this.set_container_name(node);
    },

    on_toggle: function(node, fnLoadComplete) {
        this.tree_index = node.index;
        if (node.children.length == 0 && !node.data['is_leaf']) {
            this.ajax.ajax.browse_request.request(node.data['container_id']);
            this.fn_load_comp = fnLoadComplete;
        } else {
            fnLoadComplete();
        }
    },

    set_container_name: function(node) {
        var path_arr = new Array();
    
        while (!node.isRoot()) {
            path_arr.push(node.data['label']);
            node = node.parent;
        }
        cntl('bu_t', 'update_container_name', path_arr.reverse().join('\\'));
    },

    make_node: function(node, container) {
        var data = {
            label        : container.dc_title,
            container_id : container.id,
            togglable    : !container.registered,
            nchildren    : container.nchildren,
            is_leaf      : false
        };

        var tmpNode = new YAHOO.widget.TextNode(data, node, false);
        
        if (container.nchildren == '0') {
            tmpNode.dynamicLoadComplete = true;
        }
    },

    make_child_nodes: function(containers) {
        var node = (this.tree_index == -1) ? this.tree.getRoot() : this.tree.getNodeByIndex(this.tree_index);

        if (0 == containers.length) {
            node.data['is_leaf'] = true;
            return;
        }
        for (var i = 0; i < containers.length; ++i) {
            this.make_node(node, containers[i]);
        }

        node.expand();
        this.tree.draw();
    },

    append_container: function(xml) {
        var containers = new Array();
        var children_body_node = xml.getElementsByTagName('container_list')[0].childNodes;
        for (var i = 0; i < children_body_node.length; ++i) {
            var tgt = children_body_node.item(i);
            if (tgt.nodeName == "container" && tgt.getAttribute("id")) {
                var container = new Object();
                container = {
                "id"         : tgt.getAttribute("id"),
                "dc_title"   : tgt.getAttribute("dc_title"),
                "registered" : tgt.getAttribute("registered") == "true" ? true : false,
                "nchildren"  : tgt.getAttribute("nchildren")
                };
                containers.push(container);
            }
        }

        // XXX
        if (!containers.length && arg.container_id == '0') {
            mstweb_util.update_innerhtml(this.browsing_container_id, "");
            return false;
        }
        
        this.make_child_nodes(containers);
        return true;
    }
});

