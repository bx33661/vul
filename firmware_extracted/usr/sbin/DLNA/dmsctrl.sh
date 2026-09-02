#!/bin/sh

# $Id: dmsctrl.sh 1688 2008-07-15 08:51:36Z iwamoto $

BASE=`pwd`
PGM=dms_sync
PGM_PATH=$BASE/$PGM
CONF=conf

MAX_USER_WATCHES=100000
MAX_USER_WATCHES_PATH=/proc/sys/fs/inotify/max_user_watches

start() {
    export DU_LOG_LEVEL=0
    if [ -f $MAX_USER_WATCHES_PATH -a -w $MAX_USER_WATCHES_PATH ]; then
        echo $MAX_USER_WATCHES > $MAX_USER_WATCHES_PATH
    fi
    echo "Starting $PGM..."
    "$PGM_PATH" "$BASE/$CONF" "$BASE"
}

stop() {
    echo "Shutting down $PGM..."
    killall $PGM
}

restart() {
        stop
        sleep 5
        start
}

case "$1" in
  start)
        start
        ;;
  stop)
        stop
        ;;
  restart)
        restart
        ;;
  *)
        echo "Usage: $0 {start|stop|restart}"
        exit 1
esac

exit $?
