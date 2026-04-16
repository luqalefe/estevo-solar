#!/bin/bash
# ============================================
# Estevo Solar - Setup inicial do servidor
# Execute UMA VEZ na VPS como root:
#   bash deploy/setup-server.sh
# ============================================
set -e

DOMAIN="solar.estevo.tech"
APP_DIR="/var/www/${DOMAIN}"
REPO_URL="https://github.com/luqalefe/estevo-solar.git"
DB_NAME="estevo_solar"
DB_USER="estevo_solar"

echo "🚀 Setup Estevo Solar — ${DOMAIN}"
echo ""

# ----- 1. Pré-requisitos (não reinstala se já tiver) -----
echo "🔎 Checando ferramentas..."
command -v php >/dev/null || { echo "❌ PHP não encontrado. Abortando."; exit 1; }
php -v | head -1
command -v composer >/dev/null || { echo "❌ Composer não encontrado."; exit 1; }
command -v nginx >/dev/null || { echo "❌ Nginx não encontrado."; exit 1; }
command -v node >/dev/null || { echo "❌ Node não encontrado."; exit 1; }
command -v redis-cli >/dev/null || { echo "⚠  Redis não encontrado — a app usa Redis pra cache/queue. Instale antes: apt install -y redis-server"; exit 1; }
command -v git >/dev/null || { apt update && apt install -y git; }

# ----- 2. PostgreSQL (instala só se faltar) -----
if ! command -v psql >/dev/null; then
    echo "📦 Instalando PostgreSQL..."
    apt update
    apt install -y postgresql postgresql-contrib
    systemctl enable postgresql
    systemctl start postgresql
else
    echo "✓ PostgreSQL já instalado"
fi

# ----- 3. Extensão PHP pgsql (idempotente) -----
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
if ! php -m | grep -iq pdo_pgsql; then
    echo "📦 Instalando php${PHP_VERSION}-pgsql..."
    apt install -y "php${PHP_VERSION}-pgsql"
    systemctl restart "php${PHP_VERSION}-fpm"
else
    echo "✓ pdo_pgsql já habilitado"
fi

# ----- 4. Banco + usuário (idempotente) -----
echo "🗄️  Configurando banco de dados..."
DB_EXISTS=$(sudo -u postgres psql -tAc "SELECT 1 FROM pg_database WHERE datname='${DB_NAME}'")
if [ "$DB_EXISTS" != "1" ]; then
    # Gera senha forte se o arquivo não existir
    SENHA_FILE="/root/.${DB_USER}.db-password"
    if [ ! -f "$SENHA_FILE" ]; then
        openssl rand -base64 24 > "$SENHA_FILE"
        chmod 600 "$SENHA_FILE"
    fi
    DB_PASS=$(cat "$SENHA_FILE")

    sudo -u postgres psql <<SQL
CREATE USER ${DB_USER} WITH PASSWORD '${DB_PASS}';
CREATE DATABASE ${DB_NAME} OWNER ${DB_USER};
GRANT ALL PRIVILEGES ON DATABASE ${DB_NAME} TO ${DB_USER};
SQL
    echo "  ✅ Banco e usuário criados"
    echo "  🔐 Senha do DB salva em ${SENHA_FILE}"
else
    echo "✓ Banco ${DB_NAME} já existe"
fi

# ----- 5. Diretório + clone -----
if [ ! -d "$APP_DIR" ]; then
    echo "📁 Clonando projeto em ${APP_DIR}..."
    mkdir -p "$APP_DIR"
    git clone "$REPO_URL" "$APP_DIR"
else
    echo "✓ Diretório ${APP_DIR} já existe (pulando clone)"
fi
cd "$APP_DIR"

