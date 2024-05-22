FROM ubuntu:22.04

RUN DEBIAN_FRONTEND=noninteractive apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get dist-upgrade -y \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        apt-transport-https \
        gettext \
        gnupg2 \
        apache2 \
		tzdata \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN mkdir -p /etc/apt/keyrings \
    && wget -qO- https://packages.redirection.io/gpg.key | tee /etc/apt/keyrings/redirectionio-archive-keyring.asc \
    && echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/redirectionio-archive-keyring.asc] https://packages.redirection.io/deb/stable/2 jammy main" | tee -a /etc/apt/sources.list.d/packages_redirection_io_deb.list \
    && apt-get update \
    && apt-get install -y \
        libapache2-mod-redirectionio \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# https://httpd.apache.org/docs/2.4/stopping.html#gracefulstop
STOPSIGNAL SIGWINCH

# Configuration
COPY etc /etc

COPY apache2-foreground /usr/bin/

CMD ["apache2-foreground"]
