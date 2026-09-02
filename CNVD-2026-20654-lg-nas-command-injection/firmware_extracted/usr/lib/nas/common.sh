#!/bin/bash

#===============================================================================
# common definitions 
#===============================================================================

NAS_MODEL=NC1

PATH=/sbin:/usr/sbin:/bin:/usr/bin
LIBDIR=/usr/lib/nas/
CONFDIR=/etc/nas/
LOGFILE=/var/log/nas.log
LCDFILE=/var/log/nas.lcd
BOOTING_FILE=/var/run/nas/booting

USB_LIST_FILE=/var/run/nas-usb.list
USB_DEV_LIST_FILE=/var/run/nas-usb.device
USB_PROCESS_LOCK=/var/lock/nas-usb.lock
HDD_EVENT_FILE=/var/run/hdd_event.log

SYSTEMDIR=service
VOL_MOUNT_DIR=/mnt/disk
DEFAULT_VOL=default
DEFAULT_VOL_DIR=$VOL_MOUNT_DIR/$DEFAULT_VOL
SERVICE_DIR=$DEFAULT_VOL_DIR/$SYSTEMDIR

USB_MOUNT_DIR=/mnt/device/USB
ESATA_MOUNT_DIR=/mnt/device/eSATA
CDROM_MOUNT_DIR=/mnt/device/CD-ROM
SDMMC_MOUNT_DIR=/mnt/device/SDMMC
HDD_MOUNT_DIR=/mnt/device/HDD

USB_SHARE_PREFIX=usb
ESATA_SHARE_PREFIX=esata
CDROM_SHARE_PREFIX=cdrom
SDMMC_SHARE_PREFIX=memcard

#-------------------------------------------------------------------------------
# return values 
#-------------------------------------------------------------------------------
TRUE=0
FALSE=1
FAIL=1

OK=0
ERROR=1
ERROR_EXEC=2
ERROR_FILE_NOT_EXIST=80
ERROR_INVALID_FILE=81
ERROR_SCRIPT_FAIL=82
ERROR_INVALID_PARAM=83
ERROR_EXTRACT_FAIL=84
ERROR_CREATE_FAIL=85
ERROR_COPY_FAIL=86
ERROR_DISC_NOT_EXIST=87
ERROR_UNSUPPORTED_DISC=88

ERROR_VOL_NOT_EXIST=90
ERROR_USB_NOT_EXIST=91
ERROR_MOUNT_FAIL=92
ERROR_RSYNC_FAIL=93
ERROR_ISCSI_ENABLED=94
ERROR_RESYNC_STATE=95

#-------------------------------------------------------------------------------
# common functions 
#-------------------------------------------------------------------------------

#
# $@: error messages
#
usage() {
  echo "Usage: $(basename $0) $@"
  exit 1
}

#
# $1: string
#
trim() {
  var=$1
  var="${var#"$var%%[![:apace:]]*}"}"
  var="${var%"$var##*[![:apace:]]}"}"
  echo $var
}

#
# $1: string
#
toupper() {
  echo $@ | tr '[:lower:]' '[:upper:]'
}

#
# $1: string
#
tolower() {
  echo $@ | tr '[:upper:]' '[:lower:]'
}

#
# $1: lock file
# $2: timeout (second)
#
acquire_lock() {
  ELAPSED=0
  if [ -z "$2" ]; then
    LIMIT=10
  else 
    LIMIT=$2
  fi

  usleep 10
  while [[ -e "$1"  && $ELAPSED -lt $LIMIT ]]; do
    sleep 1
    ELAPSED=$(($ELAPSED + 1))
  done
  if [ "$ELAPSED" = "$LIMIT" ]; then
    return 1
  fi

  touch $1
}

#
# $1: lock file
#
release_lock() {
  rm -f $1 >/dev/null 2>&1
}

