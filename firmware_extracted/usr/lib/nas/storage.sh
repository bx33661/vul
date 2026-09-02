#!/bin/bash

#===============================================================================
# storage.sh 
#===============================================================================

LIBDIR=$PREFIX/usr/lib/nas

. $LIBDIR/common.sh

FSTAB=/etc/fstab
FSTAB_DISK_START_COMMENT="### BEGIN DISK MOUNTING"
FSTAB_DISK_END_COMMENT="### END DISK MOUNTING"

MAX_USB_NUM=16
USB_WAIT_TIMEOUT=300

USB_BACKUP_CONF=/etc/nas/usb-backup.conf
USB_BACKUP_SELECTED_ITEM=/tmp/usb_backup_item
USB_BACKUP_START_COMMENT="### BEGIN USB BACKUP INFO"
USB_BACKUP_END_COMMENT="### END USB BACKUP INFO"

#-------------------------------------------------------------------------------
# Common functions 
#-------------------------------------------------------------------------------
default_volume() {
  DIR=$(mount | grep -m1 "/mnt/disk/" | awk '{print $3}')
  echo $DIR
}

#-------------------------------------------------------------------------------
# USB mount / unmount / backup
#-------------------------------------------------------------------------------

clean_mount_dir() {
  rm -rf $USB_MOUNT_DIR/*
  rm -rf $ESATA_MOUNT_DIR/*
  rm -rf $CDROM_MOUNT_DIR/*
}

#
# $1: device path
# $2: label | device | full | desc
#
get_device_name() {
  local FUNC="GET_DEVICE_NAME"

  DEVPATH=$1
  DEVNAME=$(basename $1)

  #DEVINFO=$(udevinfo --query=all -n $DEVNAME | grep "^E:" | cut -d " " -f 2)
  #export $DEVINFO

  VOL_ID=$(vol_id $DEVPATH)
  export $VOL_ID > /dev/null 2>&1

  VOL_LABEL=$(echo $ID_FS_LABEL | grep -x -E "[a-zA-Z0-9 _-]+")

  VENDOR=$(udevinfo -a -n $DEVNAME | grep "ATTRS{vendor}" | cut -d "=" -f 3)
  MODEL=$(udevinfo -a -n $DEVNAME | grep "ATTRS{model}" | cut -d "=" -f 3)
  SERIAL=$(udevinfo -a -n $DEVNAME | grep "ATTRS{serial}" | cut -d "=" -f 3 | md5sum)
  #SERIAL=$(echo $ID_SERIAL | md5sum)
  SERIAL=$(toupper ${SERIAL:0:8})
  PART_NUM=$(echo $DEVNAME | sed 's/^[^0-9]*//')
  TOTAL_PART=$(grep "${DEVNAME:0:3}[0-9]" /proc/partitions | wc -l)
  [ "$TOTAL_PART" = "1" ] && PART_NUM=

  if [ "$2" = "full" ]; then
    NAME="$VENDOR $MODEL $SERIAL $PART_NUM"
  elif [ "$2" = "device" ]; then
      NAME="$VENDOR $MODEL $PART_NUM"
  elif [ "$2" = "label" ]; then
    if [ -z "$VOL_LABEL" ]; then
      NAME="$VENDOR $MODEL $PART_NUM"
    else
      NAME=$VOL_LABEL
    fi
  elif [ "$2" = "desc" ]; then
    NAME="$VENDOR $MODEL $VOL_LABEL"
    NAME=$(echo $NAME | tr "\"" " " | tr -s " ")
    echo $NAME
    return 0
  else
    NAME=$VOL_LABEL
  fi

  NAME=$(echo $NAME | tr "\"" " " | tr -s " ")
  NAME=$(echo $NAME | tr " /" "_" | sed "s/-_/_/g")

  echo $NAME
}

#
# $1: device path
# $2: mount point
# 
get_device_share_name() {
  local FUNC="GET_SHARE_NAME"

  DEVPATH=$1
  DEVNAME=$(basename $1)
  # STEP 1: check device type
  if [ "$(basename $2)" = "$(basename $ESATA_MOUNT_DIR)" ]; then
    DEV_TYPE=ESATA
  elif [ "$(basename $2)" = "$(basename $SDMMC_MOUNT_DIR)" ]; then
    DEV_TYPE=SDMMC
  elif [ "$(basename $2)" = "$(basename $USB_MOUNT_DIR)" ]; then
    DEV_TYPE=USB
  elif [ "$(basename $2)" = "$(basename $HDD_MOUNT_DIR)" ]; then
    DEV_TYPE=HDD
  fi
  PART_NUM=$(echo $1 | grep -o "[0-9]*$")
  TOTAL_PART=$(grep "${DEVNAME:0:3}[0-9]" /proc/partitions | wc -l)

  #-----------------------------------------------------------
  # USB
  #-----------------------------------------------------------
  if [ "$DEV_TYPE" = "USB" ]; then
    # STEP 3: check usb already registered
    PARENT_DEV_NAME=$(echo $1 | sed 's/[0-9]*$//')
    SHARE_NAME=$(grep "$PARENT_DEV_NAME" $USB_LIST_FILE | cut -d " " -f 3 | head -n 1 | \
		sed 's/_[0-9]*$//')

    # STEP 4: assign new usb share folder num (if not registered)
    if [ -z "$SHARE_NAME" ]; then
      REGISTERED=$(cat ${USB_LIST_FILE} | cut -d " " -f 3 | sed 's/_[0-9]*$//' | sort -u) 
      for ((i = 1; i < $MAX_USB_NUM; i++)); do
        echo $REGISTERED | grep -w "${USB_SHARE_PREFIX}${i}" >/dev/null 2>&1
        if [ "$?" = "1" ]; then
          SHARE_NAME=${USB_SHARE_PREFIX}${i}
          break;
        fi
      done
    fi

    if [ -z "$SHARE_NAME" ]; then 
      log_func_result $FUNC $FALSE "USB share is created MAX"
      return 1
    fi
  #-----------------------------------------------------------
  # eSATA
  #-----------------------------------------------------------
  elif [ "$DEV_TYPE" = "ESATA" ]; then
    SHARE_NAME=$ESATA_SHARE_PREFIX
  #-----------------------------------------------------------
  # SD/MMC
  #-----------------------------------------------------------
  elif [ "$DEV_TYPE" = "SDMMC" ]; then
    SHARE_NAME=$SDMMC_SHARE_PREFIX
  #-----------------------------------------------------------
  # HDD
  #-----------------------------------------------------------
  elif [ "$DEV_TYPE" = "HDD" ]; then
    SHARE_NAME=$(basename $1)
  fi

  # STEP 5: add partition number
  if [[ ! -z "$PART_NUM" && "$TOTAL_PART" != "1" ]]; then
    SHARE_NAME=${SHARE_NAME}_$PART_NUM
  fi

  log_func $FUNC sharename=$SHARE_NAME
  echo $SHARE_NAME
}

#
# Add to USB device list (for monitoring)
#
# $1: device path
#
usb_device_add() {
  local FUNC="USB_DEVICE_ADD"
  log_func $FUNC $@
  
  SIZE=$(cat /sys/block/$(basename $1)/size)
  if [ -z "$SIZE" ]; then
    SIZE=0
  fi
  echo $1 $SIZE >> $USB_DEV_LIST_FILE
  lcd_icon $ICON_USB on

  if [ ! -e $BOOTING_FILE ]; then
    buzzer in usb
  fi
}

#
# Change USB device list 
#
# $1: device path
#
usb_device_change() {
  local FUNC="USB_DEVICE_CHANGE"
  log_func $FUNC $@

  SIZE=$(cat /sys/block/$(basename $1)/size)
  DEVNAME=$(basename $1)
  sed -i "s/$DEVNAME .*/$DEVNAME $SIZE/" $USB_DEV_LIST_FILE 
}

# 
# $1: device path
#
usb_device_remove() {
  local FUNC="USB_DEVICE_REMOVE"
  log_func $FUNC $@

  DEVNAME=$(basename $1)

  # remove from list
  sed -i "/\/$DEVNAME /d" $USB_DEV_LIST_FILE

  USB_NUM=$(wc -l $USB_DEV_LIST_FILE | awk '{print $1}')
  if [ "$USB_NUM" = "0" ]; then
    lcd_icon $ICON_USB off
  fi
  buzzer out usb
}

# check if Diag USB is inserted or not
check_diag() {
  local FUNC="CHECK_DIAG"
  MODEL="NC1"
  DIAG_FILE=$MOUNTDIR/$MODEL/"diag_asm.check"
  DIAG_USB="OFF"

  if [ -e $DIAG_FILE ]; then
    sss_mkscript -c $DIAG_FILE
    RESULT=$?
    if [ "$RESULT" = "0" ]; then
      log_func $FUNC Diag USB Detected
      DIAG_USB="ON"
      sss_mkscript -d $DIAG_FILE /tmp/diag_asm.sh
      chmod +x /tmp/diag_asm.sh

      PRE_DIAG=$(pidof diag_asm.sh)
      if [ -n "$PRE_DIAG" ]; then
        kill $PRE_DIAG
        log_func $FUNC kill process $PRE_DIAG
      fi
      /tmp/diag_asm.sh $MOUNTDIR/$MODEL
    fi  
  fi
}

# check if Install USB is inserted
check_install() {
  local FUNC="CHECK_INSTALL"
  INSTALL_FILE=$MOUNTDIR/"lgnas123.exe"
  INFO_FILE=$MOUNTDIR/"nas-info.txt"

  if [ ! -e "$INSTALL_FILE" ]; then
    INSTALL_USB="OFF"
    return
  fi

  INSTALL_USB="ON"
  while [ -e "/var/run/nas/booting" ]; do
    sleep 1
  done

  METHOD=$(nas-network get method)
  IP=$(nas-network get ipaddr)
  NETMASK=$(ifconfig eth0 | grep "Mask" | cut -d":" -f 4)
  GATEWAY=$(route | grep default | awk '{print $2}')
  HOSTNAME=$(hostname)
  DNS1=$(cat /etc/resolv.conf | grep nameserver | cut -d" " -f 2 | head -1)
  DNS2=$(cat /etc/resolv.conf | grep nameserver | cut -d" " -f 2 | tail -1)

  touch $INFO_FILE 
  cat /dev/null > $INFO_FILE 2>&1
  echo "TYPE	$METHOD" >> $INFO_FILE
  echo "IP	$IP" >> $INFO_FILE
  echo "Netmask	$NETMASK" >> $INFO_FILE
  echo "GATEWAY	$GATEWAY" >> $INFO_FILE
  echo "HOST	$HOSTNAME" >> $INFO_FILE
  echo "DNS1	$DNS1" >> $INFO_FILE
  echo "DNS2	$DNS2" >> $INFO_FILE

  sync
  log_func $FUNC "NAS Info is saved as nas-info.txt"
}


#
# $1: device path 
#
check_auto_backup() {
  local FUNC="CHECK_AUTO_BACKUP"
  log_func $FUNC $@
  DEVPATH=$1

  USBDEV_LIST=$(cat $USB_LIST_FILE | grep "^$DEVPATH" | cut -d " " -f 1)
  for USBDEV in $USBDEV_LIST; do
    NAME=$(get_device_name $USBDEV full)

    # check predefined USB
    REGISTERED=$(sed -n "/$USB_BACKUP_START_COMMENT $NAME/,/$USB_BACKUP_END_COMMENT $NAME/p" $USB_BACKUP_CONF)
    if [ -z "$REGISTERED" ]; then
      log_func $FUNC "Unregistered USB : $USBDEV $NAME"
    else 
      AUTO_BACKUP=$(sed -n "/$USB_BACKUP_START_COMMENT $NAME/,/$USB_BACKUP_END_COMMENT $NAME/ s/auto_sync:\(.*\)/\1/p" $USB_BACKUP_CONF)
      log_func $FUNC "Registered USB : $USBDEV $NAME [AutoSync=$AUTO_BACKUP]"
      if [ "$AUTO_BACKUP" = "on" ]; then
        lcd_menu $LCD_MENU_ONETOUCH_DONE
        usb_backup $USBDEV
        return $?
      fi
    fi  
  done

  lcd_menu $LCD_MENU_ONETOUCH_BACKUP
#  iomain -n -m "$MSG_USB_BACKUP_ONETOUCH"
}

