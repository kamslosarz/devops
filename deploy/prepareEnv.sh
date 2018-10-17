#!/usr/bin/env bash

ADMIN_LOGIN=kamil
ADMIN_PASSWORD=111111

rm -rf www/assets && \
touch data/devops.db3 && \
mkdir www/assets logs && \
chmod -R 777 www/assets logs data && \
chown -R devops:www-data www/assets logs data && \
cd data && \
../vendor/bin/propel model:build && \
../vendor/bin/propel sql:build --overwrite && \
../vendor/bin/propel sql:insert && \
cd .. && \
console/console cache:build && \
console/console admin:create $ADMIN_LOGIN $ADMIN_PASSWORD true && \
vendor/bin/phpunit -c tests

#./buildConfig.sh

