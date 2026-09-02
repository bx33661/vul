#!/bin/bash

#===============================================================================
# service.sh 
#===============================================================================

LIBDIR=/usr/lib/nas
CONFDIR=/etc/nas
SERVICE_CONF=$CONFDIR/service.conf
SERVICE_INFO_CONF=$CONFDIR/service-info.conf

. $LIBDIR/common.sh

#-------------------------------------------------------------------------------
# configuration 
#-------------------------------------------------------------------------------
USE_AVAHI=yes

#-------------------------------------------------------------------------------
# iscsi 
#-------------------------------------------------------------------------------

ISCSI_CONF=/etc/iscsi-scstd.conf

#
# $1: field
# $2: value
#
set_iscsi() {
  local FUNC="SET_ISCSI"
  
  log_func $FUNC $@
  
  FIELD=$1
  shift
  VALUE=$@

  case "$FIELD" in
    "iqn")
      MAC=$(nas-network get macaddress | awk '{ FS=":"; print $4$5$6 }') 
      sed -i "s/^Target.*/Target iqn.2009-01.com.lge:$VALUE.$MAC/" $ISCSI_CONF
      ;;
  esac

  log_func_check_result $FUNC $? $ERROR_EXEC
}
#
# $1 : property
#
get_iscsi() {
  case "$1" in 
    "enabled")
      get_conf_blank iscsi $SERVICE_CONF 
      ;;
    "chap")
      STATUS=$(nas-network get iscsi_chap)
      INUSER=$(nas-network get iscsi_inuser)
      INPW=$(nas-network get iscsi_inpw)
      OUTUSER=$(nas-network get iscsi_outuser)
      OUTPW=$(nas-network get iscsi_outpw)
      if [ "$STATUS" = "on" ]; then
        echo "on $INUSER $INPW $OUTUSER $OUTPW"
        return 0
      else
        if [ ! -z "$INRST" ]; then
          sed -i "s/^IncomingUser.*/#IncomingUser/" $ISCSI_CONF
        elif [ ! -z "$OUTRST" ]; then
          sed -i "s/^OutgoingUser.*/#OutgoingUser/" $ISCSI_CONF
        fi
        echo "off $INUSER $INPW $OUTUSER $OUTPW"
        return 1
      fi
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# afp
#-------------------------------------------------------------------------------

AFP_CONF=/etc/netatalk/afpd.conf

#
#   NC1_TODO
#
set_afp() {
  local FUNC="SET_AFP"
  log_func $FUNC $@

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: property
#
get_afp() {
  case "$1" in 
    "enabled")
      get_conf_blank afp $SERVICE_CONF 
      ;;
    "*") 
      echo
      ;;
  esac
}


#-------------------------------------------------------------------------------
# Timemachine
#-------------------------------------------------------------------------------

TIMEMACHINE_DIR=/mnt/disk/default/service/Timemachine
AFP_APPLEVOLUME_CONF=/etc/netatalk/AppleVolumes.default
AVAHI_TIMEMACHINE_CONF=/etc/avahi/services/timemachine.service
AVAHI_SERVICE_CONF_DIR=/etc/avahi/services

