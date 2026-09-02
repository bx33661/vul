#!/bin/bash

#===============================================================================
# firmware.sh 
#===============================================================================

LIBDIR=$PREFIX/usr/lib/nas
CONFDIR=/etc/nas

. $LIBDIR/common.sh

FIRMWARE_STATE_FILE=$CONFDIR/firmware.state
FIRMWARE_DATE_FILE=$CONFDIR/firmware.date
FIRMWARE_STATE_DOWNLOAD="download_finish"
FIRMWARE_STATE_PREINST="preinst_finish"
FIRMWARE_STATE_EXTRACT="extract_finish"
FIRMWARE_STATE_POSTINST="postinst_finish"
FIRMWARE_STATE_GENCONF="genconf_finish"
FIRMWARE_STATE_FINISH="ok"

FIRMWARE_ODD_DATE_FILE=$CONFDIR/firmware-odd.date

FIRMWARE_STEP_INIT=0
FIRMWARE_STEP_PREINST=1
FIRMWARE_STEP_EXTRACT=2
FIRMWARE_STEP_POSTINST=3
FIRMWARE_STEP_GENCONF=4
FIRMWARE_STEP_FINISH=5

#-------------------------------------------------------------------------------
# firmware update
#-------------------------------------------------------------------------------

stop_sevice_for_update() {
  nas-service control all stop
  /etc/init.d/udev stop
  /etc/init.d/crond stop
  pkill nas-cdromd
  pkill nas-usbd
  pkill nas-mond
  pkill nas-icond
  pkill ip_setupd
  pkill watch
}

#
# $1: result
# $2: error code
#
firmware_result() {
  local FUNC=FIRMWARE_UPDATE

  if [ "$1" != 0 ]; then
    sync
    lcd_msg_time $MSG_FIRMWARE_UPDATE_FAIL
    log_func_result $FUNC $2 $3
    exit $2
  fi
}

