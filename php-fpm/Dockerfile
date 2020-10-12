FROM php:7.4-fpm

ARG PROXY

RUN \
  # YARN KEYS
  curl -sL https://deb.nodesource.com/setup_13.x  | bash - \
  && curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
  && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
  \
  && apt-get update -y \
  && apt-get install -y \
          librabbitmq-dev \
          libssh-dev \
          libxslt-dev \
          wget \
  && docker-php-ext-install \
          bcmath \
          sockets \
  \
  # APCU
  && pecl install apcu-5.1.17 && docker-php-ext-enable apcu \
  \
  # AMQP
  && pecl install amqp && docker-php-ext-enable amqp \
  \
  # XDEBUG
  && pecl install xdebug && docker-php-ext-enable xdebug \
  \
  # REDIS
  && pecl install redis && docker-php-ext-enable redis \
  \
  # Yarn, NodeJs
  && apt-get install -y nodejs yarn \
  \
  # symfony
  && wget https://get.symfony.com/cli/installer -O - | bash \
  \
  && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
  \
  # Install MySQL client.
  && apt-get install --assume-yes --no-install-recommends --quiet default-mysql-client \
  \
  # Install exif extension.
  && docker-php-ext-install exif \
  \
  # Install exif extension.
  && apt-get install -y libicu-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl \
  && docker-php-ext-install opcache \
  \
  # Install GD PHP extension.
  # GD extension configuration parameters changed on PHP 7.4
  # see https://www.php.net/manual/en/image.installation.php#image.installation
  && apt-get install --assume-yes --no-install-recommends --quiet libfreetype6-dev libjpeg-dev libpng-dev \
  && PHP_MAJOR_VERSION="$(echo $PHP_VERSION | cut -d '.' -f 1)" \
  && PHP_MINOR_VERSION="$(echo $PHP_VERSION | cut -d '.' -f 2)" \
  && if [ $PHP_MAJOR_VERSION -le "5" ] || ([ $PHP_MAJOR_VERSION -eq "7" ] && [ $PHP_MINOR_VERSION -le "3" ]); then \
    # For PHP <= 5.x or PHP 7 <= 7.3, use old parameters
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
  ; else \
    docker-php-ext-configure gd --with-freetype --with-jpeg \
  ; fi \
  && docker-php-ext-install gd \
  \
  # Install mysqli PHP extension (required only for GLPI prior to 10.0).
  && docker-php-ext-install mysqli \
  \
  # Install PDO MySQL PHP extension.
  && docker-php-ext-install pdo pdo_mysql \
  \
  # Install XMLRPC PHP extension.
  && apt-get install --assume-yes --no-install-recommends --quiet libxml2-dev \
  && docker-php-ext-install xmlrpc \
  && docker-php-ext-install xml \
  && docker-php-ext-install xsl \
  \
  # SOAP/%2f/messages
  && docker-php-ext-install soap \
  \
  # Git, Zip, Unzip
  && apt-get install -y git zip unzip libzip-dev \
  && docker-php-ext-install zip \
  \
  # Composer
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
  #\
  # Clean sources list.
  #&& rm -rf /var/lib/apt/lists/*

  #RUN docker-php-ext-install xmlreader xmlwriter xml xmlrpc xsl

ADD ./php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./extconf/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./extconf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./extconf/apcu.ini /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini

RUN alias php='docker-compose exec php php'

WORKDIR /var/www