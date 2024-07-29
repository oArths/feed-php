# Use a imagem oficial do PHP com FPM
FROM php:8.0-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o arquivo composer.json e instalar dependências do Composer
COPY api/composer.json api/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Copiar o restante da aplicação para o contêiner
COPY api ./

# Ajustar permissões para o diretório de armazenamento
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expor a porta 9000 e iniciar o PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