#
# $1: device path
# $2: mount point
#
mount_partition() {
  local FUNC="MOUNT_PART"
  DEVPATH=$1
  DEVNAME=$(basename $1)
  ID_FS_TYPE=
  ID_FS_LABEL=
  NAME=

  # STEP 1: pre-check fs type
  VOL_ID=$(vol_id $DEVPATH)
  export $VOL_ID

  case "$ID_FS_TYPE" in
    "vfat"|"ntfs"|"ext2"|"ext3"|"xfs"|"hfsplus")   echo >/dev/null ;;
    *) 
      log_func $FUNC $@ $ID_FS_TYPE $NAME
      log_func_result $FUNC $ERROR_MOUNT_FAIL $DEVPATH "Unknown FS type"
      return 1
      ;;
  esac

  # STEP 2: volume name
  NAME=$(get_device_name $DEVPATH device)
  #log_func $FUNC $@ $ID_FS_TYPE $NAME

  # STEP 3: Make UNIQUE mount directory
  if [ ! -z "$NAME" ]; then
    MOUNTDIR=$(mkudir $2/$NAME)
    RESULT=$?
  else
    RESULT=1
  fi
  if [ "$RESULT" != "0" ]; then
    log_func $FUNC $@ $ID_FS_TYPE $NAME
    log_func_check_result $FUNC $? $ERROR_CREATE_FAIL
  fi

  # STEP 4: Mount
  MOUNT_RESULT=$ERROR_MOUNT_FAIL
  case "$ID_FS_TYPE" in
    "vfat")
      mount -t vfat -o users,fmask=000,dmask=000,iocharset=utf8,shortname=mixed \
	"$DEVPATH" "$MOUNTDIR"
      MOUNT_RESULT=$?
      ;;
    "ntfs")
      # mount ntfs using fuse, ntfs-3g
      mount -t ntfs-3g -o users,umask=000,nls=utf8 \
	"$DEVPATH" "$MOUNTDIR"
      MOUNT_RESULT=$?
      ;;
    "ext2"|"ext3"|"xfs")
      mount -t $ID_FS_TYPE -o users \
	"$DEVPATH" "$MOUNTDIR"
      MOUNT_RESULT=$?
      ;;
    "hfsplus")
      mount -t hfsplus -o force,rw \
	"$DEVPATH" "$MOUNTDIR"
      MOUNT_RESULT=$?
      ;;
  esac

  if [ "$MOUNT_RESULT" != "0" ]; then
    log_func $FUNC $@ $ID_FS_TYPE $NAME
    log_func_result $FUNC $ERROR_MOUNT_FAIL $DEVPATH
    rm -rf "$MOUNTDIR"
    return 1
  else
    # some filesystem change mount point's authority
    chmod 777 $MOUNTDIR
    log_func $FUNC "$DEVPATH => $MOUNTDIR [$ID_FS_TYPE]"

    # add to list
    SAMBA_SHARE_NAME=$(get_device_share_name $1 $2)
    echo $DEVPATH $MOUNTDIR $SAMBA_SHARE_NAME >> $USB_LIST_FILE
  fi

  # STEP 5: generate samba share
  DESCRIPTION=$(get_device_name $1 desc)
  nas-share add_samba_usb $SAMBA_SHARE_NAME $MOUNTDIR $DESCRIPTION

}

# 
# $1: device path
# $2: caller
#
removable_mount() {
  local FUNC="USB_MOUNT"
  DEVPATH="$1"
  DEVNAME=$(basename $DEVPATH)

  # STEP 0: wait until all current event are handled in udev event queue (IMPORTANT !!!)
  if [ "$2" = "UDEV" ]; then
    udevadm settle --timeout 30
  else
    udevadm settle --timeout 30
    sleep 1 
  fi

  # STEP 0-1: wait until unmount process is finished
  ELAPSED=0
  while pgrep -f "nas-storage usb_mount remove"; do
    [ ! -e "$1" ] \
      && log_func_check_result $FUNC $FALSE $ERROR_USB_NOT_EXIST "$1 removed before mount"
    [ "$ELAPSED" -gt $USB_WAIT_TIMEOUT ] \
      && log_func_check_result $FUNC $FALSE $ERROR_TIMEOUT "long wait for remove USB"

    ((ELAPSED++))
    sleep 1
  done

  # STEP 1: get device type
  ID_PATH=$(udevinfo --query=all -n $DEVNAME | grep ID_PATH | cut -d "=" -f 2)
  if [ "$ID_PATH" = "scsi-0:0:0:0" ]; then
    DEVTYPE=ESATA
    MOUNT_PATH=$ESATA_MOUNT_DIR
  elif [[ "$ID_PATH" = "scsi-1:0:0:0" || "$ID_PATH" = "scsi-1:0:1:0" ]]; then
    DEVTYPE=BUILT_IN_HDD
    if [ -e "/var/run/nas/booting" ]; then 
      echo $MSG_HDD_INSERTED > $HDD_EVENT_FILE
      return 0
    fi
    
    SYS_GetDiskInfoAndSaveScsi
    Disk1=`cat $SCSI_LIST_FILE |grep DISK1 |cut -d' ' -f1`
    Disk2=`cat $SCSI_LIST_FILE |grep DISK2 |cut -d' ' -f1`

    DISK1_UUID=`tune2fs -l /dev/${Disk1}1 |grep UUID | awk '{ print $3}'`
    DISK2_UUID=`tune2fs -l /dev/${Disk2}1 |grep UUID | awk '{ print $3}'`
    echo "################ udevinfo HDD  ########" $Disk1 $DISK1_UUID > /dev/console
    echo "################ udevinfo HDD  ########" $Disk2 $DISK2_UUID > /dev/console
    INS_HDD_FS=`fdisk -l /dev/${DEVNAME} | grep /dev/${DEVNAME}1 | awk '{ print $6}'`
    INS_HDD_PART=`fdisk -l /dev/${DEVNAME} | grep /dev/${DEVNAME} | wc -l`
   
    echo "################ Inserted HDD FS ###############" $INS_HDD_FS > /dev/console
    echo "################ Inserted HDD Partition ########" $INS_HDD_PART > /dev/console
    if [ "$DISK1_UUID" = "$DISK2_UUID" ] && [ "$INS_HDD_FS" = "Linux" ] && [ "$INS_HDD_PART" = "4" ]; then
      echo "################ Same HDD Sync Starting... ########" > /dev/console
      mdadm -a /dev/md1 /dev/${DEVNAME}1  
    else
      echo "####### NEW HDD Inserted  ###########" > /dev/console     
    fi

    echo $MSG_HDD_INSERTED > $HDD_EVENT_FILE
    lcd_msg_time $MSG_HDD_INSERTED
    led_hdd
    buzzer in hdd
    mount -a
    nas-system hibernation
    # ignore built in HDD
    return 0
  elif [ "$(echo $ID_PATH | grep "usb-0:1.1:")" != "" ]; then
    DEVTYPE=SDMMC
    MOUNT_PATH=$SDMMC_MOUNT_DIR
  elif [ "$(echo $ID_PATH | grep "usb")" != "" ]; then
    DEVTYPE=USB
    MOUNT_PATH=$USB_MOUNT_DIR
  else 
    DEVTYPE=HDD
    MOUNT_PATH=$HDD_MOUNT_DIR
  fi

  # all process should sleep to prevent duplicated and simultaneous function call
	DELAY=$(( $$%5 ))
	sleep $DELAY
  
  # STEP 2: acquire lock
  lcd_icon $ICON_USB blink
  acquire_lock $USB_PROCESS_LOCK $USB_WAIT_TIMEOUT
  if [ "$?" != "0" ]; then
    log_func_result $FUNC $FALSE "Cannot get USB lock $USB_PROCESS_LOCK"
    return 1
  fi

	# check whether duplicate devices are mounted
	grep "$DEVPATH" $USB_LIST_FILE > /dev/null 2>&1

	if [ "$?" = "0" ]
	then
	# already mount
	  log_func_result $FUNC $FALSE "Already exists same $DEVPATH devices"
	  lcd_icon $ICON_USB on
	  release_lock $USB_PROCESS_LOCK
	  return 0
	fi

  # STEP 3: get partitions
  # sleep 1 
  PARTITIONS=$(cat /proc/partitions | grep -E "$DEVNAME[0-9]+" | awk '{ print $4 }')

  # STEP 4: mount partitions
  if [ "$PARTITIONS" = "" ]; then
    log_func $FUNC $DEVTYPE no partition
    mount_partition $DEVPATH $MOUNT_PATH
  else
    log_func $FUNC $DEVTYPE partitions: $PARTITIONS
    for PARTITION in $PARTITIONS; do
      sleep 1
      mount_partition $(dirname $DEVPATH)/$PARTITION $MOUNT_PATH
    done
  fi

  # STEP 5: reload samba conf
  nas-service control samba reload

  # STEP 6: add to device list
  if [ "$2" = "UDEV" ]; then
    usb_device_add $1
  else
    buzzer in usb
  fi

  # STEP 7: release lock
  lcd_icon $ICON_USB on
  release_lock $USB_PROCESS_LOCK

  # STEP 8-1: check if Diag USB is inserted or not
  check_diag
  check_install

  # STEP 8-2: display one touch backup menu
  if [ ! -e /var/run/nas/booting ]; then
    if [[ "$DIAG_USB" != "ON" && "$INSTALL_USB" != "ON" ]]; then
      if [[ "$DEVTYPE" = "USB" || "$DEVTYPE" = "SDMMC" ]]; then
        check_auto_backup $1
      fi
    fi
  fi

}

#
# $1: device path
# $2: caller
#
removable_unmount() {
  local FUNC="USB_UNMOUNT"
  DEVPATH=$1
  DEVNAMES=$(mount | grep "^$DEVPATH[0-9]*" | cut -d " " -f 1)
  DEVNAME=$(basename $DEVPATH)

  # STEP 0: HDD Case (Set LED, Unmount at Individual Cases)
  DEVHDD=$(cat /proc/mdstat | grep $DEVNAME)
  if [ -n "$DEVHDD" ]; then
    led_hdd remove 
    DEL_NODE=$(cat /proc/mdstat | grep ${DEVNAME} | cut -d":" -f 1)
    log_func $FUNC $DEL_NODE $DEVNAME
    for MD_DEV in $DEL_NODE
    do
      case $MD_DEV in
	"md1") # Firmware Region
	  mdadm -f /dev/${MD_DEV} /dev/${DEVNAME}1
	  mdadm -r /dev/${MD_DEV} /dev/${DEVNAME}1
	  mdadm /dev/${MD_DEV} -r faulty
	  ;;
	"md2"|"md3") # Data
	  RAID_LEVEL=$(grep "$MD_DEV" /proc/mdstat | cut -d " " -f 4)
	  if [ "$RAID_LEVEL" = "raid1" ]; then
	    mdadm -f /dev/${MD_DEV} /dev/${DEVNAME}1
	    mdadm -r /dev/${MD_DEV} /dev/${DEVNAME}1
	    mdadm /dev/${MD_DEV} -r faulty
	  else
	    umount /dev/${MD_DEV}
	    mdadm -S /dev/${MD_DEV}
	  fi
	  ;;
	"md4"|"md5") # Swap
	  swapoff /dev/${MD_DEV}
	  mdadm -S /dev/${MD_DEV}
	  log_func $FUNC HDD is removed abnormally
 	  ;;
    	esac
    done

    nas-system hibernation
    return 0 
  fi

  # STEP 0: wait until mount process is finished
  ELAPSED=0
  while pgrep -f "nas-storage usb_mount add"; do
    [ "$ELAPSED" -gt $USB_WAIT_TIMEOUT ] \
      && log_func_check_result $FUNC $FALSE $ERROR_TIMEOUT "long wait for add USB"
    ((ELAPSED++))
    sleep 1
  done

  # STEP 1: aquire lock
  acquire_lock $USB_PROCESS_LOCK $USB_WAIT_TIMEOUT
  if [ "$?" != "0" ]; then
    log_func_result $FUNC $FALSE "Cannot get USB lock $USB_PROCESS_LOCK"
    return 1
  fi

  for DEVPATH in $DEVNAMES; do
    # STEP 2: umount 
    DEVNAME=$(basename $DEVPATH)
    MOUNTDIR=$(mount | grep "$DEVPATH " | sed 's/ type .*$//' | sed "s/.*$DEVNAME on //")
    if [ ! -z "$MOUNTDIR" ]; then
      log_func $FUNC "Mounted directory" "[$MOUNTDIR]"
      umount -f "$DEVPATH"
      if [ $? = 0 ]; then
        rm -rf "$MOUNTDIR"
      else
        umount -l "$DEVPATH"
        if [ $? = 0 ]; then
          rm -rf "$MOUNTDIR"
        fi
      fi
    fi
    log_func_result $FUNC $? 
    
    # STEP 3: remove samba share
    SAMBA_SHARE_NAME=$(grep $DEVPATH $USB_LIST_FILE | cut -d " " -f 3)
    nas-share del_samba_usb $SAMBA_SHARE_NAME

    # STEP 4: remove from list
    sed -i "/\/$DEVNAME /d" $USB_LIST_FILE
  done

  # STEP 5: reload samba conf
  nas-service control samba reload

  # STEP 6: remove from device list
  if [ "$2" = "UDEV" ]; then
    usb_device_remove $1
  else
    buzzer out usb
  fi

  # STEP 7: release lock
  release_lock $USB_PROCESS_LOCK

  lcd_menu $LCD_MENU_MAIN
}

#
# $1: action {add|remove|change}
# $2: device path
# $3: caller
#
usb_mount () {
  local FUNC="USB_EVENT"
  log_func $FUNC $@

  ACTION=$1
  shift 
  
  if [ "$ACTION" = add ]; then
    removable_mount $@ &
  elif [ "$ACTION" = remove ]; then
    removable_unmount $@ & 
  fi
}

#
# $1: device name
#
odd_mount() {
  local FUNC="ODD_MOUNT"
  log_func $FUNC $@

  ISCSI_ENABLED=$(nas-service get enabled iscsi)
  if [ "$ISCSI_ENABLED" = "on" ]; then
    log_func $FUNC "iSCSI service enabled -> disable odd mount"
    return 1
  fi

  mkdir -p $CDROM_MOUNT_DIR
  mount -t udf,iso9660 -o ro,iocharset=utf8 $1 $CDROM_MOUNT_DIR 2>&1 | log_func_pipe $FUNC
  RESULT=${PIPESTATUS[0]}

  # prevent allow 
  sleep 1
  # mkisofs run at CD Web burning
  pidof mkisofs > /dev/null 2>&1
  if [ $? != 0 ]; then
    sg_prevent -a $1
    if [ "$?" != 0 ]; then
      sleep 1
      sg_prevent -a $1
    fi
  else
    sg_prevent -p 0 $1
    sg_prevent -p 1 $1
  fi

  # refresh samba share
  smbcontrol smbd close-share $CDROM_SHARE_PREFIX

  log_func_result $FUNC $RESULT 
  return $RESULT
}

