#!/bin/bash

#===============================================================================
# network.sh 
#===============================================================================

LIBDIR=$PREFIX/usr/lib/nas
CONFDIR=/etc/nas

. $LIBDIR/common.sh

#-------------------------------------------------------------------------------
# 
#-------------------------------------------------------------------------------

#
# $1: hostname
#
set_hostname() {
  local FUNC="SET_HOSTNAME"

  log_func $FUNC $@

  nas-service control all stop fast 
  echo $1 > /etc/hostname
  /etc/init.d/hostname.sh start

  echo "127.0.0.1 $1" 		        > /etc/hosts
  echo "127.0.0.1 localhost" 		>> /etc/hosts
  echo 					>> /etc/hosts
  echo "::1 ip6-localhost ip6-loopback"	>> /etc/hosts
  echo "fe00::0 ip6-localnet"		>> /etc/hosts
  echo "ff00::0 ip6-mcastprefix"	>> /etc/hosts
  echo "ff02::1 ip6-allnodes"		>> /etc/hosts
  echo "ff02::2 ip6-allrouters"		>> /etc/hosts
  echo "ff02::3 ip6-allhosts"		>> /etc/hosts

  # set service names

  # iscsi (target iqn require globally unique name)
  nas-service set_iscsi iqn $1
  nas-service set_itunes servername "iTunes Server on $1"

  nas-service config all
  nas-service control all start fast
  
  broadcast ip
  
  log_func_check_result $FUNC $? $ERROR_EXEC
}

# $1: static, dhcp, address, netmask, gateway, dns1, dns2, mtu
# $2: ADDRESS
#
set_interface() {
  local FUNC="SET_INTERFACE"
  local CONF_FILE=/etc/network/interfaces

  log_func $FUNC $@

  case "$1" in
    "static"|"dhcp"|"ipv4ll")
      sed -i "/iface eth0 inet/ s/.*/iface eth0 inet $1/" $CONF_FILE 
      ;;
    "address"|"netmask"|"gateway"|"mtu")
      grep -n "$1" $CONF_FILE >/dev/null 2>&1
      if [ $? -eq "0" ]; then
        sed -i "s/$1.*/$1 $2/" $CONF_FILE
      else 
        echo "	$1 $2" >> $CONF_FILE
      fi
      ;;
    "dns1")
      echo "nameserver $2" > /etc/resolv.conf 
      ;;
    "dns2")
      if [ "$2" != "0.0.0.0" ]; then
        echo "nameserver $2" >> /etc/resolv.conf 
      fi
      ;;
    "all")
      shift
      echo "auto lo"			> $CONF_FILE
      echo "iface lo inet loopback"	>> $CONF_FILE
      echo ""				>> $CONF_FILE
      echo "auto eth0"			>> $CONF_FILE

      if [ "$1" = "static" ]; then
        echo "iface eth0 inet static"	>> $CONF_FILE
        echo "	address $2"		>> $CONF_FILE
        echo "	netmask $3"		>> $CONF_FILE
        echo "	gateway $4"		>> $CONF_FILE
        echo "	mtu $7"			>> $CONF_FILE

        echo "nameserver $5" 		> /etc/resolv.conf
	if [ "$6" != "0.0.0.0" ]; then
          echo "nameserver $6" 		>> /etc/resolv.conf
        fi
      elif [ "$1" = "dhcp" ]; then
        echo "iface eth0 inet dhcp"	>> $CONF_FILE
      fi
      ;;
    *) 
      return 1
      ;;
  esac 
}


#
# $1: ipaddr
#
check_conflict() {
  local FUNC="CHECK_CONFLICT"
  log_func $FUNC $@

  RESULT=$(arping -I eth0 -c1 $1 | grep Received | cut -d " " -f 2)
  log_func_result $FUNC $RESULT
  echo $RESULT
}

#
apply_interface() {
  FUNC="APPLY_INTERFACE"
  
  log_func $FUNC $@

  lcd_msg "Set IP Address..."
  nas-common button lock

  nas-service control all stop fast

  log_func $FUNC networking restart...
  /etc/init.d/networking restart
  RESULT=$?
  log_func_result $FUNC $RESULT restart result

  nas-service control all start fast

  lcd_msg_time "Complete Setting"
  nas-common button unlock
  buzzer in network

  broadcast ip
  log_func_check_result $FUNC $RESULT $ERROR_EXEC
}

