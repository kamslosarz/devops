FROM php:7.1.2-apache

RUN apt-get update -q
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql

ADD . /var/www/devops/
ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf
CMD sudo chmod 777 /var/www/devops/logs -R
CMD /usr/sbin/apache2ctl -D FOREGROUND

EXPOSE 80