#
# $device name
#
odd_umount() {
  local FUNC="ODD_UMOUNT"
  log_func $FUNC $@

  umount $1 2>&1 | log_func_pipe $FUNC
  RESULT=${PIPESTATUS[0]}

  if [ ! "$RESULT" = 0 ]; then
    umount -l $1 2>&1 | log_func_pipe $FUNC
    RESULT=${PIPESTATUS[0]}
  fi

  # refresh samba share
  smbcontrol smbd close-share $CDROM_SHARE_PREFIX

  log_func_result $FUNC $RESULT 
  return $RESULT
}


#
disk_get() {
  local FUNC="STORAGE_GET"

  case "$1" in
    "vol_default")
      default_volume
      ;;
    "vol_num")
      NUM=$(mount | grep "/mnt/disk/" | wc -l)
      echo $NUM
      ;;
    "vol_list")
      VOLS=$(mount | grep "/mnt/disk/" | cut -d " " -f 3)
      RESULT=
      for VOL in $VOLS; do
        RESULT+=$(basename $VOL)" "
       done
      echo $RESULT
      ;;
    "vol_percent")
      DEVS=$(mount | grep "/mnt/disk/" | cut -d" " -f 1)
      for DEV in $DEVS; do
        USED_ALL=$((USED_ALL+`df | grep $DEV | tr -s " " | cut -d" " -f 3`))
        TOTAL_ALL=$((TOTAL_ALL+`df | grep $DEV | tr -s " " | cut -d" " -f 4`))
      done
      USAGE=$((100*USED_ALL/TOTAL_ALL))
      echo $((USAGE+1))
      ;;
    "vol_fstab")
      VOLS=$(awk '{ print $2; }' /etc/fstab | grep "/mnt/disk/" | cut -d"/" -f 4)
      if [ -z "$VOLS" ] ; then
	RESULT="Config First"
      else
	RESULT=[$VOLS]
      fi
      echo $RESULT
      ;;

    "vol_type")
      ID_PATH=$(udevinfo --query=all -n $2 | grep ID_PATH | cut -d "=" -f 2)
      if [ "$ID_PATH" = "scsi-0:0:0:0" ]; then  DEVTYPE=ESATA
      elif [ "$ID_PATH" = "scsi-1:0:0:0" ]; then DEVTYPE=HDD1
      elif [ "$ID_PATH" = "scsi-1:0:1:0" ]; then DEVTYPE=HDD2
      elif [ "$ID_PATH" = "scsi-1:0:2:0" ]; then DEVTYPE=ODD
      elif [ "$(echo $ID_PATH | grep "usb")" != "" ]; then   
        PORT=$(udevinfo --query=env -n $2 | grep "ID_PATH" | cut -d":" -f 2)
        PORT_NUM=${PORT#[0-9].}
        case "$PORT_NUM" in
          "1") DEVTYPE="MEMCARD";;
          "2") DEVTYPE="USB1";;
          "3") DEVTYPE="USB2";;
          "4") DEVTYPE="USB3";;
          *);;
        esac
      fi
      echo $DEVTYPE
      ;;
    "dev_list")
      NAME=$2
      DEVLIST=$(ls /dev/sd[a-z])
      for DEV in $DEVLIST
      do
        DEVTYPE=$(disk_get vol_type $DEV)
        if [ "${DEVTYPE:0:3}" = "$2" ]; then
          HDDLIST="$HDDLIST $DEV"
        fi
      done
      echo $HDDLIST
      ;;
    "vol_raid")
      DISK=$2
      if [ "$DISK" = "volume1" ]; then
        DEVNAME=$(df |grep $DISK| cut -d " " -f1)
      else
        DEVNAME=$(df |grep $DISK| cut -d " " -f1)
      fi

      DEVNUM=$(mdadm --detail $DEVNAME | grep "Raid Devices" | cut -d":" -f2)
      RAID=$(mdadm --detail $DEVNAME | grep "Raid Level" | cut -d":" -f2)
      if [[ "${DEVNUM#?}" = "1" && "${RAID#?}" = "linear" ]]; then
        RAID="No RAID"
      fi
      echo $RAID
      ;;
    "md_dev_list")
      LIST=$(cat /proc/mdstat | grep -o sd[a-z] | sort | uniq)
      RESULT=
      for ITEM in $LIST; do
        RESULT="$RESULT /dev/${ITEM}"
      done
      echo $RESULT
      ;;
    *)
      log_func_result $FUNC $FALSE $ERROR_INVALID_PARAM
      ;;
  esac
}

# 
# $1: source
# $2: target
#
incremental_backup() {
  local FUNC="INC_BACKUP"
  log_func $FUNC $@

  MOUNT_DIR=$1		# source dir
  DATE_DIR=$2		# target dir
  BACKUP_DIR=$(dirname $DATE_DIR)

  # STEP 1: make backup directory
  LAST_BACKUP=lastbackup
  LAST_BACKUP_DIR=$BACKUP_DIR/$LAST_BACKUP
  mkdir -p $LAST_BACKUP_DIR
  chmod 755 $LAST_BACKUP_DIR

  # STEP 2: get differential file list
  LIST_FILE=/var/run/inc_backup.$PPID
  rsync -anv -8 --compare-dest=../$LAST_BACKUP $MOUNT_DIR/ $DATE_DIR/ > $LIST_FILE
  sed -i '/\/$/d' $LIST_FILE
  sed -i '/^sending incremental/d' $LIST_FILE
  sed -i '/^sent.*sec$/,/^total.*RUN/d' $LIST_FILE
  sed -i '/^$/d' $LIST_FILE
  MOUNT_DIR_SED=$(echo $MOUNT_DIR | sed 's/\//\\\//g')
  sed -i "s/^/$MOUNT_DIR_SED\//g" $LIST_FILE

  # STEP 3: do backup
  PFILE=/var/run/inc_backup_progress.$PPID
  cmscopy -o 1 -s $MOUNT_DIR -l $LIST_FILE -d $DATE_DIR -p $PFILE &
  BACKGROUND_PID=$!
  while ps -p $BACKGROUND_PID >/dev/null; do
    PERCENT=$(cat $PFILE 2>/dev/null)
    [ -z "$PERCENT" ] && PERCENT=0
    lcd_msg "$MSG_USB_BACKUP_PROGRESS $PERCENT%"
    sleep 1
  done
  wait $BACKGROUND_PID
  RESULT=$?
  sync

  # STEP 4: sync last backup 
  if [ "$RESULT" = "0" ]; then
    log_func $FUNC Sync $LAST_BACKUP
    rsync -a --delete --link-dest=../$(basename $DATE_DIR) $MOUNT_DIR/ $LAST_BACKUP_DIR/
    RESULT=$?
  fi

  rm $PFILE
  rm $LIST_FILE
  find $DATE_DIR/ -type d -empty -delete

  return $RESULT
}

# 
# $1: source
# $2: target
#
full_backup() {
  local FUNC="FULL_BACKUP"
  log_func $FUNC $@

  SRC_DIR=$1
  TGT_DIR=$2

  cp_progress $SRC_DIR/ $TGT_DIR $MSG_USB_BACKUP_PROGRESS
  RESULT=$?

  return $RESULT
}

