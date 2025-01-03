CONTAINER_NAME="docker-php-apache"

docker compose up -d
docker exec -it $CONTAINER_NAME chmod -R 777 /var/www/html
