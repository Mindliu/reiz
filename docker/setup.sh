#!/bin/bash

if [ ! -f ../.env ]; then
  cp ../.env.example ../.env
  echo "The .env file has been copied from .env_example successfully. Script was killed. Re-run it after .env file is filled"
  return 1
fi


#Creates an .sql file, which will be launched on docker compose setup
#-------------- START -------------
# Load variables from the .env file
export $(cat ../.env | grep -v '^#' | xargs)

# Create SQL file
SQL_FILE="mysql/init.sql"

# Deletes the old file
if [ -f "$SQL_FILE" ]; then
    rm "$SQL_FILE"
fi

echo "CREATE DATABASE IF NOT EXISTS $DB_DATABASE;" >> $SQL_FILE
echo "CREATE USER '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';" >> $SQL_FILE
echo "CREATE USER '$DB_USERNAME'@'172.20.%' IDENTIFIED BY '$DB_PASSWORD';" >> $SQL_FILE
echo "GRANT ALL PRIVILEGES ON *.* TO '$DB_USERNAME'@'localhost';" >> $SQL_FILE
echo "GRANT ALL ON *.* TO '$DB_USERNAME'@'172.20.%';" >> $SQL_FILE
echo "FLUSH PRIVILEGES;" >> $SQL_FILE

echo "SQL file '$SQL_FILE' created successfully."
#-------------- END -------------

docker compose build
docker compose up -d
#
docker compose exec reiz-php-fpm composer install

# Useful for small servers where memory is very limited
export NODE_OPTIONS=--max_old_space_size=4096
docker compose exec reiz-php-fpm npm i

docker compose exec reiz-php-fpm php artisan key:generate
echo no | docker compose exec -T reiz-php-fpm php artisan jwt:secret
docker compose exec reiz-php-fpm php artisan migrate
#docker compose exec reiz-php-fpm php artisan db:seed

docker compose exec reiz-php-fpm npm run production