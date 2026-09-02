#!/bin/sh
set -eu

cd "$(dirname "$0")"

case "${1:-}" in
    build)
        docker compose build
        ;;
    start)
        docker compose up -d --build --remove-orphans --wait --wait-timeout 90
        ;;
    stop)
        docker compose down
        ;;
    restart)
        docker compose restart
        docker compose up -d --wait --wait-timeout 90
        ;;
    logs)
        docker compose logs -f
        ;;
    status)
        docker compose ps
        ;;
    shell)
        docker compose exec lg-nas /bin/sh
        ;;
    verify)
        docker compose exec -T lg-nas sh -ec '
            version=$(nas-firmware get version)
            echo "VERSION=$version"
            test "$version" = "1.0.0_2569"

            file_count=$(find /var/www -type f | wc -l)
            echo "WEB_FILES=$file_count"
            test "$file_count" = "5395"

            tr "\000" " " < /proc/1/cmdline | grep -q "/sbin/init"
            test ! -e /var/run/nas/booting
            test -x /usr/bin/php-cgi
            test -x /usr/sbin/lighttpd
            test -f /etc/nas/db/share.db
            test -f /var/www/en/php/share_set_user_info.php

            sqlite3 /etc/nas/db/share.db ".tables" | grep -q "user"
            wget -q -O /tmp/verify-login.html http://127.0.0.1:8000/en/login/login.php
            grep -q "Welcome to LG Electronics" /tmp/verify-login.html
            wget -q -O /tmp/verify-apache.html http://127.0.0.1:9090/index.html
            grep -q "Refresh" /tmp/verify-apache.html
            rm -f /tmp/verify-login.html /tmp/verify-apache.html

            echo "BOOT_SEQUENCE=ok"
            echo "LIGHTTPD_PHP=ok"
            echo "APACHE=ok"
            echo "SQLITE=ok"
            echo "FIRMWARE_ROOTFS=ok"
        '
        ;;
    clean)
        docker compose down -v
        ;;
    *)
        echo "Usage: $0 {build|start|stop|restart|logs|status|shell|verify|clean}"
        exit 1
        ;;
esac
