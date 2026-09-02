#!/bin/bash

#===============================================================================
# share.sh 
#===============================================================================

LIBDIR=$PREFIX/usr/lib/nas

. $LIBDIR/common.sh

DBFILE=$PREFIX/etc/nas/db/share.db
FSTAB=$PREFIX/etc/fstab

SAMBA_CONF=$PREFIX/etc/samba/smb.conf
SAMBA_CONF_DEFAULT=$PREFIX/etc/nas/default/smb.conf
SAMBA_CONF_PRINTER=$PREFIX/etc/nas/default/smb_printer.conf

KERB_CONF=$PREFIX/etc/krb5.conf

FTP_CONF=$PREFIX/etc/proftpd/proftpd.conf
FTP_CONF_DEFAULT=$PREFIX/etc/nas/default/proftpd.conf

AFP_CONF=$PREFIX/etc/netatalk/AppleVolumes.default
AFP_CONF_DEFAULT=$PREFIX/etc/nas/default/AppleVolumes.default

ITUNES_CONF=$PREFIX/etc/mt-daapd.conf
ITUNES_CONF_DEFAULT=$PREFIX/etc/nas/default/mt-daapd.conf

TORRENT_CONF=$PREFIX/etc/transmission/settings.json
TORRENT_CONF_DEFAULT=$PREFIX/etc/nas/default/torrent-settings.json

DLNA_CONF=$PREFIX/etc/dlna.conf
DLNA_CONF_DEFAULT=$PREFIX/etc/nas/default/dlna.conf

SHARE_START_COMMENT="### BEGIN FOLDER INFO"
SHARE_END_COMMENT="### END FOLDER INFO"

USB_SHARE_START_COMMENT="### BEGIN USB INFO"
USB_SHARE_END_COMMENT="### END USB INFO"

CDROM_SHARE_START_COMMENT="### BEGIN CD-ROM INFO"
CDROM_SHARE_END_COMMENT="### END CD-ROM INFO"

VOLUME_START_COMMENT="### BEGIN VOLUME INFO"
VOLUME_END_COMMENT="### END VOLUME INFO"

AD_START_COMMENT="### BEGIN ACTIVE DIRECTORY CONF"
AD_END_COMMENT="### END ACTIVE DIRECTORY CONF"

TIMEMACHINE_START_COMMENT="### BEGIN TIMEMACHINE INFO"
TIMEMACHINE_END_COMMENT="### END TIMEMACHINE INFO"

ISCSI_CONF=$PREFIX/etc/iscsi-scstd.conf
ISCSI_CONF_DEFAULT=$PREFIX/etc/nas/default/iscsi-scstd.conf

#-------------------------------------------------------------------------------
# users
#-------------------------------------------------------------------------------

WEBDAV_USER_CONF=/etc/lighttpd/lighttpd.user.htdigest

