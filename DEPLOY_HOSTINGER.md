# 🚀 Deploy DeepEyes na Hostinger VPS

## 📋 Pré-requisitos

- VPS Hostinger com Ubuntu 22.04 ou superior
- Acesso SSH (root ou sudo)
- Domínio configurado (opcional)

---

## 1️⃣ Conectar na VPS

```bash
ssh root@SEU_IP_DA_VPS
```

---

## 2️⃣ Instalar Dependências

```bash
# Atualiza o sistema
apt update && apt upgrade -y

# Instala PHP 8.2 e extensões necessárias
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-sqlite3 php8.2-curl php8.2-gd \
    php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath \
    php8.2-intl php8.2-readline php8.2-redis

# Instala Nginx
apt install -y nginx

# Instala MySQL
apt install -y mysql-server

# Inicia o MySQL
systemctl start mysql
systemctl enable mysql

# Instala Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Instala Git
apt install -y git

# Instala Redis (opcional, para cache)
apt install -y redis-server
```

---

## 3️⃣ Clonar o Projeto

```bash
# Cria diretório do projeto
mkdir -p /var/www
cd /var/www

# Clona o repositório
git clone https://github.com/Shelby3344/deepeyers.git deepeyes

cd deepeyes
```

---

## 4️⃣ Configurar SQLite

```bash
# Cria arquivo do banco de dados
touch /var/www/deepeyes/database/database.sqlite

# Ajusta permissões
chmod 664 /var/www/deepeyes/database/database.sqlite
chown www-data:www-data /var/www/deepeyes/database/database.sqlite
chown www-data:www-data /var/www/deepeyes/database
```

---

## 5️⃣ Configurar o Laravel

```bash
# Copia o arquivo de ambiente
cp .env.example .env

# Edita as configurações
nano .env
```

### Configurações do .env para produção (com SQLite e OpenRouter):

```env
APP_NAME=DeepEyes
APP_ENV=production
APP_DEBUG=false
APP_URL=http://SEU_IP_OU_DOMINIO

DB_CONNECTION=sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# OpenRouter (não DeepSeek direto)
DEEPSEEK_API_KEY=sk-or-v1-SUA_CHAVE_OPENROUTER
DEEPSEEK_ENDPOINT=https://openrouter.ai/api/v1/chat/completions
DEEPSEEK_MODEL=deepseek/deepseek-chat
```

> ⚠️ **SQLite não precisa de usuário/senha!** É um banco em arquivo.
> 
> 🔑 Pegue sua API Key em: https://openrouter.ai/keys
> 
> Para salvar no nano: `Ctrl+O`, Enter, depois `Ctrl+X` para sair.

### Instalar dependências e configurar:

```bash
# Instala dependências
composer install --no-dev --optimize-autoloader

# Gera chave da aplicação
php artisan key:generate

# Roda migrações
php artisan migrate --force

# Popula com dados iniciais (planos)
php artisan db:seed --force

# Cria link para storage
php artisan storage:link

# Otimiza para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ajusta permissões
chown -R www-data:www-data /var/www/deepeyes
chmod -R 775 storage bootstrap/cache
```

---

## 6️⃣ Configurar Nginx

```bash
# Cria arquivo de configuração
nano /etc/nginx/sites-available/deepeyes
```

### Conteúdo do arquivo:

```nginx
server {
    listen 80;
    listen [::]:80;
    
    server_name seudominio.com www.seudominio.com;
    # OU use o IP: server_name SEU_IP;
    
    root /var/www/deepeyes/public;
    index index.php;
    
    # Tamanho máximo de upload (para avatares)
    client_max_body_size 10M;
    
    # Logs
    access_log /var/log/nginx/deepeyes_access.log;
    error_log /var/log/nginx/deepeyes_error.log;
    
    # Gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    # Cache para assets estáticos
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

### Ativar o site:

```bash
# Cria link simbólico
ln -s /etc/nginx/sites-available/deepeyes /etc/nginx/sites-enabled/

# Remove site default (opcional)
rm /etc/nginx/sites-enabled/default

# Testa configuração
nginx -t

# Reinicia Nginx
systemctl restart nginx
```

---

## 7️⃣ Configurar SSL (HTTPS) - Gratuito com Let's Encrypt

```bash
# Instala Certbot
apt install -y certbot python3-certbot-nginx

# Gera certificado SSL
certbot --nginx -d seudominio.com -d www.seudominio.com

# Renovação automática já está configurada
# Para testar: certbot renew --dry-run
```

---

## 8️⃣ Configurar Firewall

```bash
# Permite SSH, HTTP e HTTPS
ufw allow OpenSSH
ufw allow 'Nginx Full'

# Ativa firewall
ufw enable

# Verifica status
ufw status
```

---

## 9️⃣ Criar Usuário Admin

```bash
cd /var/www/deepeyes

# Cria usuário admin via tinker
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'contatowhiter@gmail.com',
    'password' => bcrypt('Lucas209_'),
    'role' => 'admin',
    'plan_id' => 1
]);
exit
```

---

## 🔄 Deploy de Atualizações

Quando quiser atualizar o projeto:

```bash
cd /var/www/deepeyes
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data /var/www/deepeyes
```

Ou use o script:
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🔧 Comandos Úteis

```bash
# Ver logs do Laravel
tail -f /var/www/deepeyes/storage/logs/laravel.log

# Ver logs do Nginx
tail -f /var/log/nginx/deepeyes_error.log

# Reiniciar serviços
systemctl restart nginx
systemctl restart php8.2-fpm

# Limpar cache do Laravel
php artisan cache:clear
php artisan config:clear

# Ver status dos serviços
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql
```

---

## ⚠️ Checklist Final

- [ ] PHP 8.2+ instalado
- [ ] Nginx configurado e rodando
- [ ] Banco de dados criado
- [ ] .env configurado com APP_DEBUG=false
- [ ] API Key do DeepSeek configurada
- [ ] Migrações executadas
- [ ] Storage link criado
- [ ] Permissões corretas (www-data)
- [ ] SSL configurado (HTTPS)
- [ ] Firewall ativo
- [ ] Usuário admin criado

---

## 🆘 Problemas Comuns

### Erro 500
```bash
tail -f /var/www/deepeyes/storage/logs/laravel.log
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Erro de permissão
```bash
chown -R www-data:www-data /var/www/deepeyes
find /var/www/deepeyes -type d -exec chmod 755 {} \;
find /var/www/deepeyes -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
```

### Página em branco
```bash
php artisan config:clear
php artisan cache:clear
```

### CSS/JS não carrega
```bash
php artisan storage:link
```

---

## 📞 Suporte

Se precisar de ajuda, verifique:
1. Logs do Laravel: `storage/logs/laravel.log`
2. Logs do Nginx: `/var/log/nginx/deepeyes_error.log`
3. Logs do PHP: `/var/log/php8.2-fpm.log`
