FROM php:8.3-fpm

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Настраиваем и устанавливаем расширение GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Устанавливаем необходимые расширения PHP для Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    exif \
    intl \
    pcntl \
    gd \
    zip \
    opcache

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Запуск PHP-FPM
CMD ["php-fpm"]