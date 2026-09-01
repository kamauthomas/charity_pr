# Cindy Apparel — demo deployment

Static-catalog Laravel 13 storefront. No database-backed products yet; the catalog
lives in `config/cindy.php`, and the cart is browser-side `localStorage`.
Checkout is a deliberate placeholder until M-Pesa Daraja credentials exist.

## Server requirements

- PHP 8.3+ with: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `curl`, `fileinfo`, `zip`
- Composer 2
- Nginx or Apache with document root at `<path>/public`
- No Node build step is needed — `public/css/cindy.css` and `public/js/cindy.js`
  are hand-authored and committed. Vite/Tailwind are unused leftovers from the
  Laravel skeleton.

## First-time server setup

```bash
ssh user@server
sudo mkdir -p /var/www/cindy && sudo chown $USER /var/www/cindy
```

Then from your laptop, push once and configure the environment:

```bash
DEPLOY_HOST=user@server DEPLOY_PATH=/var/www/cindy ./deploy/deploy.sh
```

The first run will stop with `FATAL: .env is missing`. That is expected. On the server:

```bash
cd /var/www/cindy
cp .env.production.example .env
nano .env                      # set APP_URL, and anything else site-specific
php artisan key:generate
touch database/database.sqlite
```

Re-run the deploy command and it will complete.

## Routine deploys

```bash
# Preview exactly what would change — touches nothing on the server:
DRY_RUN=1 DEPLOY_HOST=user@server DEPLOY_PATH=/var/www/cindy ./deploy/deploy.sh

# Ship it:
DEPLOY_HOST=user@server DEPLOY_PATH=/var/www/cindy ./deploy/deploy.sh
```

### Using a specific key

Set `DEPLOY_KEY` to an identity file, and `DEPLOY_PORT` if SSH is not on 22:

```bash
DEPLOY_KEY=~/Desktop/assets/keys/demo_rsa \
DEPLOY_HOST=deploy@server DEPLOY_PATH=/var/www/cindy ./deploy/deploy.sh
```

The key must be mode `600` or SSH silently ignores it; the script checks this and
fails with a clear message. The matching **public** key must be present in
`~/.ssh/authorized_keys` for the deploy user on the server.

`demo_rsa` is RSA-2048. OpenSSH 8.8+ disabled the old SHA-1 `ssh-rsa` signature
algorithm by default. Modern servers negotiate `rsa-sha2-256/512` and this is a
non-issue, but against an older sshd you may need to add
`-o PubkeyAcceptedKeyTypes=+ssh-rsa` to `SSH_CMD` in `deploy.sh`. The ed25519 keys
alongside it avoid the question entirely if the server will accept one.

The script excludes `.env`, `database/database.sqlite`, logs, caches, `tests/`, and
the stray 15 MB `assets/` scratch directory, so server state survives each release.

## Web server document root

Point the vhost at `public/`, never the project root — otherwise `.env` becomes
world-readable over HTTP.

```nginx
server {
    listen 80;
    server_name demo.example.co.ke;
    root /var/www/cindy/public;
    index index.php;

    location / { try_files $uri $uri/ /index.php?$query_string; }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

Add HTTPS with `sudo certbot --nginx -d demo.example.co.ke` before sharing the
link — `SESSION_SECURE_COOKIE=true` in the env template assumes TLS is present.

## Post-deploy smoke test

```bash
for p in / /shop /collections /collections/workwear \
         /products/navy-bloom-belted-dress /about /contact /cart /checkout; do
  printf '%-45s %s\n' "$p" "$(curl -s -o /dev/null -w '%{http_code}' https://demo.example.co.ke$p)"
done
```

All nine should return `200`. Then load the homepage, add an item to the cart, and
confirm the drawer opens and the subtotal updates.

## Rollback

Releases overwrite in place, so roll back by checking out the previous commit
locally and re-running the deploy script.
