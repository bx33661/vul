#!/bin/bash

CONF_LIST=(
	/etc/hosts
	/etc/hostname
	/etc/nsswitch.conf
	/etc/resolv.conf
	/etc/localtime
	/etc/krb5.conf
	/etc/ddnscli.conf
	/etc/inadyn.conf
	/etc/nas/ddns.conf
	/etc/network/interfaces
	/etc/passwd
	/etc/passwd-
	/etc/shadow
	/etc/shadow-
	/etc/group
	/etc/gshadow
	/etc/fstab
	/etc/mtab
	/etc/ssmtp/ssmtp.conf
	/etc/nas/network.conf
	/etc/nas/service.conf
	/etc/nas/system.conf
	/etc/nas/usb-backup.conf
	/etc/nas/firmware-odd.date
	/etc/nas/db/share.db
	/etc/samba/smbpasswd
	/etc/lighttpd/lighttpd.user.htdigest
	/etc/nas/service-info.conf
	/etc/apache2/extra/httpd-vhosts.conf
	/etc/apache2/extra/httpd-ssl.conf
	/etc/lighttpd/lighttpd.ports
	/var/www/index.html
	/etc/nas/upnp.conf
)

tar -zcvf ${1:-"."}/conf.tar.gz ${CONF_LIST[*]}

rm -rf /boot/update/*

exit 0 
