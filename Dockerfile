FROM php:8.2.6-apache-bullseye

RUN apt-get update \
    && apt-get install -y wget \
    vim \
    zip \
    unzip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /eastern-enterprise