#
# $1: field
# $2: new value
# #3: file
#
replace_conf() {
  case "$1" in
    "blank")	replace_conf_blank $2 $3 $4 ;;
    "equal")	replace_conf_equal $2 $3 $4 ;;
    *)		exit 1 ;;
  esac
}

replace_conf_blank() {
  [ $# -lt 3 ] && return 1
  VALUE=$(echo $2 | sed 's/\//\\\//g')
  sed -i "/^[[:space:]]*$1/ s/.*[[:space:]]*.*/$1\t\t$VALUE/" $3

}

replace_conf_space() {
  [ $# -lt 3 ] && return 1
  VALUE=$(echo $2 | sed 's/\//\\\//g')
  sed -i "/^[[:space:]]*$1/ s/.*[[:space:]]*.*/$1 $VALUE/" $3

}

replace_conf_equal() {
  [ $# -lt 3 ] && return 1
  VALUE=$(echo $2 | sed 's/\//\\\//g')
  sed -i "/^[[:space:]]*$1[[:space:]]*=/ s/.*=.*/$1 = $VALUE/" $3
}

replace_conf_equal_only() {
  [ $# -lt 3 ] && return 1
  VALUE=$(echo $2 | sed 's/\//\\\//g')
  sed -i "/^[[:space:]]*$1[[:space:]]*=/ s/.*=.*/$1=$VALUE/" $3
}

#
# $1: field
# $2: file
#
get_conf() {
  echo
}

get_conf_blank() {
  [ $# -lt 2 ] && return 1
  var=$(grep -s -E "^[[:space:]]*$1[[:space:]]+" $2 | awk '{ print $2 }')
  echo $var
}

get_conf_equal() {
  [ $# -lt 2 ] && return 1
  var=$(grep -s -E "^[[:space:]]*$1[[:space:]]*=" $2 | cut -d "=" -f 2)
  trim "$var"
}

#
# Make unique directory
#
# $1: folder_path/name
#
mkudir() {
  BASE="$1"
  TDIR="$BASE"
  NUM=1

  [ -z "$1" ] && return 1

  while [ -e "$TDIR" ]; do
    NUM=$(($NUM + 1))
    TDIR="$BASE-"$NUM
  done
  mkdir -p "$TDIR" >/dev/null 2>&1
  RESULT=$?
  chmod 777 "$TDIR" >/dev/null 2>&1
  echo "$TDIR"

  return $RESULT
}

#
# Make unique directory
#
# $1: folder_path/name
#
mkudir_order() {

  [ -z "$1" ] && return 1

  LAST=$(ls -d $1-* 2>/dev/null | grep -o "[0-9]*$" | sort -n | tail -n 1)
  if [ -z "$LAST" ]; then
    if [ -e "$1" ]; then
      TDIR=$1-2
    else
      TDIR=$1
    fi
  else
    LAST=$((LAST + 1))
    TDIR=$1-$LAST
  fi

  mkdir -p $TDIR >/dev/null 2>&1
  RESULT=$?
  chmod 777 $TDIR >/dev/null 2>&1
  echo $TDIR

  return $RESULT
}

get_arch() {
  MNAME=$(uname -m)

  case "$MNAME" in
    armv5*)	ARCH=armel ;;
    i*86)	ARCH=i386 ;;
    *)		ARCH=unknown ;;
  esac
  echo $ARCH
}

get_model() {
  echo $NAS_MODEL
}

#-------------------------------------------------------------------------------
# log functions 
#-------------------------------------------------------------------------------

log() {
  if [ "$DEBUG" != "off" ]; then
    #echo "[$(date +"%x %R:%S")] $@" | tee -a $LOGFILE
    logger -p local1.info $@
  fi
}

log_tee() {
  while read data; do
    log $data
    echo $data
  done
}

#
# $1: function name
# $2~: log messages
#
log_func() {
  local FUNC=$1

  shift
  if [ "$DEBUG" != "off" ]; then
    #echo "[$(date +"%x %R:%S")] [$FUNC] $@" | tee -a $LOGFILE
    logger -p local1.info "[$FUNC] $@"
  fi
}

#
# $1: function name
#
log_func_pipe() {
  while read data; do
    log_func $1 $data
  done
}

#
# $1: function name
# $2: error code
# $3: log messages
#
log_func_result() {
  local FUNC RESULT STR
  
  FUNC=$1
  RESULT=$2 
  case "$RESULT" in 
    $OK)			STR="OK" ;;	
    $ERROR)			STR="Error" ;;
    $ERROR_EXEC)		STR="Execution fail" ;;
    $ERROR_FILE_NOT_EXIST)	STR="File not exist" ;;
    $ERROR_INVALID_FILE)	STR="Invalid file" ;;
    $ERROR_SCRIPT_FAIL)		STR="Script exec fail" ;;
    $ERROR_INVALID_PARAM)	STR="Invalid parameter" ;;
    $ERROR_MOUNT_FAIL)		STR="Mount fail" ;;
    $ERROR_VOL_NOT_EXIST)	STR="Volume not exist" ;;
    $ERROR_USB_NOT_EXIST)	STR="USB not exist" ;;
    $ERROR_EXTRACT_FAIL)	STR="Extraction fail" ;;
    $ERROR_RSYNC_FAIL)		STR="Rsync fail" ;;
    $ERROR_CREATE_FAIL)		STR="Create fail" ;;
    $ERROR_COPY_FAIL)		STR="Copy fail" ;;
    $ERROR_DISC_NOT_EXIST)	STR="Disc Not Exist" ;;
    $ERROR_UNSUPPORTED_DISC)	STR="Unsupported Disc" ;;
    $ERROR_ISCSI_ENABLED)	STR="iSCSI enabled" ;;
    *)				STR="Unknown error" ;;
  esac
  shift 2

  if [ "$DEBUG" != "off" ]; then
    #echo "[$(date +"%x %R:%S")] [$FUNC] RESULT=$RESULT: $STR $@" | tee -a $LOGFILE
    logger -p local1.info "[$FUNC] RESULT=$RESULT: $STR $@"
    broadcast error "[$FUNC] RESULT=$RESULT: $STR $@"
  fi
}

