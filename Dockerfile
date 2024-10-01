# Используем официальный образ PHP 8.3 с FPM
FROM php:8.3-fpm

# Устанавливаем необходимые пакеты
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /src

# Копируем файлы проекта в контейнер
COPY . /src

# Устанавливаем права доступа
RUN chown -R www-data:www-data /src

# Expose порт для PHP-FPM
EXPOSE 9000

# Запускаем PHP-FPM
CMD ["php-fpm"]
