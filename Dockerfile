FROM php:8.2-apache

# Activer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier tous les fichiers du site dans le dossier web
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Exposer le port utilisé par Apache
EXPOSE 80
