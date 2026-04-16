#!/bin/bash
# ============================================
# Estevo Solar - Deploy incremental
# Executa na VPS, dentro de /var/www/solar.estevo.tech
#   bash deploy.sh
# ============================================
set -e

APP_DIR="/var/www/solar.estevo.tech"

if [ "$(pwd)" != "$APP_DIR" ]; then
    cd "$APP_DIR"
fi

echo "🚀 Deploy Estevo Solar"

echo "📥 git pull..."
git pull origin main

echo "📦 composer install..."
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction

echo "🎨 Build de assets..."
npm ci --silent
npm run build --silent

echo "🗄️  Migrations..."
php artisan migrate --force

echo "🌱 Garantindo admin (seeder idempotente)..."
php artisan db:seed --force

echo "🔗 Storage link..."
php artisan storage:link 2>/dev/null || true

echo "⚡ Cache de produção..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔐 Permissões..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
echo "🔄 Reiniciando php${PHP_VERSION}-fpm..."
systemctl restart "php${PHP_VERSION}-fpm"

echo "🔄 Reiniciando queue worker..."
systemctl restart solar-queue 2>/dev/null || echo "  (solar-queue não existe ainda — rode setup-server.sh uma vez)"

echo ""
echo "✅ Deploy concluído!"