#
# $1: hostname | off
# $2: macaddress
#
set_timemachine() {
  local FUNC="SET_TIMEMACHINE"
  log_func $FUNC $@

  # off case
  if [ "$1" = "off" ]; then
    rm /etc/avahi/services/timemachine.service
    /etc/init.d/avahi-daemon restart
    return
  fi

  # STEP 0: precheck
  VOL=$(nas-storage get vol_default)
  if [ -z "$VOL" ]; then
    enable_service timemachine off
    log_func_check_result $FUNC $FALSE $ERROR_VOL_NOT_EXIST
  fi

  rm /etc/avahi/services/timemachine.service*
  TMDIRLIST=$(echo $1 | sed 's/:/ /g')

  # STEP 2: generate AVAHI service 
  cp /etc/nas/default/timemachine.service /etc/avahi/services/
  MACADDR=$(nas-network get macaddress)
  sed -i "s/%MACADDR%/$MACADDR/" $AVAHI_TIMEMACHINE_CONF

  UUID=$(uuidgen)
  cnt=0
  for basename in $TMDIRLIST
  do
    echo "    <txt-record>dk$cnt=adVF=0xa1,adVN=$basename,adVU=$UUID</txt-record>" \
      >> /etc/avahi/services/timemachine.service.volname
                cnt=$((cnt+1))
  done

  AVAHI_TIMEMACHINE_TEMP=/etc/avahi/services/timemachine.service.temp
  head -n 13 $AVAHI_TIMEMACHINE_CONF > $AVAHI_TIMEMACHINE_TEMP
  cat /etc/avahi/services/timemachine.service.volname >> $AVAHI_TIMEMACHINE_TEMP
  tail -n 3 $AVAHI_TIMEMACHINE_CONF >> $AVAHI_TIMEMACHINE_TEMP
  mv $AVAHI_TIMEMACHINE_TEMP $AVAHI_TIMEMACHINE_CONF
  rm /etc/avahi/services/timemachine.service.volname

  log_func_check_result $FUNC $? $ERROR_EXEC
}
#
# $1 : property
#
get_timemachine() {
  #log_func TM_GET $@
  case "$1" in
    "enabled")
      get_conf_blank timemachine $SERVICE_CONF
      ;;
    "macaddress")
      FILE=$(basename $(echo $TIMEMACHINE_DIR/*.sparsebundle))
      if [ -e "$TIMEMACHINE_DIR/$FILE" ]; then
        MAC=$(echo $FILE | sed "s/.*_//" | sed "s/\..*//")
        echo ${MAC:0:2}:${MAC:2:2}:${MAC:4:2}:${MAC:6:2}:${MAC:8:2}:${MAC:10:2}  
      fi
      ;;
    "hostname")
      FILE=$(basename $(echo $TIMEMACHINE_DIR/*.sparsebundle))
      if [ -e "$TIMEMACHINE_DIR/$FILE" ]; then
        HOSTNAME=$(echo $FILE | sed "s/_.*//")
        echo $HOSTNAME
      fi
      ;;
    "afplist")
      AFPSHARE=$(cat $AFP_APPLEVOLUME_CONF | grep options | cut -d " " -f1)
      echo $AFPSHARE
      ;;
    "avahiconf")
      AVAHICONF=$(cat $AVAHI_TIMEMACHINE_CONF | grep dk | cut -d "," -f2 | cut -d "=" -f2)
      echo $AVAHICONF
      ;;
    "*")
      echo
      ;;
  esac
}


#-------------------------------------------------------------------------------
# samba
#-------------------------------------------------------------------------------

SAMBA_CONF=/etc/samba/smb.conf

#
# $1: field
# $2: value
#
set_samba() {
  local FUNC="SET_SAMBA"
  
  log_func $FUNC $@
  
  FIELD=$1
  shift
  VALUE=$@
  replace_conf_equal "$FIELD" "$VALUE" $SAMBA_CONF

  log_func_check_result $FUNC $? $ERROR_EXEC
}
#
# $1 : property
#
get_samba() {
  case "$1" in 
    "enabled")
      get_conf_blank samba $SERVICE_CONF 
      ;;
    "access")
      smbstatus -p | sed "0,/---/d" | sed "s/[()]//g" | tr -s " "
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# ftp
#-------------------------------------------------------------------------------

FTP_CONF=/etc/proftpd/proftpd.conf