#
# $1: static, dhcp
# $2~ : parameter
#
set_interface_all() {
  FUNC="SET_INTERFACE_ALL"
  log_func $FUNC $@

  if [ "$1" = "static" ]; then
    set_interface $1
    set_interface address $2
    set_interface netmask $3
    set_interface gateway $4
    set_interface dns1 $5
    set_interface dns2 $6
    apply_interface    
  else 
    set_interface $1
    apply_interface
  fi 

  log_func_result $FUNC $?
}

#
# $1: workgroup
#
set_workgroup() {
  local FUNC="SET_WORKGROUP"
  local NETWORK_CONF=/etc/nas/network.conf
  local SERVICE_CONF=/etc/nas/service.conf

  log_func $FUNC $@
# STEP 1: Change network.conf
  replace_conf_equal domain_type "workgroup" $NETWORK_CONF
  replace_conf_equal workgroup "$1" $NETWORK_CONF
  replace_conf_equal domain "NONE" $NETWORK_CONF
  replace_conf_equal domain_user "NONE" $NETWORK_CONF
  replace_conf_equal domain_pass "NONE" $NETWORK_CONF

# STEP 2: Regenerate smb.conf & Modify it, Restart
  nas-share gen_samba_conf $SAMBA_CONF

  nas-service set_samba "workgroup" "$1"
  nas-service set_samba "security" "user"
  
  nas-service control samba restart
  echo "ok"

  log_func_result $FUNC $OK
}

#
# $1: domain
# $2: user
# $3: password
#
set_domain() {
  local FUNC="SET_DOMAIN"
  local NETWORK_CONF=/etc/nas/network.conf
  
  WORKGROUP=$(echo $1 | cut -d"." -f 1)
  DOMAIN=$1
  USER=$2
  PASS=$3

  #log_func $FUNC $@

  RESULT=fail
  FOUND=fail

  # STEP 1: Check domain server
  for NAMESERVER in $(sed 's/nameserver//' /etc/resolv.conf); do
    dig +time=2 +tries=1 $1 | grep "ANSWER SECTION" >/dev/null 2>&1
    [ "$?" = "0" ] && FOUND=ok
    log_func $FUNC "Query to nameserver" $NAMESERVER : $FOUND
  done
  if [ "$FOUND" = "fail" ]; then
    log_func_result $FUNC $ERROR_EXEC "Domain Not Found"
    echo "ns_fail"
    return 1
  fi

  set_kerb_domain $DOMAIN
  ntpdate $DOMAIN
  echo $PASS | kinit $USER
  if [ "$?" != "0" ]; then
    log_func_result $FUNC $ERROR_EXEC "Wrong ID/PW"
    echo "join_fail"
    return 1
  fi

  # STEP 2: Update network.conf configuration
  replace_conf_equal workgroup "$WORKGROUP" $NETWORK_CONF
  replace_conf_equal domain_type "domain" $NETWORK_CONF
  replace_conf_equal domain "$1" $NETWORK_CONF
  replace_conf_equal domain_user "$2" $NETWORK_CONF
  replace_conf_equal domain_pass "$3" $NETWORK_CONF

  # STEP 3: Set smb.conf & krb5.conf
  set_samba_domain $WORKGROUP $DOMAIN

  # STEP 4: Start Active Domain Service
  /etc/init.d/samba restart
  [ "$?" = "0" ] && RESULT=ok

  # To-do

  if [ "$RESULT" = "ok" ]; then
    log_func_result $FUNC $OK 
    echo "ok" 
  else 
    log_func_result $FUNC $ERROR_EXEC "Domain join fail"
    echo "join_fail"
  fi 
}

#
# Set samba configuration for domain or workgroup
#
# $1 : workgroup
# $2 : domain
#
set_samba_domain() {
  local FUNC="SET_SAMBA_DOMAIN"
  local NETWORK_CONF=/etc/nas/network.conf
  local SAMBA_CONF=/etc/samba/smb.conf
  
  WORKGROUP=$(get_conf_equal workgroup $NETWORK_CONF)

  nas-share gen_samba_conf $SAMBA_CONF

  nas-service set_samba "workgroup" $1
  nas-service set_samba "security" "ads"

  nas-service set_samba "realm" $2

  log_func $FUNC $@
}

