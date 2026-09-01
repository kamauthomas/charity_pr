#!/usr/bin/env bash
# Assemble a DirectAdmin/shared-hosting release into two trees:
#
#   release/app/      -> upload OUTSIDE the web root (contains .env, vendor, code)
#   release/docroot/  -> upload INTO the subdomain's public_html
#
# Splitting them is the whole point: if the Laravel app sits inside the docroot,
# https://cindy.tomaso.co.ke/.env is downloadable and the APP_KEY leaks.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
OUT="${RELEASE_DIR:-$ROOT/release}"
APP_DIR_NAME="${APP_DIR_NAME:-cindy_app}"   # name it will have on the server
APP_URL="${APP_URL:-https://cindy.tomaso.co.ke}"

rm -rf "$OUT"
mkdir -p "$OUT/app" "$OUT/docroot"

echo "==> Installing production dependencies"
composer install --no-dev --optimize-autoloader --no-interaction --quiet

echo "==> Copying application (excluding the public/ tree and local state)"
rsync -a \
  --exclude '.git'            --exclude '.github' \
  --exclude 'node_modules'    --exclude 'tests' \
  --exclude 'public'          --exclude 'release' \
  --exclude 'assets/'         --exclude 'deploy' \
  --exclude 'docs'            --exclude '.env' \
  --exclude 'storage/logs/*'  --exclude '.phpunit.result.cache' \
  --exclude 'database/database.sqlite' \
  --exclude '*.swp'           --exclude '*.kate-swp' \
  "$ROOT/" "$OUT/app/"

echo "==> Copying public/ into the docroot"
rsync -a "$ROOT/public/" "$OUT/docroot/"

echo "==> Rewriting docroot/index.php to reach the app outside the web root"
cat > "$OUT/docroot/index.php" <<PHP
<?php

use Illuminate\\Http\\Request;

define('LARAVEL_START', microtime(true));

// The application lives one level above the web root so that .env, vendor/,
// and storage/ are never reachable over HTTP.
\$app_path = __DIR__.'/../${APP_DIR_NAME}';

if (file_exists(\$maintenance = \$app_path.'/storage/framework/maintenance.php')) {
    require \$maintenance;
}

require \$app_path.'/vendor/autoload.php';

\$app = require_once \$app_path.'/bootstrap/app.php';

\$app->handleRequest(Request::capture());
PHP

echo "==> Writing production .env"
KEY="$(php "$ROOT/artisan" key:generate --show)"
cat > "$OUT/app/.env" <<ENV
APP_NAME="Cindy Apparel"
APP_ENV=production
APP_KEY=${KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error
LOG_DEPRECATIONS_CHANNEL=null

# The storefront touches no database: the catalog is config/cindy.php and the
# cart is browser localStorage. File drivers remove the need for migrations,
# which matters because this host gives us no shell.
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
CACHE_STORE=file
QUEUE_CONNECTION=sync
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local

MAIL_MAILER=log
MAIL_FROM_ADDRESS="orders@cindyapparel.co.ke"
MAIL_FROM_NAME="Cindy Apparel"
ENV

echo "==> Preparing writable runtime directories"
mkdir -p "$OUT/app/storage/framework/"{cache/data,sessions,views} \
         "$OUT/app/storage/logs" "$OUT/app/bootstrap/cache"
find "$OUT/app/storage" "$OUT/app/bootstrap/cache" -type f \
     ! -name '.gitignore' -delete 2>/dev/null || true

# Caches bake in absolute paths and env values from THIS machine, so they must
# not be shipped. Laravel rebuilds them on demand at runtime.
rm -f "$OUT/app/bootstrap/cache/"*.php

echo "==> Release built"
echo "    app     : $OUT/app      ($(du -sh "$OUT/app" | cut -f1))"
echo "    docroot : $OUT/docroot  ($(du -sh "$OUT/docroot" | cut -f1))"
