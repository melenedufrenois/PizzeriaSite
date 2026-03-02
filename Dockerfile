# Dockerfile pour l'application Symfony Pizzeria
FROM php:8.3-fpm

# Arguments pour le build
ARG TIMEZONE=Europe/Paris

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        pdo \
        pdo_mysql \
        zip \
        gd \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configuration du timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP pour le développement
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configuration PHP personnalisée
RUN echo "memory_limit = 512M" >> "$PHP_INI_DIR/conf.d/custom.ini" \
    && echo "upload_max_filesize = 20M" >> "$PHP_INI_DIR/conf.d/custom.ini" \
    && echo "post_max_size = 20M" >> "$PHP_INI_DIR/conf.d/custom.ini"

# Création du répertoire de travail
WORKDIR /var/www/html

# Copie des fichiers de dépendances d'abord (pour le cache Docker)
COPY composer.json composer.lock ./

# Installation des dépendances Composer (sans les scripts)
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Copie de tout le projet
COPY . .

# Finalisation de l'installation Composer
RUN composer dump-autoload --optimize \
    && composer run-script post-install-cmd --no-interaction || true

# Création des répertoires nécessaires avec les bons droits
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Exposition du port
EXPOSE 9000

# Commande par défaut
CMD ["php-fpm"]