#
# $1: firmware file
# $2: start step { 0~5 : 0 = init}
# $3: save name
#
firmware_update() {
  local FUNC=FIRMWARE_UPDATE
  local NEWDIR=/boot/firmware-new/
  local FIXDIR=/boot/firmware/
  local UPDATEDIR=/boot/update/
  
  if [ -z "$2" ]; then 
    START_STEP=0
  else 
    START_STEP=$2
  fi
  if [ -z "$3" ]; then
    FIRMWARE=$NEWDIR/$(basename $1)
  else
    FIRMWARE=$NEWDIR/$3
  fi
  
  log_func $FUNC $@
  log_func $FUNC "current version = $(cat /etc/nas/firmware.version)"
  lcd_msg $MSG_FIRMWARE_UPDATE_START

  [ -e "$1" ]; firmware_result $? $ERROR_FILE_NOT_EXIST $1

  STEP=0
  TOTALSTEP=5
  START_TIME=$(date +%s)

  #-----------------------------------------------------------------------
  # STEP 0: Init & check type & validity
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_INIT" ]; then
    # Prepare 
    mkdir -p $CONFDIR
    mkdir -p $NEWDIR
    rm -rf $NEWDIR/*
    mv -f $1 $FIRMWARE
    rm -rf $UPDATEDIR/*

    firmware_result $? $ERROR_EXEC
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))

    #-----------------------------------------------------------------------
    # STEP 0.1: pre-check
    #-----------------------------------------------------------------------
    # Check file name 
    [ ${FIRMWARE:(-3)} = "bin" ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE  
    # Check size ( < 200M
    FIRMWARE_SIZE=$(du $FIRMWARE | awk '{ print $1 }')
    [ "$FIRMWARE_SIZE" -lt "200000" ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE

    # Extracting firmware
    tar xf $FIRMWARE -C $NEWDIR; firmware_result $? $ERROR_EXTRACT_FAIL $FIRMWARE
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))

    # Check file TYPE & validity
    FIRMWARE_TYPE=$(cat $NEWDIR/TYPE)
    [ -e $NEWDIR/TYPE ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE  
    case $FIRMWARE_TYPE in
      "FIRMWARE")
    	[ -e $NEWDIR/MD5SUM ] \
  	&& [ -e $NEWDIR/firmware.tar.gz ] \
	&& [ -e $NEWDIR/preinst.sh ] \
	&& [ -e $NEWDIR/postinst.sh ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE  
        ARCHIVE=firmware.tar.gz
        ;;
      "KERNEL")
    	[ -e $NEWDIR/MD5SUM ] \
  	&& [ -e $NEWDIR/uImage ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE  
        ARCHIVE=uImage
        ;;
      "UBOOT")
    	[ -e $NEWDIR/MD5SUM ] \
  	&& [ -e $NEWDIR/uboot ]; firmware_result $? $ERROR_INVALID_FILE $FIRMWARE  
        ARCHIVE=uboot
        ;;
      *) 
        firmware_result $FAIL $ERROR_INVALID_FILE $FIRMWARE  
        ;;
    esac 

    # Check version
    CURRENT_VERSION=$(firmware_get version | cut -d "_" -f 2)
    NEW_VERSION=$(cat $NEWDIR/VERSION | cut -d " " -f 2)
    if [ "$NEW_VERSION" -lt "$CURRENT_VERSION" ]; then
      firmware_result $FAIL $ERROR_INVALID_FILE $FIRMWARE  
    fi

    # Check MD5SUM
    SUM=$(md5sum $NEWDIR/$ARCHIVE | awk '{ print $1 }')
    [ "$SUM" = "$(cat $NEWDIR/MD5SUM)" ]; firmware_result $? $ERROR_INVALID_FILE "firmware.tar.gz"
    log_func $FUNC "MD5SUM check OK"  

    #-----------------------------------------------------------------------
    # STEP 0.2: update KERNEL, UBOOT, IOMICOM
    #-----------------------------------------------------------------------
    if [ "$FIRMWARE_TYPE" != "FIRMWARE" ]; then
      lcd_msg "$FIRMWARE_TYPE Update..."
      case "$FIRMWARE_TYPE" in
        "KERNEL") 	kernel_update $NEWDIR/uImage ;;
        "UBOOT")	uboot_update $NEWDIR/uboot ;;
        "IOMICOM")	iomicom_update $NEWDIR/iomicom ;;
      esac
      RESULT=$?
      firmware_result $RESULT $ERROR_EXEC "$FIRMWARE_TYPE : $ARCHIVE"
      lcd_msg $MSG_FIRMWARE_UPDATE_END
      braodcast firware_update_end
      log_func_result $FUNC $RESULT "Elapsed TIme: $(($(date +%s) - $START_TIME))sec" 
      exit $RESULT 
    fi

    echo $FIRMWARE_STATE_DOWNLOAD > $FIRMWARE_STATE_FILE; sync
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))
  fi 	# STEP0

  #-----------------------------------------------------------------------
  # STEP 0.5: Stop all service
  #-----------------------------------------------------------------------
  stop_sevice_for_update

  #-----------------------------------------------------------------------
  # STEP 1: Run preinst.sh
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_PREINST" ]; then
    $NEWDIR/preinst.sh $NEWDIR; firmware_result $? $ERROR_SCRIPT_FAIL "preinst.sh"  
    log_func $FUNC "preinst.sh exec OK"  
    echo $FIRMWARE_STATE_PREINST > $FIRMWARE_STATE_FILE; sync
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))
  fi
  
  #-----------------------------------------------------------------------
  # STEP 2: Extracting firmware.tar.gz
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_EXTRACT" ]; then
    tar xf $NEWDIR/firmware.tar.gz -C /; firmware_result $? $ERROR_INVALID_FILE firmware.tar.gz
    log_func $FUNC "firmware.tar.gz extract OK"  
    echo $FIRMWARE_STATE_EXTRACT > $FIRMWARE_STATE_FILE; sync
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))
  fi

  #-----------------------------------------------------------------------
  # STEP 3: Run postinst.sh
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_POSTINST" ]; then
    $NEWDIR/postinst.sh $NEWDIR; firmware_result $? $ERROR_SCRIPT_FAIL "postinst.sh"  
    log_func $FUNC "postinst.sh exec OK"  
    echo $FIRMWARE_STATE_POSTINST > $FIRMWARE_STATE_FILE; sync
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))
  fi

  #-----------------------------------------------------------------------
  # STEP 4: Regenerate configuration
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_GENCONF" ]; then
    nas-share gen_conf; firmware_result $? $ERROR_SCRIPT_FAIL "nas-share gen_conf"
    echo $FIRMWARE_STATE_GENCONF > $FIRMWARE_STATE_FILE; sync
    lcd_msg $MSG_FIRMWARE_UPDATE_PROGRESS ${STEP}/${TOTALSTEP}; STEP=$(($STEP + 1))
  fi
  
  #-----------------------------------------------------------------------
  # STEP 5: Update Complete
  #-----------------------------------------------------------------------
  if [ "$START_STEP" -le "$FIRMWARE_STEP_FINISH" ]; then
    lcd_msg "Update Complete"
    date +"%F" > $FIRMWARE_DATE_FILE
    rm -rf $FIXDIR
    mv -f $NEWDIR $FIXDIR
    echo $FIRMWARE_STATE_FINISH > $FIRMWARE_STATE_FILE; sync
  fi

  #-----------------------------------------------------------------------
  # STEP 6: Update image files (uboot, kernel, flash_root, flash_data, iomicom)
  #-----------------------------------------------------------------------
  image_update

  lcd_msg_time $MSG_FIRMWARE_UPDATE_END
  broadcast firmware_update_end
  log_func $FUNC "current version = $(cat /etc/nas/firmware.version)"
  log_func_result $FUNC $OK "Elapsed TIme: $(($(date +%s) - $START_TIME))sec"  
  return 0 
}

#
#
#
image_update() {
  local FUNC="IMAGE_UPDATE"
  log_func $FUNC $@

  ITEMS=( uboot kernel iomicom flash_root flash_data )
  for ITEM in ${ITEMS[@]}; do
    if [ -e "/boot/update/$ITEM" ]; then
      case "$ITEM" in
        "uboot")	uboot_update /boot/update/uboot/* ;;
        "kernel") 	kernel_update /boot/update/kernel/* ;;
        "flash_root") 	flash_root_update /boot/update/flash_root/* ;;
        "flash_data") 	flash_data_update /boot/update/flash_data/* ;;
        "iomicom")	iomicom_update /boot/update/iomicom/* ;;
      esac
    fi
  done  
}

#
# $1: firmware update from URL
#
firmware_update_url() {
  local FUNC="FIRMWARE_UPDATE_URL"
  log_func $FUNC $@

  wget -P /tmp $1 
  if [ "$?" != 0 ]; then
    log_func_result $FUNC $FALSE $1 download fail
  fi
  
  firmware_update /tmp/$(basename $1)
}

#
# factory init
#
firmware_factory_init() {
  local FUNC="FIMRWARE_FACTORY_INIT"
  log_func $FUNC $@

  lcd_msg $MSG_FACTORY_INIT_START

  mkdir -p /mnt/flash/data
  mount -t squashfs /dev/mtdblock3 /mnt/flash/data
  log_func_check_result $FUNC $? $ERROR_MOUNT_FAIL mtdblock3 mount
  log_func $FUNC mtdblock3 mount 
  
  FILENAME=$(echo /mnt/flash/data/firmware*)
  FILENAME=${FILENAME%%" "*}

  stop_sevice_for_update

#  firmware_update $FILENAME
  /usr/lib/nas/preinst-init.sh
  tar xvf $FILENAME -C /
  /usr/lib/nas/postinst-init.sh

  lcd_msg_time $MSG_FACTORY_INIT_END
  log_func_result $FUNC $? 
  exit 0
}

# $1: filename
firmware_factory_update() {
  local FUNC="FIMRWARE_FACTORY_UPDATE"
  log_func $FUNC $@

  mkdir -p /mnt/flash
  mount -t jffs2 /dev/mtdblock3 /mnt/svn
  log_func_check_result $FUNC $? $ERROR_MOUNT_FAIL mtdblock3 mount

  cp_progress $1 /mnt/flash/ "Copy Firmware"
  log_func_check_result $FUNC $? $ERROR_COPY_FAIL mtdblock3 copy

  sync
  sync
  log_func_result $FUNC $TRUE 
}


firmware_check() {
  local FUNC="FIRMWARE_CHECK"
  STATE=$(cat $FIRMWARE_STATE_FILE)

  if [ "$STATE" = "$FIRMWARE_STATE_FINISH" ]; then
    log_func $FUNC "Firmware check OK"
    return 0
  else 
    log_func $FUNC "Firmware check fail = $STATE"
    return 1
  fi
}

firmware_restore() {
  local FUNC="FIRMWARE_RESTORE"
  log_func $FUNC

  STATE=$(cat $FIRMWARE_STATE_FILE)

  NEWDIR=/boot/firmware-new
  FIRMWARE_FILE=$NEWDIR/firmware*.bin
  FIRMWARE_FILE=${FIRMWARE_FILE%%" "*}

  log_func $FUNC $STATE $FIRMWARE_FILE

  case "$STATE" in
    "$FIRMWARE_STATE_DOWNLOAD")
      cat /dev/null
      ;;
    "$FIRMWARE_STATE_PREINST")
      firmware_update $FIRMWARE_FILE $FIRMWARE_STEP_EXTRACT
      ;;
    "$FIRMWARE_STATE_EXTRACT")
      firmware_update $FIRMWARE_FILE $FIRMWARE_STEP_POSTINST
      ;;
    "$FIRMWARE_STATE_POSTINST")
      firmware_update $FIRMWARE_FILE $FIRMWARE_STEP_GENCONF
      ;;
    "$FIRMWARE_STATE_GENCONF")
      firmware_update $FIRMWARE_FILE $FIRMWARE_STEP_FINISH
      ;;
    "$FIRMWARE_STATE_FINISH")
      cat /dev/null
      ;;
    *)
      log_func $FUNC "Unkown firmware status = $STATE"
      ;;
  esac
}

#-------------------------------------------------------------------------------
# ODD firmware update
#-------------------------------------------------------------------------------
#
# $1: firmware file
#
odd_firmware_update() {
  local FUNC="ODD_UPDATE"

  log_func $FUNC $@

  # STEP 1: tray open, if disc's loaded.
  CDDEV=$(sg_map | grep /dev/scd0 | awk '{print $1}')
  sg_turs $CDDEV
  if [ $? = 0 ]; then
    mount | grep "/mnt/device/CD-ROM"
    if [ $? = 0 ]; then 
      nas-storage odd_umount /dev/sr0
    fi
    eject /dev/scd0
    sleep 1
  fi

  lcd_msg $MSG_ODD_UPDATE_START 

  # STEP 0: precheck
  START_TIME=$(date +%s)
  if [ ! -e "$1" ]; then
    lcd_msg_time $MSG_ODD_UPDATE_FAIL
    log_func_result $FUNC $FALSE $ERROR_FILE_NOT_EXIST
    return 1
  fi
  FIRMWARE_FILE=$1

  # check resync
  cat /proc/mdstat | grep resync
  if [ "$?" = "0" ]; then
    lcd_msg_time $MSG_ODD_UPDATE_FAIL
    log_func_result $FUNC $FALSE $ERROR_RESYNC_STATE
    return $ERROR_RESYNC_STATE 
  fi

  # simple validation
  cat $FIRMWARE_FILE | grep "HLDS" > /dev/null 2>&1
  if [ "$?" != "0" ]; then
    lcd_msg_time $MSG_ODD_UPDATE_FAIL
    log_func_result $FUNC $FALSE $ERROR_INVALID_FILE
    return 1
  fi

  # stop iscsi if iscsi's been started
  ISCSICHECK=$(nas-service get enabled iscsi)
  if [ "$ISCSICHECK" = "on" ]; then
    /etc/init.d/iscsi-target stop
  fi

  # STEP 2: update
  chmod u+x $FIRMWARE_FILE
  $1
  RESULT=$?
  rm -f $FIRMWARE_FILE

  if [ "$RESULT" = "0" ]; then
    # STEP 3: re-register scsi device
    sleep 5
    echo "scsi remove-single-device 1 0 2 0" > /proc/scsi/scsi
    sleep 1
    echo "scsi add-single-device 1 0 2 0" > /proc/scsi/scsi
    sleep 1
    DEVICE_NAME=$(sg_get_config /dev/sr0 | head -n 1)
    log_func $FUNC $DEVICE_NAME

    # STEP 4: finish
    date +"%F" > $FIRMWARE_ODD_DATE_FILE
#    eject -t /dev/scd0
    lcd_msg_time $MSG_ODD_UPDATE_END
  else 
    lcd_msg_time $MSG_ODD_UPDATE_FAIL
  fi    

  # start iscsi if iscsi was started
  if [ "$ISCSICHECK" = "on" ]; then
    /etc/init.d/iscsi-target start
  fi

  log_func_result $FUNC $RESULT "Elapsed TIme: $(($(date +%s) - $START_TIME))sec"  

  return $RESULT
}

#-------------------------------------------------------------------------------
# configuration backup
#-------------------------------------------------------------------------------

CONF_LIST=(
        /etc/hosts
        /etc/hostname
        /etc/nsswitch.conf
        /etc/resolv.conf
        /etc/localtime
        /etc/krb5.conf
	/etc/ddnscli.conf
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
        /etc/nas/firmware.conf
        /etc/nas/network.conf
        /etc/nas/service.conf
        /etc/nas/system.conf
        /etc/nas/usb-backup.conf
        /etc/nas/db/share.db
        /etc/samba/smbpasswd
	/etc/lighttpd/lighttpd.user.htdigest
	/etc/nas/upnp.conf
)

CONFIG_LIST_FILE=/boot/config/config.list

#
# $1: FILENAME
#
config_backup() {
  FUNC="BACKUP_CONF"
  log_func $FUNC $@

  CONFFILE=config_$(date +"%Y%m%d_%H%M%S").bin
  DATE=$(date +%F)
  VERSION=$(firmware_get version)
  TEMPFILE=/tmp/$(basename $CONFIG_LIST_FILE).$$

  [ ! -e "$(dirname $CONFIG_LIST_FILE)" ] && mkdir -p $(dirname $CONFIG_LIST_FILE)
  FILE_LIST=$(echo /boot/config/config_*)
  FILE_COUNT=$(echo $FILE_LIST | wc -w)
  OLD_FILE=${FILE_LIST%%" "*}

  [[ $FILE_COUNT -gt 4 && -e $OLD_FILE ]] && rm -f $OLD_FILE

  tar -zcvf /boot/config/$CONFFILE ${CONF_LIST[*]}

  log_func_result $FUNC $? Create /boot/config/$CONFFILE

  tail -4 $CONFIG_LIST_FILE > $TEMPFILE 2>/dev/null
  echo $CONFFILE $VERSION $DATE >> $TEMPFILE
  mv $TEMPFILE $CONFIG_LIST_FILE

  log_func_result $FUNC $OK
}

#
# $1: FILENAME
# $2: PC | NC1 (Restore Config From PC or NC1)
#
config_restore() {
  FUNC="RESTORE_CONF"
  log_func $FUNC $@

  CONF_FILE=/boot/config/$1

  if [ "$2" = "PC" ]; then
    CONF_FILE_FROM_PC=/tmp/$1
    mv $CONF_FILE_FROM_PC $CONF_FILE 
    
    VALID=$(tar tf $CONF_FILE | grep "nas/db/share.db")
    if [ -z "$VALID" ]; then
      log_func_check_result $FUNC $ERROR $ERROR_INVALID_FILE
    fi
  fi

  [ ! -e $CONF_FILE ] && log_func_check_result $FUNC $ERROR $ERROR_FILE_NOT_EXIST

  nas-service control all stop
  tar -xvf $CONF_FILE -C /
  nas-share gen_conf
  nas-service control all start

  log_func_result $FUNC $OK
}

#
# $1: name (fullpath)
#
uboot_update() {
  local FUNC="IOMICOM_UPDATE"
  log_func $FUNC $@

  #dd if=$1 of=/dev/mtdblock1
  RESULT=$?

  log_func_result $FUNC $RESULT
  return $RESULT
}

#
# $1: name (fullpath)
#
kernel_update() {
  local FUNC="KERNEL_UPDATE"
  log_func $FUNC $@

  flash_eraseall /dev/mtd1
  log_func_result $FUNC $? flash erase

  nandwrite -p /dev/mtd1 $1
  RESULT=$?
  log_func_result $FUNC $RESULT flash write

  return $RESULT
}

#
# $1: name (fullpath)
#
flash_root_update() {
  local FUNC="FLASH_ROOT_UPDATE"
  log_func $FUNC $@

  flash_eraseall /dev/mtd2
  log_func_result $FUNC $? flash erase

  nandwrite -p /dev/mtd2 $1
  RESULT=$?
  log_func_result $FUNC $RESULT flash write

  return $RESULT
}

#
# $1: name (fullpath)
#
flash_data_update() {
  local FUNC="FLASH_DATA_UPDATE"
  log_func $FUNC $@

  flash_eraseall /dev/mtd3
  log_func_result $FUNC $? flash erase

  nandwrite -p /dev/mtd3 $1
  RESULT=$?
  log_func_result $FUNC $RESULT flash write

  return $RESULT
}

#
# $1: name (fullpath)
#
iomicom_update() {
  local FUNC="IOMICOM_UPDATE"
  log_func $FUNC $@

  pkill nas-mond
  pkill nas-icond
  pkill watch
  
  sss_iodown $1
  RESULT=$?

  log_func_result $FUNC $RESULT
  return $RESULT
}

#
# $1 : property
#    : version 
#

firmware_get() {
  local FUNC="FIRMWARE_GET"

  case "$1" in 
    "state")		cat /etc/nas/firmware.state ;;
    "version")		cat /etc/nas/firmware.version ;;
    "date")		cat /etc/nas/firmware.date ;;
    "odd-version")	cat /sys/block/sr0/device/rev ;;
    "odd-date")		cat /etc/nas/firmware-odd.date ;;
    "micom-version")	iomain -r version | cut -d":" -f 2;;
    "kernel-version")	
      VERSION=$(sysctl kernel.revision | cut -d "=" -f 2)
      echo $VERSION
      ;;
    *)			
      log_func_check_result $FUNC $FALSE $ERROR_INVALID_PARAM $1
      ;;
  esac
}