#
#   NC1_TODO
#
set_ftp() {
  local FUNC="SET_FTP"

  log_func $FUNC $@
  FIELD=$1
  shift
  VALUE=$@
  replace_conf_blank "$FIELD" "$VALUE" $FTP_CONF

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1 : property
#
get_ftp() {
  case "$1" in 
    "enabled")
      get_conf_blank ftp $SERVICE_CONF 
      ;;
    "port")	
      get_conf_blank Port $FTP_CONF
      ;;
    "access")
      #ftpwho -v -o oneline | grep "[.*]" | tr -s " "
      ftpwho -v -o oneline | grep "[.*]" | tr -d "[]" | awk '{print $2" "$7}' | sort -u
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# printer
#-------------------------------------------------------------------------------
#
#
set_printer() {
  local FUNC="SET_PRINTER"
  log_func $FUNC $@

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1 : property
#
get_printer() {
  case "$1" in 
    "enabled")
      get_conf_blank printer $SERVICE_CONF 
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# itunes
#-------------------------------------------------------------------------------

ITUNES_CONF=/etc/mt-daapd.conf

#
#   NC1_TODO
#
set_itunes() {
  local FUNC="SET_ITUNES"

  log_func $FUNC $@
  FIELD=$1
  shift
  VALUE=$@
  replace_conf_equal "$FIELD" "$VALUE" $ITUNES_CONF

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1 : property
#
get_itunes() {
  case "$1" in 
    "enabled")
      get_conf_blank itunes $SERVICE_CONF 
      ;;
    "rescan")
      get_conf_equal rescan_interval $ITUNES_CONF
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# torrent
#-------------------------------------------------------------------------------
#
#
 
#
# $1 : property
#
get_torrent() {
  case "$1" in
    "enabled")
      get_conf_blank torrent $SERVICE_CONF
      ;;
    "*")
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# DLNA 
#-------------------------------------------------------------------------------
DLNA_CONF=/etc/dlna.conf
DLNA_PATH=/usr/sbin/DLNA
DLNA_INTERNAL_DIRLIST=/usr/sbin/DLNA/conf/dms/sync/dir_list
DLNA_DATABASE=/usr/sbin/DLNA/conf/dms/database
#
#   NC1_TODO
#
set_dlna() {
  local FUNC="SET_DLNA"

  log_func $FUNC $@
  FIELD=$1
  shift
  DLNA_PATH=$@

  if [ "$DLNA_PATH" = "" ]; then
    echo ok
  else
    replace_conf_blank "$FIELD" "$DLNA_PATH" $DLNA_CONF
    log_func_check_result $FUNC $? $ERROR_EXEC
    echo $DLNA_PATH > $DLNA_INTERNAL_DIRLIST
    echo ok:changed	
  fi
}

#
# $1 : property
#
get_dlna() {
  local FUNC="GET_DLNA"
  
  #log_func $FUNC $@ 
  case "$1" in 
    "enabled")
      get_conf_blank dlna $SERVICE_CONF 
      ;;
    "default_path")
      get_conf_blank dlna_default_path $DLNA_CONF 
      ;;
    "user_path")
      get_conf_blank dlna_user_path $DLNA_CONF 
      ;;
    "*") 
      echo
      ;;
  esac
}

init_dlna_db() {
  local FUNC="INIT_DLNA_DB"
  
  rm -fr $DLNA_PATH/cache
  cd $DLNA_DATABASE
  rm -f *-journal *.rec *.db
}



#-------------------------------------------------------------------------------
# DDNS
#-------------------------------------------------------------------------------

DDNS_LGNAS_CONF=/etc/ddnscli.conf
DDNS_DYNDNS_CONF=/etc/inadyn.conf
DDNS_SERVICE_CONF=/etc/nas/ddns.conf
DDNS_LGNAS_SERVER=http://ns.lgnas.com
DDNS_DYNDNS_SERVER=http://checkip.dyndns.org

# 
# $1: username
# $2: password
# $3: mac address
#
ddns_add_user() {
  local RESULT
  RESULT=$(membercli --command=useradd --userid=$1 --passwd=$2 \
           --hostname=$USER.lgnas.com --devicetype=NC1 --model=N2x1 --ttl=300 \
           --httpport=80 --myip=0.0.0.0 --mymac=$3 --ver=1.0 --desc=- | tail -1)
  echo $RESULT
}

#
# $1: username
#
ddns_check_user() {
  local RESULT
  local FUNC="DDNS_CHECK_USER"

  log_func $FUNC $@
  RESULT=$(membercli --command=isuser --hostname=$1.lgnas.com | tail -1)
  echo $RESULT
}

#
# $1: username
# $2: password
#
ddns_del_user() {
  local RESULT
  RESULT=$(membercli --command=userdel --userid=$1 --passwd=$2 \
           --hostname=$USER.lgnas.com | tail -1)
  echo $RESULT
}