log_func_check_result() {
  if [ "$2" != "0" ]; then 
    log_func_result $1 $3 $4   
    exit $3 
  fi
}

#-------------------------------------------------------------------------------
# Cron functions 
#-------------------------------------------------------------------------------

#
# $1: filename
# $2: update cycle { off | 1d | 1w | 1m }
#
register_cron() {
  FILENAME=$1
  BASENAME=$(echo $1 | cut -d '/' -f5)
  CYCLE=$2
 
  if [ "$CYCLE" = "off" ]; then
    rm -f /etc/cron.weekly/$BASENAME
    rm -f /etc/cron.daily/$BASENAME
    rm -f /etc/cron.monthly/$BASENAME
  elif [ "$CYCLE" = "1d" ]; then
    cp $FILENAME /etc/cron.daily/
    rm -f /etc/cron.weekly/$FILENAME
    rm -f /etc/cron.monthly/$FILENAME
  elif [ "$CYCLE" = "1w" ]; then
    cp $FILENAME /etc/cron.weekly/
    rm -f /etc/cron.daily/$FILENAME
    rm -f /etc/cron.monthly/$FILENAME
  elif [ "$CYCLE" = "1m" ]; then
    cp $FILENAME /etc/cron.monthly/
    rm -f /etc/cron.daily/$FILENAME
    rm -f /etc/cron.weekly/$FILENAME
  fi
}

#-------------------------------------------------------------------------------
# LCD functions 
#-------------------------------------------------------------------------------

MSG_ERROR="OK"
MSG_ERROR="Error"
MSG_REBOOT="Reboot..."
MSG_SHUTDOWN="Shutdown..."

