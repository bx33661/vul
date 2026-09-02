#!/bin/bash

#===============================================================================
# 
#===============================================================================

LIBDIR=/usr/lib/nas

. $LIBDIR/common.sh

SYSTEM_CONF_FILE=/etc/nas/system.conf

#-------------------------------------------------------------------------------
# 
#-------------------------------------------------------------------------------

#
# $1: language
#
set_language() {
  local FUNC="SET_LANG"

  log_func $FUNC $@

  replace_conf_equal codepage $1 $SYSTEM_CONF_FILE
  nas-service config language

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: time : 01/29/09 15:42:00 
#
set_time() {
  local FUNC="SET_TIME"

  log_func $FUNC $@

  # set system time
  #date -s $1
  date -s "$1 $2"

  # sync hardware time
  hwclock -w -u
  
  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: timezone
#
set_timezone() {
  local FUNC="SET_TIMEZONE"

  log_func $FUNC $@
  ln -sf /usr/share/zoneinfo/$1 /etc/localtime
  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: enable
# $2: ntp server
# $3: ntp refresh
#
set_ntp() {
  local FUNC="SET_NTP"

  log_func $FUNC $@

  replace_conf_equal ntp $1 $SYSTEM_CONF_FILE
  replace_conf_equal ntpserver $2 $SYSTEM_CONF_FILE
  replace_conf_equal ntp_update $3 $SYSTEM_CONF_FILE

  FILENAME=/etc/nas/cron/ntpdate
  RESULT=0
  if [ "$1" = "on" ]; then 
    sync_ntp
    RESULT=$?
    CYCLE=$3
  else
    CYCLE=off
  fi

  if [ $RESULT = 0 ]; then
    register_cron $FILENAME $CYCLE
    RESULT=$?
  fi

  log_func_result $FUNC $RESULT
  return $RESULT
}

#
sync_ntp() {
  local FUNC="SYNC_NTP"

  log_func $FUNC $@

  NTP=$(get_conf_equal ntp $SYSTEM_CONF_FILE)
  NTPSERVER=$(get_conf_equal ntpserver $SYSTEM_CONF_FILE)
  DEFAULT_SERVER=$(get_conf_equal default_ntpserver $SYSTEM_CONF_FILE)

  if [ "$NTP" = "on" ]; then
    if [ "$NTPSERVER" = "none" ]; then
      ntpdate -s -b $DEFAULT_SERVER
    else 
      ntpdate -s -b $NTPSERVER
      if [ $? != 0 ]; then
        ntpdate -s -b -o 3 $NTPSERVER
      fi 
    fi
  fi

  RESULT=$?

  # sync hardware time
  if [ $RESULT = 0 ]; then
    hwclock -w -u
    RESULT=$?
  fi

  log_func_result $FUNC $RESULT
  return $RESULT
}

#
# $1: on
# $2: backup method
# $3: backup cycle
#
schedule_backup() {
  local FUNC="SCHEDULE_BACKUP"

  log_func $FUNC $@
  replace_conf_equal schedule $1 $SYSTEM_CONF_FILE
  replace_conf_equal schedule_method $2 $SYSTEM_CONF_FILE
  replace_conf_equal schedule_cycle $3 $SYSTEM_CONF_FILE

  local FILENAME=/etc/nas/cron/scheduledate
  if [ "$1" = "on" ]; then
    CYCLE=$3
  else
    CYCLE=off
  fi

  register_cron $FILENAME $CYCLE

  log_func_result $FUNC $OK
}

#
# $1: message 
# $2: Mail Address
# $3: user ID
# $4: user password
#
email_test() {
  local FUNC="MAIL_TEST"
  log_func $FUNC $@

  if [ "$(nas-system get email_alert)" = "off" ]; then
    log_func $FUNC "mail notification off"
    return 1
  fi
  TEMP_MAIL=/tmp/test.eml
  if [ $# = 4 ]; then
    MAILTO=$2
  else
    MAILTO=$(nas-system get email_to)
  fi
  SUBJECT=$(nas-system get email_subject)
  SMTP_AUTH=$(nas-system get smtp_auth)

  echo "To:$MAILTO" > $TEMP_MAIL
  echo "From:$(hostname) Notification System <$MAILTO>" >> $TEMP_MAIL
  echo "Subject:$SUBJECT : $1" >> $TEMP_MAIL
  echo "" >> $TEMP_MAIL
  echo $1 >> $TEMP_MAIL
  echo "" >> $TEMP_MAIL
  if [ $# = 4 ]; then
    echo "Login ID       : $3" >> $TEMP_MAIL
    echo "Login PASSWORD : $4" >> $TEMP_MAIL
    echo "" >> $TEMP_MAIL
    echo "You can access NAS at http://$(nas-network get ipaddr)" >> $TEMP_MAIL
    echo "" >> $TEMP_MAIL
  fi

  if [ "$(tolower $SMTP_AUTH)" = "on" ]; then
    SMTP_USER=$(nas-system get smtp_user)
    SMTP_PASS=$(nas-system get smtp_pass)
    ssmtp -au$SMTP_USER -ap$SMTP_PASS $MAILTO < $TEMP_MAIL
  else 
    ssmtp $MAILTO < $TEMP_MAIL
  fi

  rm $TEMP_MAIL
}

#
# $1: on, off, test
#
email_alert() {
  local FUNC="SET_MAIL_ALERT"
  log_func $FUNC $@

  MODE=$(tolower $1)
  SMTP_CONF=/etc/ssmtp/ssmtp.conf
  SMTP_SERVER=$(nas-system get smtp_server)
  SMTP_SSL=$(tolower $(nas-system get smtp_ssl))
  EMAIL_TERM=$(nas-system get email_term)

  if [[ "$MODE" = "on" || "$MODE" = "off" ]]; then

    # Apply mail setting
    replace_conf_equal "email_alert" $MODE $SYSTEM_CONF_FILE
    replace_conf_equal_only "Mailhub" $SMTP_SERVER $SMTP_CONF
    if [ "$SMTP_SSL" = "on" ]; then
      replace_conf_equal_only "UseTLS" "YES" $SMTP_CONF
    else 
      replace_conf_equal_only "UseTLS" "NO" $SMTP_CONF
    fi

    # register to crond

    if [ "$MODE" = "off" ]; then
      CYCLE=off
    else
      case "$EMAIL_TERM" in
        "Daily")	CYCLE=1d ;;
        "Weekly")	CYCLE=1w ;;
        "Monthly")	CYCLE=1m ;;
      esac
    fi
    
    EMAIL_HDD_REPORT=$(system_get email_hdd_report)
    if [ "$EMAIL_HDD_REPORT" = "OFF" ]; then
      register_cron /etc/nas/cron/hddreport off
    else
      register_cron /etc/nas/cron/hddreport $CYCLE
    fi

    # send mail
    if [ "$MODE" != "off" ]; then
      if [ "$EMAIL_HDD_REPORT" != "OFF" ]; then
         /etc/nas/cron/hddreport &
      fi
    fi
  elif [ "$MODE" = "test" ]; then
    # email test
    email_test "EMAIL CONFIGURATION TEST"
  elif [ "$MODE" = "user" ]; then
    # email user
    email_test "User Registration Notification" $2 $3 $4
  fi
}

#
start_sleep() {
  ENABLE=$(get_conf_equal hibernation $SYSTEM_CONF_FILE)
  TIME=$(get_conf_equal hibernation_time $SYSTEM_CONF_FILE)
  HDDLIST=$(nas-storage get md_dev_list HDD)

  /etc/init.d/noflushd stop
  if [ "$ENABLE" = "on" ]; then
    log_func $FUNC Hibernation ON [Time = $TIME] $HDDLIST
    mount -t ext3 -o remount,noatime,commit=3000 / /
    echo 95 > /proc/sys/vm/dirty_ratio
    echo 5 > /proc/sys/vm/laptop_mode
    echo 10 > /proc/sys/vm/swappiness
    noflushd -n $TIME $HDDLIST 
  else
    log_func $FUNC Hibernation OFF
    mount -t ext3 -o remount,noatime,commit=30 / /
    echo 40 > /proc/sys/vm/dirty_ratio
    echo 0 > /proc/sys/vm/laptop_mode
    echo 60 > /proc/sys/vm/swappiness
  fi
}

# Mount Option & Tweak Settings
before_sleep() {
  export DEBUG=off	# temporary	

  local FUNC="BEFORE_SLEEP"
  log_func $FUNC $@

  /etc/init.d/sysklogd stop
  /etc/init.d/klogd stop
  /etc/init.d/crond stop
  pkill nas-mond
  nas-mond led &
  system_set fan 0
    
  sync
}
after_sleep() {
  export DEBUG=off	# temporary

  local FUNC="AFTER_SLEEP"
  log_func $FUNC $@

  /etc/init.d/sysklogd start
  /etc/init.d/klogd start
  /etc/init.d/crond start
  system_set fan 24 
  pkill nas-mond
  nas-mond &
}


#
# none : start hibernation
# 
# $1: enable
# $2: wait time
#
set_hibernation() {
  local FUNC="SET_HIBER"
  log_func $FUNC $@

  if [ "$#" = "0" ]; then
    start_sleep
    return $?
  fi

  [ ! -z "$1" ] && replace_conf_equal hibernation $1 $SYSTEM_CONF_FILE
  [ ! -z "$2" ] && replace_conf_equal hibernation_time $2 $SYSTEM_CONF_FILE

  start_sleep
  
  log_func_result $FUNC $OK  
}

#
start_ups() {
  local FUNC="START_UPS"

  ENABLE=$(get_conf_equal ups $SYSTEM_CONF_FILE)
  UPS_POWEROFF=$(get_conf_equal ups_poweroff $SYSTEM_CONF_FILE)

  if [ "$ENABLE" = "on" ]; then
    log_func $FUNC UPS start 
    if [ "$UPS_POWEROFF" = "off" ]; then
      ARGS="-p"
    else 
      ARGS=
    fi
    if [ -z "$(pidof apcupsd)" ]; then
      /sbin/apcupsd $ARGS
    else
      log_func $FUNC "UPS deamon already started"
    fi
  else   
    log_func $FUNC UPS stop
    pkill apcupsd
  fi
}

stop_ups() {
  local FUNC="STOP_UPS"
  COUNT=$(udevinfo -a -p /class/usb/hiddev0 | grep -y -E -c "American Power Conversion|UPS")
  if [ $COUNT = 0 ]; then
    # unpluged APC-UPS
    log_func $FUNC UPS "daemon stop"
    pkill -KILL apcupsd
  fi
}

#
# none: start service
#
# $1: UPS enable
# $2: shutdown time
# $3: UPS poweroff
#
set_ups() {
  local FUNC="SET_UPS"
  log_func $FUNC $@

  if [ "$#" = "0" ]; then
    start_ups
    return $?
  elif [[ "$#" = "1" && "$1" = "off" ]]; then
    stop_ups
    return $?
  fi

  UPS_CONF=/etc/apcupsd/apcupsd.conf

  replace_conf_equal ups $1 $SYSTEM_CONF_FILE
  replace_conf_equal ups_shutdown_time $2 $SYSTEM_CONF_FILE
  replace_conf_equal ups_poweroff $3 $SYSTEM_CONF_FILE
 
  replace_conf_blank TIMEOUT $2 $UPS_CONF

  start_ups
  
  log_func_result $FUNC $?
}

#
system_shutdown() {
  local FUNC="SHUTDOWN"

  log_func $FUNC $@
  lcd_msg $MSG_SHUTDOWN
  
  broadcast shutdown

# Press Set key to Initialize Password in shutdown process
  SetKey=$(hibernate -r 30 | cut -d" " -f 4)
  LeftKey=$(hibernate -r 31 | cut -d" " -f 4)

  if [[ "$SetKey" = "0" && "$LeftKey" = "0" ]]; then
    passwd=$(nas-common md5 admin)
    sqlite3 /etc/nas/db/share.db "delete from user where uid='admin'"
    sqlite3 /etc/nas/db/share.db "insert into user values('admin','$passwd','System Admin',' ','Default System Administrator')"
    nas-share mod_user admin admin
    log_func $FUNC "Init Password"
    lcd_msg $MSG_INIT_PASSWD
  fi


  sync
  pkill nas-cdromd
  eject -t /dev/sr0
  nas-service control all stop
  umount -a

  buzzer out
  halt -d3 
}

#-------------------------------------------------------------------------------
# Scheduled Power On/Off 
#-------------------------------------------------------------------------------

SCHEDULE_POWER_START_COMMENT="### BEGIN SCHEDULE POWER"
SCHEDULE_POWER_END_COMMENT="### BEGIN SCHEDULE POWER"

write_cron() {
  echo "$SCHEDULE_POWER_START_COMMENT"
  SCH_OFF_TIME=`cat /etc/nas/system.conf |grep schedule_power_Stime |cut -d " " -f3`
  HOURS=`echo $SCH_OFF_TIME | cut -d ":" -f1`
  MINS=`echo $SCH_OFF_TIME | cut -d ":" -f2`

  #HDDLIST=$(nas-storage get dev_list HDD)
  #echo "$((PREMINS%60)) $((PREMINS/60))       * * * noflushd -n 1 -p 1 $HDDLIST" 
  echo "$MINS $HOURS       * * * cd / && nas-system start_hibernation"
  echo "$SCHEDULE_POWER_END_COMMENT"
}

pre_schedule_power() {
  local FUNC="PRE_SCHEDULE_POWER"
  log_func $FUNC $@

  sync
  /etc/init.d/noflushd stop

  HDDLIST=$(nas-storage get md_dev_list HDD)
  noflushd -n 1 -p 1 $HDDLIST 
}

# 
# $1: enable
# $2: start time
# $3: end time
#
set_schedule_power() {
  local FUNC="SET_SCHEDULE_POWER"
  log_func $FUNC $@

  [ ! -z "$1" ] && replace_conf_equal schedule_power $1 $SYSTEM_CONF_FILE
  [ ! -z "$2" ] && replace_conf_equal schedule_power_Stime $2 $SYSTEM_CONF_FILE
  [ ! -z "$3" ] && replace_conf_equal schedule_power_Etime $3 $SYSTEM_CONF_FILE
  
  local FILENAME=/var/spool/cron/crontabs/root
  local CRON_DAILY=/etc/cron.daily/root
  if [ "$1" = "on" ]; then
    sed -i "/$SCHEDULE_POWER_START_COMMENT/,/$SCHEDULE_POWER_END_COMMENT/d" $FILENAME
    write_cron >> $FILENAME   
  else 
    sed -i "/$SCHEDULE_POWER_START_COMMENT/,/$SCHEDULE_POWER_END_COMMENT/d" $FILENAME
  fi
  /etc/init.d/crond restart
  log_func_result $FUNC $OK  
}


#
system_restart() {
  local FUNC="RESTART"

  log_func $FUNC $@
  lcd_msg $MSG_REBOOT
  
  broadcast reboot
  
  sync
  pkill nas-cdromd
  eject -t /dev/sr0
  nas-service control all stop
  umount -a

  buzzer out  
  reboot -d3
}

#
# $1 : num of retry 
# return val 0: No I/O, 1: I/O Exists
#
check_diskio() {

  RETRY=$1

  VOLNUM=$(nas-storage get vol_num)
  if [ "$VOLNUM" = "2" ]; then
    CHECK_VOL="md[2-3]"
  elif [ "$VOLNUM" = "1" ]; then
    CHECK_VOL="md2"
  else
    return 0
  fi

  sync
  sleep 3

  for i in `seq $RETRY`
  do
    IO_BEFORE=$(cat /proc/diskstats | grep $CHECK_VOL)
    sleep 5 
    IO_AFTER=$(cat /proc/diskstats | grep $CHECK_VOL)

    if [ "$IO_BEFORE" = "$IO_AFTER" ]; then
      echo "No DISK I/O"
      return 0
    else
      echo "DISK I/O Exists"
    fi
  done

  return 1 # I/O Exists
}

#
start_hibernation() {
  local FUNC="START_HIBERNATION"
  log_func $FUNC $@

  lcd_msg $MSG_HIBERNATION_START 

  # STEP 0: check squash mount, exception for iscsi&web burning & Disk I/O 
  ISCSI_ENABLED=$(nas-service get enabled iscsi)
  if [ "$ISCSI_ENABLED" = "on" ]; then
    log_func $FUNC $MSG_HIBERNATION_ISCSI_FAIL
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_HIBERNATION_ISCSI_FAIL
    return 1
  fi

  WEB_BURNING=$(pidof odd_burning)
  if [ -n "$WEB_BURNING" ]; then
    log_func $FUNC $MSG_HIBERNATION_BURN_FAIL
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_HIBERNATION_BURN_FAIL
    return 1
  fi

  check_diskio 1
  RET=$?
  if [ "$RET" != "0" ]; then
    log_func $FUNC $MSG_HIBERNATION_IO_FAIL $RET
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_HIBERNATION_IO_FAIL
    return $RET 
  fi

  mount -t squashfs /dev/mtdblock2 /mnt/flash/rootfs
  RET=$?
  if [ "$RET" != "0" ]; then
    log_func $FUNC $MSG_HIBERNATION_MOUNT_FAIL $RET
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_HIBERNATION_MOUNT_FAIL
    return $RET 
  fi

  # STEP 1: stop all service / daemon
  sync
  pkill nas-cdromd
  nas-service control all stop
  /etc/init.d/udev stop
  /etc/init.d/dbus stop
  /etc/init.d/crond stop
  /etc/init.d/noflushd stop
  pkill lighttpd
  pkill avahi-daemon
  pkill nas-usbd
  pkill nas-mond
  pkill nas-icond
  pkill watch  
  pkill ip_setupd

  # STEP 2:
  cd /tmp
  AD_PASSWD=`sqlite3 /etc/nas/db/share.db "select passwd from user where uid='admin'"`
  SCH_GREP=`cat /etc/nas/system.conf |grep schedule_power |grep on`
  SCH_OFF=`echo $?` 
  CUR_TIME=`date '+%m%d%H%M'`
  # schedule enable --> SCH_OFF=0, disable --> SCH_OFF=1
  if [ $SCH_OFF = 0 ]; then
    # enable
    SCH_ON_TIME=`cat /etc/nas/system.conf |grep schedule_power_Etime |cut -d " " -f3`
  else
    # disable
    SCH_ON_TIME=
  fi
  sync
  umount /sys
  umount /lib/init/rw
  umount /var/lock
  umount /dev/pts
  umount /proc
  umount /dev/md2
  umount /dev/md3
  umount /mnt/device/CD-ROM
  umount /mnt/device/USB/*
  umount /mnt/device/eSATA
  umount /mnt/device/SDMMC/*

  led_set 13 on
  led_set $LED_HDD1_BLUE on
  led_set $LED_HDD2_BLUE on
  led_set $LED_ODD_BLUE on
  system_set fan 0
  for i in 1 2 3 4 5 6 7
  do
    lcd_icon $i off
  done
  lcd_msg $MSG_HIBERNATION_END
  echo -e "\n"
  broadcast hibernation_end

  sync
  chroot /mnt/flash/rootfs /hib_init.sh $AD_PASSWD $CUR_TIME $SCH_ON_TIME
  chroot /mnt/flash/rootfs
}

#
# $1 : property
#
system_get() {
  local FUNC="SYSTEM_GET"

  case "$1" in
    "timezone")		
      TIMEZONE=$(ls -l /etc/localtime | sed "s/.*zoneinfo\///")
      echo $TIMEZONE
      ;;
    "ntp"|"ntpserver"|"default_ntpserver"|"ntp_update"| \
    "hibernation"|"hibernation_time"| \
    "ups"|"ups_shutdown_time"|"ups_poweroff"| \
    "schedule_power"|"schedule_power_Stime"|"schedule_power_Etime"| \
    "codepage"| \
    "email_alert"|"email_to"|"email_subject"|"email_term"|"email_hdd_report"| \
    "smtp_server"|"smtp_auth"|"smtp_user"|"smtp_pass"|"smtp_ssl")
      get_conf_equal "$1" $SYSTEM_CONF_FILE
      ;; 
    "temperature")
      TEMP=$(iomain -r temp | cut -d ":" -f 2)
      echo $TEMP
      ;;
    "fan")
      nas-common button lock
      RPM=$(iomain -r fan | cut -d":" -f2)
      nas-common button unlock
      RPM_MODE=$(cat /var/run/fan_status)
      echo "$RPM_MODE $RPM"
      ;;
    "odd_type")
      TYPE=$(cat /sys/block/sr0/device/model | grep "BD")
      if [ -z "$TYPE" ]; then
        echo "DVD"
      else
        echo "BD"
      fi
      ;;
    *)			
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}

#
# $1 : property
#
system_set() {
  local FUNC="SYSTEM_SET"

  log_func $FUNC $@

  case "$1" in
    "email_alert"|"email_to"|"email_subject"|"email_term"|"email_hdd_report"| \
    "smtp_server"|"smtp_auth"|"smtp_user"|"smtp_pass"|"smtp_ssl")
      PROPERTY=$1
      shift
      VALUE=$@
      replace_conf_equal "$PROPERTY" "$VALUE" $SYSTEM_CONF_FILE
      ;;  
    "fan")  iomain -w $2;;
    *)			
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}


#
# $1: on, off
#
button_enable() {
  if [ "$1" = "on" ]; then
    cat /dev/null
  else
    cat /dev/null
  fi
}

#
# check status periodially for icon 
#
check_icon() {

  # USER icon
  COUNT=$(smbstatus -p | wc -l)
  if [ "$(($COUNT - 4))" != "0" ]; then
    lcd_icon $ICON_USER on
  else
    lcd_icon $ICON_USER off
  fi

  # HDD icon

}


