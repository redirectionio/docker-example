FROM nginx:1.15

RUN apt update \
    && apt install -y nginx build-essential gawk wget unzip \
    libssl-dev libpcre3-dev libzip-dev libxml2-dev libxslt-dev libgd-dev libgeoip-dev libperl-dev \
    gawk

# get nginx redirection.io module sources
WORKDIR /tmp

RUN wget https://github.com/redirectionio/libnginx-mod-redirectionio/archive/master.zip
RUN unzip master.zip

# get nginx sources
WORKDIR /root

RUN NGINX_VERSION=`nginx -v 2>&1 | gawk 'match($0,/nginx version: nginx\/([0-9\.]+?)/,a) {print a[1]}'` && \
    echo $NGINX_VERSION && \
    wget http://nginx.org/download/nginx-$NGINX_VERSION.tar.gz && \
    tar -xzvf nginx-$NGINX_VERSION.tar.gz && \
    mv /root/nginx-$NGINX_VERSION /root/nginx && \
    rm nginx-$NGINX_VERSION.tar.gz

# build the module
WORKDIR /root/nginx

RUN NGINX_CONFIGURE_ARGUMENTS=`nginx -V 2>&1 | grep 'configure arguments:' | cut -d\  -f3-` && \
    NGINX_MODULES_PATH=`nginx -V 2>&1 | gawk 'match($0,/--modules-path=(\S+?)/,a) {print a[1]}'` && \
    eval "./configure $NGINX_CONFIGURE_ARGUMENTS --add-dynamic-module=/tmp/libnginx-mod-redirectionio-master" && \
    make -j8 modules && \
    cp objs/ngx_http_redirectionio_module.so $NGINX_MODULES_PATH/ngx_http_redirectionio_module.so

# Configuration
COPY etc/nginx/nginx.conf      /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx"]