#
# Set kerberos configuration for domain or workgroup
# $1 : domain
#
set_kerb_domain() {
  local FUNC="SET_DOMAIN"
  local KERB_CONF=/etc/krb5.conf

  DOMAIN="$1"
  KDC=$(nslookup $DOMAIN | grep Server | cut -d ":" -f 2 | sed -e 's/^[ \t]*//')
  
  echo "[libdefaults]"  > $KERB_CONF
  echo "             default_realm = $DOMAIN" >> $KERB_CONF
  echo "[realms]"  >> $KERB_CONF
  echo "        $DOMAIN = {" >> $KERB_CONF
  echo "                    kdc = $KDC"  >> $KERB_CONF
  echo "        }" >> $KERB_CONF

  log_func $FUNC $@
}

#
# $1: check time (100ms unit)
#
link_status() {
  local FUNC="LINK_STATUS"

  for ((i=0; i < ${1:-"1"}; i+=1)); do
    ifplugstatus eth0 > /dev/null 2>&1
    if [ $? != "2" ]; then
      LINK_STATUS="DOWN"
      break
    fi
    usleep 100
  done

  if [ "$LINK_STATUS" = "DOWN" ]; then
    return 1
  fi
}

#
# parameter:
# $1 : property
#    : type, address, netmask, gateway, dns1, dns2
#
network_get() {
  local FUNC="NETWORK_GET"
  local CONF_FILE=/etc/network/interfaces
  local DOMAIN_CONF=/etc/nas/network.conf

  case "$1" in
    "method")
      METHOD=$(grep "iface eth0 inet" $CONF_FILE | awk '{ print $4 }')
      echo $METHOD
      ;;
    "macaddress")
      ifconfig eth0 | grep HWaddr | awk '{print $5}'
      ;;
    "ipaddr")
      IP=$(ifconfig eth0 | grep "inet addr" | cut -d ":" -f 2 | cut -d " " -f 1)
      if [ -z "$IP" ]; then
        IP=$(ifconfig eth0:avahi | grep "inet addr" | cut -d ":" -f 2 | cut -d " " -f 1)
      fi
      if [ -z "$IP" ]; then
        IP="0.0.0.0"
      fi
      echo $IP
      ;;
    "netmask")
      NETMASK=$(ifconfig eth0 | grep "inet addr" | cut -d ":" -f 4)
      if [ -z "$NETMASK" ]; then
        NETMASK=$(ifconfig eth0:avahi | grep "inet addr" | cut -d ":" -f 4)
      fi
      if [ -z "$NETMASK" ]; then
        NETMASK="0.0.0.0"
      fi
      echo $NETMASK
      ;;

    "domain_type"|"workgroup"|"domain"|"domain_user"|"domain_pass")
      get_conf_equal "$1" $DOMAIN_CONF
      ;;
    "iscsi_chap"|"iscsi_inuser"|"iscsi_inpw"|"iscsi_outuser"|"iscsi_outpw"|"iscsi_definuser"|"iscsi_definpw"|"iscsi_defoutuser"|"iscsi_defoutpw")
      get_conf_equal "$1" $DOMAIN_CONF
      ;;
    "smbweb_addr")
      TYPE=$(get_conf_equal domain_type $DOMAIN_CONF)
      if [ "$TYPE" = "workgroup" ]; then
        DOMAIN=$(get_conf_equal workgroup $DOMAIN_CONF)
      else
        DOMAIN=$(get_conf_equal domain $DOMAIN_CONF)
      fi
      NAME=$(hostname)
      echo ${DOMAIN}/${NAME}
      ;;
    *)
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}
#
# parameter:
# $1 : discovery, status, on, off
#
UPNPC=/usr/sbin/upnpc-static
HTTP_PORT1=80
HTTP_PORT2=8000
HTTPS_PORT=443
ADDED_PORT=9090
TORRENT_PORT=9091
TCP=tcp
PROFTPD_CONF=/etc/proftpd/proftpd.conf
set_port_forwarding() {
  local FUNC="PORT_FORWARDING"
  local CONF_FILE=/etc/network/interfaces
  local DOMAIN_CONF=/etc/nas/network.conf

  case "$1" in
    "discovery")
      $UPNPC -s | grep desc: | head -n 1 | awk '{ print $2 }'
      if [ "$?" = "" ]; then
         echo "No Device"
      #else
      # echo $?
      fi
      ;;
    "status")
      $UPNPC -l | grep -i "TCP" > "$2"
      #$UPNPC | grep -i "FTP" >> "$2"
      IP=$(network_get ipaddr)
   
      #Test
      IS_ON=$(grep -i $IP:$HTTP_PORT1 "$2")
      if [ -z $IS_ON ]; then
        echo "off:port forwarding"
      else
   	    echo "on:port forwarding"
      fi
      ;;
    "on")
      replace_conf_equal enable "on" /etc/nas/upnp.conf
      clear_port_forwarding
      	
      FTP_PORT=$(get_conf_blank Port $PROFTPD_CONF)
      PASSIVE_FIRST_PORT=$(get_conf_blank PassivePorts $PROFTPD_CONF)
      HTTP_USER_PORTS=$(grep ^Listen /etc/apache2/extra/httpd-vhosts.conf | cut -d ' ' -f2 | egrep -v '9091|^80|^8000' |sed -e ':a;N;$!ba;s/\n/ tcp /g')
      
      REGISTERED="$FTP_PORT $TCP $(( FTP_PORT-1 )) $TCP ";
      REGISTERED+="$HTTP_PORT1 $TCP $HTTP_PORT2 $TCP $HTTPS_PORT $TCP ";
      if [ $HTTP_USER_PORTS != "" ]; then
	REGISTERED+="$HTTP_USER_PORTS $TCP "
      fi
      REGISTERED+="$ADDED_PORT $TCP $TORRENT_PORT $TCP ";
      for a in {0..128}; do
        PORTNUM=$(($a+$PASSIVE_FIRST_PORT));
        REGISTERED+="$PORTNUM $TCP ";
      done
     
      $UPNPC -r $REGISTERED
      echo "on"
      ;;
    "off")
      replace_conf_equal enable "off" /etc/nas/upnp.conf
      clear_port_forwarding
      echo "off"
      ;;
    *)
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}

