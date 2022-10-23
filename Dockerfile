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
    php${PHP_VERSION}-intl php${PHP_VERSION}-mbstring php${PHP_VERSION}-mysql php${PHP_VERSION}-curl \
    php${PHP_VERSION}-opcache php${PHP_VERSION}-zip php${PHP_VERSION}-exif php${PHP_VERSION}-dom


RUN chown -R nobody:nogroup /run

RUN echo "date.timezone=${PHP_TIMEZONE=Europe/Bucharest}" >> /etc/php/8.1/fpm/conf.d/custom.ini && \
    echo "memory_limit=${PHP_MEMORY_LIMIT:-2G}" >> /etc/php/8.1/fpm/conf.d/custom.ini && \
    echo "file_upoads=${PHP_FILE_UPLOADS:-On}" >> /etc/php/8.1/fpm/conf.d/custom.ini && \
    echo "upload_max_filesize=${PHP_UPLOAD_MAX_FILESIZE:-1G}" >> /etc/php/8.1/fpm/conf.d/custom.ini && \
    echo "post_max_size=${PHP_POST_MAX_SIZE:-2G}" >> /etc/php/8.1/fpm/conf.d/custom.ini && \
    echo "max_execution_time=${PHP_MAX_EXECUTION_TIME:-1700}" >> /etc/php/8.1/fpm/conf.d/custom.ini \
    echo "session.cookie_httponly=True" >> /etc/php/8.1/fpm/conf.d/custom.ini \
    echo "session.cookie_secure=True"  >>  /etc/php/8.1/fpm/conf.d/custom.ini \

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

RUN apt-get -y install composer

WORKDIR /var/www/html

COPY composer.json /var/www/html

RUN service php8.1-fpm start

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
