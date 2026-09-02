#!/bin/sh
set -eu

prepare_runtime()
{
    mkdir -p \
        /dev/pts \
        /mnt/disk/default/.webtmp \
        /mnt/disk/volume1/myweb \
        /tmp \
        /var/cache/lighttpd/compress \
        /var/lock \
        /var/log \
        /var/run/lighttpd \
        /var/run/nas

    rm -f /tmp/php.socket /var/run/lighttpd.pid
    chown www-data:www-data /var/cache/lighttpd/compress /var/run/lighttpd
    chmod 0750 /var/run/lighttpd
    chmod 1777 /tmp
}

preserve_container_network()
{
    # The appliance requests a fresh DHCP lease during rcS. Inside Docker that
    # replaces the address assigned to eth0 and breaks published ports. Keep
    # the Docker-provided address while leaving the rest of rcS unchanged.
    cat > /etc/network/interfaces <<'EOF'
auto lo
iface lo inet loopback

auto eth0
iface eth0 inet manual
EOF
}

prepare_runtime

case "${1:-full}" in
    full)
        preserve_container_network
        exec /sbin/init
        ;;
    web)
        # Diagnostic mode: original rootfs, original web tree, original
        # lighttpd and original PHP-CGI, without hardware service startup.
        exec /usr/sbin/lighttpd -D -f /etc/lighttpd/lighttpd.conf
        ;;
    shell)
        exec /bin/sh
        ;;
    *)
        exec "$@"
        ;;
esac