clear_port_forwarding() {
    FTP_PORT=$(get_conf_blank Port $PROFTPD_CONF)
    PASSIVE_FIRST_PORT=$(get_conf_blank PassivePorts $PROFTPD_CONF)
    HTTP_USER_PORTS=$(grep ^Listen /etc/apache2/extra/httpd-vhosts.conf | cut -d ' ' -f2 | egrep -v '9091|^80|^8000' |sed -e ':a;N;$!ba;s/\n/ tcp /g')
      
    REGISTERED="$FTP_PORT $TCP $(( FTP_PORT-1 )) $TCP ";
    REGISTERED+="$HTTP_PORT1 $TCP $HTTP_PORT2 $TCP $HTTPS_PORT $TCP ";
    if [ $HTTP_USER_PORTS != "" ]; then
	REGISTERED+="$HTTP_USER_PORTS $TCP "
    fi
    REGISTERED+="$ADDED_PORT $TCP $TORRENT_PORT $TCP ";
    for a in {0..128}; do
       PORTNUM=$(($a+$PASSIVE_FIRST_PORT));
       REGISTERED+="$PORTNUM $TCP ";
    done
     
    $UPNPC -f $REGISTERED
}

#
# Set iscsi-scstd configuration 
#
# $1 : on / off / init
# $2 : IncomingUser
# $3 : IncomingPw
# $4 : OutgoingUser
# $5 : OutgoingPw
#
set_iscsi_chap() {
  local FUNC="SET_ISCSI_CONF"
  log_func $FUNC $@

  local NETWORK_CONF=/etc/nas/network.conf
  local ISCSI_CONF=/etc/iscsi-scstd.conf

  if [ "$1" = "init" ]; then
    replace_conf_equal iscsi_chap "off" $NETWORK_CONF
    DEF_INUSER=$(nas-network get iscsi_definuser)
    DEF_INPW=$(nas-network get iscsi_definpw)
    DEF_OUTUSER=$(nas-network get iscsi_defoutuser)
    DEF_OUTPW=$(nas-network get iscsi_defoutpw)
    replace_conf_equal iscsi_inuser $DEF_INUSER $NETWORK_CONF
    replace_conf_equal iscsi_inpw $DEF_INPW $NETWORK_CONF
    replace_conf_equal iscsi_outuser $DEF_OUTUSER $NETWORK_CONF
    replace_conf_equal iscsi_outpw $DEF_OUTPW $NETWORK_CONF

  else
    if [ "$1" = "on" ]; then
      replace_conf_equal iscsi_chap $1 $NETWORK_CONF
      replace_conf_equal iscsi_inpw $2 $NETWORK_CONF
      replace_conf_equal iscsi_outpw $3 $NETWORK_CONF
    else
      replace_conf_equal iscsi_chap "off" $NETWORK_CONF
    fi
  fi
  nas-share gen_iscsi_chap $ISCSI_CONF
}
