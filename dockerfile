FROM php:7.4

WORKDIR /var/www

COPY /src /var/www

RUN apt-get update && apt-get install

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD composer install

ENTRYPOINT [ "php", "index.php" ]
