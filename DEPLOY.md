# Deploy — Estevo Solar (solar.estevo.tech)

VPS da Hostinger compartilhada com `estevo.tech` e `americatec.estevo.tech`. Este projeto coexiste com os outros — não mexe em configuração global, só adiciona vhost Nginx + systemd service próprios.

**Stack assumida já instalada na VPS:** PHP 8.4 + PHP-FPM, Nginx, Redis, Composer, Node 20+, Certbot. Só instala PostgreSQL (ao lado do MySQL existente — portas diferentes).

---

## Primeira instalação (uma vez)

### 1. DNS

Confirme que `solar.estevo.tech` aponta pra `187.127.24.234`:
```bash
getent hosts solar.estevo.tech
```

### 2. Conectar na VPS

```bash
ssh root@187.127.24.234
```

### 3. Clonar o projeto

```bash
cd /var/www
git clone https://github.com/luqalefe/estevo-solar.git solar.estevo.tech
cd solar.estevo.tech
```

### 4. Rodar o setup

```bash
bash deploy/setup-server.sh
```

Ele vai instalar PostgreSQL (se faltar), criar o banco `estevo_solar` com usuário próprio, copiar `.env` do template e **parar** pra você editar.

### 5. Configurar o `.env`

```bash
nano /var/www/solar.estevo.tech/.env
```

Preencha:
- `DB_PASSWORD` → senha gerada em `/root/.estevo_solar.db-password`
- `GEMINI_API_KEY` → sua chave do Google AI Studio
- `ADMIN_PASSWORD` → senha forte pro login admin
- `EMPRESA_WHATSAPP` → número da empresa (formato internacional, ex: `5568999999999`)

### 6. Rodar o setup de novo

```bash
bash deploy/setup-server.sh
```

Agora ele vai: `composer install`, gerar APP_KEY, buildar assets, rodar migrations + seed, criar vhost Nginx e systemd service da queue.

### 7. HTTPS com Certbot

```bash
certbot --nginx -d solar.estevo.tech
```

Responde `y` nas perguntas. Renovação automática via `certbot renew` já vem configurada no cron.

### 8. Verificar

- Site: https://solar.estevo.tech
- Admin: https://solar.estevo.tech/admin (login: `admin@estevo.tech` + senha que você pôs no `.env`)

---

## Atualizações futuras

Depois de push pra `main`:

```bash
ssh root@187.127.24.234
cd /var/www/solar.estevo.tech
bash deploy.sh
```

O `deploy.sh` faz: `git pull`, composer, npm build, migrations, seed, cache, permissões e reinicia php-fpm + queue.

---

## Troubleshooting

```bash
# Status dos serviços
systemctl status nginx php8.4-fpm postgresql redis-server solar-queue

# Logs
tail -f /var/www/solar.estevo.tech/storage/logs/laravel.log
journalctl -u solar-queue -n 50

# Testar conexão com banco
sudo -u postgres psql -c '\l' | grep estevo_solar
```

### Rollback

```bash
cd /var/www/solar.estevo.tech
git log --oneline -5
git reset --hard <sha_do_commit_bom>
bash deploy.sh
```
