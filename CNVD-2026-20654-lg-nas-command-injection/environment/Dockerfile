FROM scratch

# Build the image from the pristine vendor root filesystem rather than from a
# modern distribution with a rewritten vulnerable endpoint.
ADD firmware_extracted/firmware.tar.gz /

LABEL org.opencontainers.image.title="CNVD-2026-20654 LG NAS firmware userspace" \
      org.opencontainers.image.version="1.0.0_2569" \
      org.opencontainers.image.source="cnvd-2026-20654-lg-nas-command-injection"

# The wrapper only prepares container runtime directories. In full mode it
# hands PID 1 to the firmware's original BusyBox init and /etc/init.d/rcS.
COPY --chmod=0755 environment/container-entrypoint.sh /usr/local/sbin/container-entrypoint

EXPOSE 21 80 111 139 443 445 8000 9090 9091 24989

ENTRYPOINT ["/usr/local/sbin/container-entrypoint"]
CMD ["full"]
