FROM php:8.1-fpm

# Arguments définis dans docker-compose.yml
ARG user=www-data
ARG uid=1000

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Nettoyer le cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Obtenir Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un répertoire système pour stocker le code de l'application
WORKDIR /var/www/html

# Copier le code de l'application existant
COPY . .

# Installer les dépendances de l'application
RUN composer install --no-interaction --no-dev --prefer-dist

# Générer la clé d'application
RUN php artisan key:generate

# Modifier les droits du répertoire de stockage
RUN chmod -R 775 storage bootstrap/cache

# Exposer le port 9000 et démarrer php-fpm
EXPOSE 9000
CMD ["php-fpm"] 