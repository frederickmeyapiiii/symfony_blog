FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Activer le mod_rewrite d'Apache
RUN a2enmod rewrite

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Config Apache pour Symfony
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Aller dans le dossier Symfony
WORKDIR /var/www/html

# Installer les dépendances PHP
RUN composer install --no-interaction --no-scripts --optimize-autoloader