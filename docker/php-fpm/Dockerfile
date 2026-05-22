FROM phpdockerio/php:8.5-fpm
WORKDIR "/app"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.5-mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
