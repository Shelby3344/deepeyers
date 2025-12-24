#!/bin/bash
# DeepEyes Deploy Script
# Execute na VPS: bash deploy.sh

echo "🚀 Iniciando deploy do DeepEyes..."

# Navegar para o diretório
cd /var/www/deepeyes

# Limpar alterações locais e atualizar
echo "📥 Atualizando código..."
git checkout -- .
git pull origin main

# Limpar caches do Laravel
echo "🧹 Limpando caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Otimizar para produção
echo "⚡ Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissões
echo "🔐 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "✅ Deploy concluído!"
echo ""
echo "📊 Otimizações aplicadas:"
echo "  - Tailwind CSS compilado localmente (~50KB vs ~3MB do CDN)"
echo "  - Fontes Google com carregamento assíncrono"
echo "  - Font Awesome com carregamento assíncrono"
echo "  - Preconnect para CDNs externos"
echo "  - Cache do Laravel otimizado"