#
# $1: operation { add | remove }
#
register_ddns() {
  local FUNC="REGISTER_DDNS"

  log_func $FUNC $@

  REGISTER="fail"
  USER=$(get_conf_blank username $DDNS_LGNAS_CONF)
  PASS=$(get_conf_blank password $DDNS_LGNAS_CONF)
  MACADDR=$(nas-network get macaddress)

  RESULT=$(ddns_check_user $USER)

  if [ "$1" = "remove" ]; then
    if [ "$RESULT" = "good" ]; then
      ddns_del_user $USER $PASS
    fi
    return 0
  fi

  if [ "$RESULT" = "bad" ]; then
    # user is not registered
    RESULT=$(ddns_add_user $USER $PASS $MACADDR)
    [ "$RESULT" = "good" ] && REGISTER="ok"
  elif [ "$RESULT" = "good" ]; then
    # user is already registered
    RESULT=$(ddns_del_user $USER $PASS)
    if [ "$RESULT" = "good" ]; then
      # re-register user
      RESULT=$(ddns_add_user $USER $PASS $MACADDR)
      [ "$RESULT" = "good" ] && REGISTER="ok"
    else
    	# password doesn't match. Can't delete a previous setting.
    	echo "id_fail"
    	return 0
    fi 
  fi

# TODO
  if [ "$REGISTER" = "ok" ]; then
    echo ok
  else
    echo fail
  fi
}

set_ddns() {
  local FUNC="SET_DDNS"

#  log_func $FUNC $@
#2010/04/28 park94
  if [ "$2" != "password" ]; then
	  log_func $FUNC $@
  fi

  SERVICE=$1
  shift
  FIELD=$1
  shift
  VALUE=$@

  if [ "$SERVICE" = "lgnas" ]; then
    DDNS_CONF=$DDNS_LGNAS_CONF
  else
    DDNS_CONF=$DDNS_DYNDNS_CONF
  fi

  replace_conf_space "$FIELD" "$VALUE" $DDNS_CONF 
  
  if [ "$FIELD" = "alias" ]; then
    replace_conf_equal service "$SERVICE" $DDNS_SERVICE_CONF
  fi
  
  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1 : property
#
get_ddns() {
  SERVICE=$(get_conf_equal service $DDNS_SERVICE_CONF)

  if [ "$SERVICE" = "lgnas" ]; then
    DDNS_CONF=$DDNS_LGNAS_CONF
  else
    DDNS_CONF=$DDNS_DYNDNS_CONF
  fi

  case "$1" in 
    "enabled")
      get_conf_blank ddns $SERVICE_CONF 
      ;;
    "service")
      get_conf_equal service $DDNS_SERVICE_CONF
      ;;
    "username"|"password"|"alias")
      get_conf_blank "$1" $DDNS_CONF
      ;;
    "registered_ip")
      # TODO
        wget -q -O - $DDNS_LGNAS_SERVER | cut -d " " -f 6 | cut -d "<" -f 1
      ;;
    "confirm_ip")
      DOMAIN=$(get_conf_blank alias $DDNS_CONF)
      for i in `seq 5`
      do
        IP=$(dig +time=1 +tries=1 $DOMAIN | grep $DOMAIN | tail -1 | awk '{print $5}')
        if [ -n "$IP" ]; then
          echo $IP
          return
        fi
      done
      ;;
    "*") 
      echo
      ;;
  esac
}

#-------------------------------------------------------------------------------
# service control
#-------------------------------------------------------------------------------

# $1: service name
#
get_initd() {
  case "$1" in 
    "samba")		NAME="samba" ;;
    "ftp")		NAME="proftpd" ;;
    "iscsi")		NAME="iscsi-target" ;;
    "itunes")		NAME="mt-daapd" ;;
    "torrent")         	NAME="transmission-daemon" ;;
    "afp")		NAME="atalk" ;;
    "timemachine")	NAME="atalk" ;;
    "printer")		NAME="lprng" ;;
    "ddns")		NAME="ddnscli" ;;
    "dlna")		NAME="dlnad" ;;
    "mysql")            NAME="mysql" ;;
     *)			NAME="unknown" ;;
  esac
  echo $NAME
}