MSG_FIRMWARE_UPDATE_START="F/W Update..."
MSG_FIRMWARE_UPDATE_PROGRESS="F/W Update"
MSG_FIRMWARE_UPDATE_END="F/W Update Done"
MSG_FIRMWARE_UPDATE_FAIL="F/W Update Fail"

MSG_ODD_UPDATE_START="ODD Update..."
MSG_ODD_UPDATE_PROGRESS="ODD Update"
MSG_ODD_UPDATE_END="ODD Update Done"
MSG_ODD_UPDATE_FAIL="ODD Update Fail"

MSG_FACTORY_INIT_START="Initialize..."
MSG_FACTORY_INIT_END="Initialize Done"

MSG_VOL_NOT_EXIST="Volume Not Exist"
MSG_USB_NOT_EXIST="USB Not Exist"
MSG_DISC_NOT_EXIST="Disc Not Exist"
MSG_UNSUPPORTED_DISC="Unsupported Disc"

MSG_USB_BACKUP_START="Backup..."
MSG_USB_BACKUP_PROGRESS="Backup"
MSG_USB_BACKUP_END="Backup Done"
MSG_USB_BACKUP_FAIL="Backup Fail"

MSG_USB_COPY_START="USB Copy..."
MSG_USB_COPY_PROGRESS="USB Copy"
MSG_USB_COPY_END="USB Copy Done"
MSG_USB_COPY_FAIL="USB Copy Fail"

MSG_USB_BACKUP_ONETOUCH="Backup:S, Main:L"

MSG_ISCSI_ENABLED="iSCSI Enabled"

MSG_ODD_BACKUP_START="ODD Backup..."
MSG_ODD_BACKUP_PROGRESS="ODD Backup"
MSG_ODD_BACKUP_END="ODD Backup Done"
MSG_ODD_BACKUP_FAIL="ODD Backup Fail"

MSG_ODD_BURN_START="ODD Burn..."
MSG_ODD_BURN_PROGRESS="ODD Burn"
MSG_ODD_BURN_END="ODD Burn Done"
MSG_ODD_BURN_FAIL="ODD Burn Fail"

MSG_HDD_INIT_START="HDD Init..."
MSG_HDD_INIT_END="HDD Init Done"
MSG_HDD_INIT_FAIL="HDD Init Fail"
MSG_HDD_FORCE_OUT="HDD is Removed"
MSG_HDD_INSERTED="HDD is Inserted"

MSG_UPS_ONBATTERY="UPS Power Mode"
MSG_UPS_OFFBATTERY="UPS Power return"
MSG_UPS_MAINBACK="UPS main back"
MSG_UPS_DOREBOOT="UPS do reboot"
MSG_UPS_DOSHUTDOWN="UPS do shutdown"
MSG_UPS_EMERGENCY="UPS emergency"

MSG_HIBERNATION_MOUNT_FAIL="Flash Mount Fail"
MSG_HIBERNATION_ISCSI_FAIL="Fail:iSCSI On"
MSG_HIBERNATION_BURN_FAIL="Fail:ODD Burning"
MSG_HIBERNATION_IO_FAIL="Fail:Disk I/O"
MSG_HIBERNATION_START="Now Working..."
MSG_HIBERNATION_END="Hibernation Done"

MSG_INIT_PASSWD="Init Admin PW"

ICON_LEFT=1
ICON_NET=2
ICON_USER=3
ICON_USB=4
ICON_DISC=5
ICON_HDD=6
ICON_RIGHT=7

LCD_MENU_MAIN=2
LCD_MENU_ONETOUCH_BACKUP=31
LCD_MENU_ONETOUCH_DONE=32
LCD_MENU_PREMAIN=36

LCD_MSG_TIMEOUT=10

#
# $1: message
#
lcd_msg() {
  MSG="$@"
  iomain -n -m "$MSG"
  #echo "[LCD] $MSG"
}

