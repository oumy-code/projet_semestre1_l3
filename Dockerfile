# Utilisation de l'image officielle PHP 8.4 avec Apache
FROM php:8.4-apache

# Installation des dépendances système pour Symfony et PostgreSQL
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip

# --- CONFIGURATION APACHE ---
# On définit le dossier public comme racine du serveur
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Mise à jour de la configuration des sites Apache
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# IMPORTANT : Autorise la lecture du fichier .htaccess (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Active le module de réécriture d'URL (mod_rewrite)
RUN a2enmod rewrite

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie des fichiers du projet dans le conteneur
COPY . /var/www/html
WORKDIR /var/www/html

# --- CONFIGURATION SYMFONY ---
ENV APP_ENV=prod

# Installation des dépendances (sans les outils de dev)
RUN composer install --no-dev --optimize-autoloader

# Droits sur les dossiers de cache et de logs
RUN chown -R www-data:www-data var/

# Exposition du port 80
EXPOSE 80