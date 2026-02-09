FROM php:7.4

WORKDIR /var/www

COPY /src /var/www

RUN apt-get update && apt-get install -y \
  git \
  unzip \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["bash", "-c", "composer install --no-interaction && exec php index.php"]