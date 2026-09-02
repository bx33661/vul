#!/bin/sh

. /usr/lib/nas/common.sh

[ -e ${1:-"."}/conf.tar.gz ] || exit 1

tar -xvf ${1:-"."}/conf.tar.gz -C /

ldconfig
depmod
nas-share gen_default_web_folder
nas-network hostname $(hostname)
nas-service control torrent stop

# Additional UPnP Setting for before version 2482
CURRENT_UPNP_FILE_SETTING=$(cat /etc/nas/upnp.conf | grep 'enable' | cut -d "=" -f2)

if [ $CURRENT_UPNP_FILE_SETTING = "off" ]; then
	ROUTER_SETTING=$(nas-network port_forwarding status "/tmp/upnp_devices" | cut -d ":" -f1)

	if [ $ROUTER_SETTING = "on" ]; then
		replace_conf_equal enable "on" /etc/nas/upnp.conf
	fi
fi

exit 0
