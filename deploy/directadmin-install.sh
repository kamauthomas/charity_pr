#!/usr/bin/env bash
# Run this ON THE SERVER, in the DirectAdmin terminal, after uploading
# cindy-release.tar.gz to your home directory.
#
#   bash ~/directadmin-install.sh
#
# It is safe to re-run: it replaces the app and docroot contents, and never
# touches the existing tomaso.co.ke site.

set -euo pipefail

TARBALL="${TARBALL:-$HOME/cindy-release.tar.gz}"
SUBDOMAIN="${SUBDOMAIN:-cindy.tomaso.co.ke}"
APP_DIR="$HOME/cindy_app"

say() { printf '\n\033[1m==> %s\033[0m\n' "$1"; }
fail() { printf '\033[31mFATAL: %s\033[0m\n' "$1" >&2; exit 1; }

say "Checking prerequisites"
[[ -f "$TARBALL" ]] || fail "Tarball not found at $TARBALL — upload it first."

PHPBIN="$(command -v php || true)"
[[ -n "$PHPBIN" ]] || fail "No php binary on PATH."
PHPVER="$($PHPBIN -r 'echo PHP_VERSION;')"
echo "    php $PHPVER ($PHPBIN)"
$PHPBIN -r 'exit(version_compare(PHP_VERSION, "8.3.0", ">=") ? 0 : 1);' \
  || fail "PHP $PHPVER is too old; this app needs 8.3+. Switch it in DirectAdmin > PHP Selector."

for ext in mbstring openssl tokenizer xml curl fileinfo; do
  $PHPBIN -m | grep -qix "$ext" || fail "Missing required PHP extension: $ext"
done
echo "    all required extensions present"

say "Locating the docroot for $SUBDOMAIN"
CANDIDATES=(
  "$HOME/domains/$SUBDOMAIN/public_html"
  "$HOME/domains/tomaso.co.ke/public_html/${SUBDOMAIN%%.*}"
)
DOCROOT=""
for c in "${CANDIDATES[@]}"; do
  [[ -d "$c" ]] && { DOCROOT="$c"; break; }
done
[[ -n "$DOCROOT" ]] || fail "No docroot found. Create the subdomain in DirectAdmin first.
Looked in:
  ${CANDIDATES[0]}
  ${CANDIDATES[1]}"
echo "    $DOCROOT"

# Refuse to run against the main site's docroot.
[[ "$DOCROOT" == "$HOME/domains/tomaso.co.ke/public_html" ]] \
  && fail "That is the MAIN site docroot. Aborting rather than overwriting tomaso.co.ke."

say "Extracting release"
WORK="$(mktemp -d)"
trap 'rm -rf "$WORK"' EXIT
tar xzf "$TARBALL" -C "$WORK"
[[ -d "$WORK/release/app" && -d "$WORK/release/docroot" ]] \
  || fail "Tarball layout unexpected — expected release/app and release/docroot."

say "Installing application to $APP_DIR (outside the web root)"
rm -rf "$APP_DIR"
mv "$WORK/release/app" "$APP_DIR"

say "Installing docroot contents to $DOCROOT"
find "$DOCROOT" -mindepth 1 -maxdepth 1 -exec rm -rf {} + 2>/dev/null || true
cp -a "$WORK/release/docroot/." "$DOCROOT/"

say "Pointing the front controller at $APP_DIR"
sed -i "s|__DIR__.'/../cindy_app'|'$APP_DIR'|" "$DOCROOT/index.php"
grep -q "$APP_DIR" "$DOCROOT/index.php" || fail "Failed to rewrite index.php"

say "Setting permissions"
chmod 600 "$APP_DIR/.env"
mkdir -p "$APP_DIR/storage/framework/"{cache/data,sessions,views} \
         "$APP_DIR/storage/logs" "$APP_DIR/bootstrap/cache"
chmod -R 755 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
find "$DOCROOT" -type d -exec chmod 755 {} +
find "$DOCROOT" -type f -exec chmod 644 {} +
echo "    .env is 0600, storage writable, docroot 755/644"

say "Warming caches"
cd "$APP_DIR"
$PHPBIN artisan config:clear >/dev/null 2>&1 || true
$PHPBIN artisan optimize:clear >/dev/null 2>&1 || true
$PHPBIN artisan config:cache  >/dev/null 2>&1 && echo "    config cached"  || echo "    config cache skipped"
$PHPBIN artisan route:cache   >/dev/null 2>&1 && echo "    routes cached"  || echo "    route cache skipped"
$PHPBIN artisan view:cache    >/dev/null 2>&1 && echo "    views cached"   || echo "    view cache skipped"

say "Self-check"
echo -n "    .env unreadable from web root: "
[[ -f "$DOCROOT/.env" ]] && echo "FAIL — .env is in the docroot!" || echo "ok"
echo -n "    app outside docroot: "
[[ "$APP_DIR" != "$DOCROOT"* ]] && echo "ok" || echo "FAIL"
echo -n "    APP_DEBUG: "; grep -E '^APP_DEBUG=' "$APP_DIR/.env" | cut -d= -f2
echo -n "    APP_ENV:   "; grep -E '^APP_ENV=' "$APP_DIR/.env" | cut -d= -f2

say "Done — now browse https://$SUBDOMAIN"
echo "If you see a 500, check: tail -n 40 $APP_DIR/storage/logs/*.log"