#
# $1: all, samba, ftp, http, ...
# $2: start, stop, restart
# $3: fast (use for network setting)
#
control_service() {
  FUNC="CONTROL_SERVICE"

  log_func $FUNC $@
  
  [ -e $SERVICE_CONF ]; log_func_check_result $FUNC $? $ERROR_FILE_NOT_EXIST $SERVICE_CONF
  
  grep '^[^#]' $SERVICE_CONF | \
  while read service state; do
    INITD=$(get_initd $service)
    [ -e /etc/init.d/$INITD ] || continue

    if [ "$service" = "$1" ] || [ "$1" = "all" ]; then
      if [ "$state" = "off" ]; then
        [ "$runlevel" = "S" ] && continue
        [ "$3" = "fast" ] && continue
        /etc/init.d/$INITD stop
        log_func $FUNC $INITD stop : result = $?
      else 
        /etc/init.d/$INITD $2
        log_func $FUNC $INITD $2 : result = $?
      fi
    fi
  done

  # Selective mirror
  if [ "$1" = "all" ] && [ "$2" = "stop" ]; then
		log_func "SelectiveMirror" "service stopped"
		echo 0 > /proc/fs/s_mirror/sm_enable
  else
		log_func "SelectiveMirror" "Restore configuration"
		cat '/etc/sm_enable' > /proc/fs/s_mirror/sm_enable

  fi

  # avahi
  [ -e /var/run/nas/booting ] && USE_AVAHI=no
  if [[ "$USE_AVAHI" = "yes" && "$2" != "reload" ]] ; then
    /etc/init.d/avahi-daemon restart
    log_func $FUNC avahi-daemon $2 : result = $?
  fi

  log_func_result $FUNC $TRUE $ERROR_EXEC
}

#
# $1: all, samba, ftp, http, afp ...
# $2: on, off
#
enable_service() {
  FUNC="ENABLE_SERVICE"

  log_func $FUNC $@

  if [ "$1" = "all" ]; then
    if [ "$2" = "off" ]; then
      sed -i '/^[^#]/ s/[[:space:]]on/\toff/' $SERVICE_CONF    
    else 
      sed -i '/^[^#]/ s/[[:space:]]off/\ton/' $SERVICE_CONF    
    fi
  else
    replace_conf_blank $1 $2 $SERVICE_CONF
  fi

  case "$1" in
    "afp")
      if [ "$2" = "off" ]; then
        rm -f /etc/avahi/services/afpd.service
        cp -f /etc/nas/default/samba.service /etc/avahi/services/
      else 
        rm -f /etc/avahi/services/samba.service
        cp -f /etc/nas/default/afpd.service /etc/avahi/services/
      fi
      ;;
    "timemachine")
      set_timemachine off
      ;;
    "iscsi")
      if [ "$2" = "on" ]; then
        mount | grep "$CDROM_MOUNT_DIR"
        if [ $? = 0 ]; then
          nas-storage odd_umount /dev/scd0
          sleep 1
        fi

        grep "### BEGIN CD-ROM INFO" /etc/samba/smb.conf
        if [ $? = 0 ]; then
          nas-share del_samba_cdrom
        fi		  
      else 
        mkdir -p $CDROM_MOUNT_DIR
        # check file system type
#        sg_turs /dev/scd0
#        if [ $? = 0 ]; then
#          vol_id --type /dev/scd0
#          if [ $? = 0 ]; then
#            nas-storage odd_mount /dev/scd0
#          fi
#        fi
        nas-share add_samba_cdrom /etc/samba/smb.conf
      fi
      ;; 
  esac

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: all, property 
# $2: value (if necessary)
#
config_service() {
  FUNC="CONFIG_SERVICE"

  log_func $FUNC $@
  
  case "$1" in 
    "all")
      echo
      ;;
    "hostname")		
      echo
      ;;
    "description")	
      shift
      set_samba "server string" $@
      control_service all reload      
      ;;
    "printer")
      # TODO 
      #control_service samba reload
      control_service samba restart
      ;;
    "language")
      LANGUAGE=$(nas-system get codepage)
      log_func $FUNC "Language = $LANGUAGE" 
      
      # netatalk
      if [ "$LANGUAGE" = "CP949" ]; then
        AFP_LANG="MAC_KOREAN"
      fi

      if [ "$LANGUAGE" = "CP932" ]; then
        AFP_LANG="MAC_JAPANESE"
      fi

      if [ "$AFP_LANG" = "" ]; then
        AFP_LANG="MAC_ROMAN"
      fi
      replace_conf_equal_only "ATALK_MAC_CHARSET" "'"$AFP_LANG"'" /etc/init.d/atalk
      echo "- -transall -maccodepage $AFP_LANG -unixcodepage UTF8" > /etc/netatalk/afpd.conf
      control_service atalk reload

      # samba
      replace_conf_equal "dos charset" $LANGUAGE $SAMBA_CONF 
      control_service samba restart

      # ftp
      replace_conf_blank "UseEncoding" "utf8 $LANGUAGE" $FTP_CONF  
      control_service ftp restart
      ;;
    *)
      echo
      ;;
  esac

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: property
#
service_get() {
  local FUNC="SERVICE_GET"

  case "$1" in
    "enabled")
       RES=$(get_conf_blank "$2" $SERVICE_CONF)
      if [ -z "$RES" ]; then
        echo "off"
        echo "$2         off" >> $SERVICE_CONF
      else
        echo $RES
      fi
      ;;
    "running")
      if [ "$2" = "odd_backup" ]; then
        if [ -e /var/run/odd_backup.pid ]; then
          echo "on"
        else
          echo "off"
        fi
      fi
      ;;
    *)
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}

