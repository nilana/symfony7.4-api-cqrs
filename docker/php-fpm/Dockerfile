FROM phpdockerio/php:8.5-fpm
WORKDIR "/app"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.5-mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && sed -i 's|listen = /run/php/php8.5-fpm.sock|listen = 9000|' /etc/php/8.5/fpm/pool.d/www.conf

CMD ["sh", "-c", "composer install && \
php bin/console doctrine:database:create | true && \
php bin/console doctrine:schema:drop --force && \
php bin/console doctrine:schema:update --force --complete && \
php bin/console lexik:jwt:generate-keypair --overwrite && \
php-fpm8.5"]
