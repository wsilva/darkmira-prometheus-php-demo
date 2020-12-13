FROM php:7.4
RUN pecl install apcu \
    && docker-php-ext-enable apcu
WORKDIR /app
CMD ["php", "-S", "0.0.0.0:8888", "/app/demo.php"]