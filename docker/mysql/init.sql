# Configuração do VirtualHost para o servidor web Apache da plataforma Trampix.
# O VirtualHost escuta na porta 80 para requisições HTTP.
<VirtualHost *:80>
    # Define o diretório raiz da sua aplicação Laravel dentro do container.
    # A pasta 'public' do Laravel é o ponto de entrada público.
    DocumentRoot /var/www/html/public

    # Configurações para o diretório 'public' do Laravel, permitindo o uso de .htaccess e reescrita de URL.
    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Configura os arquivos de log de erros e acesso do Apache.
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
