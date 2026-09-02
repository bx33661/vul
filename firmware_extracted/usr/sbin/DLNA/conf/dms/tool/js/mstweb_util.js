/* $Id: mstweb_util.js,v 1.10 2007/01/19 08:38:58 iwamoto Exp $ */

var class_mstweb_util = new Class({
    initialize: function() {
    },

    make_query: function(controller, action, form, content) {
        if (!content) content = 'partial';
        var query = {
            'controller': controller, 
            'action': action, 
            'content': content,
            'session_id': Form.Element.getValue($('session_id'))
        };
        return $H(query).toQueryString() + (form ? ('&' + form) : '');
    },

    get_session_id: function(xml) {
        return tut_xml.get_text_node_val(xml.getElementsByTagName('session_id')[0]);
    },

    get_html: function(xml) {
        if (!xml) return null;
        return tut_xml.get_text_node_val(xml.getElementsByTagName('html')[0]);
    },

    get_html_list: function(xml) {
        var list = "";
        var html_list = xml.getElementsByTagName("list_item");
        if (!html_list) {return null;}
        for (var i=0; i<html_list.length; ++i) {
            list += tut_xml.get_text_node_val(html_list.item(i));
        }
        return list;
    },

    get_html_target: function(xml, elem_name) {
        var elem = xml.getElementsByTagName(elem_name)[0];
        if (!elem) return null;
        return elem.getAttributeNode('target').value;
    },

    update_html: function(xml, target) {
        this.update_innerhtml(target, this.get_html(xml));
        return true;
    },

    update_html_list: function(xml, target) {
        this.update_innerhtml(target, this.get_html_list(xml));
        return true;
    },

    get_child_node: function(xml, node_name) {
        return tut_xml.get_text_node_val(xml.getElementsByTagName(node_name)[0]);
    },

    update_session_id: function(xml) {
        $("session_id").value = this.get_session_id(xml);
        return true;
    },

    get_message_list: function(xml) {
        var message_list = new Object();
        var e_msg_lists = xml.getElementsByTagName("message_list");
        if (!e_msg_lists || 1 != e_msg_lists.length) return null;
        var e_msg_list = e_msg_lists.item(0).childNodes;
        for (var i = 0; i < e_msg_list.length; ++i) {
            var e_msg = e_msg_list.item(i).childNodes;
            var id = null;
            var value = null;
            for (var j = 0; j < e_msg.length; ++j) {
                var e_tag = e_msg.item(j);
                switch (e_tag.tagName) {
                case 'id':
                    id = tut_xml.get_text_node_val(e_tag);
                    break;
                case 'value':
                    value = tut_xml.get_text_node_val(e_tag);
                    break;
                default:
                    break;
                }
                if (!id || !value) {continue;}
                message_list[id] = value;
            }
        }
        return message_list;
    },

    // XXX
    is_no_session: function(xml) {
        if ("MSTWEB_NO_SESSION" == xml.getElementsByTagName('message_id').item(0).firstChild.nodeValue) return true;
        return false;
    },

    parse_response: function(xml) {
        if (!xml) {$ERR("no xml"); return 0;}
        var head = xml.getElementsByTagName('head');
        var body = xml.getElementsByTagName('body');
        if (!head || 1 != head.length) {$ERR("no head"); return 0;}
        if (!body || 1 != body.length) {$ERR("no body"); return 0;}
        if (!head[0] || !head[0].childNodes) {$ERR("invalid head"); return 0;}
        var children_action_node = head[0].childNodes;
        var controller = 0, action = 0, state = 0, message = 0, message_id = 0;
        for (var i = 0; i < children_action_node.length; ++i) {
            if (!children_action_node.item(i) || !children_action_node.item(i).tagName) continue;
            switch (children_action_node.item(i).tagName) {
            case "controller" : controller = 1; break;
            case "action"     : action     = 1; break;
            case "state"      : state      = 1; break;
            case "message"    : message    = 1; break;
            case "message_id" : message_id = 1; break;
            }
        }
        return (controller && action && state && message && message_id);
    },

    is_success: function(xml) {
        var children_action_node = xml.getElementsByTagName('head')[0].childNodes;
        for (var i = 0; i < children_action_node.length; ++i) {
            if (children_action_node.item(i).tagName == "state") {
                return ("success" == tut_xml.get_text_node_val(children_action_node.item(i)))? true: false;
            }
        }
        return false;
    },

    update_innerhtml: function(id, msg) {
        $(id).innerHTML = msg;

        return true;
    },

    update_classname: function(id, classname) {
        if (!$(id)) return false;
        $(id).className = classname;

        return true;
    },

    update_state: function(xml, id, class_name) {
        var real_class_name;

        switch (class_name) {
        case 'ok' : real_class_name = "mst_state_ok"; break;
        case 'err' : real_class_name = "mst_state_error"; break;
        case 'inf' : real_class_name = "mst_state_info"; break;
        default: real_class_name = "mst_state"; break;
        }

        if (xml) {
            this.update_innerhtml(id, this.get_html(xml));
        }
        this.update_classname(id, real_class_name);

        return true;
    },

    replace_all: function(str, s, r) {
        var tmp;
        while (true) {
            tmp = str;
            str = str.replace(s, r);
            if (str == tmp) break;
        }
        return str;
    },

    get_messages: function(message_list) {
        var ajax = new class_mst_ajax('mstweb_message', 'get_messages');
        var serial = "";

        ajax.init();
        message_list.each( function(o) {
            if (serial.length) serial += "&";
            serial += "msg=" + o;
        });
        return ajax.request({'serial': serial});
    },

    show_error_dialog: function(msg) {
        if (this._show_error_dialog) alert("[E] " + msg);
        return true;
    },

    show_info_dialog: function(msg) {
        if (this._show_info_dialog) alert("[I] " + msg);
        return true;
    },

    show_debug_dialog: function(msg) {
        if (this._show_debug_dialog) alert("[D] " + msg);
        return true;
    }
});

