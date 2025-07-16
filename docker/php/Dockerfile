# Usa uma imagem base oficial do PHP com Apache na versão 8.2.
FROM php:8.2-apache

# Define o diretório de trabalho padrão dentro do container.
WORKDIR /var/www/html

# Instala as dependências do sistema operacional que as extensões PHP precisam.
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* # Limpa o cache do apt para reduzir o tamanho da imagem

# Instala o Composer globalmente no container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala as extensões PHP necessárias para conexão com o MySQL e para o Laravel.
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring xml bcmath gd

# Habilita o módulo 'mod_rewrite' do Apache, fundamental para URLs amigáveis e frameworks PHP.
RUN a2enmod rewrite

# Copia o arquivo de configuração personalizado do Apache para o local correto no container.
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80 do container, indicando que o serviço Apache estará disponível nesta porta.
EXPOSE 80
