#===============================================================================
# SSS Firmware
#===============================================================================
# File 			:	Original File from lib_diag
# Who			:	JWB
# Descripttion	:	libray of sss diagnostic function
#
# Copyright		:	LG ELECTRONICS INC.
# history 		:	2008.10.29 started first
#===============================================================================
#!/bin/bash

#. /etc/sss_script/event/lib_sss
LIBDIR=$PREFIX/usr/lib/nas
DIAGLOGFILE="/var/log/diag.log"

. $LIBDIR/common.sh

spODDNODE="sr0"
#spODDMODEL="BD-RE  BH08NS20 "
spODDMODELLIST=(
"BD-RE  BH10NS30 "
"DVDRAM GH50N    "
)
spSWAPTOTAL=256952

#1 PART
#2 ODD
SCODE_ODD_CONNECT="0x21"	#ok
SCODE_ODD_NODE="0x22"		#ok
SCODE_ODD_MODEL="0x23"		#ok
SCODE_ODD_CMDTOUT="0x24"	#To Do
#3 SYS
SCODE_SYS_DEGRADED="0x31"	#
SCODE_SYS_LOWFREE="0x32"
#4 SWP
SCODE_SWP_SWAPON="0x41"
SCODE_SWP_DEGRADED="0x42"
#5 USR
SCODE_USR_VOL1="0x51"
SCODE_USR_MOUNT="0x52"
SCODE_USR_DEGRADED="0x53"
SCODE_USR_LOWFREE="0x54"
#6 NET
SCODE_NET_LINKDOWN="0x61"
SCODE_NET_IFDOWN="0x62"
SCODE_NET_IFCFG="0x63"
#7 APP
SCODE_APP_NAME=("nas-usbd" "nas-cdromd"	"udevd" "buttond"	"syslogd"	"lighttpd"	"smbd" "crond")
SCODE_APP_CODE=("0x71"		 "0x72"		"0x73"	   "0x74"		"0x75"		"0x76"	"0x77" "0x78" )

DIAG_RMT_ITEM_ENC=(
"PART"	#1
"ODD"	#2
"SYS"	#3
"SWP"	#4
"USR"	#5
"NET"	#6
#"APP"	#7
)

DIAG_RMT_ITEM_HDD=(
"HDD"	#8
)

Fun_RmtT_ENC=(
"Fun_RmtODD"
"Fun_RmtSysSwap"
"Fun_RmtUSR"
"Fun_RmtNET"
"Fun_RmtAPP"
)
Fun_RmtT_HDD=(
"Fun_RmtHDD"
)

SMART_RET_Bay1=""
SMART_RET_Bay2=""
SMART_RET_Bay3=""
SMART_RET_Bay4=""

#1 ITEM #2 SCODE name
sEnc=0
sHdd=0

DIAG_ITEM_NAME=(
#"SVC"	#1 	: service code
#"SRN"	#2 	: Serial Number
"MAC"	#3 	: Mac Address
"VER"	#4 	:
"RTC"	#5
"FAN"	#6
"LED"	#7
"ODD"	#8
"VOL"	#9
"BAY"	#10
#"SMART" #9
)
SVC_SUB=(
'SVC_CODE' 		'Service Code'	'FunSvc'
)
SRN_SUB=(
'S/N' 		'Serial Number'	'FunSrn'
)
MAC_SUB=(
'MAC'		'Address'		'FunMac'
)
VER_SUB=(
'Version'	'Main IC'		'FunVerMain'
'Version'	'System'		'FunVerSys'
'Version'	'Uboot'			'FunVerUbt'
'Version'	'Micom'			'FunVerMcu'
'Version'	'Kernel'		'FunVerKnl'
)
RTC_SUB=(
'RTC'		'Sys Time'		'FunRtc'
)
FAN_SUB=(
'FAN'		'RPM'			'FunFanRpm'
)
ODD_SUB=(
'ODD'	'ID'			'FunOddId'
'ODD'	'F/W'			'FunOddVer'
)
VOL_SUB=(
'Volume'	'Node'			'FunVolNode'
'Volume'	'Mount'			'FunVolMount'
'Volume'	'RAID'			'FunVolRaid'
'Volume'	'Dev'			'FunVolRaidDev'
)
LED_SUB=(
'LED'		'BAY(1 2)'	'FunLed'
)
BAY_SUB=(
'Bay'		'ID'			'FunBayId'
'Bay'		'Capa'			'FunBayCapa'
'Bay'		'Temp SMART'	'FunBayGetSmart'
)
#===============================================================================
# Description	:	Service Code
# input 		:	1()
# output 		:   1()
#===============================================================================
Fun_RmtEncScode()
{
	sEnc=$1
}

