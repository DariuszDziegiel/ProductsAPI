#!/bin/bash

chmod -R 0777 var/

if [ ! -d vendor ]; then
    echo "--------------------------------------------------------"
    echo "Composer install"
    echo "--------------------------------------------------------"
    composer install --optimize-autoloader --no-interaction
fi

echo "--------------------------------------------------------"
echo "Waiting for MySQL to be ready..."
echo "--------------------------------------------------------"
while ! mysqladmin ping -h mysql -u products -pproducts --silent; do
    echo "MySQL is not yet ready. Retrying in 1 second..."
    sleep 1
done


echo "------Running doctrine migrations-------------"
bin/console doctrine:migration:migrate -n -vv
echo "--------------------------------------------------------"

echo "------Messenger transports setup-------------"
bin/console messenger:setup-transports failed
bin/console messenger:setup-transports async

echo "--------------------------------------------------------"
echo "Start supervisord"
echo "--------------------------------------------------------"
exec supervisord -n -c /etc/supervisor/supervisord.conf
