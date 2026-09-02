/* $Id: mst.js,v 1.18 2007/05/17 13:49:48 iwamoto Exp $ */

/*** message ***/
var class_mst_message = new Class({
    _messages: [],

    initialize: function() {
    },

    add_messages: function(message_hash) {
        if (!message_hash) { return false; }
        for (var i in message_hash) {
            this._messages[i] = message_hash[i];
        }
        return true;
    },

    get_message: function(id) {
        var str = this._messages[id];
        if (!str) {$ERR("class_mst_message:1:" + id); return null;}
        str = mstweb_util.replace_all(str, "\\n", "\n");
        str = mstweb_util.replace_all(str, "\\t", "\t");
        return str;
    }
});

/*** ajax **/
var class_mst_ajax_state = new Class({
    connecting: null
});

var class_mst_ajax = class_tut_ajax.extend({
    owner: null,
    controller: null,
    action: null,
    url: window.location,
    state: null,
    failed: null,

    initialize: function(controller, action, owner, timeout) {
        this.parent(this.url, timeout);
        this.owner = owner;
        this.controller = controller;
        this.action = action;
        this.failed = true;
    },

    init: function(owner, controller) {
        this.state = new class_mst_ajax_state();
        this.state.connecting = false;
        this.failed = true;

        if (owner) {
            this.owner = owner;
        }
        if (controller) {
            this.controller = controller;
        }
    },

    request: function(arg) {
        try {
            if (this.state.connecting) return false;
            this.state.connecting = true;

            var query = {
                controller: this.controller, 
                action: this.action, 
                content: 'partial',
                session_id: $('session_id') ? Form.Element.getValue($('session_id')) : ""
            };

            var serial = $H(query).toQueryString();

            if (arg && arg.serial) {
                serial += '&' + arg.serial;
            }
            if (arg && arg.hash) {
                serial += '&' + $H(arg.hash).toQueryString();;
            }

            return this.parent(serial) ? true : false;
        } catch (e) {
            return false;
        }
    },

    onsuccess: function(o) {
        this.state.connecting = false;
        if (!this.parent(o)) {return false;}
        var xml = o.responseXML;
        if (!xml) {$ERR('no xml response'); return false;}
        if (!mstweb_util.parse_response(xml)) {$ERR("invalid format: response xml"); return false;}
        if (mstweb_util.is_success(xml)) {
            this.failed = false;
            msg_add(mstweb_util.get_message_list(xml));
            return true;
        } else {
            this.failed = true;
            if (mstweb_util.is_no_session(xml)) { mstweb_logout(true); return false;}
            return false;
        }
    },

    onfailure: function(o) {
        this.failed = true;
        this.state.connecting = false;
        if (!this.parent(o)) {return false;}
        return true;
    }
});

var class_mst_ajaxes = new Class({
    ajax: [],

    initialize: function(ajax) {
        this.ajax = ajax;
    },

    // XXX: dup(a)
    abort_all_connection: function() {
        for (var i in this.ajax) {
            var func = this.ajax[i].abort_all_connection;
            var func_owner = this.ajax[i];
            if (func && $type(func) == 'function') { 
                func.call(func_owner); 
            }
        }
    },

    // XXX: dup(a)
    init: function(obj, controller) {
        for (var i in this.ajax) {
            var func = this.ajax[i].init;
            var func_owner = this.ajax[i];
            if (func && $type(func) == 'function') { 
                func.call(func_owner, obj, controller); 
            }
        }
    }
});

/*** page ***/
var class_mst_page = new Class({
    page_name: null,
    page_id: null,
    showing: null,
    load_completed: null,

    initialize: function(page_name, page_id) {
        this.page_name = page_name;
        this.page_id = page_id;
        this.showing = false;
        this.load_completed = false;
    },

    _show_page: function(show) {
        $(this.page_id).style.display = show ? "block" : "none";
        this.showing = show;
        return true;
    },

    _check_page: function() {
        if (!$(this.page_id)) return false;
        return true;
    },

    _call_vertial_method: function(method) {
        if (method && $type(method) == "function") {
            return method.call(this);
        }
        return null;
    },

    init: function() {
        if (!this._check_page()) return false;
        this.load_completed = false;
        return this._show_page(false);
    },

    enter: function() {
        if (!this._check_page()) return false;
        if (!this.load_completed) {
            this._call_vertial_method(this.on_load);
        }
        if (!this._show_page(true)) return false;
        this._call_vertial_method(this.on_loaded);
        return true;
    },

    leave: function() {
        if (!this._check_page()) return false;
        return this._show_page(false);
    },

    terminate: function() {
        return true;
    },

    load_complete: function() {
        if (!this._check_page()) return false;
        this.load_completed = true;
        this._call_vertial_method(this.on_load_complete);
    }
});

