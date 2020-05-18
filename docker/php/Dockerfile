FROM debian:bullseye

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get upgrade & apt-get update -y

RUN apt-get install -y \
    git \
    zip \
    php7.3 \
    php-fpm \
    php-pgsql \
    php-dev \
    php-mbstring \
    php-bcmath

WORKDIR /tmp

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --quiet \
    && rm composer-setup.php \
    && mv composer.phar /usr/local/bin/composer

EXPOSE 9000

WORKDIR /srv/balances

CMD ["php", "-a"]
