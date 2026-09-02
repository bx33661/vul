#!/bin/sh

MAX_USER_WATCHES=100000
MAX_USER_WATCHES_PATH=/proc/sys/fs/inotify/max_user_watches

if [ -f $MAX_USER_WATCHES_PATH -a -w $MAX_USER_WATCHES_PATH ]; then
    echo $MAX_USER_WATCHES > $MAX_USER_WATCHES_PATH
else
    echo "W: Failed to modify max_user_watches. This program must be executed by root user."
fi

echo I: maximum number of watches is `cat $MAX_USER_WATCHES_PATH` \(expected value is $MAX_USER_WATCHES\).

./dms_sync ./conf
