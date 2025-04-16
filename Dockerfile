FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev zip curl \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Activer le mod_rewrite d'Apache
RUN a2enmod rewrite

# Copier la configuration Apache
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Créer un utilisateur non-root
RUN useradd -m symfonyuser
USER symfonyuser

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html/var

# Aller dans le dossier du projet
WORKDIR /var/www/html

# Installer les dépendances Symfony (désactiver les auto-scripts)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts