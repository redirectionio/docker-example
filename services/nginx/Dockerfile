FROM ubuntu:20.04

RUN DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -y \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        apt-transport-https \
        gnupg2 \
        nginx \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN wget -q -O - https://packages.redirection.io/gpg.key | apt-key add - \
    && echo "deb https://packages.redirection.io/deb/stable/2 focal main" | tee -a /etc/apt/sources.list.d/packages_redirection_io_deb.list \
    && apt-get update \
    && apt-get install -y \
        libnginx-mod-redirectionio \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Configuration
COPY etc /etc

CMD ["nginx", "-g", "daemon off;"]
