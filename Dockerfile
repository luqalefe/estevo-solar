FROM php:8.4-cli-alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache \
        git \
        curl \
        libpq \
        libzip \
        oniguruma \
        icu-libs \
        bash \
        tesseract-ocr \
        tesseract-ocr-data-por \
    && apk add --no-cache --virtual .build-deps \
        linux-headers \
        postgresql-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql redis intl bcmath zip opcache pcntl \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN addgroup -g ${GID} app && adduser -D -u ${UID} -G app app

WORKDIR /var/www/html
USER app

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
