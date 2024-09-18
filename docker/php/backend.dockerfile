FROM php:8.2-fpm-alpine

# Needed for SSR
RUN apk add npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

###########################################
# Uncomment to update apk
###########################################
#RUN apk update
#RUN apk upgrade
###########################################

# Install the PHP pdo extention
RUN docker-php-ext-install pdo

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

###########################################
# Uncomment to use Postgr SQL
###########################################
# Install the PHP pdo_pgsql extention
#RUN apk add postgresql-dev
#RUN docker-php-ext-install pdo_pgsql
###########################################


###########################################
# Uncomment to use ZIP
###########################################
RUN apk add libzip-dev
RUN docker-php-ext-install zip
###########################################


###########################################
# Uncomment to use YAML
###########################################
# Install YAML extension
#RUN apk add g++
#RUN apk add make
#RUN apk add autoconf
#RUN apk add yaml-dev
#RUN pecl channel-update pecl.php.net
#RUN pecl install yaml
#RUN docker-php-ext-enable yaml
###########################################


###########################################
# Uncomment to use gd library
###########################################
#RUN apk add libjpeg-turbo-dev
#RUN apk add libpng-dev
#RUN apk add libwebp-dev
#RUN apk add freetype-dev
## Configure gd library
## As of PHP 7.4 we don't need to add --with-png
#RUN docker-php-ext-configure gd  \
#    --with-jpeg  \
#    --with-webp  \
#    --with-freetype
#
## Install the PHP gd library
#RUN docker-php-ext-install gd
###########################################


###########################################
# Uncomment to use openssl
###########################################
#RUN apk add openssl
###########################################


###########################################
# Uncomment to use XDebug
###########################################
# Add xdebug
#RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS
#RUN apk add --update linux-headers
#RUN pecl install xdebug-3.1.5
#RUN docker-php-ext-enable xdebug
#RUN apk del -f .build-deps
#ADD ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
###########################################


###########################################
# Uncomment to use PCOV
###########################################
#RUN apk add --no-cache autoconf build-base
#RUN pecl install pcov
#RUN docker-php-ext-enable pcov
###########################################


###########################################
# Uncomment to use xml
###########################################
#RUN apk add --update libxml2-dev
#RUN docker-php-ext-install xml
###########################################


###########################################
# Uncomment to use Process Control (pcntl)
###########################################
# It is required for Laravel Reverb
###########################################
RUN apk add php-pcntl
RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl
###########################################


## Install opcache
#RUN docker-php-ext-install opcache
#
## Install sockets (using workaround because of docker-php-ext-install issue)
#ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
#RUN chmod +x /usr/local/bin/install-php-extensions
#RUN install-php-extensions sockets
#
## Install intl extension
#RUN docker-php-ext-install intl
#
## Install GD
#RUN apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev zlib-dev libwebp-dev
#RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
#RUN docker-php-ext-install gd
#
## Install some global packages
#RUN apk add --no-cache bash git jq moreutils openssh rsync yq
#
## Add localhost SSL certificates
#ADD ssl /etc/ssl


# Set cofiguration .ini file
ADD ./docker/php/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

#EXPOSE 9000
#
#COPY ./platform/serve.sh .
#RUN chmod 755 serve.sh
#CMD ./serve.sh

#CMD php artisan storage:link
