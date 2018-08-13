FROM alpine:3.8
ADD ./docker/entrypoint.sh /

RUN apk update \
    && apk add --no-cache \
                php7 \
                php7-mcrypt \
                php7-bcmath \
                php7-pdo_mysql \
                php7-phar \
                php7-json \
                php7-mbstring \
                php7-openssl \
                php7-curl \
                php7-ctype \
                php7-fpm \
                php7-pear \
                php7-dev \
                php7-pcntl \
                php7-tokenizer \
                php7-dom \
                php7-xmlwriter \
                php7-simplexml \
                php7-soap \
                php7-intl \
                php7-session \
                php7-apcu \
                php7-opcache \
                php7-posix \
                php7-iconv \
                autoconf \
                apache2 \
                apache2-utils \
                apache2-proxy \
                util-linux \
                curl \
                cmake \
                make \
                gcc \
                libc-dev \
                openssl-dev \
                git \
                openssh \
                redis \
                rsyslog \
    && mkdir /run/apache2 \
    && mkdir -p /var/www/opcache \
    && chown apache /var/www/opcache \
    && chmod +x /entrypoint.sh \
    && rm -rf /var/cache/apk/* /tmp/* \
    && mkdir -p /tmp/php-fpm \
    && chown apache /tmp/php-fpm

# Add apache to run and configure
COPY ./docker/etc/apache2/default.conf /etc/apache2/conf.d/default.conf
COPY ./docker/etc/pool.d/alpine-www.conf /etc/php7/php-fpm.d/www.conf

WORKDIR /var/www/html
CMD sh /entrypoint.sh
