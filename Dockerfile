FROM php:7.1.2-apache
RUN apt-get update -q && \
    a2enmod env && \
    a2enmod rewrite && \
    apt-get install zip unzip  && \
    docker-php-ext-install pdo_mysql && \
    rm -rf /var/www/devops && \
    mkdir 0777 /var/www/devops && \
    useradd -g www-data -d /var/www/devops -s /bin/bash -p $(echo devops | openssl passwd -1 -stdin) devops && \
    chown -R devops:www-data /var/www/devops && \
    apt-get install -y git && \
    apt-get install openssh-client

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/devops
RUN rm -rf /var/www/devops/*

COPY www www
COPY src src
COPY tests tests
COPY data data
COPY config config
COPY deploy deploy
COPY console console
COPY composer.json composer.json

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin && \
    su - devops -c "composer.phar install --prefer-dist"

RUN chown -R devops:www-data  /var/www/devops

CMD /usr/sbin/apache2ctl -D FOREGROUND
EXPOSE 80

RUN chmod 777 deploy/prepareEnv.sh
RUN ./deploy/prepareEnv.sh