#
# $1: timeout (second)
# $2: message
#
lcd_msg_time() {
  TIMEOUT=$(printf "%d" "$1" 2>/dev/null)
  if [ $TIMEOUT -eq 0 ]; then
    TIMEOUT=$LCD_MSG_TIMEOUT
    MSG="$@"
  else  
    shift
    MSG="$@"
  fi

  iomain -e $TIMEOUT -n -m "$MSG"
}

#
# $1: error code
#
lcd_error() {
  case "$1" in
    0)					MSG=$MSG_OK ;;
    $ERROR_VOL_NOT_EXIST)		MSG=$MSG_VOL_NOT_EXIST ;;
    $ERROR_ISCSI_ENABLED)		MSG=$MSG_ISCSI_ENABLED ;;
    $ERROR_DISC_NOT_EXIST)		MSG=$MSG_DISC_NOT_EXIST ;;
    $ERROR_UNSUPPORTED_DISC)		MSG=$MSG_UNSUPPORTED_DISC ;;
    *)					MSG=$MSG_ERROR ;;
  esac
  lcd_msg_time $MSG 
}

#
# $1: message
#
lcd_msg_center() {
  MSG="$@"

  iomain -e $LCD_MSG_TIMEOUT -c -m "$MSG"
}

#
# $1: icon number
# $2: mode {on|off|blink}
#
lcd_icon() {
#  local FUNC="ICON"
#  log_func $@
  iomain -i $1 $2
#  echo "[ICN] $1 $2" 
}

# 
# $1: menu number
#
lcd_menu() {
  iomain -b $1
}

#-------------------------------------------------------------------------------
# backup functions 
#-------------------------------------------------------------------------------

cp_progress() {
  cp_progress_cms $@
}

# $1: source
# $2: target
# $3~: lcd message
cp_progress_cms() {
  PFILE=/var/run/cp_progress.$PPID
  SRC=$1
  TARGET=$2
  shift 2
  MESSAGE=$@

  TOTAL=$(find $SRC/ | wc -l)

  cmscopy -s $SRC -d $TARGET -p $PFILE >/dev/null &
  BACKGROUND_PID=$!

  while ps -p $BACKGROUND_PID >/dev/null; do
    PERCENT=$(cat $PFILE 2>/dev/null)
    [ -z "$PERCENT" ] && PERCENT=0
    lcd_msg $MESSAGE $PERCENT%
    sleep 1
  done
  wait $BACKGROUND_PID
  RESULT=$?
  sync

  rm $PFILE
  return $RESULT
}

# $1: source
# $2: target
# $3: message(cancel, fail..)
cp_progress_cms_web() {
  PFILE=/tmp/esata/esata_prog
  SRC=$1
  TARGET=$2
  MESSAGE=$3
  shift 3 

  TOTAL=$(find $SRC/ | wc -l)
  cmscopy -l $SRC -d $TARGET -p $PFILE -m $MESSAGE 1>/dev/null &

  BACKGROUND_PID=$!
  while ps -p $BACKGROUND_PID >/dev/null; do
    PERCENT=$(cat $PFILE)
    [ -z "$PERCENT" ] && PERCENT=0
    lcd_msg $@ $PERCENT%
    sleep 1
  done
  wait $BACKGROUND_PID
  RESULT=$?
  sync

  rm $PFILE
  return $RESULT
}