# 
# $1: source
# $2: target
#
sync_backup() {
  local FUNC="SYNC_BACKUP"
  log_func $FUNC $@

  cp -au $1/* $2
  cp -au $2/* $1

  return $RESULT
}

# 
# USB backup function
# 
# [$1]: device path 
#
usb_backup() {
  local FUNC="USB_BACKUP"
  log_func $FUNC $@  
  lcd_msg $MSG_USB_BACKUP_START

  START_TIME=$(date +%s)
  VOL=$(default_volume)
  if [ -z "$1" ]; then
    USBDEV_INFO=$(cat $USB_LIST_FILE | grep -v "esata[0-9_]*$" | tail -n 1  2>/dev/null) 
  else 
    USBDEV_INFO=$(cat $USB_LIST_FILE | grep "^$1 " | tail -n 1  2>/dev/null) 
  fi

  # STEP 0: precheck before backup
  if [ -z "$VOL" ]; then
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_VOL_NOT_EXIST
    broadcast usb_backup_fail
    log_func_check_result $FUNC $FALSE $ERROR_VOL_NOT_EXIST
  fi
  if [ -z "$USBDEV_INFO" ]; then
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_USB_NOT_EXIST
    broadcast usb_backup_fail
    log_func_check_result $FUNC $FALSE $ERROR_USB_NOT_EXIST 
  fi

  USB_PID=/var/run/usb_backup.pid
  echo $$ > $USB_PID

  # STEP 1: collect USB information
  USBDEV=$(echo $USBDEV_INFO | cut -d " " -f 1)
  NAME=$(get_device_name $USBDEV full)
  MOUNT_DIR=$(echo $USBDEV_INFO | cut -d " " -f 2)
  DATE=$(date +%Y%m%d)
  log_func $FUNC $USBDEV $MOUNT_DIR $NAME

  # STEP 2: check predefined USB
  REGISTERED=$(sed -n "/$USB_BACKUP_START_COMMENT $NAME/,/$USB_BACKUP_END_COMMENT $NAME/p" $USB_BACKUP_CONF)
  if [ -z "$REGISTERED" ]; then
    BACKUP_DIR_BASE=$VOL/$SYSTEMDIR/backup/usb
    BACKUP_METHOD=incremental
    log_func $FUNC "Unregistered USB : $NAME"
  else 
    BACKUP_DIR_BASE=$(sed -n "/$USB_BACKUP_START_COMMENT $NAME/,/$USB_BACKUP_END_COMMENT $NAME/ s/dest:\(.*\)/\1/p" $USB_BACKUP_CONF)
    BACKUP_METHOD=$(sed -n "/$USB_BACKUP_START_COMMENT $NAME/,/$USB_BACKUP_END_COMMENT $NAME/ s/method:\(.*\)/\1/p" $USB_BACKUP_CONF)
    log_func $FUNC "Registered USB : $NAME Method=$BACKUP_METHOD"
  fi

  # STEP 3: make backup directory
  BACKUP_DIR=$BACKUP_DIR_BASE/$NAME/${BACKUP_METHOD}
  DATE_DIR=$(mkudir_order $BACKUP_DIR/$DATE)
  chmod 777 $BACKUP_DIR_BASE
  chmod 777 $BACKUP_DIR_BASE/$NAME
  chmod 777 $BACKUP_DIR_BASE/$NAME/${BACKUP_METHOD}

  if [ -z "$(echo $DATE_DIR | grep '^/mnt/disk')" ]; then
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_USB_BACKUP_FAIL $ERROR_VOL_NOT_EXIST
    log_func_check_result $FUNC $FALSE $ERROR_VOL_NOT_EXIST
  fi

  # STEP 4: do backup
  nas-common button lock

  case "$BACKUP_METHOD" in
    "incremental")
      incremental_backup $MOUNT_DIR $DATE_DIR
      RESULT=$?
      ;;
    "full")
      full_backup $MOUNT_DIR $DATE_DIR
      RESULT=$?
      ;;
    "sync")
      sync_backup $MOUNT_DIR $DATE_DIR
      RESULT=$?
      ;;
  esac

  chmod 777 $DATE_DIR >/dev/null 2>&1
  chmod 777 $(dirname $DATE_DIR) >/dev/null 2>&1

  lcd_menu $LCD_MENU_PREMAIN
  if [ "$RESULT" = "0" ]; then
    lcd_msg_time $MSG_USB_BACKUP_END
    broadcast usb_backup_end
  else
    lcd_msg_time $MSG_USB_BACKUP_FAIL
    broadcast usb_backup_fail
  fi
  nas-common button unlock
  rm $USB_PID

  log_func_result $FUNC $RESULT "Elapsed Time: $(($(date +%s) - $START_TIME))sec"
  return $RESULT
}


#
## $1: src path 
## $2: dest path
## $3: message path 
#
usb_copy() {
  local FUNC="USB_COPY"
  log_func $FUNC $@  
  log_func $FUNC SRC :$1:
  log_func $FUNC TGT :$2:
  log_func $FUNC CCL :$3:
  lcd_msg $MSG_USB_COPY_START

  START_TIME=$(date +%s)
  VOL=$(default_volume)
  USBDEV_INFO=$(tail -n 1 $USB_LIST_FILE 2>/dev/null) 
  
  if [ -z "$VOL" ]; then
    lcd_msg_time $MSG_USB_BACKUP_FAIL $ERROR_VOL_NOT_EXIST
    log_func_check_result $FUNC $FALSE $ERROR_VOL_NOT_EXIST 
  fi
  if [ -z "$USBDEV_INFO" ]; then
    lcd_msg_time $MSG_USB_BACKUP_FAIL $ERROR_USB_NOT_EXIST
    log_func_check_result $FUNC $FALSE $ERROR_USB_NOT_EXIST 
  fi

  USBDEV=$(echo $USBDEV_INFO | cut -d " " -f 1)
  USBDEV_PARENT=$(basename $USBDEV | sed 's/[0-9]*$//')
  SERIAL=$(udevinfo --query=all -p /sys/block/$USBDEV_PARENT \
		| grep "ID_SERIAL=" | cut -d "=" -f 2)
  SERIAL=${SERIAL%%"-"*}

  SOURCE_DIR=$1	
  TARGET_DIR=$2
  CANCEL_DIR=$3
  #while [ -e "$TARGET_DIR" ]; do
    #NUM=$(($NUM + 1))
    #TARGET_DIR="$TARGET_DIR_BASE-"$NUM
  #done
   
  cp_progress_cms_web $SOURCE_DIR $TARGET_DIR $CANCEL_DIR 

  RESULT=$?
  if [ "$RESULT" = "0" ]; then
    lcd_msg_time $MSG_USB_COPY_END
  else
    lcd_msg_time $MSG_USB_COPY_FAIL
  fi

  log_func_result $FUNC $RESULT "Elapsed Time: $(($(date +%s) - $START_TIME))sec"
  return $RESULT
}

#
# $1: device (sg type)
#
get_iso_image_name() {

  DATE=$(date +%Y%m%d)

  SR_DEV=$(sg_map -sr | grep $1 | awk '{print $2}')
  VOL_ID=$(vol_id $SR_DEV)
  export $VOL_ID > /dev/null 2>&1
  
  DISC_TYPE=$(nat -d $1 -c "disctype")
  if [ "$?" = 0 ]; then
    if [ -z "$ID_FS_LABEL_SAFE" ]; then
      IMG_NAME=${DATE}_${DISC_TYPE}
    else
      IMG_NAME=${DATE}_${DISC_TYPE}_${ID_FS_LABEL_SAFE}
    fi
  fi
  IMG_NAME=$(echo $IMG_NAME | tr " " "_" | tr -s "_")
  echo $IMG_NAME
}

# 
# $1: method {image|data}
# [$2]: image filename 
#
odd_backup() {
  local FUNC="ODD_BACKUP"
  log_func $FUNC $@
  lcd_msg $MSG_ODD_BACKUP_START

  ODD_PID=/var/run/odd_backup.pid
  echo $$ > $ODD_PID

  START_TIME=$(date +%s)
  VOL=$(default_volume)
  MOUNT_DIR=/mnt/device/CD-ROM

  # STEP 0: PRE check
  if [ -z "$VOL" ]; then
    rm -rf $ODD_PID
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_VOL_NOT_EXIST
    log_func_check_result $FUNC $FALSE $ERROR_VOL_NOT_EXIST
  fi

  if [ "$(nas-service get enabled iscsi)" = "on" ]; then
    rm -rf $ODD_PID
    lcd_menu $LCD_MENU_PREMAIN
    lcd_msg_time $MSG_ISCSI_ENABLED
    log_func_check_result $FUNC $FALSE $ERROR_ISCSI_ENABLED
  fi

  DATE=$(date +%Y%m%d)
  ODD_DEV=$(ls -al /dev/sg* | grep cdrom | head -n 1)
  ODD_DEV=${ODD_DEV##*" "}

  #if disc is loading, wait until 5 min
  for (( TIME=1; TIME <= 300; TIME += 1))
  do
    SENSE=$(sg_turs -v $ODD_DEV 2>&1 | grep "Additional sense" | cut -d: -f2 | awk '{print $1}')
    if [[ -z "$SENSE" || "$SENSE" = "Medium" ]]; then
      # recognized or Medium not present
      log_func $FUNC "disc recognised or not present, $TIME"
      break
    fi
    sleep 1
  done

  DISC=$(sg_get_config $ODD_DEV | grep "Current profile" | cut -d " " -f 3)
  if [ -z "$DISC" ]; then
      #Medium not present
      rm -rf $ODD_PID
      lcd_menu $LCD_MENU_PREMAIN
      lcd_msg_time $MSG_DISC_NOT_EXIST
      eject /dev/scd0
      log_func_check_result $FUNC $FALSE $ERROR_DISC_NOT_EXIST
  elif [ "$DISC" = "DVD-ROM" ]; then
    # Unsupport CSS/CPPM title
    COPYRIGHT=$(sg_raw -r 16 $ODD_DEV AD 0 0 0 0 0 0 1 0 10 0 0  2>&1 | grep "00" | awk '{print $6}')
    if [[ ${PIPESTATUS[0]} = 0 && "$COPYRIGHT" = "01" ]]; then
      rm -rf $ODD_PID
      lcd_menu $LCD_MENU_PREMAIN
      lcd_msg_time $MSG_UNSUPPORTED_DISC
      eject /dev/scd0
      log_func_check_result $FUNC $FALSE $ERROR_UNSUPPORTED_DISC
    fi
  elif [ "$DISC" = "CD-ROM" ]; then
     ADDR=$(sg_readcap $ODD_DEV | grep "logical block address" | cut -d "=" -f 2 | awk '{print $1}')
     if [ $ADDR -gt 40960 ]; then
        sg_raw -r 2k $ODD_DEV 28 0 0 0 A0 0 0 0 1 0 2>&1 | grep "Sense key: Illegal Request"
        if [ $? = 0 ]; then
          rm -rf $ODD_PID
          lcd_menu $LCD_MENU_PREMAIN
          lcd_msg_time $MSG_UNSUPPORTED_DISC
          eject /dev/scd0
          log_func_check_result $FUNC $FALSE $ERROR_UNSUPPORTED_DISC
        fi
     fi
  fi

  nat -d $ODD_DEV -c "checkDisc"
  if [ $? = 9 ]; then 
      rm -rf $ODD_PID
      lcd_menu $LCD_MENU_PREMAIN
      lcd_msg_time $MSG_UNSUPPORTED_DISC
      eject /dev/scd0
      log_func_check_result $FUNC $FALSE "$ERROR_UNSUPPORTED_DISC Mix or Extra CD"
  fi

  # Pre-check mount running
  sg_raw -r 2k $ODD_DEV 28 0 0 0 0 10 0 0 1 0 2>&1 | grep "CD001"
  ISO_FS=$?
  sg_raw -r 2k $ODD_DEV 28 0 0 0 0 20 0 0 1 0 2>&1 | grep "OSTA"
  UDF_FS=$?
  if [[ $ISO_FS = 0 || $UDF_FS = 0 ]]; then
    log_func $FUNC "wait mount"
    for (( TIME=1; TIME <= 40; TIME += 1))
    do
      if [ ! -z "$(mount | grep $MOUNT_DIR)" ]; then
        log_func $FUNC "mount done $TIME"
        break
      fi
      sleep 1
    done
  fi

  if [[ "$1" = "data" && -z "$(mount | grep $MOUNT_DIR)" ]]; then
      rm -rf $ODD_PID
      lcd_menu $LCD_MENU_PREMAIN
      lcd_msg_time $MSG_UNSUPPORTED_DISC
      eject /dev/scd0
      log_func_check_result $FUNC $FALSE $ERROR_UNSUPPORTED_DISC
  fi

  nas-common button lock
  lcd_menu 27

  if [ "$1" = "image" ]; then
    # STEP 1: make backup directory
    #BACKUP_FILE_BASE=$VOL/$SYSTEMDIR/backup/disc-image/$DATE
    BACKUP_FILE_BASE=$VOL/$SYSTEMDIR/backup/disc-image/$(get_iso_image_name $ODD_DEV)
    
    BACKUP_FILE=$BACKUP_FILE_BASE.iso
    NUM=1
    while [ -e "$BACKUP_FILE" ]; do
      NUM=$(($NUM + 1))
      BACKUP_FILE="$BACKUP_FILE_BASE-"$NUM.iso
    done
    
    # for Applet
    if [ ! -z "$2" ]; then
      BACKUP_FILE=$2
    fi

    mkdir -p $(dirname $BACKUP_FILE)
    chmod 777 $(dirname $BACKUP_FILE_BASE)
    chmod 777 $(dirname $BACKUP_FILE)
    log_func $FUNC Backup file = $BACKUP_FILE
  
    # STEP 2: do backup
    # dd if=$ODD_DEV of=$BACKUP_FILE 		# dd does not work
    log_func $FUNC device = $ODD_DEV

    nat -d $ODD_DEV -c "mkimage $BACKUP_FILE" | \
    while read data; do
      if [ "${data:(-1)}" = "%" ]; then
        lcd_msg $MSG_ODD_BACKUP_PROGRESS $data
      else
        log_func $FUNC $data
      fi
    done
    RESULT=${PIPESTATUS[0]}

  elif [ "$1" = "data" ]; then
    # STEP 1: make backup directory
    BACKUP_DIR_BASE=$VOL/$SYSTEMDIR/backup/disc-data/$DATE
    BACKUP_DIR=$BACKUP_DIR_BASE
    NUM=1
    while [ -e "$BACKUP_DIR" ]; do
      NUM=$(($NUM + 1))
      BACKUP_DIR="$BACKUP_DIR_BASE-"$NUM
    done

    mkdir -p $BACKUP_DIR
    chmod 777 $(dirname $BACKUP_DIR)
    chmod 777 $BACKUP_DIR
    log_func $FUNC Backup dir = $BACKUP_DIR
  
    # STEP 2: do backup
    cp_progress $MOUNT_DIR/ $BACKUP_DIR $MSG_ODD_BACKUP_PROGRESS

    RESULT=$?
  fi

  lcd_menu $LCD_MENU_PREMAIN
  if [ "$RESULT" = "0" ]; then
    lcd_msg_time $MSG_ODD_BACKUP_END
  elif [[ "$RESULT" = "2" || "$RESULT" = "8" || "$RESULT" = "10" ]]; then
    lcd_msg_time $MSG_UNSUPPORTED_DISC
  else
    lcd_msg_time $MSG_ODD_BACKUP_FAIL
  fi
  nas-common button unlock
  rm -rf $ODD_PID
  
  # eject after backup
  eject /dev/scd0

  log_func_result $FUNC $RESULT "Elapsed Time: $(($(date +%s) - $START_TIME))sec"
  return $RESULT
}
# 
# $1: Disc's volume name
#
odd_burn() {
  local FUNC="ODD_BURN"
  log_func $FUNC $@

  echo "$1" > /tmp/volume_id
  odd_burning
  RESULT=$?
  return $RESULT
}


usb_cancel() {
  local FUNC="USB_CANCEL"

  if [ ! -e "/var/run/usb_backup.pid" ]; then 
    sleep 1
  fi

  BACKUP_PID=$(cat /var/run/usb_backup.pid)
  log_func $FUNC $BACKUP_PID

  kill $BACKUP_PID
  pkill rsync
  pkill tee

  RSYNC_PID=$(pidof rsync)
  log_func $FUNC pid $RSYNC_PID

# if cmscopy 
  pkill cmscopy

  rm /var/run/usb_backup.pid
  nas-common button unlock
  lcd_msg_time "Backup Canceled"
}

odd_cancel() {
  local FUNC="ODD_CANCEL"
  log_func $FUNC $@

  if [ ! -e "/var/run/odd_backup.pid" ]; then 
    sleep 1
  fi

  BACKUP_PID=$(cat /var/run/odd_backup.pid)
  log_func $FUNC $BACKUP_PID

  kill $BACKUP_PID
  pkill -n nat
  pkill cmscopy

  rm /var/run/odd_backup.pid
  nas-common button unlock

  umount /mnt/device/CD-ROM
  eject /dev/scd0
  lcd_msg_time "Backup Canceled"
}

usb_sync() {
  local FUNC="USB_SYNC"
  log_func $FUNC $@

  USBDEV_INFO=$(tail -n 1 $USB_LIST_FILE 2>/dev/null) 
  if [ -z "$USBDEV_INFO" ]; then
    lcd_msg_time "USB Not Exist" 
  else
    sync
    lcd_msg_time "USB Sync Done"
  fi
}


#
# Write usb information which is set by user
# $1 : control number 
# $2 : name 
# $3 : description
# $4 : auto sync {on|off}
# $5 : backup method {incremental|full|sync}
#
write_usb_backup_info()
{
  FILE=$1 
   
  NAME=$(cat $FILE | grep "name" | cut -d ":" -f 2)
  DESCRIPT=$(cat $FILE | grep "descript" | cut -d ":" -f 2)
  DEST=$(cat $FILE | grep "dest" | cut -d ":" -f 2)
  AUTO_SYNC=$(cat $FILE | grep "auto_sync" | cut -d ":" -f 2)
  CONTROL_NUM=$(cat $FILE | grep "control_num" | cut -d ":" -f 2)
  METHOD=$(cat $FILE | grep "method" | cut -d ":" -f 2)
   
  echo "$USB_BACKUP_START_COMMENT $CONTROL_NUM"
  echo "control_num:$CONTROL_NUM"
  echo "name:$NAME"	
  echo "descript:$DESCRIPT"
  echo "dest:$DEST"
  echo "auto_sync:$AUTO_SYNC"
  echo "method:$METHOD"
  echo "$USB_BACKUP_END_COMMENT $CONTROL_NUM"
}

#
# $1 : generation type {create|edit|delete}
# $2 : control num   
#
gen_usb_backup_info() {
  local FUNC="GEN_USB_BACKUP_INFO"
  log_func $FUNC $@

  GEN_TYPE=$1
  CONTROL_NUM=$2
  shift
  shift

  if [ ! -e "$USB_BACKUP_CONF" ]; then
     touch $USB_BACKUP_CONF
     chmod 666 $USB_BACKUP_CONF
  fi 
    
  if [ "$GEN_TYPE" = "create" ]; then
     # lcd_msg_time "CREATE USB INFO"
     write_usb_backup_info $@ >> $USB_BACKUP_CONF   	
  elif [ "$GEN_TYPE" = "edit" ]; then
     # lcd_msg_time "EDIT USB INFO"
     sed -i "/$USB_BACKUP_START_COMMENT $CONTROL_NUM/,/$USB_BACKUP_END_COMMENT $CONTROL_NUM/d" $USB_BACKUP_CONF
     write_usb_backup_info $@ >> $USB_BACKUP_CONF
  else
     # lcd_msg_time "DELETE USB INFO"
     sed -i "/$USB_BACKUP_START_COMMENT $CONTROL_NUM/,/$USB_BACKUP_END_COMMENT $CONTROL_NUM/d" $USB_BACKUP_CONF
  fi      
}
#
# $1 : get type {all|one}
# $2 : control num  <= edit/delete mode only  
#
get_usb_backup_list() {
  local FUNC="GET_USB_BACKUP_LIST"
  log_func $FUNC $@

  GET_TYPE=$1
   
  if [ "$GET_TYPE" = "all" ]; then
     FILE=$USB_BACKUP_CONF
  else
     FILE=$USB_BACKUP_SELECTED_ITEM
     if [ ! -e "$FILE" ]; then
        touch $FILE
        chmod 666 $FILE
     fi 
     CONTROL_NUM=$2
     sed -n "/$USB_BACKUP_START_COMMENT $CONTROL_NUM/,/$USB_BACKUP_END_COMMENT $CONTROL_NUM/p" $USB_BACKUP_CONF > $FILE 
  fi      
 
  NAME=$(cat $FILE | grep "name")
  DESCRIPT=$(cat $FILE | grep "descript")
  DEST=$(cat $FILE | grep "dest")
  AUTO_SYNC=$(cat $FILE | grep "auto_sync" | cut -d ":" -f 2)
  CONTROL_NUM=$(cat $FILE | grep "control_num" | cut -d ":" -f 2)
  METHOD=$(cat $FILE | grep "method" | cut -d ":" -f 2)

  if [ -z "$NAME" ]; then
      echo "error||No device"
  else 
      echo $NAME"||"$DESCRIPT"||"$DEST"||"$CONTROL_NUM"||"$AUTO_SYNC"||"$METHOD
  fi
}


#-------------------------------------------------------------------------------
# Disk Management
#-------------------------------------------------------------------------------
EMPTY_PART_INFO=/etc/nas/default/empty.out

delpart() {
  local FUNC="DEL_PART"
  log_func $FUNC $@
  sfdisk -f $1 < $2
  log_func_result $FUNC $?
}

copyrootfs() {
  tar jxvf /mnt/data/data.tar.bz2 -C /mnt/rootfs
}  

makeraid() {
  local FUNC="MAKE_RAID"
  log_func $FUNC

if [ $3 = "+0M" ]; then
  echo "no touch rootfs"
else
  mdadm --create -f /dev/md1 --raid-devices=2 --level=raid1  "${1}1"  "${2}1" << EOF
yes
EOF
fi
  
if [ $4 = 1 ]; then
  echo "indep mode"
elif [ $4 = 2 ]; then
  mdadm --create -f /dev/md2 --raid-devices=2 --level=$5  "${1}3"  "${2}3" <<EOF
yes
EOF
elif [ $4 = 3 ]; then
  mdadm --create -f /dev/md2 --raid-devices=2 --level=$5  "${1}3"  "${2}3" <<EOF
yes
EOF
  mdadm --create -f /dev/md3 --raid-devices=2 --level=$6  "${1}4"  "${2}4" <<EOF
yes
EOF
else
  echo "error: RAID_NO must be a wrong number"
fi
  
if [ "$?" -ne "0" ]; then
  umount /dev/md2
  umount /dev/md3
  mdadm --stop /dev/md2
  mdadm --stop /dev/md3
  cat /proc/partitions
  cat /sys/block/sda/sda3/size
  cat /sys/block/sdb/sdb3/size
  
  #$0 $1 $2 $3 $4 $5 $6
  exit 1
fi

  log_func_result $FUNC $OK
}

#-------------------------------------------------------------------------------

# $1 : device name (ex: /dev/sda)
# $2 : root size [M] (ex: +10M)
# $3 : swap size [M] (ex: +128M)
# $4 : raid size [M] (ex: +100M)
# $5 : linear size [M] (ex: +300M)
# example
# $4 = NULL --> independent HDD
# $4 = 0 && $5 = 0 --> indep
# $4 = 0 && $5 = NULL --> full linear or raid
# $4 = 0 && $5 = x --> linear mode  
# $4 = x && $5 = 0 --> raid only
# $4 = x && $5 = y or NULL --> raid + linear(full size) or raid1 + raid0

# 0) don't touch rootfs & swap
#    rootfs($2) = 0, swap($3) = 0
makepart() {
  local FUNC="MAKE_PART"

  log_func $FUNC $@

if [ $2 = "+0M" ]; then
  if [ $3 = "+0M" ]; then
    # indep($4=NULL)
    if [ -z "$4" ]; then
      log_func $FUNC "only independent"
      fdisk $1 << EOF
n
p
3

$4
t
3
83
p
w
EOF
    # linear($4 = 0)
    elif [ "$4" = "+0M" ]; then
      log_func $FUNC "only linear / raid"
      fdisk $1 << EOF
n
p
3

$5
t
3
fd
p
w
EOF
    # raid only($5 = 0)
    elif [ $5 = "+0M" ]; then
      log_func $FUNC "only raid"
      fdisk $1 << EOF
n
p
3

$4
t
3
fd
p
w
EOF
    # raid + linear($5 = x)
    else 
      log_func $FUNC "raid! + linear or raid1 + raid0"
      fdisk $1 << EOF
n
p
3

$4
n
p
4

$5
t
3
fd
t
4
fd
p
w
EOF
    fi
  fi
       
# 1) rootfs($2) + swap($3) + indep($4, full)
elif [ -z "$4" ]; then 
  log_func $FUNC "no raid"
  fdisk $1 << EOF
n
p
1

$2
n
p
2

$3
n
p
3

$4
t
1
fd
t
2
82
t
3
83
p
w
EOF

# 2) rootfs($2) + swap($3) + linear($5, full)
elif [ $4 = "+0M" ]; then
  log_func $FUNC "linear / raid0"
  fdisk $1 << EOF
n
p
1

$2
n
p
2

$3
n
p
3

$5
t
1
fd
t
2
82
t
3
fd
p
w
EOF

# 3) rootfs($2) + swap($3) + raid($4)
elif [ $5 = "+0M" ]; then
  log_func $FUNC "raid"
  fdisk $1 << EOF
n
p
1

$2
n
p
2

$3
n
p
3

$4
t
1
fd
t
2
82
t
3
fd
p
w
EOF

# 3) rootfs($2) + swap($3) + raid($4) + linear($5, full)
else
  log_func $FUNC "raid + linear"
  fdisk $1 << EOF
n
p
1

$2
n
p
2

$3
n
p
3

$4
n
p
4

$5
t
1
fd
t
2
82
t
3
fd
t
4
fd
p
w
EOF

fi

  log_func_result $FUNC $?
}

#-------------------------------------------------------------------------------

#
# type (0~10)
#     (0)RAID0: 	raid0 only
#     (1)RAID1: 	raid1 only
#     (2)INDEP: 	independent only
#     (3)LINEAR: 	linear only 	(default)
#     (4)RAID1_RAID0: 	raid1 + RAID0
#     //(5)RAID1_INDEP: 	raid1 + independent
#     (6)RAID1_LINEAR: 	raid1 + linear
#     //(7)RAID0_INDEP: 	raid0 + independent
#     //(8)RAID0_LINEAR: 	raid0 + linear
#     //(9)RAID1_RAID0_INDEP: raid1 + RAID0 + indpendent
#     //(10)RAID1_RAID0_LINEAR: raid1 + RAID0 + linear
#
# $1: format type (0~2) for linear or independent area only
#     (0)EXT2
#     (1)EXT3 (default)
#     (2)XFS
#
# $2: raid1 size [G] for only raid1 area
#     RAID_SIZE=0G (default)
#
# $3: linear size [G] 
#     LINEAR_SIZE=NULL (default)
init2() {
  HDD_NO=0
  ROOT_SIZE=10
  
  DISK_A="/dev/sda"
  DISK_B="/dev/sdb"
  PART_INFO=$EMPTY_PART_INFO
  
  if [ -e /sys/block/sda ]; then
    SDA_SEC_SIZE=$(cat /sys/block/sda/size)
    SDA_REMOV=$(cat /sys/block/sda/removable)
  
    if [ $SDA_REMOV = 0 ]; then
      HDD_NO=1
    fi
  fi
  
  if [ -e /sys/block/sdb ]; then
    SDB_SEC_SIZE=$(cat /sys/block/sdb/size)
    SDB_REMOV=$(cat /sys/block/sdb/removable)
  
    if [ $SDB_REMOV = 0 ]; then
      HDD_NO=2
    fi
  fi
  
  if [ $HDD_NO = 0 ]; then
    echo "no disk"
    exit 1
  fi
  
  if [ ! -z "$SDB_SEC_SIZE" ]; then
    if [ $SDA_SEC_SIZE = $SDB_SEC_SIZE ]; then
      echo "SDA = SDB"
      MIN_SEC_SIZE=$SDA_SEC_SIZE
    elif [ $SDA_SEC_SIZE -gt $SDB_SEC_SIZE ]; then
      echo "SDA > SDB" 
      MIN_SEC_SIZE=$SDB_SEC_SIZE
    else
      echo "SDB > SDA"
      MIN_SEC_SIZE=$SDA_SEC_SIZE
    fi
  fi
  
  let 'MIN_SIZE = (MIN_SEC_SIZE*512)/(1000*1000*1000)' 
  #let 'RAID_SIZE = (MIN_SIZE-ROOT_SIZE)/2' ;half size
  
  LINEAR_SIZE_G=
  if [ -z $2 ]; then 
    RAID_SIZE=0
  else
    RAID_SIZE=$2
    if [ ! -z $3 ]; then
      LINEAR_SIZE=$3
      LINEAR_SIZE_G="+"${3}G
    fi
  fi
  
  MIN_SIZE_G="+"${MIN_SIZE}G
  ROOT_SIZE_G="+"${ROOT_SIZE}G
  RAID_SIZE_G="+"${RAID_SIZE}G
  
  if [ $RAID_SIZE = 0 ]; then
    RAID_NO=2
  else  
    RAID_NO=3
  fi
  
  # 1) linear mode
  if [ $RAID_SIZE_G = "+0G" ]; then 
    MD2_LEVEL=linear
  # 2) raid
  else 
    MD2_LEVEL=raid1
    if [ $LINEAR_SIZE_G = "+0G" ]; then
      MD3_LEVEL=
    else 
      MD3_LEVEL=linear
    fi
  fi
  
  if [ $HDD_NO = 1 ]; then 
    echo "1 hard disk"
    delpart $DISK_A $PART_INFO 
   
    cd /tmpfs
    makepart $DISK_A $ROOT_SIZE_G $SWAP_SIZE_G $RAID_SIZE_G $LINEAR_SIZE_G
    fdisk -l
    read
  
    formatpart $RAID_NO $FS_TYPE1 $FS_TYPE2 $DEV_NAME
    mountraid $RAID_NO
    df
    read
  
    copyrootfs 
  
   exit 1
  fi
  
  if [ $HDD_NO = 2 ]; then
    SWAP_SIZE_G=+128M
    
    #dd if=/dev/zero of=$DISK_A count=1
    #dd if=/dev/zero of=$DISK_B count=1
    umount -f /mnt/disk/disk1
    umount -f /mnt/disk/disk2
    umount -f /mnt/disk/raid
    umount -f /mnt/disk/linear
  
    mdadm --stop /dev/md1
    mdadm --stop /dev/md2
    mdadm --stop /dev/md3
  
    delpart $DISK_A  $EMPTY_PART_INFO
    delpart $DISK_B  $EMPTY_PART_INFO
    fdisk -l
  
    cd /tmpfs
    makepart $DISK_A $ROOT_SIZE_G $SWAP_SIZE_G $RAID_SIZE_G $LINEAR_SIZE_G
    makepart $DISK_B $ROOT_SIZE_G $SWAP_SIZE_G $RAID_SIZE_G $LINEAR_SIZE_G
    fdisk -l
  
    makeraid $DISK_A $DISK_B $RAID_NO $MD2_LEVEL $MD3_LEVEL
    mdadm --detail --scan
    cd /
  
    formatpart $RAID_NO $FS_TYPE1 $FS_TYPE2 $DEV_NAME
    mountraid $RAID_NO
    df
  
    copyrootfs 
  
    #chroot /mnt/rootfs a.out    
  fi
}

#-------------------------------------------------------------------------------

# 
# $1: RAID_SIZE_M
#     +0M: 
#
# $2: SET_TYPE
#     (0)RAID0:         raid0 only
#     (1)RAID1:         raid1 only
#     (2)LINEAR:        linear only     (default)
#     (3)RAID1_LINEAR:  raid1 + linear
#     (4)RAID1_RAID0:   raid1 + RAID0
#     (5)INDEP:         independent only
#
# $3: FS_TYPE ( format type )
#     EXT2: raid or linear or independent
#     EXT3: raid or linear or independent
#     XFS: linear or independent
#
# $4: DEV_NAME ( device name )
#     DISK_A: /dev/sda (default)
#     DISK_B: /dev/sdb
#
#
formatpart() {
  local FUNC="FORMAT_PART"
  log_func $FUNC $@

  FS_TYPE="ext3"
  if [ ! -z $3 ]; then 
    FS_TYPE=$3
  fi
  
  DEV_MD1="/dev/md1"
  DEV_MD2="/dev/md2"
  DEV_MD3="/dev/md3"
  
  DISK_A="/dev/sda"
  DISK_B="/dev/sdb"
  
  DEV_NAME=$4
  
  # 1 HDD (rootfs, swap, indep) 
  if [ "$DEV_NAME" = "$DISK_A" ]; then
    # no rootfs 
    if [ $1 = "+0M" ]; then
      # mkfs.$FS_TYPE ${DEV_NAME}3
      mkfs_progress $FS_TYPE ${DEV_NAME}3 "Format $(basename ${DEV_NAME}3)"
    # rootfs
    else
      # mkfs.ext3 ${DEV_NAME}1
      mkfs_progress $FS_TYPE ${DEV_NAME}1 "Format $(basename ${DEV_NAME}1)"

      mkswap ${DEV_NAME}2

      # mkfs.$FS_TYPE ${DEV_NAME}3
      mkfs_progress $FS_TYPE ${DEV_NAME}3 "Format $(basename ${DEV_NAME}3)"
    fi
  
  # 2 HDD (1 HDD addition (indep), rootfs, swap, raid1/raid0 + raid0/linear/indep)
  # 1) HDD addition
  elif [ "$DEV_NAME" = "$DISK_B" ]; then
    # mkfs.$FS_TYPE ${DEV_NAME}1  
    mkfs_progress $FS_TYPE ${DEV_NAME}1 "Format $(basename ${DEV_NAME}1)"
  
  # raid1 rootfs and swap
  else
    if [ $1 = "+0M" ]; then
      echo "no touch rootfs"
    else
      # mkfs.ext3 $DEV_MD1
      mkfs_progress ext3 $DEV_MD1 "Format $(basename rootfs)"
      mkswap ${DISK_A}2
      mkswap ${DISK_B}2
    fi
  # 2) 1 raid: raid1 rootfs and indep
    if [ $2 = 5 ]; then
      # mkfs.$FS_TYPE ${DISK_A}3
      # mkfs.$FS_TYPE ${DISK_B}3
      mkfs_progress $FS_TYPE ${DISK_A}3 "Format $(basename ${DISK_A}3)"
      mkfs_progress $FS_TYPE ${DISK_B}3 "Format $(basename ${DISK_B}3)"
  
  # 3) 2 raid: raid1 rootfs and raid1 or raid0 
    elif [ $2 = 2 ]; then
      # mkfs.$FS_TYPE $DEV_MD2
      mkfs_progress $FS_TYPE $DEV_MD2 "Format $(basename $DEV_MD2)"
  # 3-1) raid: raid1 rootfs and linear
    else 
      # mkfs.ext3 $DEV_MD2
      mkfs_progress ext3 $DEV_MD2 "Format $(basename $DEV_MD2)"
  
  # 4) 3 raid: raid1 rootfs and raid1 and raid0/linear
      if [ $2 = 3 ]; then
        # mkfs.$FS_TYPE $DEV_MD3
        mkfs_progress $FS_TYPE $DEV_MD3 "Format $(basename $DEV_MD3)"
      elif [ $2 = 4 ]; then
        # mkfs.ext3 $DEV_MD3
        mkfs_progress ext3 $DEV_MD3 "Format $(basename $DEV_MD3)"
      fi
    fi
  fi
  
  log_func_result $FUNC $?
}

#-------------------------------------------------------------------------------

DEV_MD1=/dev/md1
DEV_MD2=/dev/md2
DEV_MD3=/dev/md3
DEV_MD4=/dev/md4
DEV_MD5=/dev/md5
MNT_DISK=/mnt/disk
MNT_VOL1="/mnt/disk/volume1"
MNT_VOL2="/mnt/disk/volume2"

#
#  New HDD setup script by J. Cho April. 13. 2009
#
#
#
SCSI_LIST_DIR="/var/run"
SCSI_LIST_FILE=${SCSI_LIST_DIR}"/scsi_list"

SYS_GetDiskInfoAndSaveScsi()
{
  echo [`date`]"SYS_GetDiskInfoAndSaveScsi($*)"
  rm -f ${SCSI_LIST_FILE}
  find /sys/block/ -name device |xargs ls -l |awk '{print $9 $11}'|sort -t'/' -k8,9 |awk -F'/' '{
    addr = $(NF-2)
    if(match($(NF-2),"host0")){
      gsub(/host0/, "ESATA",$(NF-2));
    }
    if(match($(NF-6),"usb1")){
      gsub(/^[0-9]*:/, "",$NF);
      gsub(/0/, "",$NF);
      gsub(/:/, "",$NF);
      if($NF != "")
      $NF = "_"$NF;
      gsub(/1-1./, "USB",$(NF-3));
      gsub(/:1.0/, "",$(NF-3));
      #gsub(/\./, "-",$(NF-3));
      gsub(/USB2/, "MemCard",$(NF-3));
      print $4" "$(NF-3)$NF" "addr$NF
    }else if(match($(NF-2),"host1")){
      gsub(/target1:0:0/,"DISK1",$(NF-1));
      gsub(/target1:0:1/,"DISK2",$(NF-1));
      gsub(/target1:0:2/,"CDROM",$(NF-1));
      print $4" "$(NF-1)" "addr
    }else{
      print $4" "$(NF-2)" "addr
    }
  }' > ${SCSI_LIST_FILE}
}

VOL_RaidWaitSync()
{
  while :
  do
    echo "wait for finish resync.."
    sleep 5
    grep faulty /proc/mdstat > /dev/null 2>&1
    sRetFaulty=$?

    if [ "$sRetFaulty" = 0 ] ; then
      echo "found faulty"
    fi
    grep "]  resync =" /proc/mdstat > /dev/null 2>&1
    sRet1=$?
    grep "]  recovery =" /proc/mdstat > /dev/null 2>&1
    sRet2=$?
    if [ "$sRet1" != 0 ] && [ "$sRet2" != 0 ] ; then
      return
    fi
  done
}

grow_raid()
{
  VOL_RaidWaitSync
  lcd_msg "grow raid..."
  mdadm --grow ${DEV_MD2} --verbose --size=max
  VOL_RaidWaitSync
  lcd_msg "resize FS..."
  resize2fs -F -p ${DEV_MD2}
  if [ $? != 0 ]; then
    resize2fs -F -p ${DEV_MD2}
  fi
  #e2fsck -f ${DEV_MD2}
  lcd_menu $LCD_MENU_MAIN
}

check_make_raid()
{
  SET_TYPE=$1

  MD2=`cat /proc/mdstat |grep md2`	
  MD3=`cat /proc/mdstat |grep md3`

  if [ "$MD2" != "" ]; then 
    echo "md2 is alive"
    if [ $SET_TYPE = 3 ] || [ $SET_TYPE = 5 ]; then
      if [ "$MD3" != "" ]; then
        echo "md3 is alive"
        return 0
      else 
        echo "md3 is not alive"
        return 1
      fi
    else 
      echo "OK"
      return 0
    fi
  else 
    echo "md2 is not alive"
    return 1
  fi
}

# 
# $1: SET_TYPE
# $2: FS_TYPE
# $3: DISK1
# $4: DISK2
#
writefstab()
{
  SET_TYPE=$1
  FS_TYPE=$2
  DEV_SWAP1=$3
  DEV_SWAP2=$4
  ADD_DISK=$5

  Disk1=`cat $SCSI_LIST_FILE |grep DISK1 |cut -d' ' -f1`
  Disk2=`cat $SCSI_LIST_FILE |grep DISK2 |cut -d' ' -f1`

  echo "$FSTAB_DISK_START_COMMENT $CHG_TYPE"

  echo "$DEV_SWAP1  swap  swap  pri=42  0  0"
  if [ ! -z ${DEV_SWAP2} ]; then
    echo "$DEV_SWAP2  swap 	swap  defaults 	0  0"
  fi

  if [ $SET_TYPE = 0 ]; then
    echo "$DEV_MD2	${MNT_DISK}/volume1	$FS_TYPE	defaults,noatime 	0	2"
  elif [ $SET_TYPE = 1 ]; then
    echo "$DEV_MD2	${MNT_DISK}/volume1	ext3	defaults,noatime 	0	2"
  elif [ $SET_TYPE = 2 ]; then
    echo "$DEV_MD2	${MNT_DISK}/volume1 $FS_TYPE	defaults,noatime 	0	2"
  elif [ $SET_TYPE = 3 ]; then
    echo "$DEV_MD3	${MNT_DISK}/volume1	$FS_TYPE	defaults,noatime 	0	2"
    echo "$DEV_MD2	${MNT_DISK}/volume2	ext3	defaults,noatime 	0	0"
  elif [ $SET_TYPE = 4 ]; then
    echo "$DEV_MD3	${MNT_DISK}/volume1	$FS_TYPE	defaults,noatime 	0	2"
    echo "$DEV_MD2	${MNT_DISK}/volume2	ext3	defaults,noatime 	0	2"
  elif [ $SET_TYPE = 5 ]; then
    if [ $DEV_SWAP1 = $DEV_MD4 ]; then
      echo "$DEV_MD2	${MNT_DISK}/volume1	$FS_TYPE	defaults,noatime 	0	2"
	elif [ $DEV_SWAP1 = $DEV_MD5 ]; then
      echo "$DEV_MD3	${MNT_DISK}/volume2	$FS_TYPE	defaults,noatime 	0	2"
	fi
	if [ ! -z ${DEV_SWAP2} ]; then
      echo "$DEV_MD3    ${MNT_DISK}/volume2       $FS_TYPE        defaults,noatime        0       2"
	fi
  elif [ $SET_TYPE = 6 ]; then
    if [ $ADD_DISK = ${Disk1} ]; then
	  echo "$DEV_MD3      ${MNT_DISK}/volume2       $FS_TYPE        defaults,noatime        0       2"
	  echo "$DEV_MD2      ${MNT_DISK}/volume1       $FS_TYPE        defaults,noatime        0       2"
	else
	  echo "$DEV_MD2      ${MNT_DISK}/volume1       $FS_TYPE        defaults,noatime        0       2"
	  echo "$DEV_MD3      ${MNT_DISK}/volume2       $FS_TYPE        defaults,noatime        0       2"
	fi
  fi 
  
  echo "$FSTAB_DISK_END_COMMENT $CHG_TYPE"
}

#
# $1: SET_TYPE
# $2: FS_TYPE
# $3: CHG_TYPE
#   0) init: initialize HDD
#   1) change: change volume
# $4: DISK1 (include /dev/)
# $5: DISK2
#
addfstab() {
  local FUNC="ADD_FSTAB"
  log_func $FUNC $@

  LOG_FILE="/var/www/system/volume.log"
  CHG_TYPE=$1
  SET_TYPE=$2
  FS_TYPE=$3
  DEV_SWAP1=$4
  DEV_SWAP2=$5
  ADD_DISK=$6

  if [ $CHG_TYPE = change ]; then
    sed -i "/$FSTAB_DISK_START_COMMENT/,/$FSTAB_DISK_END_COMMENT/d" $FSTAB
    writefstab $SET_TYPE $FS_TYPE $DEV_SWAP1 $DEV_SWAP2 $ADD_DISK >> $FSTAB
  elif [ $CHG_TYPE = remove ]; then
    sed -i "/$FSTAB_DISK_START_COMMENT/,/$FSTAB_DISK_END_COMMENT/d" $FSTAB
  fi

  cp /etc/fstab /var/www/system/fstab
  cp $SCSI_LIST_FILE /var/www/system
  echo "<date>" >> $LOG_FILE
  date >> $LOG_FILE
  echo "<fdisk -l>" >> $LOG_FILE
  fdisk -l >> $LOG_FILE
  echo "</proc/mdstat>" >> $LOG_FILE
  cat /proc/mdstat >> $LOG_FILE
  echo "<df>" >> $LOG_FILE
  df >> $LOG_FILE


  log_func_result $FUNC $OK
}

#-------------------------------------------------------------------------------


initialize()
{
  local FUNC="INIT_DISK"

  log_func $FUNC $@

  umount -f /mnt/disk/volume1
  umount -f /mnt/disk/volume2

  mdadm --stop /dev/md2
  mdadm --stop /dev/md3

  mdadm --zero-superblock --force /dev/${1}3       
  echo -e 'd\n 3\n d\n 4\n n\n p\n 3\n \n \n t\n 3\n fd\n w\n' |fdisk /dev/$1
  partprobe /dev/$1
  cat /proc/mdstat | grep md1 | awk '{ print $5 }' | grep $1
  RST1=$?
  if [ -e $2 ]; then                               
    mdadm --zero-superblock --force /dev/${2}3
    echo -e 'd\n 3\n d\n 4\n n\n p\n 3\n \n \n t\n 3\n fd\n w\n' |fdisk /dev/$2
    partprobe /dev/$2
    cat /proc/mdstat | grep md1 | awk '{ print $5 }' | grep $2
    RST2=$?
  fi                                           

  if [ "$RST1" = "0" ]; then
    mdadm --add /dev/md1 /dev/${2}1
  elif [ "$RST2" = "0" ]; then
    mdadm --add /dev/md1 /dev/${1}1
  fi

  addfstab remove 

  lcd_icon $ICON_HDD off
  lcd_msg_time $MSG_HDD_INIT_END
  log_func_result $FUNC $OK
}


#
# $1: SET_TYPE (0~5)
#     (0)RAID0: 	raid0 only
#     (1)RAID1: 	raid1 only
#     (2)LINEAR: 	linear only 	(default)
#     (3)RAID1_LINEAR: 	raid1 + linear
#     (4)RAID1_RAID0: 	raid1 + RAID0
#     (5)INDEP: 	independent only
#     //(6)RAID1_INDEP: 	raid1 + independent
#     //(7)RAID0_INDEP: 	raid0 + independent
#     //(8)RAID0_LINEAR: 	raid0 + linear
#     //(9)RAID1_RAID0_INDEP: raid1 + RAID0 + indpendent
#     //(10)RAID1_RAID0_LINEAR: raid1 + RAID0 + linear
#
# $2: format type (0~2) for linear or independent area only
#     (0)EXT2
#     (1)EXT3 (default)
#     (2)XFS
#
# $3: raid1 size [M] for only raid1 area
#     RAID_SIZE=0M (default)
#
# $4: linear size [M] 
#     LINEAR_SIZE=NULL (default)
#
# RETURN TYPE (1~4) //Juny
#     (1)SETUP OK
#     (2)HDD Fail
#     (3)SET_TYPE parameters wrong
#     (4)No MD2 LEVEL

hddsetup() {
  local FUNC="HDD_SETUP"
 
  CFG_TYPE=$1
  FORMAT_TYPE=$2
  RAID_SIZE=$3
  LINEAR_SIZE=$4

  log_func $FUNC $@
  lcd_msg $MSG_HDD_INIT_START
  nas-common button lock

  SYS_GetDiskInfoAndSaveScsi
  Disk1=`cat $SCSI_LIST_FILE |grep DISK1 |cut -d' ' -f1`
  Disk2=`cat $SCSI_LIST_FILE |grep DISK2 |cut -d' ' -f1`

  if [ -z ${Disk2} ]; then
    if [ -z ${Disk1} ]; then
      log_func_result $FUNC $ERROR "no disk"
      lcd_msg_time $MSG_HDD_INIT_FAIL
      return 2 #HDD fail
    else
      echo [`date`]" --> 1 HDD: " ${Disk1} 
    fi
  elif [ -z ${Disk1} ]; then
    #Disk1=$Disk2
    #Disk2=
    echo [`date`]" --> 1 HDD: " ${Disk2}
  else
    echo [`date`]" --> 2 HDDs: " ${Disk1} ${Disk2}
  fi

  lcd_icon $ICON_HDD blink

  if [ $CFG_TYPE = raid0 ]; then
    SET_TYPE=0
    MD2_LEVEL=raid0
    RAID_SIZE=0
    if [ $LINEAR_SIZE = 0 ]; then
      LINEAR_SIZE=
    else
      let 'LINEAR_SIZE = LINEAR_SIZE/2'
    fi
  elif [ $CFG_TYPE = raid1 ]; then
    SET_TYPE=1
    MD2_LEVEL=raid1
    LINEAR_SIZE=0
    if [ $RAID_SIZE = 0 ]; then
      RAID_SIZE=
    fi
  elif [ $CFG_TYPE = linear ]; then
    SET_TYPE=2
    MD2_LEVEL=linear
    RAID_SIZE=0
    if [ $LINEAR_SIZE = 0 ]; then
      LINEAR_SIZE=
    else
      let 'LINEAR_SIZE = LINEAR_SIZE/2'
    fi
  elif [ $CFG_TYPE = raidlinear ]; then
    SET_TYPE=3
    MD2_LEVEL=raid1
    MD3_LEVEL=linear
    if [ $LINEAR_SIZE = 0 ]; then
      LINEAR_SIZE=
    else
      let 'LINEAR_SIZE = LINEAR_SIZE/2'
    fi
  elif [ $CFG_TYPE = raid01 ]; then
    SET_TYPE=4
    MD2_LEVEL=raid1
    MD3_LEVEL=raid0
    if [ $LINEAR_SIZE = 0 ]; then
      LINEAR_SIZE=
    else
      let 'LINEAR_SIZE = LINEAR_SIZE/2'
    fi
  elif [ $CFG_TYPE = individual ]; then
    SET_TYPE=5
    MD2_LEVEL=linear
    RAID_SIZE=
    LINEAR_SIZE=
    if [ ! -z Disk2 ]; then
      MD3_LEVEL=linear
    fi
  elif [ $CFG_TYPE = addhdd ]; then
    SET_TYPE=6
    MD3_LEVEL=linear
    FORMAT=$RAID_SIZE
    VOL_TYPE=$LINEAR_SIZE
    lcd_msg "Add HDD..."
  elif [ $CFG_TYPE = removehdd ]; then
    SET_TYPE=5
    REMOVE_DISK=$RAID_SIZE
    VOL_TYPE=$LINEAR_SIZE
    lcd_msg "Remove HDD..."
  elif [ $CFG_TYPE = init ]; then
    echo " CFG_TYPE = init"
    initialize ${Disk1} ${Disk2}
    return 1
  else
    echo "error: SET_TYPE parameter is wrong"
    return 3 #set_type parameter
  fi

  ZERO_SIZE_M="+0M"
  if [ -z $RAID_SIZE ]; then
    RAID_SIZE_M=
  else
    RAID_SIZE_M="+"${RAID_SIZE}M
  fi
  if [ -z $LINEAR_SIZE ]; then
    LINEAR_SIZE_M=
  else
    LINEAR_SIZE_M="+"${LINEAR_SIZE}M
  fi
  FS_TYPE=ext3
  if [ ! -z $2 ]; then
    FS_TYPE=$2
  fi

  log_func $FUNC $1 $2 $3 $4 $SET_TYPE $RAID_SIZE_M $LINEAR_SIZE_M \
    $MD2_LEVEL $MD3_LEVEL ${Disk1} ${Disk2}

# 1 HDD
  if [ "$Disk1" = "" ] || [ "$Disk2" = "" ]; then
    if [ "$Disk2" = "" ]; then
      # Disk1 is active
      DATA_MD=/dev/md2
      ACT_DISK=/dev/$Disk1
      SWAP_MD=/dev/md4
      MNT_VOL=$MNT_VOL1
      FORMAT_TXT="Format HDD1"
    else
      # Disk2 is active
      DATA_MD=/dev/md3
      ACT_DISK=/dev/$Disk2
      SWAP_MD=/dev/md5
      MNT_VOL=$MNT_VOL2
      FORMAT_TXT="Format HDD2"
    fi

    log_func $FUNC "1 hard disk" $DATA_MD $ACT_DISK $SWAP_MD $MNT_VOL

    # 0. stop services & unmount devices
    nas-service control all stop
    /etc/init.d/crond stop
    cd /
    umount -f ${MNT_VOL1}
    umount -f ${MNT_VOL2}
    swapoff ${SWAP_MD}
    
    mdadm -S /dev/md2
    mdadm -S /dev/md3

    rm -rf ${MNT_VOL}

    # 1. delete & make user data partition
    mdadm --zero-superblock --force ${ACT_DISK}3
    echo -e 'd\n 3\n d\n 4\n n\n p\n 3\n \n \n t\n 3\n fd\n w\n' |fdisk ${ACT_DISK}
    partprobe ${ACT_DISK}

    # 2. make /dev/md2
    sleep 1
    echo -e 'yes\n'| mdadm -C -f ${DATA_MD} -llinear -n1 ${ACT_DISK}3
    mkswap ${SWAP_MD}
    swapon ${SWAP_MD}
 
    # 3. format user data partition
    mkdir -p ${MNT_VOL}
    mkfs_progress $FS_TYPE $DATA_MD $FORMAT_TXT
    addfstab change $SET_TYPE $FS_TYPE ${SWAP_MD}
    mount -a
    df
    
    # STEP 7: regenerate link and share
    rm -rf /mnt/disk/*
    #rm -f $DEFAULT_VOL_DIR
    ln -s $(nas-storage get vol_default) $DEFAULT_VOL_DIR

    nas-share del_all_folder
    nas-share gen_default_folder
    nas-share gen_folder_link
    nas-share gen_conf
    sync

    # STEP 8: start services
    nas-service control all start
    /etc/init.d/crond start
    nas-system sleep start

  elif [ $CFG_TYPE = addhdd ]; then
    log_func $FUNC "add hard disk"

    # 0. stop services & unmount devices
    nas-service control all stop
    /etc/init.d/crond stop

    cd /
    if [ $VOL_TYPE = "RAID1" ]; then
      MD2_DISK=`cat /proc/mdstat |grep md2| grep ${Disk1}`
      if [ "$MD2_DISK" = "" ]; then
	  # HDD2 is active #
        ADD_DISK=${Disk1}
        DATA_MD=${DEV_MD2}
        SWAP_MD=${DEV_MD4}
      else
	  # HDD1 is active #
        ADD_DISK=${Disk2}
        DATA_MD=${DEV_MD2}
        SWAP_MD=${DEV_MD5}
      fi
      
      umount -f ${MNT_VOL1}

      # 1. delete & make user data partition
      mdadm --zero-superblock --force /dev/${ADD_DISK}1
      mdadm --zero-superblock --force /dev/${ADD_DISK}2
      mdadm --zero-superblock --force /dev/${ADD_DISK}3
      echo "VOL_MakePartition($*)"
      echo -e 'd\n 1\n d\n 2\n d\n 3\n d\n 4\n n\n p\n 1\n \n +1216\n n\n p\n 2\n \n +31\n n\n p\n 3\n \n \n t\n 1\n fd\n t\n 2\n fd\n t\n 3\n fd\n w\n'| fdisk /dev/${ADD_DISK}
      partprobe /dev/${ADD_DISK}
  
      # 2. make /dev/md2
      sleep 1
      mdadm -C -f ${SWAP_MD} -llinear -n1 /dev/${ADD_DISK}2 
      sleep 3
      
      mdadm /dev/md1  --add /dev/${ADD_DISK}1
      mdadm --add ${DATA_MD} /dev/${ADD_DISK}3
      sleep 10

      mount -a
      # Move to escape Unable to find Swap signature 
      partprobe
      mkswap ${SWAP_MD}
      swapon ${SWAP_MD}

    else # VOL_TYPE = INDIVIDUAL #
      MD2_DISK=`df |grep md2`
      if [ "$MD2_DISK" = "" ]; then 
        ADD_DISK=${Disk1}
        MNT_VOL=${MNT_VOL1}
        DATA_MD=${DEV_MD2}
        SWAP_MD=${DEV_MD4}
        FORMAT_TXT="Format HDD1"
      else
        ADD_DISK=${Disk2}
        MNT_VOL=${MNT_VOL2}
        DATA_MD=${DEV_MD3}
        SWAP_MD=${DEV_MD5}
        FORMAT_TXT="Format HDD2"
      fi 
      echo $ADD_DISK $MNT_VOL $DATA_MD $SWAP_MD $FORMAT $

      umount -f ${MNT_VOL}
      mdadm -S ${DATA_MD}
      swapoff ${SWAP_MD}
      mdadm -S ${SWAP_MD}
      rm -rf  ${MNT_VOL}

      # 1. delete & make user data partition
      if [ "$FORMAT" = "OFF" ]; then 
        echo "NC1 is about to mount the additional HDD"
        sleep 1
        mdadm /dev/md1  --add /dev/${ADD_DISK}1
        RST1=0
        echo -e 'yes\n'| mdadm -A -f ${DATA_MD} /dev/${ADD_DISK}3
        RST1=$?
        RST2=0
        echo -e 'yes\n'| mdadm -A -f ${SWAP_MD}  /dev/${ADD_DISK}2
        RST2=$?
        mkswap ${SWAP_MD}
        swapon ${SWAP_MD}


      else 
        echo "NC1 is about to delete all information in the additional HDD"
        mdadm --zero-superblock --force /dev/${ADD_DISK}1
        mdadm --zero-superblock --force /dev/${ADD_DISK}2
        mdadm --zero-superblock --force /dev/${ADD_DISK}3
        echo "VOL_MakePartition($*)"
        echo -e 'd\n 1\n d\n 2\n d\n 3\n d\n 4\n n\n p\n 1\n \n +1216\n n\n p\n 2\n \n +31\n n\n p\n 3\n \n \n t\n 1\n fd\n t\n 2\n fd\n t\n 3\n fd\n w\n'| fdisk /dev/${ADD_DISK}
        partprobe /dev/${ADD_DISK}
  
        # 2. make /dev/md3
        sleep 1
        mdadm /dev/md1  --add /dev/${ADD_DISK}1
        RST1=0
        echo -e 'yes\n'| mdadm -C -f ${DATA_MD} -llinear -n1 /dev/${ADD_DISK}3
        RST1=$?
        RST2=0
        echo -e 'yes\n'| mdadm -C -f ${SWAP_MD} -llinear -n1 /dev/${ADD_DISK}2
        RST2=$?
        mkfs_progress $FS_TYPE ${DATA_MD} $FORMAT_TXT 
        mkswap ${SWAP_MD}
        swapon ${SWAP_MD}

      fi

      echo $RST1 $RST2 $Disk2 $SET_TYPE ${DATA_MD} ${SWAP_MD} ${ADD_DISK} ${MNT_VOL}

      # 3. mount data partition
      mkdir -p ${MNT_VOL}
      addfstab change $SET_TYPE $FS_TYPE ${DEV_MD4} ${DEV_MD5} ${ADD_DISK}
      mount -a
      df

      # STEP 7: regenerate link and share
      nas-share add_volume ${MNT_VOL}
      sync
    fi

    # STEP 8: start services
    nas-service control all start
    /etc/init.d/crond start
    nas-system sleep start
    
  elif [ $CFG_TYPE = removehdd ]; then
    log_func $FUNC "remove hard disk"

    # 0. stop services & unmount devices
    nas-service control all stop
    /etc/init.d/crond stop
    cd /
    if [ $VOL_TYPE = "RAID1" ]; then
	  DATA_MD=${DEV_MD2}
      if [ $REMOVE_DISK = "HDD1" ]; then
        RM_DISK=${Disk1}
        SWAP_MD=${DEV_MD4}
      else
        RM_DISK=${Disk2}
        SWAP_MD=${DEV_MD5}
      fi
      mdadm -f ${DEV_MD1} /dev/${RM_DISK}1
      swapoff ${SWAP_MD}
      mdadm -S ${SWAP_MD}
      mdadm -f ${DATA_MD} /dev/${RM_DISK}3
      sleep 5
      mdadm -r ${DATA_MD} /dev/${RM_DISK}3
      sleep 5
      mdadm -r ${DEV_MD1} /dev/${RM_DISK}1
      mdadm --zero-superblock /dev/${RM_DISK}1
      mdadm --zero-superblock /dev/${RM_DISK}2
      mdadm --zero-superblock /dev/${RM_DISK}3
      tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${RM_DISK}1
      tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${RM_DISK}2
      tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${RM_DISK}3
																					 
    else # VOL_TYPE = INDIVIDUAL #
      if [ $REMOVE_DISK = "HDD1" ]; then
        mdadm -f ${DEV_MD1} /dev/${Disk1}1
        umount -f ${MNT_VOL1}
        mdadm -S ${DEV_MD2}
        swapoff ${DEV_MD4}
        mdadm -S ${DEV_MD4}
        sleep 1
        mdadm -r ${DEV_MD1} /dev/${Disk1}1
        #mdadm --zero-superblock /dev/${Disk1}1
        #mdadm --zero-superblock /dev/${Disk1}2
        #mdadm --zero-superblock /dev/${Disk1}3
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk1}1
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk1}2
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk1}3
        addfstab change $SET_TYPE $FS_TYPE ${DEV_MD5}
        rm -rf ${MNT_VOL1}
        REMOVE_VOL=${MNT_VOL1}
      else
        mdadm -f ${DEV_MD1} /dev/${Disk2}1
        umount -f ${MNT_VOL2}
        mdadm -S ${DEV_MD3}
        swapoff ${DEV_MD5}
        mdadm -S ${DEV_MD5}
        sleep 1
        mdadm -r ${DEV_MD1} /dev/${Disk2}1
        #mdadm --zero-superblock /dev/${Disk2}1
        #mdadm --zero-superblock /dev/${Disk2}2
        #mdadm --zero-superblock /dev/${Disk2}3
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk2}1
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk2}2
        #tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk2}3
        addfstab change $SET_TYPE $FS_TYPE ${DEV_MD4}
        rm -rf ${MNT_VOL2}
        REMOVE_VOL=${MNT_VOL2}
      fi

      # STEP 7: regenerate link and share
      nas-share remove_volume $REMOVE_VOL
      sync
    fi

    # STEP 8: start services
    nas-service control all start
    /etc/init.d/crond start
    nas-system sleep start

  else
    log_func $FUNC "2 hard disk"

    # STEP 1: stop service
    sync
    sync
    sync
    nas-service control all stop
    /etc/init.d/crond stop
    
    # STEP 2: unmount disk
    cd /
    umount -f ${MNT_VOL1}
    umount -f ${MNT_VOL2}

    mdadm -S /dev/md2
    mdadm -S /dev/md3

    SWAP_SIZE=`free | awk '/Swap/ {print $2}'`
    if [ "$SWAP_SIZE" = "0" ]; then
      mkswap $DEV_MD4
      mkswap $DEV_MD5
      swapon $DEV_MD4
      swapon $DEV_MD5
    fi

    rm -rf ${MNT_DISK}
    #rm -rf ${MNT_VOL1}
    #rm -rf ${MNT_VOL2}

    # STEP 3: delete && make partition
    mdadm --zero-superblock --force /dev/${Disk1}3
    mdadm --zero-superblock --force /dev/${Disk2}3
    mdadm --zero-superblock --force /dev/${Disk1}4
    mdadm --zero-superblock --force /dev/${Disk2}4

    tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk1}3
    tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk2}3
    tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk1}4
    tune2fs -U 00000000-0000-0000-0000-000000000000 /dev/${Disk2}4

    sleep 10

    if [ $SET_TYPE = 3 ] || [ $SET_TYPE = 4 ]; then
      if [ -z RAID_SIZE ]; then
        echo "SET_TYPE is "$SET_TYPE ". RAID_SIZE_M is required."
      else
       echo -e "d\n 3\n d\n 4\n n\n p\n 3\n \n ${RAID_SIZE_M}\n n\n p\n 4\n \n \n t\n 3\n fd\n t\n 4\n fd\n p\n w\n" |fdisk /dev/${Disk1}
       echo -e "d\n 3\n d\n 4\n n\n p\n 3\n \n ${RAID_SIZE_M}\n n\n p\n 4\n \n \n t\n 3\n fd\n t\n 4\n fd\n p\n w\n" |fdisk /dev/${Disk2}
      fi
    else
      echo -e 'd\n 3\n d\n 4\n n\n p\n 3\n \n \n t\n 3\n fd\n w\n' |fdisk /dev/${Disk1}
      echo -e 'd\n 3\n d\n 4\n n\n p\n 3\n \n \n t\n 3\n fd\n w\n' |fdisk /dev/${Disk2}
    fi
    sleep 10
    partprobe /dev/${Disk1}
    partprobe /dev/${Disk2}
    cat /proc/partitions | log_func_pipe $FUNC

    # STEP 4: make raid
    echo "Make Raid" > /dev/console
    if [ $SET_TYPE = 5 ]; then
      echo -e 'yes \n' | mdadm -C -f /dev/md2 -n1 -l${MD2_LEVEL} /dev/${Disk1}3
      echo -e 'yes \n' | mdadm -C -f /dev/md3 -n1 -l${MD3_LEVEL} /dev/${Disk2}3

      mkdir -p ${MNT_VOL1}
      mkdir -p ${MNT_VOL2}
    else
      echo -e 'yes \n' | mdadm -C -f /dev/md2 -n2 -l${MD2_LEVEL} /dev/${Disk1}3 /dev/${Disk2}3
	  
      mkdir -p ${MNT_VOL1}
      if [ $SET_TYPE = 3 ] || [ $SET_TYPE = 4 ]; then
        echo -e 'yes \n' | mdadm -C -f /dev/md3 -n2 -l${MD3_LEVEL} /dev/${Disk1}4 /dev/${Disk2}4

	    mkdir -p ${MNT_VOL2}
      fi
    fi

    check_make_raid $SET_TYPE | log_func_pipe $FUNC
	RST1=${PIPESTATUS[0]}
	echo $RST1 | log_func_pipe $FUNC
    if [ $RST1 != 0 ]; then
      echo "1st mdadm creation ERROR!!!" 
      sleep 30
      if [ $SET_TYPE = 5 ]; then
        echo -e 'yes \n' | mdadm -C -f /dev/md2 -n1 -l${MD2_LEVEL} /dev/${Disk1}3
        echo -e 'yes \n' | mdadm -C -f /dev/md3 -n1 -l${MD3_LEVEL} /dev/${Disk2}3
      else
        echo -e 'yes \n' | mdadm -C -f /dev/md2 -n2 -l${MD2_LEVEL} /dev/${Disk1}3 /dev/${Disk2}3
        if [ $SET_TYPE = 3 ] || [ $SET_TYPE = 4 ]; then
          echo -e 'yes \n' | mdadm -C -f /dev/md3 -n2 -l${MD3_LEVEL} /dev/${Disk1}4 /dev/${Disk2}4
        fi
      fi
      check_make_raid $SET_TYPE | log_func_pipe $FUNC
      RST1=${PIPESTATUS[0]}
	  echo $RST1 | log_func_pipe $FUNC
      if [ $RST1 != 0 ]; then
        echo "2nd mdadm creation ERROR!!!" 
        sleep 300
        if [ $SET_TYPE = 5 ]; then
          echo -e 'yes \n' | mdadm -C -f /dev/md2 -n1 -l${MD2_LEVEL} /dev/${Disk1}3
          echo -e 'yes \n' | mdadm -C -f /dev/md3 -n1 -l${MD3_LEVEL} /dev/${Disk2}3
        else 
          echo -e 'yes \n' | mdadm -C -f /dev/md2 -n2 -l${MD2_LEVEL} /dev/${Disk1}3 /dev/${Disk2}3
          if [ $SET_TYPE = 3 ] || [ $SET_TYPE = 4 ]; then
            echo -e 'yes \n' | mdadm -C -f /dev/md3 -n2 -l${MD3_LEVEL} /dev/${Disk1}4 /dev/${Disk2}4
          fi
        fi
        check_make_raid $SET_TYPE | log_func_pipe $FUNC
        RST1=${PIPESTATUS[0]}
        if [ $RST1 != 0 ]; then
          echo "3nd mdadm creation ERROR!!!" 
          return 2
        fi
      fi
    fi

    # STEP 5: format partition
    if [ $SET_TYPE = 5 ]; then
		DISK_TYPE1=HDD1
		DISK_TYPE2=HDD2
	else
		DISK_TYPE1=${MD2_LEVEL}
		DISK_TYPE2=${MD3_LEVEL}
		if [ ${DISK_TYPE1} = linear ]; then
			DISK_TYPE1=JBOD
		fi
		if [ ${DISK_TYPE2} = linear ]; then
			DISK_TYPE2=JBOD
		fi
	fi

    mkfs_progress $FS_TYPE /dev/md2 "Format ${DISK_TYPE1}"
    if [ $SET_TYPE -ge 3 ]; then
      mkfs_progress $FS_TYPE /dev/md3 "Format ${DISK_TYPE2}"
    fi
  
    # STEP 6: mount disk
    addfstab change $SET_TYPE $FS_TYPE /dev/md4 /dev/md5
    mount -a

    # STEP 7: regenerate link and share
    rm -f $DEFAULT_VOL_DIR
    ln -s $(nas-storage get vol_default) $DEFAULT_VOL_DIR

    nas-share del_all_folder
    nas-share gen_default_folder
    nas-share gen_folder_link
    nas-share gen_conf
    sync

    # STEP 8: start services
    nas-service control all start
    /etc/init.d/crond start
    nas-system sleep start

  fi

  lcd_icon $ICON_HDD on
  lcd_msg_time $MSG_HDD_INIT_END

  log_func_result $FUNC $OK
  nas-common button unlock
  return 1
}

#
#
# check_md1 : when md1 is fault, readd device

check_md1() {
  local FUNC="CHK_MD1"
  log_func $FUNC

  RECOVERY=$1

  SYS_GetDiskInfoAndSaveScsi
  Disk1=`cat $SCSI_LIST_FILE |grep DISK1 |cut -d' ' -f1`
  Disk2=`cat $SCSI_LIST_FILE |grep DISK2 |cut -d' ' -f1`

  fault1=`cat /proc/mdstat |grep -A2 md1 |grep U_`
  RST1=$?
  fault2=`cat /proc/mdstat |grep -A2 md1 |grep _U`
  RST2=$?
  if [[ $RST1 = 0 || $RST2 = 0 ]]; then
      DISK1_UUID=`tune2fs -l /dev/${Disk1}1 |grep UUID | awk '{ print $3}'`
      DISK2_UUID=`tune2fs -l /dev/${Disk2}1 |grep UUID | awk '{ print $3}'`
      if [ "$DISK1_UUID" = "$DISK2_UUID" ]; then
      echo "same UUID"
    else
      echo "different UUID"
      log_func_result $FUNC $OK
      return 1
    fi
  else
    echo "same UUID" $RST1 $RST2 [$RECOVERY]
    log_func_result $FUNC $OK
    return 0
  fi

  if [ "$RECOVERY" = "OFF" ]; then
    return 0
  else
    fault1_dev=`cat /proc/mdstat |grep -A2 md1 |grep "$Disk1"`
    RST1_dev=$?
    fault2_dev=`cat /proc/mdstat |grep -A2 md1 |grep "$Disk2"`
    RST2_dev=$?

    if [ $RST1_dev = 0 ]; then
      mdadm --add /dev/md1 /dev/${Disk2}1
    elif [ $RST2_dev = 0 ]; then
      mdadm --add /dev/md1 /dev/${Disk1}1
    else
      echo "md1 is fault, but cannot search that device"
    fi

    return 0
  fi
}