Fun_RmtHddScode()
{
	sHdd=$1
}

Fun_RmtODD()
{
	#echo "Fun_RmtODD($*)"
	log_func ODDTEST

	#if [ $? = 0 ]; then
	if [ -d /sys/block/sr0 ]; then
		#sNODE="sr0"
		NODE="sr0"
                sNODE=`udevinfo -a -n ${NODE} | grep -w "KERNEL" | cut -d '"' -f2`
    		if [ "$sNODE" != "$spODDNODE" ]; then Fun_RmtEncScode $SCODE_ODD_NODE;   return 1; fi
		sVENDOR=`cat /sys/block/${sNODE}/device/vendor`
		sMODEL=`cat /sys/block/${sNODE}/device/model`
	        bOMLcnt=${#spODDMODELLIST[*]}
    		bEcnt=0
    		while [ "$bEcnt" -lt "$bOMLcnt" ]; do
      			if [ "$sMODEL" != "${spODDMODELLIST[$bEcnt]}" ]; then
        			bEcnt=$(($bEcnt + 1))
        			continue;
      			else
        			sVERSION=`cat /sys/block/${sNODE}/device/rev`;
        			return 0
      			fi
    		done
    		Fun_RmtEncScode $SCODE_ODD_MODEL;
    		return 1
	else
		Fun_RmtEncScode $SCODE_ODD_CONNECT
		return 1
	fi
}

Fun_RmtSysSwap()
{
	log_func SWAPTEST

	VOL_NUM=$(nas-storage get vol_num)
	VOl_CNT=1

	#SCODE-SYS_DEGRADED
	VOL_LIST=$(nas-storage get vol_list)
	for VOL in $VOL_LIST
	do
	  MOUNT_OK=$(mount |grep "/mnt/disk/$VOL")
	  if [ -n "$MOUNT_OK" ]; then
	    VOL_CNT=$((VOL_CNT+1))
#	    sMdQuerySys=(`mdadm -Q /dev/md${VOL_CNT} | grep detail`)
	    sMdQuerySys=(`cat /proc/mdstat | grep md2 | awk '{print $3}'`)
     	    if [ $? != 0 ] || [ "$sMdQuerySys" != "active" ]; then
	     Fun_RmtEncScode $SCODE_SYS_DEGRADED
	     return 1
	    fi

	  fi
	done

	#SCODE_SYS_LOWFREE ?
	sUse=(`df | grep 'rootfs'`)
	if [ "${sUse[4]%\%}" -gt "80" ] ; then
		Fun_RmtEncScode $SCODE_SYS_LOWFREE
		return 1;
	fi
	#SCODE_SWP_SWAPON ?
#	sSwap=(`cat /proc/meminfo |grep 'SwapTotal'`)
#	if [ "${sSwap[1]}" = "" ] || [ "${sSwap[1]}" -lt "$spSWAPTOTAL" ] ; then
#		Fun_RmtEncScode $SCODE_SWP_SWAPON
#		return 1;
#	fi
	return 0;
}

Fun_RmtUSR()
{
	#echo "Fun_RmtUSR($*)"
	log_func USRTEST
	# check is there Vol1 ?
	VOL_NUM=$(nas-storage get vol_num)
	VOL_LIST=$(nas-storage get vol_list)

	if [ $VOL_NUM -lt 1 ]; then
	     #Fun_RmtEncScode $SCODE_SYS_DEGRADED
	     Fun_RmtEncScode $SCODE_USR_VOL1
	     return 1
	fi


#	if [ $? != 0 ] || [ -z "${sVolConf[1]}" ]; then
#		#no volume1
#		Fun_RmtEncScode $SCODE_USR_VOL1
#		return 1;
#	fi

	# volume all mount ?

	for VOL in $VOL_LIST
	do
	  MOUNT_OK=$(mount |grep "/mnt/disk/$VOL")
	  if [ -z "$MOUNT_OK" ]; then
	    Fun_RmtEncScode $SCODE_USR_MOUNT
	    return 1;
	  fi
	  sUse=$(df | grep /mnt/disk/$VOL)
	  if [ "${sUse[4]%\%}" -gt "90" ] ; then
	    Fun_RmtEncScode $SCODE_USR_LOWFREE
	    return 1;
	  fi
	done
# NS1
	return 0;
}

Fun_RmtNET()
{
	log_func Fun_RmtNET

	link_status=$(cat /var/run/link_status)
	#SCODE_NET_LINKDOWN ?
	echo $link_status | grep 'link up' > /dev/null 2>&1
	if [ $? != 0 ]; then
		Fun_RmtEncScode $SCODE_NET_LINKDOWN
		return 1;
	fi
	#SCODE_NET_IFDOWN ?
	ifconfig | grep eth0 > /dev/null 2>&1
	if [ $? != 0 ]; then
		Fun_RmtEncScode $SCODE_NET_IFDOWN
		return 1;
	fi
	#SCODE_NET_IFCFG ?
	if [ ! -f /etc/network/interfaces ]; then
		Fun_RmtEncScode $SCODE_NET_IFCFG
		return 1;
	fi
	grep 'eth0' /etc/network/interfaces > /dev/null 2>&1
	if [ $? != 0 ]; then
		Fun_RmtEncScode $SCODE_NET_IFCFG
		return 1;
	fi
	return 0;
}

Fun_RmtAPP()
{
	log_func Fun_RmtAPP
	#echo "Fun_RmtAPP($*)"
	bCnt=0
	bTCnt=${#SCODE_APP_NAME[*]}
	sPS=`ps ax`
	while [ "$bCnt" -lt "$bTCnt" ]; do
		echo $sPS | grep  -i ${SCODE_APP_NAME[$bCnt]} > /dev/null 2>&1
		if [ $? != 0 ]; then
			Fun_RmtEncScode ${SCODE_APP_CODE[$bCnt]}
			return 1;
		fi
		bCnt=$(($bCnt + 1))
	done
	return 0
}

Fun_RmtHDD()
{
	sHddFail=0
	sHdd=0
  bCnt=0
	for DISK in sda sdb
	do
#		BAY_IsThereHdd "Bay"${bCnt}
		DISKOK=$(find /sys/block -name $DISK)
		#if [ -z $DISKOK ]; then
		        #sHddFail=$(( 0x80 >> $(($bCnt -1)) ))
			#continue
		#fi
    bCnt=$(($bCnt+1))
		sTempSmart=`FunBaySmart $bCnt`
		if [ $? != 0 ]; then
			sHddFail=$(( 0x80 >> $(($bCnt -1)) ))
		else
			sHddFail=0
		fi
		FunBaySetSmart $bCnt $sTempSmart
		Fun_RmtHddScode $(($sHdd | $sHddFail ))
	done
}
Fun_RmtTest_ENC()
{
	bTcnt=${#Fun_RmtT_ENC[*]}
	bEcnt=0
	while [ "$bEcnt" -lt "$bTcnt" ]; do
		${Fun_RmtT_ENC[$bEcnt]}
		if [ $? != 0 ]; then
			return 1
		fi
		bEcnt=$(($bEcnt + 1))
	done
	return 0
}
Fun_RmtTest_HDD()
{
	bTcnt=${#Fun_RmtT_HDD[*]}
	bHcnt=0
	while [ "$bHcnt" -lt "$bTcnt" ]; do
		${Fun_RmtT_HDD[$bHcnt]}
		if [ $? != 0 ]; then
			return 1
		fi
		bHcnt=$(($bHcnt + 1))
	done
	return 0
}

Diag_Rmt_Boot()
{
	Fun_RmtTest_ENC
	ret_Enc=$?
	#Fun_RmtTest_HDD
	#ret_Hdd=$?
	SSS_SetSvcCode `printf "0x%02x" ${sEnc}` `printf "0x%02x" ${sHdd}`
	sTemp=SC[`printf "%02x" ${sEnc}`_`printf "%02x" ${sHdd}`]
	#if [ "$ret_Enc" != 0 ] || [ "$ret_Hdd" != 0 ]; then
	if [ "$ret_Enc" != 0 ]; then
		iomain -n -m "${sTemp}"
	fi
	return 0
}
#===============================================================================
# Description	:	Web Display
# input 		:	1()
# output 		:   1()
#===============================================================================
FunSvc()
{
	Fun_RmtTest_ENC
	Fun_RmtTest_HDD
	sSvcCode=SVC_CODE[`printf "%02x" ${sEnc}`_`printf "%02x" ${sHdd}`]
}
FunSrn()
{
	sTemp=`fw_printenv serialNo`
	if [ "${sTemp}" != "" ]; then
		echo ${sTemp##*=}
	else
		echo No Serial Number
	fi
}
FunMac()
{
	sTemp=$(nas-network get macaddress)
#	sTemp=`fw_printenv ethaddr`
#	sTemp=${sTemp##*=}
	echo ${sTemp//:/" "}
}
FunVerMain()
{
#	echo `cat /proc/soc_type`
	echo `cat /proc/board_type`
}
FunVerSys()
{
#	echo `cat /etc/sss_script/event/sss_fw_version | grep -i 'sss firmware version'|cut -d ':' -f 2`
	nas-firmware get version
#	echo $(cat /etc/nas/firmware.version)
}
FunVerUbt()
{
#	sTemp=`cat /dev/mtdblock0 |grep 'U-Boot 1.1.'`
	sTemp=$(cat /dev/mtd0 | egrep -a -o "patch version: .*[.][0-9]*")
#	echo ${sTemp}
	echo $(echo $sTemp | sed 's/patch version://')
}
FunVerMcu()
{
#	SSS_VerMcu
	version=$(nas-firmware get micom-version)
#	version=$(iomain -r version | cut -d":" -f 2)
	echo $version
}
FunVerKnl()
{
	sTemp=`uname -a`
	REV=$(sysctl -n kernel.revision)
	echo ${sTemp//:/"/"}
	echo "REV.$REV"
}
FunRtc()
{
	sTemp=`date`
	echo ${sTemp//:/"/"}
}
FunFanRpm()
{
	FAN_CONF="/etc/nas/fan.conf"
	RPM_SILENT=1000
	RPM_NORM=1300
	RPM_COOL=1650

	PWM_SILENT=36
	PWM_NORM=42
	PWM_COOL=96

	if [ ! -e "$FAN_CONF" ]; then
	  touch $FAN_CONF
	  echo "SILENT  24" >>$FAN_CONF
	  echo "NORM    34" >>$FAN_CONF
	  echo "COOL    84" >>$FAN_CONF
	fi
	RPM=$(iomain -r fan | cut -d":" -f2)
	STATUS=$(cat /var/run/fan_status)
	if [ "$STATUS" = "RPM_SILENT" ]; then
	  PWM=$(get_conf_blank SILENT $FAN_CONF)
	  while [[ "$PWM" -lt "$PWM_SILENT" && "$RPM" -lt "$RPM_SILENT" ]];
	  do
	    PWM=$((PWM+2))
	    iomain -w "$PWM"
	    sleep 4
	    RPM=$(iomain -r fan | cut -d":" -f2)
	  done	
	  replace_conf_blank SILENT "$PWM" $FAN_CONF
	elif [ "$STATUS" = "RPM_NORM" ]; then
	  PWM=$(get_conf_blank NORM $FAN_CONF)
	  while [[ "$PWM" -lt "$PWM_SILENT" && "$RPM" -lt "$RPM_SILENT" ]];
	  do
	    PWM=$((PWM+2))
	    iomain -w "$PWM"
	    sleep 4
	    RPM=$(iomain -r fan | cut -d":" -f2)
	  done	
	  replace_conf_blank NORM "$PWM" $FAN_CONF
	else	#RPM_COOL
	  PWM=$(get_conf_blank COOL $FAN_CONF)
	  while [[ "$PWM" -lt "$PWM_SILENT" && "$RPM" -lt "$RPM_SILENT" ]];
	  do
	    PWM=$((PWM+2))
	    iomain -w "$PWM"
	    sleep 4
	    RPM=$(iomain -r fan | cut -d":" -f2)
	  done	
	  replace_conf_blank COOL "$PWM" $FAN_CONF
	fi
	echo $RPM
}
FunOddId()
{
#	sNODE=`cat /etc/sss_script/disk/scsi_list |grep "host4"|awk '{print $1}'`
	sNODE="sr0"
	sVENDOR=`cat /sys/block/${sNODE}/device/vendor`
	sMODEL=`cat /sys/block/${sNODE}/device/model`
	echo ${sVENDOR} ${sMODEL}
}
FunOddVer()
{
#	sNODE=`cat /etc/sss_script/disk/scsi_list |grep "host4"|awk '{print $1}'`
	sNODE="sr0"
	sVERSION=`cat /sys/block/${sNODE}/device/rev`
	echo ${sVERSION}
}
FunLed()
{
#	sTemp=(`cat /etc/sss_script/disk/disk_led_status|awk '{print $2}'`)
	LED_HDD1=$(hibernate -r 20 | cut -d":" -f2)
	LED_HDD2=$(hibernate -r 22 | cut -d":" -f2)

	if [ "$BAY_NAME_CONVERT" = "on" ]; then
		echo $LED_HDD2 $LED_HDD1
	else
		echo $LED_HDD1 $LED_HDD2
	fi
}

FunVolNode()
{
#	sTemp=(`cat ${VOLCONF} | grep "Vol${1}"`)
#	if [ "${sTemp[1]%%[0-9]}" = 'md' ]; then
#		echo ${sTemp[1]}
#	else
#		echo ${sTemp[4]}
#	fi
	DISK=$1
	sTemp=$(mount |grep "/mnt/disk/$DISK" | awk '{ print $1 }')

	if [ $? = 0 ]; then
	  echo $sTemp
	else
	  echo "Mount Fail"
	fi

}
FunVolMount()
{
#	for DISK in disk1 disk2 raid linear
#	do
	DISK=$1
	sTemp=$(mount |grep "/mnt/disk/$DISK" | awk '{ print $3 }')

	if [ $? = 0 ]; then
	  echo $sTemp
	else
	  echo "Mount Fail"
	fi

}
# $1 : volume1, volume2
FunVolRaid()
{
	DISK=$1
	RAID=$(nas-storage get vol_raid $DISK)
	echo $RAID
}
FunVolRaidDev()
{
#	sTemp=(`cat ${VOLCONF} | grep "Vol${1}"`)
	DISK=$1
	VOL_NUM=$(nas-storage get vol_num)
if false; then
	if [ "$DISK" = "disk1" ]; then
	  echo "Bay1"
	elif [ "$DISK" = "disk2" ]; then
	  echo "Bay2"
	else
	  echo "Bay1 Bay2"
	fi
fi
}

# $1 : sda or sdb
FunBayId()
{
	sNode=$1
	sVendor=`cat /sys/block/${sNode}/device/vendor`
	sModel=`cat /sys/block/${sNode}/device/model`
	echo $sVendor $sModel
}
FunBayCapa()
{
	sCapaUnit=("B" "K" "M" "G" "T")
	sNode=$1
	sSecSize=`cat /sys/block/${sNode}/size`
	sSize=$((${sSecSize} * 512)) #Byte
	bCapaCnt=0
	while :
	do
		if [ "${sSize}" -gt 1000 ]; then
			sSize=$((${sSize} / 1000)) #KByte
		else
			break
		fi
		bCapaCnt=$((${bCapaCnt} + 1))
	done
	echo ${sSize}${sCapaUnit[${bCapaCnt}]}
}
FunBaySmart()
{
	#echo "39C OK"
	#echo "39C NG A8"
#	sNode=`VOL_BayToDev Bay${1}`
	Node=$1
        sNode=`cat /var/run/scsi_list | grep "DISK${Node}" | awk '{print $1}'`    # kingsson add
	sDev="/dev/"${sNode}
	sSmartData=`smartctl -A -d marvell -s on ${sDev}`
	[ $? = 0 ] || return 1;

	sTemper=`echo ${sSmartData##*Temperature_Celsius }|awk '{print $8}'`
	sSpinup=`echo ${sSmartData##*Spin_Up_Time }|awk '{if($2>$4) print "0x0"; else print "0x80";}'`
	sRsc=`echo ${sSmartData##*Reallocated_Sector_Ct }|awk '{if($2>$4) print "0x0"; else print "0x40";}'`
	sSer=`echo ${sSmartData##*Seek_Error_Rate }|awk '{if($2>$4) print "0x0"; else print "0x20";}'`
	sCpsc=`echo ${sSmartData##*Current_Pending_Sector }|awk '{if($2>$4) print "0x0"; else print "0x10";}'`
	sUcer=`echo ${sSmartData##*UDMA_CRC_Error_Count }|awk '{if($2>$4) print "0x0"; else print "0x8";}'`

	sSmartRet=$(( ${sSpinup} | ${sRsc} | ${sSer} | ${sCpsc} | ${sUcer} ))
	if [ "$sSmartRet" != 0 ]; then
		sSmartRet="NG "`printf "%0X" ${sSmartRet}`
		echo ${sTemper}"C" ${sSmartRet}
		return 1
	else
		sSmartRet="OK"
		echo ${sTemper}"C" ${sSmartRet}
		return 0
	fi
	#sSmartSelf=`echo FunBaySelf`
}
FunBayGetSmart()			# kingsson mod
{
	# TEMPERATURE=$(iomain -r temp| cut -d":" -f2)
	# echo "$TEMPERATURE C"
	# case "$1" in
	case "$2" in
	"1")
		echo $SMART_RET_Bay1
		;;
	"2")
		echo $SMART_RET_Bay2
		;;
	"3")
		echo $SMART_RET_Bay3
		;;
	"4")
		echo $SMART_RET_Bay4
		;;
	esac
}
FunBaySetSmart()
{
	case "$1" in
	"1")
		SMART_RET_Bay1=$2
		;;
	"2")
		SMART_RET_Bay2=$2
		;;
	"3")
		SMART_RET_Bay3=$2
		;;
	"4")
		SMART_RET_Bay4=$2
		;;
	esac
}
DIAG_Init()
{
	DIAG_ITEM=""
	DIAG_SUBITEM=""
	DIAG_RET=""
	cat /dev/null > ${DIAGLOGFILE}
}
DIAG_Log()
{
	echo ${DIAG_ITEM}:${DIAG_SUBITEM}:${DIAG_RET} >> ${DIAGLOGFILE}
}

