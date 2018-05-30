FROM php:7.1.2-apache

RUN apt-get update -q && \
    a2enmod rewrite && \
    docker-php-ext-install pdo_mysql && \
    curl --show-error https://getcomposer.org/installer | php && \
    rm -rf /var/www/devops && \
    mkdir 755 /var/www/devops && \
    useradd -g users -d /var/www/devops -s /bin/bash -p $(echo devops | openssl passwd -1 -stdin) devops && \
    chown -R devops /var/www/devops

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf
ADD . /var/www/devops
RUN chmod 777 -R /var/www/devops/www/assets && \
    chmod 777 -R /var/www/devops/logs

CMD /usr/sbin/apache2ctl -D FOREGROUND

WORKDIR /var/www/devops
EXPOSE 80