# ----- 6. .env -----
if [ ! -f "$APP_DIR/.env" ]; then
    echo "⚙️  Copiando .env template..."
    cp "$APP_DIR/deploy/env.production.example" "$APP_DIR/.env"
    echo ""
    echo "  ⚠️  EDITE o .env AGORA antes de continuar:"
    echo "     nano ${APP_DIR}/.env"
    echo ""
    echo "  Preencha: DB_PASSWORD (ver /root/.${DB_USER}.db-password),"
    echo "            GEMINI_API_KEY, ADMIN_PASSWORD, EMPRESA_WHATSAPP."
    echo ""
    echo "  Depois rode: bash ${APP_DIR}/deploy.sh"
    exit 0
fi

# ----- 7. Dependências e migrations (pula se .env não configurado) -----
if grep -q "TROQUE_ESTA_SENHA_FORTE\|COLE_SUA_CHAVE" "$APP_DIR/.env"; then
    echo "⚠️  .env ainda tem placeholders — edite antes de prosseguir."
    exit 1
fi

echo "📦 Dependências PHP..."
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader --no-interaction

echo "🔑 Gerando APP_KEY (se vazia)..."
php artisan key:generate --force

echo "🎨 Build de assets..."
npm ci --silent
npm run build --silent

echo "🗄️  Migrations..."
php artisan migrate --force

echo "🌱 Seed (admin)..."
php artisan db:seed --force

echo "⚡ Cache de produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔐 Permissões..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# ----- 8. Nginx vhost (copiado do arquivo versionado) -----
VHOST="/etc/nginx/sites-available/${DOMAIN}"
VHOST_SRC="${APP_DIR}/deploy/nginx/${DOMAIN}.conf"
if [ ! -f "$VHOST" ]; then
    echo "🌐 Criando Nginx vhost (HTTP-only até Certbot rodar)..."
    # Vhost inicial só em porta 80. Depois do Certbot, o arquivo correto com
    # HTTPS vem de deploy/nginx/${DOMAIN}.conf
    cat > "$VHOST" <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    root ${APP_DIR}/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 20M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
NGINX
    ln -sf "$VHOST" "/etc/nginx/sites-enabled/${DOMAIN}"
    nginx -t && systemctl reload nginx
    echo "  ✅ Vhost HTTP criado — rode 'certbot --nginx -d ${DOMAIN}' e depois copie $VHOST_SRC pra ativar a config final"
elif [ -f "$VHOST_SRC" ]; then
    echo "🌐 Atualizando vhost a partir de $VHOST_SRC..."
    cp "$VHOST_SRC" "$VHOST"
    ln -sf "$VHOST" "/etc/nginx/sites-enabled/${DOMAIN}"
    nginx -t && systemctl reload nginx
    echo "  ✅ Vhost atualizado"
else
    echo "✓ Vhost já existe"
fi

# ----- 9. systemd queue worker -----
SERVICE="/etc/systemd/system/solar-queue.service"
if [ ! -f "$SERVICE" ]; then
    echo "⚙️  Criando systemd service para queue..."
    cat > "$SERVICE" <<UNIT
[Unit]
Description=Estevo Solar Queue Worker
After=network.target redis-server.service postgresql.service

[Service]
User=www-data
Group=www-data
Restart=always
RestartSec=5
ExecStart=/usr/bin/php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3 --max-time=3600
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
UNIT
    systemctl daemon-reload
    systemctl enable solar-queue
    systemctl start solar-queue
    echo "  ✅ solar-queue iniciado"
else
    echo "✓ Queue service já existe"
fi

echo ""
echo "============================================"
echo "  ✅ SETUP CONCLUÍDO!"
echo "============================================"
echo ""
echo "  🌐 Site: http://${DOMAIN}"
echo "  🔐 Admin: http://${DOMAIN}/admin"
echo ""
echo "  Próximo passo — SSL HTTPS (se ainda não configurou):"
echo "    certbot --nginx -d ${DOMAIN}"
echo ""
echo "  Para atualizar no futuro:"
echo "    cd ${APP_DIR} && bash deploy.sh"
echo ""
