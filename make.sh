#!/bin/bash
#
# Install Helper
#
# SPDX-License-Identifier: MIT
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail

APP_ROOT=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

cd "$APP_ROOT"

composer install --no-ansi --no-progress --classmap-authoritative

npm install --no-audit --no-fund

mkdir -p var/
chown -R www-data:www-data var/
chown openthc:www-data var/
chmod 02770 var/

mkdir -p webroot/output/
chown -R www-data:www-data webroot/output/
chown openthc:www-data webroot/output/
chmod 02770 webroot/output/
