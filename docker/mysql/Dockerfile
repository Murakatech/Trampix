# Usa uma imagem base oficial do PHP com Apache na versão 8.2.
FROM php:8.2-apache

# Define o diretório de trabalho padrão dentro do container.
WORKDIR /var/www/html

# Instala as extensões PHP necessárias para conexão com o MySQL e para o Laravel.
# mysqli, pdo, pdo_mysql: para conexão com MySQL.
# mbstring: para manipulação de strings multibyte (essencial para Laravel).
# xml: para manipulação de XML (usado por algumas libs do Laravel).
# bcmath: para operações matemáticas de alta precisão (usado por algumas libs do Laravel).
# gd: para manipulação de imagens (se for usar upload de imagens, por exemplo).
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring xml bcmath gd

# Habilita o módulo 'mod_rewrite' do Apache, fundamental para URLs amigáveis e frameworks PHP.
RUN a2enmod rewrite

# Copia o arquivo de configuração personalizado do Apache para o local correto no container.
# Este arquivo já aponta para a pasta 'public' do Laravel.
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80 do container, indicando que o serviço Apache estará disponível nesta porta.
EXPOSE 80