#-------------------------------------------------------------------------------
# transcoder
#-------------------------------------------------------------------------------
FFMPEG_LOG=/etc/ffmpeg.log
FFMPEG_PROGRESS=/etc/ffmpeg.prg
TRANSCODE_DURATION=/var/www/run/trans_duration
get_trans_info() {
  local FUNC="GET_TRANS_INFO"

  case "$1" in
    "duration")
      if [ ! -z "$2" ]; then
         ffmpeg -i $2 > $FFMPEG_LOG 2>&1
      fi
      
      RESULT=$(cat $FFMPEG_LOG | grep "Duration: " | cut -d "," -f 1)
      echo $RESULT"||"$3"||"$4 > $TRANSCODE_DURATION
      ;;
    "progress")
      TEMP=$(cat $FFMPEG_PROGRESS)
      RESULT=${TEMP##*"time="}
      RESULT=$(echo $RESULT | cut -d "." -f 1)
      DURATION=$(cat $TRANSCODE_DURATION)
      echo $DURATION"||"$RESULT

      ;;
  esac
}

#
# $1 : input file path
# $2 : options (-acodec, -vcodec, bitrate ...)
# $3 : output file path
#
TRANSCODE_FILE=/var/www/run/trans_list
TRANSCODE_OPTIONS=/var/www/run/trans_options
start_transcoding() {
  local FUNC="START_TRANSCODING"
  local NUMBER=0
  local CNT=0
  OPTIONS=$(cat $TRANSCODE_OPTIONS | grep "option" | cut -d ":" -f 2)
  EXTENSION=$(cat $TRANSCODE_OPTIONS | grep "extension" | cut -d ":" -f 2)

  TRANS_LIST=$(cat $TRANSCODE_FILE)

  LIST=$(cat /tmp/tmp)
  LAST=${LIST##*"time="}
  echo $LAST | cut -d "." -f 1 > /home/test
  
  for FILEPATH in $TRANS_LIST; do
    NUMBER=$((NUMBER+1))
  done

  for FILEPATH in $TRANS_LIST; do
    CNT=$((CNT+1))
    get_trans_info duration $FILEPATH $CNT $NUMBER   
    FILE='[T]'$(basename $FILEPATH)
    DIR=$(dirname $FILEPATH)

    FILE=$(echo $FILE | sed 's/\(.*\)\..*/\1/')
    TRANS_FILEPATH=$DIR/$FILE.$EXTENSION

    echo $FILEPATH $OPTIONS $TRANS_FILEPATH >> /home/juny
    ffmpeg -i $FILEPATH $OPTIONS $TRANS_FILEPATH > $FFMPEG_PROGRESS 2>&1 
  done
   
  echo "COMPLETE"

}

get_web(){
  local FUNC="Get User Web Info"

  case "$1" in
    "enabled")
      get_conf_equal www $SERVICE_INFO_CONF
      ;;
    "port")
      get_conf_equal www_port $SERVICE_INFO_CONF
      ;;
    "ssl")
      get_conf_equal www_ssl $SERVICE_INFO_CONF
      ;;
    "ssl_port")
      get_conf_equal www_ssl_port $SERVICE_INFO_CONF
      ;;
  esac 
}
set_web(){
 case "$1" in
    "enabled")
      replace_conf_equal www "$2 " $SERVICE_INFO_CONF
      replace_conf_equal www_port "$3 " $SERVICE_INFO_CONF
      replace_conf_equal www_ssl "$4 " $SERVICE_INFO_CONF
      replace_conf_equal www_ssl_port "$5 " $SERVICE_INFO_CONF

      if [ "$2" = 'on' ]; then 
	nas-share add_myweb
      fi 

