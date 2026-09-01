#!/usr/bin/env bash
# Cindy Apparel — demo deploy over SSH (rsync push, no git needed on the server).
#
#   DEPLOY_HOST=user@server DEPLOY_PATH=/var/www/cindy ./deploy/deploy.sh
#
# Add DRY_RUN=1 to see exactly what would transfer without touching the server.

set -euo pipefail

HOST="${DEPLOY_HOST:?set DEPLOY_HOST, e.g. deploy@203.0.113.10}"
DEST="${DEPLOY_PATH:?set DEPLOY_PATH, e.g. /var/www/cindy}"
SSH_PORT="${DEPLOY_PORT:-22}"
ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Optional identity file. SSH refuses keys readable by group or other, so fail
# early with a clear message rather than a confusing "Permission denied".
SSH_CMD=(ssh -p "${SSH_PORT}")
if [[ -n "${DEPLOY_KEY:-}" ]]; then
  [[ -f "${DEPLOY_KEY}" ]] || { echo "FATAL: DEPLOY_KEY not found: ${DEPLOY_KEY}"; exit 1; }
  if [[ "$(stat -c '%a' "${DEPLOY_KEY}")" != "600" ]]; then
    echo "FATAL: ${DEPLOY_KEY} must be mode 600 (run: chmod 600 ${DEPLOY_KEY})"
    exit 1
  fi
  SSH_CMD+=(-i "${DEPLOY_KEY}" -o IdentitiesOnly=yes)
fi

RSYNC_FLAGS=(-az --delete --human-readable --info=stats1)
[[ -n "${DRY_RUN:-}" ]] && RSYNC_FLAGS+=(--dry-run --itemize-changes)

# Never ship local state, dev tooling, or the stray scratch images.
EXCLUDES=(
  --exclude '.git'          --exclude '.github'
  --exclude '.env'          --exclude '.env.backup'
  --exclude 'node_modules'  --exclude 'tests'
  --exclude 'assets/'                 # 15MB of unused scratch photos at repo root
  --exclude 'storage/logs/*'
  --exclude 'storage/framework/cache/data/*'
  --exclude 'storage/framework/sessions/*'
  --exclude 'storage/framework/views/*'
  --exclude '*.kate-swp'    --exclude '*.swp'
  --exclude '.phpunit.result.cache'
  --exclude 'database/database.sqlite' # keep the server's own DB file
)

# The deploy ships vendor/ so the server needs no Composer. Building --no-dev
# locally would strip your dev packages, so restore them when the script exits.
restore_dev_deps() {
  echo "==> Restoring local dev dependencies"
  composer install --no-interaction --quiet
}

echo "==> Building production autoloader locally"
trap restore_dev_deps EXIT
composer install --no-dev --optimize-autoloader --no-interaction --quiet

echo "==> Syncing ${ROOT} -> ${HOST}:${DEST} (port ${SSH_PORT})"
rsync "${RSYNC_FLAGS[@]}" "${EXCLUDES[@]}" \
  -e "${SSH_CMD[*]}" \
  "${ROOT}/" "${HOST}:${DEST}/"

if [[ -n "${DRY_RUN:-}" ]]; then
  echo "==> DRY RUN complete. Nothing was changed on the server."
  exit 0
fi

echo "==> Running remote release steps"
"${SSH_CMD[@]}" "${HOST}" bash -euo pipefail <<REMOTE
cd "${DEST}"

# .env must already exist on the server (from .env.production.example).
test -f .env || { echo "FATAL: ${DEST}/.env is missing"; exit 1; }
grep -q '^APP_KEY=base64:' .env || { echo "FATAL: APP_KEY not set — run: php artisan key:generate"; exit 1; }

test -f database/database.sqlite || touch database/database.sqlite

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

chmod -R ug+rw storage bootstrap/cache
echo "Release complete."
REMOTE

echo "==> Done. Smoke-test the site before sharing the link."
