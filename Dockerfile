# Utilisation de l'image officielle PHP 8.4 avec Apache
FROM php:8.4-apache

# Installation des dépendances système pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip

# Configuration du DocumentRoot d'Apache vers le dossier /public de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie des fichiers du projet dans le conteneur
COPY . /var/www/html
WORKDIR /var/www/html

# --- FIX: On définit l'environnement de production AVANT l'installation ---
ENV APP_ENV=prod

# Installation des dépendances sans les outils de dev pour éviter l'erreur DebugBundle
RUN composer install --no-dev --optimize-autoloader

# Droits sur les dossiers indispensables pour Symfony
RUN chown -R www-data:www-data var/