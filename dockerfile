FROM php:8.4-fpm


RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html

EXPOSE 9000

CMD php -S 0.0.0.0:9000 -t /var/www/html/public
