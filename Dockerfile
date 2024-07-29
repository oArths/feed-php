# Use a imagem oficial do PHP com FPM
FROM php:8.0-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Instalar extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar todos os arquivos da aplicação para o contêiner
COPY api ./

# Ajustar permissões para o diretório de armazenamento
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Executar o Composer como um usuário não root
RUN useradd -ms /bin/bash composeruser
USER composeruser
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Voltar para o usuário root
USER root

# Expor a porta 9000 e iniciar o PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
