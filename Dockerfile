FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

ARG USER=www-data
ENV PHP_VERSION=8.1

RUN apt update
RUN apt install lsb-release ca-certificates apt-transport-https software-properties-common -y
RUN add-apt-repository ppa:ondrej/php

RUN apt-get install -y --no-install-recommends \
    curl zip unzip mc cron nginx supervisor \
    zlib1g-dev libpng-dev libicu-dev libcurl4-openssl-dev libonig-dev libxml2-dev libzip-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists*

RUN apt-get update && apt-get -y install php${PHP_VERSION}-cli php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-intl php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-opcache php${PHP_VERSION}-zip php${PHP_VERSION}-exif


RUN chown -R nobody:nogroup /run

#    docker-php-ext-install intl mbstring mysqli opcache pdo pdo_mysql zip exif

#RUN echo "date.timezone=${PHP_TIMEZONE=Europe/Bucharest}" >> ${PHP_INI_DIR}/conf.d/custom.ini && \
#    echo "memory_limit=${PHP_MEMORY_LIMIT:-2G}" >> ${PHP_INI_DIR}/conf.d/custom.ini && \
#    echo "file_upoads=${PHP_FILE_UPLOADS:-On}" >> ${PHP_INI_DIR}/conf.d/custom.ini && \
#    echo "upload_max_filesize=${PHP_UPLOAD_MAX_FILESIZE:-1G}" >> ${PHP_INI_DIR}/conf.d/custom.ini && \
#    echo "post_max_size=${PHP_POST_MAX_SIZE:-2G}" >> ${PHP_INI_DIR}/conf.d/custom.ini && \
#    echo "max_execution_time=${PHP_MAX_EXECUTION_TIME:-1700}" >> ${PHP_INI_DIR}/conf.d/custom.ini

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

RUN apt-get -y install composer

WORKDIR /var/www/html

COPY composer.json /var/www/html

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
