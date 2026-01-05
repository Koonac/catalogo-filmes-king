FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip \
	nodejs \
	npm \
	&& docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Criar diretórios necessários se não existirem
RUN mkdir -p storage/framework/cache \
	&& mkdir -p storage/framework/sessions \
	&& mkdir -p storage/framework/views \
	&& mkdir -p storage/logs \
	&& mkdir -p bootstrap/cache

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
	&& chmod -R 755 storage \
	&& chmod -R 755 bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]