#      if [ "$3"= '80' ]; then
#        nas-share add_myweb
#      fi

#      if [ "$4" = 'on' ]; then
#        nas-share add_myweb
#      fi

#      if [ "$5" = 'on' ]; then
#        nas-share add_myweb
#      fi


      gen_lighttpd_conf > /etc/lighttpd/lighttpd.ports
      gen_http_vhost_conf > /etc/apache2/extra/httpd-vhosts.conf 

      /etc/init.d/apache2 reload
      /etc/init.d/lighttpd reload
      
      ;;
    "port")
      get_conf_equal www_port $SERVICE_INFO_CONF
      ;;
    "ssl")
      get_conf_equal www_ssl $SERVICE_INFO_CONF
      ;;
    "ssl_port")
      get_conf_equal www_ssl_port $SERVICE_INFO_CONF
      ;;
  esac 
}
get_sql(){
   case "$1" in
    "enabled")
      service_get enabled mysql
      get_conf_equal mysql $SERVICE_INFO_CONF
      get_conf_equal_blank mysql $SERVICE_CONF
      ;;
    "port")
      get_conf_equal mysql_port $SERVICE_INFO_CONF
      ;;
    "pass")
      get_conf_equal mysql_pass $SERVICE_INFO_CONF
      ;;
  esac 
}
set_sql(){
  case "$1" in
    "enabled")
      replace_conf_equal mysql "$2 " $SERVICE_INFO_CONF
      #replace_conf_equal mysql_port "$3 " $SERVICE_INFO_CONF
      OLD_PASS=$(get_conf_equal mysql_pass $SERVICE_INFO_CONF)
      replace_conf_equal mysql_pass "$3 " $SERVICE_INFO_CONF
      if [ "$2" = 'on' ]; then 
	nas-share add_mysql
        nas-service enable mysql on
	nas-service control mysql start 
        sleep 2
        mysqladmin -u root -p$OLD_PASS password $3
      else 
        nas-service enable mysql off
	nas-service control mysql stop 
      fi 
      ;;
    "port")
      get_conf_equal mysql_port $SERVICE_INFO_CONF
      ;;
    "pass")
      get_conf_equal mysql_pass $SERVICE_INFO_CONF
      ;;
  esac
}

gen_lighttpd_conf(){
   local FUNC="GEN HTTP VHOST CONF"
   ADMIN_WEB_PORT="8000"

   USER_WEB=$(get_conf_equal www $SERVICE_INFO_CONF)
   USER_WEB_PORT=$(get_conf_equal www_port $SERVICE_INFO_CONF)
   USER_WEB_SSL=$(get_conf_equal www_ssl $SERVICE_INFO_CONF)
   USER_WEB_SSL_PORT=$(get_conf_equal www_ssl_port $SERVICE_INFO_CONF)

   if [ "$USER_WEB" = "on" ]; then

      if [ "$USER_WEB_PORT" = '80' ]; then
         echo "#80 for userweb"
         
      else
         echo "\$SERVER[\"socket\"] == \"0.0.0.0:80\" {"
         echo " server.document-root = \"/var/www/default\""
         echo "}"
      fi

      if [ "$USER_WEB_SSL" = "on" ];then

          if [ "$USER_WEB_SSL_PORT" = '443' ]; then

           echo "#443 port for userweb ssl"
          
          else
           echo "\$SERVER[\"socket\"] == \"0.0.0.0:443\" {"
           echo " ssl.engine  = \"enable\""
           echo " ssl.pemfile = \"/etc/lighttpd/server.pem\""
           echo "}"
          fi 
       else
           echo "\$SERVER[\"socket\"] == \"0.0.0.0:443\" {"
           echo " ssl.engine  = \"enable\""
           echo " ssl.pemfile = \"/etc/lighttpd/server.pem\""
           echo "}"
      fi

   else
      echo "\$SERVER[\"socket\"] == \"0.0.0.0:80\" {"
      echo " server.document-root = \"/var/www/default\""
      echo "}"
      echo "\$SERVER[\"socket\"] == \"0.0.0.0:443\" {"
      echo " ssl.engine  = \"enable\""
      echo " ssl.pemfile = \"/etc/lighttpd/server.pem\""
      echo "}"
   fi
} 

