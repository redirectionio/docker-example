FROM nginx:1.25

# install build tools
RUN apt update \
    && apt install -y \
        autoconf \
        build-essential \
        curl \
        gawk \
        libgd-dev \
        libgeoip-dev \
        libpcre3-dev \
        libperl-dev \
        libssl-dev \
        libxml2-dev \
        libxslt-dev \
        libzip-dev \
        nginx \
        procps \
        unzip \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# install Rust
ENV RUSTUP_HOME=/opt/rust
ENV CARGO_HOME=/opt/rust
RUN curl https://sh.rustup.rs -sSf | sh -s -- --default-toolchain 1.81.0 -y --no-modify-path
ENV PATH=/opt/rust/bin:$PATH
RUN mkdir -p /opt/rust/registry

# get libredirectionio sources
WORKDIR /tmp
RUN wget -O libredirectionio-master.zip https://github.com/redirectionio/libredirectionio/archive/master.zip \
    && unzip libredirectionio-master.zip

# build libredirectionio
WORKDIR /tmp/libredirectionio-master
RUN autoreconf -i \
    && ./configure \
    && make clean \
    && make \
    && make install

# get nginx redirection.io module sources
WORKDIR /tmp
RUN wget -O libnginx-mod-redirectionio-master.zip https://github.com/redirectionio/libnginx-mod-redirectionio/archive/master.zip \
    && unzip libnginx-mod-redirectionio-master.zip

# get nginx sources
WORKDIR /root
RUN NGINX_VERSION=`nginx -v 2>&1 | gawk 'match($0,/nginx version: nginx\/([0-9\.]+?)/,a) {print a[1]}'` \
    && echo $NGINX_VERSION \
    && wget http://nginx.org/download/nginx-$NGINX_VERSION.tar.gz \
    && tar -xzvf nginx-$NGINX_VERSION.tar.gz \
    && mv /root/nginx-$NGINX_VERSION /root/nginx \
    && rm nginx-$NGINX_VERSION.tar.gz

# build the module
WORKDIR /root/nginx

RUN NGINX_CONFIGURE_ARGUMENTS=`nginx -V 2>&1 | grep 'configure arguments:' | cut -d\  -f3- | sed "s/ --with-cc-opt='/ --with-cc-opt='-I\/tmp\/libredirectionio-master\/target /" | sed "s/ --with-ld-opt='/ --with-ld-opt='-L\/tmp\/libredirectionio-master\/target\/release /"` \
    && NGINX_MODULES_PATH=`nginx -V 2>&1 | gawk 'match($0,/--modules-path=(\S+?)/,a) {print a[1]}'` \
    && eval "./configure $NGINX_CONFIGURE_ARGUMENTS --add-dynamic-module=/tmp/libnginx-mod-redirectionio-master" \
    && make -j modules \
    && cp objs/ngx_http_redirectionio_module.so $NGINX_MODULES_PATH/ngx_http_redirectionio_module.so

# Configuration
COPY etc /etc
