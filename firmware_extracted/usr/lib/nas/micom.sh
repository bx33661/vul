#!/bin/bash

LIBDIR=$PREFIX/usr/lib/nas
CONFDIR=/etc/nas

#========================================================
# micom.sh
#========================================================

DecToHex(){
      printf "0x%x 0x%x 0x%x 0x%x 0x%x 0x%x 0x%x 0x%x" $1 $2 $3 $4 $5 $6 $7 $8
}
IOWrite(){
      echo `DecToHex $1 $2 $3 $4 $5 $6 $7 $8` > /dev/i2c-micom
}

# Show the Available Free Space in User HDD Configuration

hdd_percent(){
# DATA=`nas-storage get vol_percent`
  DEVS=$(mount | grep "/mnt/disk/" | cut -d" " -f 1)
  if [ -z "$DEVS" ]; then
    iomain -e 10 -n -m "HDD Config First"
  else
    for DEV in $DEVS; do
      DF=$(df $DEV | tail -n 1 | tr -s " ")
      USED_ALL=$((USED_ALL+`echo $DF | cut -d" " -f 3`))
      TOTAL_ALL=$((TOTAL_ALL+`echo $DF | cut -d" " -f 4`))
    done
    USAGE=$((100*USED_ALL/TOTAL_ALL))
    iomain -e 10 -n -m "HDD USAGE[$((USAGE+1))%]"
  fi
}
set_fstab(){
  DATA=`nas-storage get vol_fstab`
  iomain -e 10 -n -m "HDD$DATA"
}
set_date(){
      YEAR=`date | tr -s ' ' | cut -d" " -f 6`
      DATA=`date +" "%-m" "%-d" "%-H" "%-M`
      STR=`printf "/%.2d/%.2d %.2d:%.2d" $DATA`
      iomain -e 10 -n -m "$YEAR$STR"
}
set_temp(){
      DATA=`iomain -r temp | cut -d" " -f 3`
      STR=`printf "Temperature[%.2dC]" $DATA`
      iomain -e 10 -n -m "$STR"
}
set_fwver(){
      DATA=`cat /etc/nas/firmware.version`
      iomain -e 10 -n -m "FW Ver$DATA"
}
info_fan(){
  RPM=$(iomain -r fan|cut -d" " -f 4)
  iomain -e 10 -n -m "FAN[$RPM]"
}
info_ip(){
  ISLINKUP=$(cat /var/run/link_status | grep 'link up')

  if [ "$METHOD" = "static" ] && [ -z "$ISLINKUP" ]; then
    IPADDR=$(cat /etc/network/interfaces | grep address | cut -d" " -f 2)
  else
    IPADDR=$(ifconfig eth0 | grep "inet addr" | cut -d ":" -f 2 | cut -d " " -f 1)
    if [ -z "$IPADDR" ]; then
      IPADDR=$(ifconfig eth0:avahi | grep "inet addr" | cut -d ":" -f 2 | cut -d" " -f 1)
    fi
  fi
  if [ -z "$IPADDR" ]; then
    iomain -e 10 -n -m "I000.000.000.000"
  else
    IP_1st=$(echo $IPADDR | cut -d"." -f 1)
    IP_2nd=$(echo $IPADDR | cut -d"." -f 2)
    IP_3rd=$(echo $IPADDR | cut -d"." -f 3)
    IP_4th=$(echo $IPADDR | cut -d"." -f 4)
    STR=`printf "%.3d.%.3d.%.3d.%.3d" $IP_1st $IP_2nd $IP_3rd $IP_4th`
    iomain -e 10 -n -m "I$STR"
  fi
}
info_micom(){
  VER=$(iomain -r version| cut -d":" -f 2)
  iomain -e 10 -n -m "Micom v.$VER"
}
