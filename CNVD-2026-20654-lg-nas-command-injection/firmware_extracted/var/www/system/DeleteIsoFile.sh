#!/bin/sh

# Iso File created cd burning is deleted.

if [ -e /var/www/system/burn.iso ]; then
  rm -rf /var/www/system/burn.iso
fi

