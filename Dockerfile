FROM php:8.3-apache

#1.  Instala la extensión PDO para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# apache en puerto 8080 y 80 (fly.io)
RUN sed -i 's/Listen 80/Listen 8080/' \
    /etc/apache2/ports.conf \
    /etc/apache2/sites-available/000-default.conf

#habilitar mod_rewrite
RUN a2enmod rewrite


# Copia index.php
COPY src/index.php /var/www/html/index.php

#copiar init.sql
COPY sql/init.sql /sql/init.sql

#permisos
RUN chown -R www-data:www-data /var/www/html

#entrypoint que inicializa la BD  y luego arranca Apache
RUN printf '%s\n' \
'#!/bin/bash' \
'set -e' \
'' \
'if [ -n "$DATABASE_URL" ] && [ -f /sql/init.sql ];  then' \
'fi' \
'' \
'exec apache2-foreground' \
> /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expone el puerto 80
EXPOSE 80
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
