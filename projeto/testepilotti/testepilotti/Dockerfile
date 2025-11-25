# syntax=docker/dockerfile:1
FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
      libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo pdo_mysql gd intl zip opcache \
  && a2enmod rewrite headers expires \
  && rm -rf /var/lib/apt/lists/*


ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf


RUN sed -ri \
    -e 's/AllowOverride[[:space:]]+None/AllowOverride All/g' \
    -e 's/Options[[:space:]]+Indexes[[:space:]]+FollowSymLinks/Options -Indexes +FollowSymLinks/g' \
    /etc/apache2/apache2.conf

RUN printf "\nDirectoryIndex index.php index.html\n" > /etc/apache2/conf-available/dirindex.conf \
 && a2enconf dirindex \
 && printf "ServerName localhost\n" > /etc/apache2/conf-available/fqdn.conf \
 && a2enconf fqdn

WORKDIR /var/www/public