#!/bin/bash

cp /etc/nas/firmware-odd.date /boot/

rm -rf $(echo /var/* | sed "s/\/var\/lock//")
rm -rf /etc/*
rm -rf /usr/var/*
rm -rf /lib/modules/$(uname -r)/*
#rm -rf /tmp/*

rm -rf /boot/update/*

exit 0 
