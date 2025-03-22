#!/bin/bash
#
# Installer
#

composer install --no-ansi --no-dev --no-progress --quiet --classmap-authoritative

npm install --quiet >/dev/null

mkdir -p var/
chown -R www-data:www-data var/
chown openthc:www-data var/
chmod 02770 var/

mkdir -p webroot/output/
chown -R www-data:www-data webroot/output/
chown openthc:www-data webroot/output/
chmod 02770 webroot/output/
