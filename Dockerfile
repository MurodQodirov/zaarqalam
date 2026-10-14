FROM php:8.3-cli-alpine

RUN docker-php-ext-install mbstring

WORKDIR /app

COPY . .

RUN mkdir -p data public/uploads \
    && chmod -R a+rwX data public/uploads

EXPOSE 10000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} router.php"]