gen_http_vhost_conf(){
    local FUNC="GEN HTTP VHOST CONF"
    DBFILE=/etc/nas/db/share.db
    ADMIN_WEB_PORT="8000"
    TORRENT_WEB_PORT="9091"

    USER_WEB=$(get_conf_equal www $SERVICE_INFO_CONF)
    USER_WEB_PORT=$(get_conf_equal www_port $SERVICE_INFO_CONF)
    USER_WEB_SSL=$(get_conf_equal www_ssl $SERVICE_INFO_CONF)
    USER_WEB_SSL_PORT=$(get_conf_equal www_ssl_port $SERVICE_INFO_CONF)
    USER_WEB_DIR=$(/usr/bin/sqlite3 $DBFILE "select path from folder_info where folder='myweb'")

    echo "Listen $TORRENT_WEB_PORT"
    echo "NameVirtualHost *:$TORRENT_WEB_PORT"
	

    #echo #$USER_WEB $USER_WEB_DIR
    if [ "$USER_WEB" = "on" ]; then
        	
	    echo "Listen $USER_WEB_PORT"
	    echo "NameVirtualHost *:$USER_WEB_PORT"
            echo "<VirtualHost *:$USER_WEB_PORT>"
	    echo "  DocumentRoot \"$USER_WEB_DIR\""
	    echo "<Directory \"$USER_WEB_DIR\">"
	    echo "    Options Indexes FollowSymLinks"
	    echo "    AllowOverride None"
	    echo "    Order allow,deny"
	    echo "    Allow from all"
	    echo "</Directory>"
	    echo "</VirtualHost>"
	if [ "$USER_WEB_SSL" = "on" ];then  
        
              echo "Listen $USER_WEB_SSL_PORT"
	      echo "NameVirtualHost *:$USER_WEB_SSL_PORT"
              echo "<VirtualHost _default_:$USER_WEB_SSL_PORT>"
	      echo "DocumentRoot \"$USER_WEB_DIR\""
	      echo "ServerName www.example.com:$USER_WEB_SSL_PORT"
	      echo "ServerAdmin you@example.com"
	      echo "ErrorLog \"/var/log/apache2/error_log\""
	      echo "TransferLog \"/var/log/apache2/access_log\""
	      echo "SSLEngine on"

	      echo "SSLCipherSuite ALL:!ADH:!EXPORT56:RC4+RSA:+HIGH:+MEDIUM:+LOW:+SSLv2:+EXP:+eNULL"

	      echo "SSLCertificateFile \"/etc/apache2/lgnas.crt\""
	      echo "SSLCertificateKeyFile \"/etc/apache2/lgnas.key\""
	      echo "<FilesMatch \"\.(cgi|shtml|phtml|php)$\">"
	      echo "    SSLOptions +StdEnvVars"
	      echo "</FilesMatch>"
	      echo "<Directory \"$USER_WEB_DIR\">"
	      echo "    Options Indexes FollowSymLinks"
	      echo "    AllowOverride None"
	      echo "    Order allow,deny"
	      echo "    Allow from all"
	      echo "</Directory>"

	      echo "BrowserMatch \".*MSIE.*\" \\"
	      echo "         nokeepalive ssl-unclean-shutdown \\"
	      echo "         downgrade-1.0 force-response-1.0"
	      echo "CustomLog \"/var/log/apache2/ssl_request_log\" \\"
	      echo "          \"%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \\"%r\\\" %b\"
	      echo "</VirtualHost>"
        fi
    fi 	
   
    echo "<VirtualHost *:$TORRENT_WEB_PORT>"
    echo  "DocumentRoot \"/var/www/torrent\""
    echo "</VirtualHost>"
}