#
# $1: 
#
init_user() {
  local FUNC="INIT_USER"

  log_func $FUNC $@

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: username
#
check_user() {
  local FUNC="CHECK_USER"

  grep -e "^$1:" /etc/passwd >/dev/null 2>&1
  if [ "$?" = "0" ]; then
    echo "fail"
  else
    echo "ok"
  fi
}

#
# $1: username
# $2: password 
#
add_user() {
  local FUNC="ADD_USER"

  log_func $FUNC $1 $(echo $2 | md5sum | awk '{ print $1 }')

  # add system user
  # adduser -H -D $1			
  useradd -G users $1
  echo -e "$2\n$2\n" | passwd $1

  # add samba user
  echo -e "$2\n$2\n" | smbpasswd -s -a $1

  # add webdav user
  lightdigest -u $1 -p $2 -r webdav -f $WEBDAV_USER_CONF
  
  # add user to default group
  # addgroup $1 users		# can not add user to group more than 22

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: username
# $2: password 
#
mod_user() {
  local FUNC="MOD_USER"

  log_func $FUNC $1 $(echo $2 | md5sum | awk '{ print $1 }')

  # mod system user
  echo -e "$2\n$2\n" | passwd $1

  # add samba user
  echo -e "$2\n$2\n" | smbpasswd -s $1

  # add webdav user
  lightdigest -u $1 -p $2 -r webdav -f $WEBDAV_USER_CONF

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: username
#
del_user() {
  local FUNC="DEL_USER"

  log_func $FUNC $@

  # del samba user
  smbpasswd -x $1

  # del webdav user
  lightdigest -d -u $1 -f $WEBDAV_USER_CONF

  # del system user
  deluser $1
  delgroup $1

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#-------------------------------------------------------------------------------
# group
#-------------------------------------------------------------------------------

#
# $1: groupname
#
add_group() {
  local FUNC="ADD_GROUP"

  log_func $FUNC $@

  addgroup $1

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: groupname 
#
mod_group() {
  local FUNC="MOD_GROUP"

  log_func $FUNC $@

  addgroup $1

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: groupname
#
del_group() {
  local FUNC="DEL_GROUP"

  log_func $FUNC $@

  delgroup $1

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#-------------------------------------------------------------------------------
# samba share generation
#-------------------------------------------------------------------------------

#
# $1: folder name
#
gen_samba_share() {
  FOLDER="$1"

  FOLDER_INFO=$(sqlite3 $DBFILE "select * from folder_info where folder='$FOLDER';")
#  USER_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='user';")
#  GROUP_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='group';")

#  DOMAIN_USER_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='Domainuser';")
#  DOMAIN_GROUP_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='Domaingroup';")

#  RO_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $2 }');    
#  RW_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $3 }');    
#  RO_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($2 ~ /.+/) print "@"$2; }');   
#  RW_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($3 ~ /.+/) print "@"$3; }');   
   
  RO_USER=$(sqlite3 $DBFILE "select ro from folder_member where folder='$FOLDER' and attr='user';" | sed '/^$/d'|sed 's/$/,/g');    
  RW_USER=$(sqlite3 $DBFILE "select rw from folder_member where folder='$FOLDER' and attr='user';" | sed '/^$/d'|sed 's/$/,/g');    
  RO_GROUP=$(sqlite3 $DBFILE "select ro from folder_member where folder='$FOLDER' and attr='group';" | sed '/^$/d' | sed 's/^/@/g' |sed 's/$/,/g');   
  RW_GROUP=$(sqlite3 $DBFILE "select rw from folder_member where folder='$FOLDER' and attr='group';" | sed '/^$/d' | sed 's/^/@/g' |sed 's/$/,/g');   

#  RO_DOMAIN_USER=$(echo $DOMAIN_USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $2 }' | sed 's/\\/\\\\/g');    
#  RW_DOMAIN_USER=$(echo $DOMAIN_USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $3 }' | sed 's/\\/\\\\/g');    
#  RO_DOMAIN_GROUP=$(echo $DOMAIN_GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($2 ~ /.+/) print "@"$2; }' | sed 's/\\/\\\\/g');   
#  RW_DOMAIN_GROUP=$(echo $DOMAIN_GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($3 ~ /.+/) print "@"$3; }' | sed 's/\\/\\\\/g');   

  RO_DOMAIN_USER=$(sqlite3 $DBFILE "select ro from folder_member where folder='$FOLDER' and attr='Domainuser';" | sed 's/\\/\\\\/g'| sed '/^$/d' | sed 's/$/,/g');    
  RW_DOMAIN_USER=$(sqlite3 $DBFILE "select rw from folder_member where folder='$FOLDER' and attr='Domainuser';" | sed 's/\\/\\\\/g'| sed '/^$/d' | sed 's/$/,/g');    
  RO_DOMAIN_GROUP=$(sqlite3 $DBFILE "select ro from folder_member where folder='$FOLDER' and attr='Domaingroup';" | sed 's/\\/\\\\/g'| sed '/^$/d' | sed 's/^/@"/g' |sed 's/$/",/g');   
  RW_DOMAIN_GROUP=$(sqlite3 $DBFILE "select rw from folder_member where folder='$FOLDER' and attr='Domaingroup';" | sed 's/\\/\\\\/g'| sed '/^$/d' | sed 's/^/@"/g' |sed 's/$/",/g');   

#  VALID_USERS=$(echo $RO_USER $RW_USER $RO_GROUP $RW_GROUP $RO_DOMAIN_USER $RW_DOMAIN_USER $RO_DOMAIN_GROUP $RW_DOMAIN_GROUP| sed "s/ /, /g")
#  READ_LIST=$(echo $RO_USER $RO_GROUP $RO_DOMAIN_USER $RO_DOMAIN_GROUP| sed "s/ /, /g")
#  WRITE_LIST=$(echo $RW_USER $RW_GROUP $RW_DOMAIN_USER $RW_DOMAIN_GROUP| sed "s/ /, /g")

  VALID_USERS=$(echo $RO_USER $RW_USER $RO_GROUP $RW_GROUP $RO_DOMAIN_USER $RW_DOMAIN_USER $RO_DOMAIN_GROUP $RW_DOMAIN_GROUP | sed "s/,$//")
  READ_LIST=$(echo $RO_USER $RO_GROUP $RO_DOMAIN_USER $RO_DOMAIN_GROUP | sed "s/,$//")
  WRITE_LIST=$(echo $RW_USER $RW_GROUP $RW_DOMAIN_USER $RW_DOMAIN_GROUP | sed "s/,$//")

  if [ -z "$VALID_USERS" ]; then
    VALID_USERS=no
  fi

  echo "$SHARE_START_COMMENT $FOLDER"

  echo $FOLDER_INFO \
    | awk -v valid_users="$VALID_USERS" -v read_list="$READ_LIST" -v write_list="$WRITE_LIST" \
      'BEGIN { FS="|" } 
       {
       if ($6 ~ /YES/) {				# WINDOWS
           if ($4 ~ /NORMAL/) {
             print "["$1"]";
           } else {
             print "["$1"$]";
           }
           print "  comment = "$2; 
           print "  path = "$3; 
           print "  writable = yes";
           print "  printable = no";
           print "  browsable = yes";
           if ($10 ~ /YES/) {				# ACL
             print "  valid users = "valid_users;
             print "  read list = "read_list;
             print "  write list = "write_list;
           } else {
             print "  guest ok = yes";
           }
           print "  force create mode = 666";
           print "  force directory mode = 777";
           print "  csc policy = disable"
           if ($5 ~ /YES/) {				# RECYCLE
             print "  vfs objects = commit, audit, recycle";
             print "    recycle:repository = trashbox";
             print "    recycle:keeptree = 1";
             print "    recycle:versions = 1";
             print "    recycle:directory_mode = 0777";
             print "    audit:facility = LOCAL6";
             print "    audit:priority = INFO";
           } else {
             print "  vfs objects = commit, audit";
             print "    audit:facility = LOCAL6";
             print "    audit:priority = INFO";
           } 
         }
       }'
           
  echo "$SHARE_END_COMMENT $FOLDER"
}

#
# $1: folder name
#
add_samba_share() {
  local FUNC="ADD_SAMBA_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  gen_samba_share $FOLDER >> $SAMBA_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
mod_samba_share() {
  local FUNC="MOD_SAMBA_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $SAMBA_CONF
  gen_samba_share $FOLDER >> $SAMBA_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
del_samba_share() {
  local FUNC="DEL_SAMBA_SHARE"
  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $SAMBA_CONF
  log_func_result $FUNC $OK 
}

del_samba_ad() {
  local FUNC="DEL_SAMBA_AD_CONF"
  log_func $FUNC $@

  sed -i "/$AD_START_COMMENT/,/$AD_END_COMMENT/d" $SAMBA_CONF
  if [ "$?" = "0" ]; then
    log_func_result $FUNC $OK
  else
    log_func_result $FUNC $ERROR_EXEC
  fi
}

#
# $1: share folder name
# $2: mount directory
# $3~: description
# 
gen_samba_device() {
  FOLDER=$1
  echo "[$FOLDER]"
  echo "  path = $2"
  shift 2
  echo "  comment = $@"

  if [ "$FOLDER" = "$CDROM_SHARE_PREFIX" ]; then
    echo "  writable = no"
  else
    echo "  writable = yes"
  fi

  echo "  printable = no"
  echo "  browsable = yes"
  echo "  guest ok = yes"
  echo "  force create mode = 666"
  echo "  force directory mode = 777"

  if [ "$FOLDER" != "$CDROM_SHARE_PREFIX" ]; then
    echo "  vfs objects = commit"
    #echo "    commit:sync = yes"
    echo "    commit:sync_all = yes"
  fi
}

#
# $1: share folder name
# $2: mount directory
# $3~: description
#
add_samba_usb() {
  local FUNC="ADD_SAMBA_USB"
  log_func $FUNC $@

  FOLDER=$1
  echo $USB_SHARE_START_COMMENT $FOLDER >> $SAMBA_CONF
  gen_samba_device $@ >> $SAMBA_CONF
  echo $USB_SHARE_END_COMMENT $FOLDER >> $SAMBA_CONF
}

#
# $1: share folder name
#
del_samba_usb() {
  local FUNC="DEL_SAMBA_USB"
  log_func $FUNC $@

  FOLDER=$1
  sed -i "/$USB_SHARE_START_COMMENT $FOLDER$/,/$USB_SHARE_END_COMMENT $FOLDER$/d" $SAMBA_CONF
  smbcontrol smbd close-share $FOLDER
}

#
# none
#
del_samba_usb_all() {
  local FUNC="DEL_SAMBA_USB_ALL"
  log_func $FUNC $@

  sed -i "/$USB_SHARE_START_COMMENT/,/$USB_SHARE_END_COMMENT/d" $SAMBA_CONF
}

#
# #1 : generated conf file
#
add_samba_cdrom() {
  local FUNC="ADD_SAMBA_CDROM"
  log_func $FUNC $@

  echo $CDROM_SHARE_START_COMMENT >> $1
  gen_samba_device $CDROM_SHARE_PREFIX $CDROM_MOUNT_DIR "CD-ROM device" >> $1
  echo $CDROM_SHARE_END_COMMENT >> $1
  nas-service control samba reload
}

#
# none
#
del_samba_cdrom() {
  local FUNC="DEL_SAMBA_CDROM"
  log_func $FUNC $@

  FOLDER=$CDROM_SHARE_PREFIX
  sed -i "/$CDROM_SHARE_START_COMMENT/,/$CDROM_SHARE_END_COMMENT/d" $SAMBA_CONF
  smbcontrol smbd close-share $FOLDER
  nas-service control samba reload
}

set_samba_printer() {
  local FUNC="MANAGE_SAMBA_PRINTER"
  log_func $FUNC $@

  LP_LIST=`ls /dev/usb/lp[0-9]* | cut -d '/' -f4`
	rm -rf /var/spool/lp*
        echo "" > /etc/printcap
        echo "" > $SAMBA_CONF_PRINTER

	if [ -z $LP_LIST ]; then
               nas-service enable printer off
 	       nas-share gen_samba_conf /etc/samba/smb.conf
 	       nas-service control printer stop
 	       nas-service control samba reload
               rm -rf /var/spool/samba/*

        else
                mkdir -m777 -p /var/spool/samba
		echo "#NAS Printer Configutaion" 	> /etc/printcap
		echo "### BEGIN PRINTER INFO" 		> $SAMBA_CONF_PRINTER

	for LP in $LP_LIST
	do 
		MFG=`udevinfo -a -p /class/usb/$LP | grep ieee | cut -d ":" -f2 | cut -d ";" -f1 | sed 's/ \+/_/g'`
                if [ "$MFG" = '' ]; then
                  MFG="nas-printer_"$LP
                fi
		MDL=`udevinfo -a -p /class/usb/$LP | grep ieee | cut -d ":" -f4 | cut -d ";" -f1| sed 's/ \+/_/g'`
		SERIAL=`udevinfo -a -p /class/usb/$LP | grep ATTRS{serial} | awk 'NR==1' | cut -d "\"" -f2 | sed 's/"\+//g'`
		MODEL=`echo $MFG"_"$LP`
		ID=`echo $MFG"_"$SERIAL`

		#if [ ! -d "/var/spool/$LP" ]; then
 		#	mkdir -m 700 /var/spool/$LP
		#fi

		echo "$ID:\\"	 																			>> /etc/printcap
		echo "               :ml=0:\\"											>> /etc/printcap
		echo "               :mx=0:\\"											>> /etc/printcap
		echo "               :sd=/var/spool/$LP:\\"					>> /etc/printcap
		echo "               :sh:\\"												>> /etc/printcap
		echo "               :lp=/dev/usb/"$LP":"						>> /etc/printcap
		echo " "						>> /etc/printcap

		echo "[$MODEL]"																			>>$SAMBA_CONF_PRINTER
		echo "  comment = Serial No:$SERIAL"								>>$SAMBA_CONF_PRINTER
		echo "  path = /var/spool/samba"										>>$SAMBA_CONF_PRINTER
		echo "  print command = /usr/bin/lpr -r -P$ID %s"		>>$SAMBA_CONF_PRINTER
		echo "  force user = root"													>>$SAMBA_CONF_PRINTER
		echo "  printer name = $MODEL"											>>$SAMBA_CONF_PRINTER
		echo "  public = Yes"																>>$SAMBA_CONF_PRINTER
		echo "  printable = Yes"														>>$SAMBA_CONF_PRINTER
		echo "  browseable = Yes"														>>$SAMBA_CONF_PRINTER
		echo "  available = Yes"														>>$SAMBA_CONF_PRINTER
		echo " "																						>>$SAMBA_CONF_PRINTER

	done

	echo "# END NAS Printer Configutaion" >> /etc/printcap
	echo "### END PRINTER INFO" >> /etc/nas/default/smb_printer.conf

	nas-service enable printer on
	nas-share gen_samba_conf /etc/samba/smb.conf
	nas-service control printer restart
	nas-service control samba reload
 
fi
}




#
# $1: filename
#
gen_samba_conf() {
  local FUNC="GEN_SAMBA_CONF"

  log_func $FUNC $@
  START_TIME=$(date +%s)
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  cat $SAMBA_CONF_DEFAULT > $OUTFILE

  # STEP 1: add domain & printer configuration
  if [ -z "$PREFIX" ]; then
    WORKGROUP=$(nas-network get workgroup)
    DOMAIN_TYPE=$(nas-network get domain_type)
    CODEPAGE=$(nas-system get codepage)
    replace_conf_equal "workgroup" $WORKGROUP $OUTFILE
    replace_conf_equal "dos charset" $CODEPAGE $OUTFILE
    if [ "$DOMAIN_TYPE" = "workgroup" ]; then
      del_samba_ad $OUTFILE
    else
      REALM=$(nas-network get domain)
      replace_conf_equal "security" "ads" $OUTFILE
      replace_conf_equal "realm" $REALM $OUTFILE
    fi

    USE_PRINTER=$(nas-service get enabled printer)
    if [ "$USE_PRINTER" = "on" ]; then
      cat $SAMBA_CONF_PRINTER >> $OUTFILE
    fi
  fi

  # STEP 2: add share folder
  FOLDERS=$(sqlite3 $DBFILE "select folder from folder_info;")
  for FOLDER in $FOLDERS; do
    gen_samba_share $FOLDER >> $OUTFILE
  done

  # STEP 3: gen cdrom device folder
  if [ -z "$PREFIX" ]; then
    if [ "$(nas-service get_iscsi enabled)" = "off" ]; then
      add_samba_cdrom $OUTFILE
    fi
  fi

  # STEP 4: gen usb device folder
  cat $USB_LIST_FILE 2>/dev/null | \
  while read DEV_PATH MOUNT_DIR SHARE_NAME; do
    log_func $FUNC $SHARE_NAME $MOUNT_DIR 

    echo $USB_SHARE_START_COMMENT $SHARE_NAME >> $OUTFILE
    gen_samba_device $SHARE_NAME $MOUNT_DIR $(nas-storage get_device_name $DEV_PATH desc) >> $OUTFILE
    echo $USB_SHARE_END_COMMENT $SHARE_NAME >> $OUTFILE
  done

  log_func_result $FUNC $OK "Elapsed TIme: $(($(date +%s) - $START_TIME))sec"
}


#-------------------------------------------------------------------------------
# ftp share generation
#-------------------------------------------------------------------------------

#
# $1: folder name
#
gen_ftp_share() {
  FOLDER="$1"

  FOLDER_INFO=$(sqlite3 $DBFILE "select * from folder_info where folder='$FOLDER';")
  USER_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='user';")
  GROUP_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='group';")

  RO_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $2 }');    
  RW_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $3 }');    
  RO_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $2 }');   
  RW_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $3 }');   

  RO_USER=$(echo $RO_USER)
  RW_USER=$(echo $RW_USER)
  RO_GROUP=$(echo $RO_GROUP)
  RW_GROUP=$(echo $RW_GROUP)

  echo $SHARE_START_COMMENT $FOLDER

  echo $FOLDER_INFO \
    | awk -v ro_user="$RO_USER" -v rw_user="$RW_USER" -v ro_group="$RO_GROUP" -v rw_group="$RW_GROUP" \
      'BEGIN { FS="|" } 
       {
         print "<Directory "$3">";
         if ($8 ~ /YES/) {				# FTP
           if ($10 ~ /YES/) {				# ACL
             				print "  <Limit All>"
             				print "    DenyAll"
             if (ro_user || rw_user) 	print "    AllowUser "ro_user" "rw_user;
             if (ro_group || rw_group) 	print "    AllowGroup "ro_group" "rw_group;
             				print "  </Limit>"   
             				print "  <Limit READ>"
             if (ro_user)		print "    AllowUser "ro_user;
             if (ro_group)		print "    AllowGroup "ro_group;
             				print "  </Limit>"   
             				print "  <Limit WRITE>"
             				print "    Order allow,deny"
             if (rw_user)		print "    AllowUser "rw_user;
             if (rw_group)		print "    AllowGroup "rw_group;
             if (ro_user) 		print "    DenyUser "ro_user;
             if (ro_group)		print "    DenyGroup "ro_group;
             				print "  </Limit>"   
           } else {
           }
         } else {
           print "  HideNoAccess on"
           print "  <Limit All>"
           print "    DenyAll"
           print "  </Limit>"
         }
         print "</Directory>"
       }'
           
  echo $SHARE_END_COMMENT $FOLDER
}

#
gen_ftp_volume() {

  echo $VOLUME_START_COMMENT
  
  if [ -z "$PREFIX" ]; then
    VOLS=( $(nas-storage get vol_list) )
    DEFAULT_VOL=$(nas-storage get vol_default)
  fi

  for VOL in ${VOLS[@]}; do
    echo "<Directory /mnt/disk/$VOL/lost+found>"
    echo "  HideNoAccess on"
    echo "  <Limit ALL>"
    echo "    DenyAll"
    echo "  </Limit>"
    echo "</Directory>"
  done

  if [ -z "$PREFIX" ]; then
    echo "<Directory $DEFAULT_VOL/.webtmp>"
    echo "  HideNoAccess on"
    echo "  <Limit ALL>"
    echo "    DenyAll"
    echo "  </Limit>"
    echo "</Directory>"
  fi


  echo $VOLUME_END_COMMENT
}

#
# $1: filename
#
gen_ftp_conf() {
  local FUNC="GEN_FTP_CONF"

  log_func $FUNC $@
  START_TIME=$(date +%s)
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=$FTP_CONF
  fi

  cat $FTP_CONF_DEFAULT > $OUTFILE

  if [ -z "$PREFIX" ]; then
    DDNS_ENABLED=$(nas-service get_ddns enabled)
    IP_CHECK_SERVER="http://checkip.dyndns.org"
    DDNS_IP=$(wget -q -O - $IP_CHECK_SERVER | cut -d " " -f 6 | cut -d "<" -f 1)
    if [ "$DDNS_ENABLED" = "on" ] && [ -n "$DDNS_IP" ]; then
#      echo "MasqueradeAddress $DDNS_IP" >> $OUTFILE
      echo "DefaultAddress localhost $DDNS_IP" >> $OUTFILE
    fi
  fi

  gen_ftp_volume >> $OUTFILE

  FOLDERS=$(sqlite3 $DBFILE "select folder from folder_info;")
  for FOLDER in $FOLDERS; do
    gen_ftp_share $FOLDER >> $OUTFILE
  done

  log_func_result $FUNC $OK "Elapsed TIme: $(($(date +%s) - $START_TIME))sec"
}

#
# $1: folder name
#
add_ftp_share() {
  local FUNC="ADD_FTP_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  gen_ftp_share $FOLDER >> $FTP_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
mod_ftp_share() {
  local FUNC="MOD_FTP_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $FTP_CONF
  gen_ftp_share $FOLDER >> $FTP_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
del_ftp_share() {
  local FUNC="DEL_FTP_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $FTP_CONF
  log_func_result $FUNC $OK 
}


#-------------------------------------------------------------------------------
# afp share generation
#-------------------------------------------------------------------------------

#
# $1: folder name
#
gen_afp_share() {
  FOLDER="$1"

  FOLDER_INFO=$(sqlite3 $DBFILE "select * from folder_info where folder='$FOLDER';")
  USER_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='user';")
  GROUP_SHARE=$(sqlite3 $DBFILE "select * from folder_member where folder='$FOLDER' and attr='group';")

  RO_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $2 }');    
  RW_USER=$(echo $USER_SHARE | awk 'BEGIN { FS="|"; RS=" " } { print $3 }');    
  RO_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($2 ~ /.+/) print "@"$2; }');   
  RW_GROUP=$(echo $GROUP_SHARE | awk 'BEGIN { FS="|"; RS=" " } { if ($3 ~ /.+/) print "@"$3; }');   

  VALID_USERS=$(echo $RO_USER $RW_USER $RO_GROUP $RW_GROUP | sed "s/ /,/g")
  READ_LIST=$(echo $RO_USER $RO_GROUP | sed "s/ /,/g")
  WRITE_LIST=$(echo $RW_USER $RW_GROUP | sed "s/ /,/g")

  if [ "$WRITE_LIST" = "" ]; then
    WRITE_LIST="avahi"
  fi

  echo "$SHARE_START_COMMENT $FOLDER"

  echo $FOLDER_INFO \
    | awk -v valid_users="$VALID_USERS" -v read_list="$READ_LIST" -v write_list="$WRITE_LIST" \
      'BEGIN { FS="|" } 
       {
         if ($7 ~ /YES/) {                              # ATALK
           if ($10 ~ /YES/) {
             print $3" \""$1"\" allow:"valid_users" rwlist:"write_list" options:usedots,upriv dperm:0777 fperm:0777";
           } else {
             print $3" \""$1"\" options:usedots,upriv dperm:0777 fperm:0777";
           }
         }
       }'
           
  echo "$SHARE_END_COMMENT $FOLDER"
}

#
# $1: filename
#
gen_afp_conf() {
  local FUNC="GEN_AFP_CONF"

  log_func $FUNC $@
  START_TIME=$(date +%s)
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  cat $AFP_CONF_DEFAULT > $OUTFILE

  FOLDERS=$(sqlite3 $DBFILE "select folder from folder_info;")
  for FOLDER in $FOLDERS; do
    gen_afp_share $FOLDER >> $OUTFILE
  done

  if [ -z "$PREFIX" ]; then
    if [ "$(nas-service get_timemachine enabled)" = "on" ]; then
      echo $TIMEMACHINE_START_COMMENT >> $OUTFILE
      echo "$SERVICE_DIR/Timemachine \"Timemachine\" allow:admin rwlist:admin" >> $OUTFILE
      echo $TIMEMACHINE_END_COMMENT >> $OUTFILE
    fi
  fi
  
  log_func_result $FUNC $OK "Elapsed TIme: $(($(date +%s) - $START_TIME))sec"
}

#
# $1: folder name
#
add_afp_share() {
  local FUNC="ADD_AFP_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  gen_afp_share $FOLDER >> $AFP_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
mod_afp_share() {
  local FUNC="MOD_AFP_SHARE"

  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $AFP_CONF
  gen_afp_share $FOLDER >> $AFP_CONF

  log_func_result $FUNC $OK 
}

#
# $1: folder name
#
del_afp_share() {
  local FUNC="DEL_AFP_SHARE"
  log_func $FUNC $@

  FOLDER=$(basename $1)
  sed -i "/$SHARE_START_COMMENT $FOLDER$/,/$SHARE_END_COMMENT $FOLDER$/d" $AFP_CONF
  log_func_result $FUNC $OK 
}

#
del_timemachine_share() {
  local FUNC="DEL_AFP_SHARE"
  log_func $FUNC $@

  sed -i "/$TIMEMACHINE_START_COMMENT$/,/$TIMEMACHINE_END_COMMENT$/d" $AFP_CONF
  log_func_result $FUNC $OK 
}

#-------------------------------------------------------------------------------
# itunes share generation
#-------------------------------------------------------------------------------

#
# $1: filename
#
gen_itunes_conf() {
  local FUNC="GEN_ITUNES_CONF"

  log_func $FUNC $@
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  cat $ITUNES_CONF_DEFAULT > $OUTFILE

  if [ -z "$PREFIX" ]; then
    DIR=$(nas-storage get vol_default)/$SYSTEMDIR/iTunes
    replace_conf_equal mp3_dir $DIR $OUTFILE
  else 
    replace_conf_equal mp3_dir /tmp $OUTFILE
  fi

  HOSTNAME=$(cat $PREFIX/etc/hostname)
  replace_conf_equal servername "iTunes Server on $HOSTNAME" $OUTFILE

  log_func_result $FUNC $OK 
}

#-------------------------------------------------------------------------------
# dlna share generation
#-------------------------------------------------------------------------------
DLNA_INTERNAL_DIRLIST=/usr/sbin/DLNA/conf/dms/sync/dir_list
#
# $1: filename
#
gen_dlna_conf() {
  local FUNC="GEN_DLNA_CONF"

  log_func $FUNC $@
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  cat $DLNA_CONF_DEFAULT > $OUTFILE

  if [ -z "$PREFIX" ]; then
    DIR=$(nas-storage get vol_default)/$SYSTEMDIR/DLNA
    replace_conf_blank dlna_dir $DIR $OUTFILE
    echo $DIR > $DLNA_INTERNAL_DIRLIST
  else 
    replace_conf_blank dlna_dir /tmp $OUTFILE
  fi

  log_func_result $FUNC $OK 
}


#-------------------------------------------------------------------------------
# torrent share generation
#-------------------------------------------------------------------------------

#
# $1: filename
#
gen_torrent_conf() {
  local FUNC="GEN_TORRENT_CONF"

  log_func $FUNC $@
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  cat $TORRENT_CONF_DEFAULT > $OUTFILE

  if [ -z "$PREFIX" ]; then
    DIR=$(nas-storage get vol_default)/$SYSTEMDIR/Torrent
  else 
    DIR=/tmp
  fi
  DIR=$(echo $DIR | sed 's/\//\\\\\\\//g')
  sed -i "s/%DOWNLOAD_DIR%/$DIR/" $OUTFILE

  log_func_result $FUNC $OK 
}


#-------------------------------------------------------------------------------
# folder 
#-------------------------------------------------------------------------------

gen_conf() {
  local FUNC="GEN_CONF_ALL"
  log_func $FUNC

  # samba
  gen_samba_conf /tmp/smb.conf.temp 
  mv /tmp/smb.conf.temp $SAMBA_CONF 

  # ftp
  gen_ftp_conf /tmp/proftpd.conf.temp 
  mv /tmp/proftpd.conf.temp $FTP_CONF 

  # afp
  gen_afp_conf /tmp/AppleVolumes.default.temp 
  mv /tmp/AppleVolumes.default.temp $AFP_CONF 

  # iTunes
  gen_itunes_conf /tmp/itunes.conf.temp 
  mv /tmp/itunes.conf.temp $ITUNES_CONF 

  # Torrent
  gen_torrent_conf /tmp/torrent.conf.temp 
  mv /tmp/torrent.conf.temp $TORRENT_CONF 

  # dlna
  gen_dlna_conf /tmp/dlna.conf.temp
  mv /tmp/dlna.conf.temp $DLNA_CONF

  # additional setting apply
  if [ -z "$PREFIX" ]; then
    chown admin:admin $TORRENT_CONF
    nas-service config language
  fi 

  # iscsi
  gen_iscsi_chap /tmp/iscsi-scstd.conf.temp 
  mv /tmp/iscsi-scstd.conf.temp $ISCSI_CONF 

  log_func_result $FUNC $OK
}

gen_folder() {
  local FUNC="GEN_FOLDER"

  FOLDERS=$(sqlite3 $DBFILE "select path from folder_info;")
  for FOLDER in $FOLDERS; do
    mkdir -p $FOLDER >/dev/null 2>&1
    chmod 777 $FOLDER >/dev/null 2>&1
  done
}

gen_folder_link() {
  local FUNC="GEN_FOLDER_LINK"

  log_func $FUNC $@

  # WEB dav link
  LINK_DIR=/var/www/dav
  mkdir -p $LINK_DIR
  find $LINK_DIR/ -maxdepth 1 -type l -delete

  FOLDERS=$(sqlite3 $DBFILE "select path from folder_info where webdav='YES';")
  for FOLDER in $FOLDERS; do
    ln -sf $FOLDER $LINK_DIR/$(basename $FOLDER)
  done

  NO_WEBDAV_FILE=$LINK_DIR/no_webdav_folders
  if [ -z "$FOLDERS" ]; then
    touch $NO_WEBDAV_FILE
  else
    rm -f $NO_WEBDAV_FILE
  fi
}

gen_default_service_folder() {
  SHARENAME="service"
  DIR=$(nas-storage get vol_default)/$SHARENAME
  mkdir -p $DIR/iTunes
  chmod 777 $DIR/iTunes
  mkdir -p $DIR/backup
  chmod 777 $DIR/backup
  mkdir -p $DIR/Torrent
  chmod 777 $DIR/Torrent
  mkdir -p $DIR/DLNA
  chmod 777 $DIR/DLNA
  #mkdir -p $DIR/Timemachine
  #chmod 777 $DIR/Timemachine
  mkdir -p $DIR/trashbox
  chmod 777 $DIR/trashbox

  sqlite3 $DBFILE "INSERT INTO folder_info VALUES('$SHARENAME', 'Default Service Folder', '$DIR', 'NORMAL', 'YES', 'YES', 'YES', 'YES', 'NO', 'YES');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_info"
  sqlite3 $DBFILE "INSERT INTO folder_member VALUES('$SHARENAME', '', 'admin', '', 'user');"
  sqlite3 $DBFILE "INSERT INTO folder_member VALUES('$SHARENAME', 'users', '', '', 'group');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_member"

  sync
}

gen_default_web_folder() {
  # 1. make web temp folder
  WEBNAME=".webtmp"
  DEFAULT_VOLUME=$(nas-storage get vol_default)/$WEBNAME
  mkdir -p $DEFAULT_VOLUME
  chmod 755 $DEFAULT_VOLUME
  chown www-data:www-data $DEFAULT_VOLUME

}


gen_default_folder() {
  local FUNC="GEN_DEFAULT_FOLDER"

  # 1. Default share directory for volume
  VOLS=$(grep "/mnt/disk/" /etc/fstab | awk '{ print $2 }')

  for VOL in $VOLS; do
    SHARENAME=$(basename $VOL)_public
    DIR=${VOL}/$SHARENAME
    mkdir -p $DIR
    chmod 777 $DIR
    sqlite3 $DBFILE "INSERT INTO folder_info VALUES('$SHARENAME', 'Default folder of $SHARENAME', '$DIR', 'NORMAL', 'YES', 'YES', 'YES', 'YES', 'NO', 'YES');"
    [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_info"
    sqlite3 $DBFILE "INSERT INTO folder_member VALUES('$SHARENAME', '', 'users', '', 'group');"
    [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_member"
  done

  #Create Service folder and temp web folder if it is not exist 
  DIR=$(nas-storage get vol_default)/service
  RESULT=$(sqlite3 $DBFILE "SELECT path FROM folder_info where path='$DIR';")
  # 2. Default share directory for system 
  if [ -z $RESULT ]; then
    gen_default_service_folder
  fi

  # 3. Make web temporary folder to default volume
  if [ ! -e "$(nas-storage get vol_default)/.webtmp" ]; then
    gen_default_web_folder
  fi

  log_func $FUNC $VOLS 
}

#
# $1: foldername
#
check_folder() {
  local FUNC="CHECK_FOLDER"
  RESULT=0

  LIST=$(nas-storage get vol_list)
  LIST+=" system"

  for FOLDER in $LIST; do
    if [ "$1" = "$FOLDER" ]; then
      RESULT=1
      break 
    fi
  done
  [ "$RESULT" = "0" ] && echo "ok"
  return $RESULT
}

#
# $1: foldername (full path)
#
add_folder() {
  local FUNC="ADD_FOLDER"

  log_func $FUNC $@

  # check
  [ -e $(dirname $1) ] || log_func_check_result $FUNC $? $ERROR_VOL_NOT_EXIST
  mkdir -p $1
  chmod 777 $1

  add_samba_share $1
  add_ftp_share $1
  add_afp_share $1

  nas-service control samba reload  
  nas-service control ftp reload  
  nas-service control afp reload  

  gen_folder_link

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: foldername
#
mod_folder() {
  local FUNC="MOD_FOLDER"

  log_func $FUNC $@

  mod_samba_share $1
  mod_ftp_share $1
  mod_afp_share $1

  FOLDER=$(basename $1)
  WINDOWS=$(sqlite3 $DBFILE "select windows from folder_info where folder='$FOLDER';")
  if [ "$WINDOWS" != "YES" ]; then
    smbcontrol smbd close-share $FOLDER
  fi

  nas-service control samba reload
  nas-service control ftp reload
  nas-service control afp reload

  gen_folder_link

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
# $1: foldername
#
del_folder() {
  local FUNC="DEL_FOLDER"

  log_func $FUNC $@

  del_samba_share $1
  del_ftp_share $1
  del_afp_share $1

  # close samba share
  smbcontrol smbd close-share $(basename $1)

  nas-service control samba reload
  nas-service control ftp reload
  nas-service control afp reload
  gen_folder_link

  rm -rf $1  

  log_func_check_result $FUNC $? $ERROR_EXEC
}

#
del_all_folder() {
  local FUNC="DEL_ALL_FOLDER"
  log_func $FUNC $@

  sqlite3 $DBFILE "DELETE FROM folder_info;"
  sqlite3 $DBFILE "DELETE FROM folder_member;"
}

#
# $1: volume name
#
add_volume() {
  local FUNC="ADD_VOLUME"
  log_func $FUNC $@

  VOL=$1

  SHARENAME=$(basename $VOL)_public
  DIR=${VOL}/${SHARENAME}
  mkdir -p $DIR
  chmod 777 $DIR
  sqlite3 $DBFILE "INSERT INTO folder_info VALUES('$SHARENAME', 'Default folder of $SHARENAME', '$DIR', 'NORMAL', 'YES', 'YES', 'YES', 'YES', 'NO', 'YES');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_info"
  sqlite3 $DBFILE "INSERT INTO folder_member VALUES('$SHARENAME', '', 'users', '', 'group');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_member"

  gen_folder_link
  gen_conf

  log_func_result $FUNC $TRUE
}

# 
# $1: volume name
#
remove_volume() {
  local FUNC="REMOVE_VOLUME"
  log_func $FUNC $@

  # STEP 0: check default volume
  REMOVED_VOL=$(basename $1)
  PREV_DEFAULT_VOL=$(basename "$(ls -al /mnt/disk/default)")

  if [ "$REMOVED_VOL" = "$PREV_DEFAULT_VOL" ]; then
    log_func $FUNC "Default volume removed $1"
    gen_default_service_folder

    rm -f $DEFAULT_VOL_DIR
    ln -s $(nas-storage get vol_default) $DEFAULT_VOL_DIR
  fi

  # STEP 1: Delete remove volume folder
  FOLDER_LIST=$(sqlite3 $DBFILE "SELECT path from folder_info;" | grep "^${VOL_MOUNT_DIR}/${REMOVED_VOL}")
  for FOLDER in $FOLDER_LIST; do
    log_func $FUNC Remove folder: $FOLDER
    sqlite3 $DBFILE "DELETE from folder_info WHERE path='$FOLDER';"
  done

  gen_folder_link
  gen_conf

  log_func_result $FUNC $TRUE
}

#
# $1: volume
#
del_trashbox() {
  local FUNC="DEL_TRASHBOX"
  log_func $FUNC $@

  VOL_DIR=$VOL_MOUNT_DIR/$1
  FOLDERS=$(sqlite3 $DBFILE "select path from folder_info where recycle='YES';" | grep $VOL_DIR)

  for folder in $FOLDERS; do
    rm -rf $folder/trashbox
    if [ "$(basename $folder)" = $SYSTEMDIR ]; then 
      mkdir -p $folder/trashbox
      chmod 777 $folder/trashbox
    fi
  done

  log_func_result $FUNC $?
}

#
# Generate folder list for main applet
#
# $1: username
#
gen_user_folder_list() {
  local FUNC="GEN_USER_FOLDER_LIST"
  log_func $FUNC $@

  USER=$1
  GROUP_LIST=$(sqlite3 $DBFILE "SELECT gid from group_user where uid='$USER';")

  SHARE_DIRS=$(sqlite3 $DBFILE "SELECT folder from folder_info where acl='NO';");
  
  USER_DIRS=$(sqlite3 $DBFILE "SELECT folder from folder_member where attr='user' AND (ro='$USER' OR rw='$USER');")
  
  for GROUP in $GROUP_LIST; do
    GROUP_DIRS+=$(sqlite3 $DBFILE "SELECT folder from folder_member where attr='group' AND (ro='$GROUP' OR rw='$GROUP');")
  done
 
  #log_func $FUNC "group =" $GROUP_LIST
  #log_func $FUNC "guest dirs =" $SHARE_DIRS
  #log_func $FUNC "user dirs =" $USER_DIRS
  #log_func $FUNC "group dirs =" $GROUP_DIRS

  echo usb $SHARE_DIRS $USER_DIRS $GROUP_DIRS > /var/www/run/$USER.share 
}

#-------------------------------------------------------------------------------
# iscsi-scst conf generation
#-------------------------------------------------------------------------------

#
# $1: filename
#
gen_iscsi_chap() {
  local FUNC="GEN_ISCSI_CONF"

  log_func $FUNC $@
  if [ ! -z "$1" ]; then
    OUTFILE=$1
  else 
    OUTFILE=/dev/stdout
  fi

  if [ -e $OUTFILE ]; then
    TARGET=$(grep "^Target" $OUTFILE)
  else
    TARGET=$(grep "^Target" $ISCSI_CONF_DEFAULT)
  fi

  cat $ISCSI_CONF_DEFAULT > $OUTFILE

  if [ -z "$PREFIX" ]; then
    if [ "$(nas-network get iscsi_chap)" = "on" ]; then
      INUSER=$(nas-network get iscsi_inuser)
      INPW=$(nas-network get iscsi_inpw)
      OUTUSER=$(nas-network get iscsi_outuser)
      OUTPW=$(nas-network get iscsi_outpw)
      sed -i "s/^#*IncomingUser.*/IncomingUser $INUSER $INPW/" $OUTFILE
      sed -i "s/^#*OutgoingUser.*/OutgoingUser $OUTUSER $OUTPW/" $OUTFILE
      # the following command is for target user access id and password
      #sed -i "s/^\t#*IncomingUser.*/\tIncomingUser $INUSER $INPW/" $OUTFILE
      #sed -i "s/^\t#*OutgoingUser.*/\tOutgoingUser $OUTUSER $OUTPW/" $OUTFILE
    fi
    sed -i "s/Target.*/$TARGET/" $OUTFILE
  fi

  log_func_result $FUNC $OK
}

add_myweb(){
  local FUNC="ADD MY WEB"
  log_func $FUNC $@

  RESULT=$(/usr/bin/sqlite3 $DBFILE "SELECT folder FROM folder_info where folder='myweb';")
  if [ -z "$RESULT" ]; then

  DEFAULT_VOLUME=$(nas-storage get vol_default)
  DIR=${DEFAULT_VOLUME}/myweb
  mkdir -p $DIR
  chmod 777 $DIR
  /usr/bin/sqlite3 $DBFILE "INSERT INTO folder_info VALUES('myweb', 'Default folder of myweb', '$DIR', 'NORMAL', 'YES', 'YES', 'YES', 'YES', 'NO', 'YES');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_info"
  /usr/bin/sqlite3 $DBFILE "INSERT INTO folder_member VALUES('myweb', '', 'admin', '', 'user');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_member"

  add_samba_share myweb
  add_afp_share myweb
  add_ftp_share myweb

  share_control reload

  tar xjf /etc/nas/default/userweb_default.tar.bz2 -C $DIR

  fi

  log_func_result $FUNC $TRUE
}

add_mysql(){
  local FUNC="ADD MySQL"
  log_func $FUNC $@

  EXIST=`cat /etc/passwd | grep mysql`
  if [ -z "$EXIST" ]; then
  useradd -s /bin/false mysql
  fi

  RESULT=$(/usr/bin/sqlite3 $DBFILE "SELECT folder FROM folder_info where folder='mysql';")
  if [ -z "$RESULT" ]; then
  DEFAULT_VOLUME=$(nas-storage get vol_default)
  DIR=${DEFAULT_VOLUME}/mysql
  mkdir -p $DIR
  chmod 777 $DIR
  /usr/bin/sqlite3 $DBFILE "INSERT INTO folder_info VALUES('mysql', 'Default folder of mysql', '$DIR', 'NORMAL', 'YES', 'YES', 'YES', 'NO', 'NO', 'YES');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_info"
  /usr/bin/sqlite3 $DBFILE "INSERT INTO folder_member VALUES('mysql', '', 'admin', '', 'user');"
  [ "$?" != 0 ] && log_func_result $FUNC $FAIL "Database insert fail : folder_member"

  add_samba_share mysql
  add_afp_share mysql
  add_ftp_share mysql

  share_control reload

  cp -a /var/lib/mysql/* $DIR
  chown mysql: $DIR -R

  else
   DIR=$(/usr/bin/sqlite3 $DBFILE "SELECT path FROM folder_info where folder='mysql';")
  fi

  if [ -d "$DIR/mysql" ]; then
    echo ""
  else
    cp -a /var/lib/mysql/* $DIR
    chown mysql: $DIR -R
  fi

#  cp -a /var/lib/mysql/* $DIR
#  chown mysql: $DIR -R

  replace_conf_equal datadir $DIR /etc/mysql/my.cnf

#  nas-service get enabled mysql
#  nas-service enable mysql on

#  nas-service control mysql start

  log_func_result $FUNC $TRUE
}



