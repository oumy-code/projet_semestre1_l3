# Utilisation de l'image officielle PHP 8.4 avec Apache
FROM php:8.4-apache

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip

# --- CONFIGURATION APACHE ---
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie des fichiers et dossier de travail
COPY . /var/www/html
WORKDIR /var/www/html

# --- CONFIGURATION SYMFONY ---
ENV APP_ENV=prod
# On s'assure que Composer peut travailler
RUN composer install --no-dev --optimize-autoloader

# FIX CSRF/SESSION : Permissions strictes sur le dossier var
RUN chown -R www-data:www-data /var/www/html/var
RUN chmod -R 777 /var/www/html/var

EXPOSE 80