/*** pages ***/
var class_mst_pages = new Class({
    pages: null,
    initial_page_name: null,
    active_page_name: null,

    initialize: function(pages, initial_page_name) {
        this.pages = pages;
        this.initial_page_name = initial_page_name;
    },

    init: function() {
        for (var i in this.pages) {
            this.pages[i].init();
        }
        this.active_page_name = null;
    },

    open: function(page_name) {
        if (!this.pages) {$ERR("class_mst_pages:open 3"); return false;}
        if (!this.pages[page_name]) {$ERR("class_mst_pages:open 4"); return false;}
        if (this.active_page_name) {
            if (!this.pages[this.active_page_name].leave()) {$ERR("class_mst_pages:open 1"); return false;}
        }
        if (!this.pages[page_name].enter()) {$ERR("class_mst_pages:open 2"); return false;}
        this.active_page_name = page_name;
        return true;
    }
});

/*** tab ***/
var class_mst_tab_class_tab = new Class({
    active: null,        
    hover: null,        
    normal: null        
});

var class_mst_tab_state = new Class({
    active_page_name: null,        
    hover_page_name: null
});

var class_mst_tab = new Class({
    tab_ids: null,
    pages: null,
    class_tab: null,
    state: null,

    initialize: function(pages, initial_page_name, tab_ids, class_tab) {
        this.pages = new class_mst_pages(pages, initial_page_name);
        this.tab_ids = tab_ids;
        this.class_tab = new class_mst_tab_class_tab();
        this.class_tab.active = class_tab.active;
        this.class_tab.hover = class_tab.hover;
        this.class_tab.normal = class_tab.normal;
        this.state = new class_mst_tab_state();
    },

    init: function() {
        this.state.active_page_name = null;
        this.state.hover_page_name = null;

        this.pages.init();

        this.open(this.pages.initial_page_name);        

        for (var tab_name in this.tab_ids) {
            if ($(this.tab_ids[tab_name])) {
                $(this.tab_ids[tab_name]).onclick = this.on_tab_click.bindAsEventListener(this);
                $(this.tab_ids[tab_name]).onmouseover = this.on_tab_mouseover.bindAsEventListener(this);
                $(this.tab_ids[tab_name]).onmouseout = this.on_tab_mouseout.bindAsEventListener(this);
            }
        }
    },

    _event2page_name: function(ev) {
        var id;
        // XXX: li
        if (!Event.findElement(ev, "li")) return null;
        if (!(id = Event.findElement(ev, "li").id)) return null;

        for (var i in this.tab_ids) {
            if (this.tab_ids[i] == id) {
                return i;
            }
        }
        return null;
    },

    set_initial_page: function(initial_page_name) {
        this.pages.initial_page_name = initial_page_name;
    },

    open: function(page_name) {
        if (!this.pages.open(page_name)) {$ERR("class_mst_tab:open:1"); return false;}

        if (this.state.active_page_name == page_name) return true;
        this.change_class($(this.tab_ids[page_name]), this.class_tab.active);
        if (this.state.active_page_name) {
            this.change_class($(this.tab_ids[this.state.active_page_name]), this.class_tab.normal);
        }
        this.state.active_page_name = page_name;
        return true;
    },

    on_tab_click: function(ev) {
        var page_name = this._event2page_name(ev);

        if (this.state.active_page_name == page_name) return true;
        if (!page_name) {$ERR("class_mst_tab:on_tab_click:1"); return false;}
        if (!this.pages.pages[page_name]) {$ERR("class_mst_tab:on_tab_click:2"); return false;}
        this.open(page_name);
    },

    on_tab_mouseout: function(ev) {
        var page_name = this._event2page_name(ev);

        if (this.state.active_page_name == page_name) return true;

        if (!page_name) {$ERR("class_mst_tab:on_tab_mouseout:1"); return false;}
        if (!this.pages.pages[page_name]) {$ERR("class_mst_tab:on_tab_mouseout:2"); return false;}
        this.change_class($(this.tab_ids[page_name]), this.class_tab.normal);
    },

    on_tab_mouseover: function(ev) {
        var page_name = this._event2page_name(ev);

        if (this.state.active_page_name == page_name) return true;

        if (!page_name) {$ERR("class_mst_tab:on_tab_mouseout:1"); return false;}
        if (!this.pages.pages[page_name]) {$ERR("class_mst_tab:on_tab_mouseout:2"); return false;}
        this.change_class($(this.tab_ids[page_name]), this.class_tab.hover);
    },

    change_class: function(el, new_class) {
        el.className = new_class;
        this.on_class_changed(el);
    },

    on_class_changed: function(el) {
    }
});