DIAG_Item()
{
	DIAG_ITEM=$*
}
DIAG_SubItem()
{
	DIAG_SUBITEM=$*
}
DIAG_Ret()
{
	DIAG_RET=$*
	DIAG_Log
}

DIAG_SRN()
{
	#echo "DIAG_SRN($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#SRN_SUB[*]} ] ;	do
		DIAG_Item 		${SRN_SUB[$bSubCnt]}
		DIAG_SubItem 	${SRN_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${SRN_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

DIAG_MAC()
{
	#echo "DIAG_MAC($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#MAC_SUB[*]} ] ;	do
		DIAG_Item 		${MAC_SUB[$bSubCnt]}
		DIAG_SubItem 	${MAC_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${MAC_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

DIAG_VER()
{
	#echo "DIAG_VER($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#VER_SUB[*]} ] ;	do
		DIAG_Item 		${VER_SUB[$bSubCnt]}
		DIAG_SubItem 	${VER_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${VER_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

DIAG_RTC()
{
	#echo "DIAG_RTC($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#RTC_SUB[*]} ] ;	do
		DIAG_Item 		${RTC_SUB[$bSubCnt]}
		DIAG_SubItem 	${RTC_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${RTC_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

DIAG_FAN()
{
	#echo "DIAG_FAN($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#FAN_SUB[*]} ] ;	do
		DIAG_Item 		${FAN_SUB[$bSubCnt]}
		DIAG_SubItem 	${FAN_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${FAN_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

DIAG_ODD()
{
	#echo "DIAG_ODD($*)"
	bSubCnt=0
	sSubItem=""
	sRet=""
	while [ "$bSubCnt" -lt ${#ODD_SUB[*]} ] ;	do
		sSubItem=${sSubItem}${ODD_SUB[$(($bSubCnt + 1))]}" "
		sRet=${sRet}`${ODD_SUB[$(($bSubCnt + 2))]}`" "
		bSubCnt=$(($bSubCnt + 3))
	done
	DIAG_Item ${ODD_SUB[0]}${1}
	DIAG_SubItem $sSubItem
	DIAG_Ret $sRet
}

DIAG_LED()
{
	#echo "DIAG_LED($*)"
	bSubCnt=0
	while [ "$bSubCnt" -lt ${#LED_SUB[*]} ] ;	do
		DIAG_Item 		${LED_SUB[$bSubCnt]}
		DIAG_SubItem 	${LED_SUB[$(($bSubCnt + 1))]}
		DIAG_Ret		`${LED_SUB[$(($bSubCnt + 2))]}`
		bSubCnt=$(($bSubCnt + 3))
	done
}

# $1: volume type (disk1,disk2,raid,linear)
# $2: volume number (1,2)

DIAG_VOL_SUB()
{
	#echo "DIAG_VOL_SUB($*)"
	bSubCnt=0
	sSubItem=""
	sRet=""
	while [ "$bSubCnt" -lt ${#VOL_SUB[*]} ] ;	do
		sSubItem=${sSubItem}${VOL_SUB[$(($bSubCnt + 1))]}" "
		sRet=${sRet}`${VOL_SUB[$(($bSubCnt + 2))]} ${1}`" "
		bSubCnt=$(($bSubCnt + 3))
	done

	DIAG_Item ${VOL_SUB[0]}${2}
	DIAG_SubItem $sSubItem
	DIAG_Ret $sRet
}

# $1: disk type (sda,sdb)
# $2: disk number (1,2)

DIAG_BAY_SUB()
{
	#echo "DIAG_BAY_SUB($*)"
	bSubCnt=0
	sSubItem=""
	sRet=""
#	DIAG_Item `BAY_ToDisplayBayName ${BAY_SUB[0]}${1}`
	DIAG_Item ${BAY_SUB[0]}${2}
#	BAY_IsThereHdd "Bay"${1}
#	if [ $? != 0 ]; then
#		sSubItem="Not detected"
#		sRet="Cannot detect HDD"
#	else
	while [ "$bSubCnt" -lt ${#BAY_SUB[*]} ] ;	do
		sSubItem=${sSubItem}${BAY_SUB[$(($bSubCnt + 1))]}" "
#		sRet=${sRet}`${BAY_SUB[$(($bSubCnt + 2))]} ${1}`" "
		sRet=${sRet}`${BAY_SUB[$(($bSubCnt + 2))]} ${1} ${2}`" "
		bSubCnt=$(($bSubCnt + 3))
	done
#	fi
	DIAG_SubItem $sSubItem
	DIAG_Ret $sRet
}

DIAG_VOL()
{
	#echo "DIAG_VOL($*)"
	bVolCnt=0
#	VOL_LIST=$(nas-storage get vol_list)
	for VOL in volume1 volume2
	do
	  MOUNT_OK=$(mount |grep "/mnt/disk/$VOL")
	  if [ -n "$MOUNT_OK" ]; then
	    bVolCnt=$((bVolCnt+1))
	    DIAG_VOL_SUB $VOL $bVolCnt
	  fi
	done

#	while [ "$bVolCnt" -lt 2 ] ;	do
#		bVolCnt=$(($bVolCnt + 1))
#		sTemp=(`cat ${VOLCONF} | grep "Vol${bVolCnt}"`)
#		if [ "${sTemp[1]}" != "" ]; then
#			DIAG_VOL_SUB $bVolCnt
#		else
#			continue
#		fi
}

DIAG_BAY()
{
	#echo "DIAG_BAY($*)"
	bDiskCnt=0
	for DISK in "1:0:0:0" "1:0:1:0"
	do
	  bDiskCnt=$((bDiskCnt+1))
	  DEV_EXIST=$(find /sys/block/sd* -name device | xargs ls -l | grep $DISK)
	  if [ ! -z "$DEV_EXIST" ]; then
	    DEV_NODE=$(echo $DEV_EXIST | cut -d " " -f 9 | cut -d "/" -f 4)
	    DIAG_BAY_SUB $DEV_NODE $bDiskCnt
	  fi
	done

}

#DIAG_WebLog()
#{
#	local FUNC "WEB_DIAG"
	lcd_msg "Web Diag Test"
	#led_set 20 off
    	#led_set 22 off

	svcpw=`cat /etc/shadow |grep svcuser: | cut -d':' -f2`
	[ ! -z "${svcpw//$/}" ] && /etc/init.d/telnetd start;

	bWCnt=0
	DIAG_Init

	DIAG_Item 		'SVC_CODE'
	DIAG_SubItem 	'Service Code'
	FunSvc
	DIAG_Ret		"${sSvcCode}"
	#SSS_SetSvcCode `printf "0x%02x" ${sEnc}` `printf "0x%02x" ${sHdd}`
	lcd_msg "${sSvcCode}"


	while [ "$bWCnt" -lt ${#DIAG_ITEM_NAME[*]} ] ;	do
		DIAG_${DIAG_ITEM_NAME[$bWCnt]}
		log_func $FUNC $bWCnt DIAG_${DIAG_ITEM_NAME[$bWCnt]}
		bWCnt=$(($bWCnt + 1))
	done
	lcd_msg_time "DIAG Completed"
#}
