FROM httpd:2.4

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
        apache2-dev \
        procps \
        unzip \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# install Rust
ENV RUSTUP_HOME=/opt/rust
ENV CARGO_HOME=/opt/rust
RUN curl https://sh.rustup.rs -sSf | sh -s -- --default-toolchain 1.90.0 -y --no-modify-path
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

# get apache2 redirection.io module sources
WORKDIR /tmp
RUN wget -O libapache2-mod-redirectionio-master.zip https://github.com/redirectionio/libapache2-mod-redirectionio/archive/master.zip \
    && unzip libapache2-mod-redirectionio-master.zip

# build the module
WORKDIR /tmp/libapache2-mod-redirectionio-master
RUN autoreconf -i \
    && ./configure \
    && make clean \
    && make \
    && make install

# Configuration
COPY usr /usr

RUN echo "LoadModule redirectionio_module /usr/lib/apache2/modules/mod_redirectionio.so" >> /usr/local/apache2/conf/httpd.conf
RUN echo "Include conf/extra/httpd-vhosts.conf" >> /usr/local/apache2/conf/httpd.conf
