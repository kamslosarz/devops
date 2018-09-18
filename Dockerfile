FROM php:7.1.2-apache

RUN apt-get update -q && \
    a2enmod env && \
    a2enmod rewrite && \
    apt-get install zip unzip  && \
    docker-php-ext-install pdo_mysql && \
    rm -rf /var/www/devops && \
    mkdir 0777 /var/www/devops && \
    useradd -g www-data -d /var/www/devops -s /bin/bash -p $(echo devops | openssl passwd -1 -stdin) devops && \
    chown -R devops /var/www/devops

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/devops

COPY composer.json composer.json
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin && \
    su - devops -c "composer.phar install --prefer-dist"

COPY www www
COPY src src
COPY tests tests
COPY data data
COPY config config
COPY console console

RUN mkdir -p www/assets logs data && \
    chmod 777 -R www/assets logs data && \
    rm -rf www/assets/* && \
    chown -R devops www

RUN cd data && \
    ../vendor/bin/propel model:build && \
    ../vendor/bin/propel sql:build && \
    ../vendor/bin/propel sql:insert && \
    cd ..

CMD console/console cache:build
CMD /usr/sbin/apache2ctl -D FOREGROUND

EXPOSE 80
