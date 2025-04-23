#!/bin/bash

chmod -R 0775 var/

if [ ! -d vendor ]; then
    echo "--------------------------------------------------------"
    echo "Composer install"
    echo "--------------------------------------------------------"
    composer install --optimize-autoloader --no-interaction
fi

composer dump-autoload -o

echo "--------------------------------------------------------"
echo "Start supervisord"
echo "--------------------------------------------------------"
exec supervisord -n -c /etc/supervisor/supervisord.conf
