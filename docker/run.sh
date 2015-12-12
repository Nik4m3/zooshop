#!/bin/bash

set -e

function setEnvironmentVariable() {
    if [ -z "$2" ]; then
            echo "Environment variable '$1' not set."
            return
    fi
    echo "env[$1] = \"$2\"" >> /etc/php5/fpm/pool.d/www.conf
}

# Grep all ENV variables
for _curVar in `env | awk -F = '{print $1}'`;do
    # awk has split them by the equals sign
    # Pass the name and value to our function
    setEnvironmentVariable ${_curVar} ${!_curVar}
done

# prepare log output
touch /var/log/nginx/access.log \
      /var/log/nginx/error.log \

# start PHP and nginx
composer install
service php5-fpm start
/usr/sbin/nginx
