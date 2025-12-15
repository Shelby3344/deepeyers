#!/bin/bash

# ============================================
# DeepEyes - Script de Deploy para VPS
# ============================================

echo "🚀 Iniciando deploy do DeepEyes..."

# Atualiza o código do repositório
git pull origin main

# Instala dependências do Composer (produção)
composer install --no-dev --optimize-autoloader

# Limpa e otimiza cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Gera caches otimizados para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Roda migrações
php artisan migrate --force

# Cria link simbólico para storage
php artisan storage:link

# Ajusta permissões
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Deploy concluído!"
