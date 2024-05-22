FROM ubuntu:22.04

RUN DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -y \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        apt-transport-https \
        gettext \
        gnupg2 \
        nginx \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN mkdir -p /etc/apt/keyrings \
    && wget -qO- https://packages.redirection.io/gpg.key | tee /etc/apt/keyrings/redirectionio-archive-keyring.asc \
    && echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/redirectionio-archive-keyring.asc] https://packages.redirection.io/deb/stable/2 jammy main" | tee -a /etc/apt/sources.list.d/packages_redirection_io_deb.list \
    && apt-get update \
    && apt-get install -y \
        libnginx-mod-redirectionio \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Configuration
COPY default.template /tmp/default.template

COPY entrypoint.sh /tmp/entrypoint.sh
ENTRYPOINT ["/tmp/entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]