#
# $1: source
# $2: target
# $3~: lcd message
#
cp_progress_filenum() {
  PFILE=/var/run/cp_progress.$PPID
  SRC=$1
  TARGET=$2
  shift 2

  TOTAL=$(find $SRC/ | wc -l)

  cp -av $SRC/* $TARGET | tee $PFILE >/dev/null &
  BACKGROUND_PID=$!
  CP_PID=$(pgrep -n -x cp)

  while ps -p $BACKGROUND_PID >/dev/null; do
    CURRENT=$(wc -l $PFILE | awk '{print $1}')
    let 'PERCENT = (CURRENT*100/TOTAL)' 
    # echo $CURRENT $TOTAL $PERCENT%
    lcd_msg $@ $PERCENT%
    sleep 1
  done
  wait $CP_PID
  RESULT=$?
  sync

  rm $PFILE
  return $RESULT
}

#
# $1: source
# $2: target
# $3~: lcd message
#
cp_progress_strace() {
  PFILE=/var/run/cp_progress2.$PPID
  SRC=$1
  TARGET=$2
  shift 2

  TOTAL=$(du -d 0 -k $SRC/)
  TOTAL=${TOTAL%%"/"*}
  strace -q -ewrite -s 1 cp -a $SRC/* $TARGET 2>&1 | \
  while read data; do
    SIZE=${data##*" "}
    let "SUM+=SIZE"
    let "CPERCENT=((SUM/1024)*100)/TOTAL"
    if [ "$CPERCENT" != "$PERCENT" ]; then
      #echo $CPERCENT
      PERCENT=$CPERCENT
    fi
  done
  RESULT=$?
  sync

  rm $PFILE
  return $RESULT
}

#
# $1: lcd message
# $2~: rsync parameters
#
rsync_progress() {
  PFILE=/var/run/rsync_progress.$$
  LCD_MSG=$1 
  shift

  rsync $@ | tee $PFILE >/dev/null &
  BACKGROUND_PID=$!
  RSYNC_PID=$(pgrep -g $$ -o -x rsync)

  while ps -p $BACKGROUND_PID >/dev/null; do
    PROGRESS=$(grep to-check $PFILE | tail -n 1 | cut -d "=" -f 2)
    [ -z "$PROGRESS" ] && continue
    CURRENT=${PROGRESS%%"/"*}
    TOTAL=${PROGRESS##*"/"}
    TOTAL=${TOTAL%%")"*}
    let 'PERCENT = ((TOTAL-CURRENT)*100/TOTAL)' 
    # echo $CURRENT $TOTAL $PERCENT%
    lcd_msg $LCD_MSG $PERCENT%
    sleep 1
  done

  wait $RSYNC_PID
  RESULT=$?
  sync

  rm $PFILE
  return $RESULT
}

#
# $1: filesystem
# $2: device name
#
mkfs_progress() {
  local FUNC="MKFS_PROGRESS"
  log_func $FUNC $@

  PFILE=/var/run/mkfs_progress.$$
  FS=$1
  DEVNAME=$2
  shift 2
 
  if [ $FS = "ext3" ]; then
    mkfs.$FS -m 0 $DEVNAME | tee $PFILE > /dev/null &
  else
    mkfs.$FS $DEVNAME | tee $PFILE >/dev/null &
  fi
  BACKGROUND_PID=$!
  MKFS_PID=$(pgrep -g $$ -o -x mkfs.$FS)

  while ps -p $BACKGROUND_PID >/dev/null; do
    PROGRESS=$(tail -c 30 $PFILE | tr "" " " | grep -o -E "[0-9]+/[0-9]+")
    [ -z "$PROGRESS" ] && continue
    CURRENT=${PROGRESS%%"/"*}
    TOTAL=${PROGRESS##*"/"}
    let 'PERCENT = (CURRENT*100/TOTAL)' 
    # echo $PROGRESS $PERCENT
    lcd_msg $@ $PERCENT%
    sleep 1
  done

  wait $MKFS_PID
  RESULT=$?
  rm $PFILE
  return $RESULT
}

#-------------------------------------------------------------------------------
# buzzer functions 
#-------------------------------------------------------------------------------

buzzer_ok() {
  iomain -s 17 19 21 0 1 1
}

buzzer_error() {
  iomain -s 21 17 21 17 1 1
}

buzzer_boot() {
  iomain -s 17 19 21 33 1 1
}

buzzer_in() {
  case $1 in
    "usb") iomain -s 17 18 19 19 1 1;;
    "odd") iomain -s 17 19 17 19 1 1;;
    "hdd") iomain -s 17 18 17 21 1 1;;
    "network") iomain -s 17 19 21 17 1 1;;
    *) iomain -s 17 19 21 0 1 1;;
  esac
}

buzzer_out() {
  case $1 in
    "usb") iomain -s 19 18 17 17 1 1;;
    "odd") iomain -s 19 17 19 17 1 1;;
    "hdd") iomain -s 21 17 18 17 1 1;;
    "network") iomain -s 21 19 17 17 1 1;;
    *) iomain -s 21 19 17 17 1 1;;
  esac
}

#
# $1: action
#
buzzer() {
  case "$(tolower $1)" in
    "ok")	buzzer_ok ;;
    "error")	buzzer_error ;;
    "boot")	buzzer_boot ;;
    "in")	buzzer_in $2;;
    "out")	buzzer_out $2;;
    *)		buzzer_ok ;;
  esac
}

#-------------------------------------------------------------------------------
# LED functions 
#-------------------------------------------------------------------------------
LED_HDD1_BLUE=33
LED_HDD2_BLUE=34
LED_ODD_BLUE=35
LED_HDD1_RED=20
LED_HDD2_RED=22

#
# $1 : MPP Pin Number
# $2 : "on" / "off"
#
led_set() {
  if [ "$2" = "on" ]; then
    hibernate -w $1 1
  else
    hibernate -w $1 0
  fi
}
#
# $1 : (remove,blank)
#
led_hdd(){
  local hddcnt=0
  for DISK in "1:0:0:0" "1:0:1:0"
  do
    HDD=$(find /sys/block/sd* -name device |xargs ls -l |grep "$DISK")
    if [ ! -z "$HDD" ]; then
      led_set $((LED_HDD1_BLUE+hddcnt)) off
      led_set $((LED_HDD1_RED+hddcnt*2)) off
    else
      led_set $((LED_HDD1_BLUE+hddcnt)) on
      led_set $((LED_HDD1_RED+hddcnt*2)) on
      # buzzer, lcd
      if [ "$1" = "remove" ]; then
        buzzer_out hdd
        lcd_msg_time $MSG_HDD_FORCE_OUT      
        echo $MSG_HDD_FORCE_OUT > $HDD_EVENT_FILE
      fi
    fi
    hddcnt=$((hddcnt+1))
  done
}

#-------------------------------------------------------------------------------
# Button functions 
#-------------------------------------------------------------------------------

#
# $1 : lock or unlock
#

button_lock() {
  PARAM=$1
  iomain -l "$PARAM"
}

#-------------------------------------------------------------------------------
# broadcast message functions 
#-------------------------------------------------------------------------------
broadcast_message() {
	message_senderd $1 "$2" 2>&1 > /dev/null
}

broadcast_ip() {
	broadcast_message 110
}

broadcast_poweron() {
	broadcast_message 111
}

broadcast_shutdown() {
	broadcast_message 112
}

broadcast_reboot() {
	broadcast_message 113
}

broadcast_usb_backup_end() {
	broadcast_message 114
}

broadcast_usb_backup_fail() {
	broadcast_message 115
}

broadcast_firmware_update_end() {
	broadcast_message 116
}

broadcast_hibernation_end() {
	broadcast_message 117
}

broadcast_error() {
	broadcast_message 1 "$1"
}

broadcast() {
	case "$(tolower $1)" in
		"ip") broadcast_ip ;;
		"poweron") broadcast_poweron ;;
		"shutdown") broadcast_shutdown ;;
		"reboot") broadcast_reboot ;;
		"usb_backup_end") broadcast_usb_backup_end ;;
		"usb_backup_fail") broadcast_usb_backup_fail ;;
		"firmware_update_end") broadcast_firmware_update_end ;;
		"hibernation_end") broadcast_hibernation_end ;;
		"error") broadcast_error "$2" ;;
	esac
}
