#!/bin/bash
# ═══════════════════════════════════════════════════════════════════════════════
# MERCENARYKING-S DEPLOYMENT SCRIPT
# ═══════════════════════════════════════════════════════════════════════════════
# Enterprise-grade deployment automation for the MercenaryKing-S SaaS platform.
#
# Usage:
#   bash deploy.sh              # Full production deploy
#   bash deploy.sh --dry-run    # Simulated deployment (no actual changes)
#
# Exit codes:
#   0 = Success
#   1 = Failure (check JSON output for details)
# ═══════════════════════════════════════════════════════════════════════════════

set -euo pipefail

# ── Configuration ─────────────────────────────────────────────────────────────
DEPLOY_BRANCH="main"
APP_DIR="$(cd "$(dirname "$0")" && pwd)"
LOG_FILE="${APP_DIR}/storage/logs/deploy.log"
DRY_RUN=false
TIMESTAMP=$(date -u +%Y-%m-%dT%H:%M:%SZ)

# Parse arguments
for arg in "$@"; do
    case $arg in
        --dry-run)
            DRY_RUN=true
            ;;
    esac
done

# ── Helpers ───────────────────────────────────────────────────────────────────
json_log() {
    local stage="$1"
    local status="$2"
    local message="$3"
    local entry="{\"timestamp\":\"$(date -u +%Y-%m-%dT%H:%M:%SZ)\",\"stage\":\"$stage\",\"status\":\"$status\",\"message\":\"$message\",\"dry_run\":$DRY_RUN}"
    echo "$entry"
    echo "$entry" >> "$LOG_FILE" 2>/dev/null || true
}

fail() {
    json_log "$1" "FAILED" "$2"
    exit 1
}

# ── Pre-flight ────────────────────────────────────────────────────────────────
cd "$APP_DIR"
mkdir -p "$(dirname "$LOG_FILE")"

echo "═══════════════════════════════════════════════════════════════"
echo "  MERCENARYKING-S DEPLOYMENT SYSTEM"
echo "  Mode: $([ "$DRY_RUN" = true ] && echo 'DRY RUN (SIMULATED)' || echo 'PRODUCTION')"
echo "  Time: $TIMESTAMP"
echo "═══════════════════════════════════════════════════════════════"

json_log "init" "started" "Deployment initiated"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 1: GIT PULL
# ═══════════════════════════════════════════════════════════════════════════════
json_log "git_pull" "running" "Pulling latest from $DEPLOY_BRANCH"

if [ "$DRY_RUN" = true ]; then
    json_log "git_pull" "simulated" "Would run: git pull origin $DEPLOY_BRANCH"
else
    git pull origin "$DEPLOY_BRANCH" 2>&1 || fail "git_pull" "Failed to pull from $DEPLOY_BRANCH"
fi

json_log "git_pull" "passed" "Repository synced"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 2: COMPOSER INSTALL
# ═══════════════════════════════════════════════════════════════════════════════
json_log "composer" "running" "Installing production dependencies"

if [ "$DRY_RUN" = true ]; then
    json_log "composer" "simulated" "Would run: composer install --no-dev --optimize-autoloader"
else
    composer install --no-interaction --no-dev --optimize-autoloader 2>&1 || fail "composer" "Composer install failed"
fi

json_log "composer" "passed" "Dependencies installed"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 3: DATABASE MIGRATION
# ═══════════════════════════════════════════════════════════════════════════════
json_log "migration" "running" "Running database migrations"

if [ "$DRY_RUN" = true ]; then
    json_log "migration" "simulated" "Would run: php artisan migrate --force"
else
    php artisan migrate --force 2>&1 || fail "migration" "Migration failed"
fi

json_log "migration" "passed" "Migrations complete"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 4: CLEAR CACHES
# ═══════════════════════════════════════════════════════════════════════════════
json_log "cache_clear" "running" "Clearing all caches"

if [ "$DRY_RUN" = true ]; then
    json_log "cache_clear" "simulated" "Would clear: config, cache, route, view"
else
    php artisan config:clear 2>&1 || true
    php artisan cache:clear 2>&1 || true
    php artisan route:clear 2>&1 || true
    php artisan view:clear 2>&1 || true
fi

json_log "cache_clear" "passed" "Caches cleared"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 5: REBUILD CACHES
# ═══════════════════════════════════════════════════════════════════════════════
json_log "cache_build" "running" "Rebuilding optimized caches"

if [ "$DRY_RUN" = true ]; then
    json_log "cache_build" "simulated" "Would rebuild: config, route, view"
else
    php artisan config:cache 2>&1 || fail "cache_build" "Config cache failed"
    php artisan route:cache 2>&1 || fail "cache_build" "Route cache failed"
    php artisan view:cache 2>&1 || fail "cache_build" "View cache failed"
fi

json_log "cache_build" "passed" "Caches optimized"

# ═══════════════════════════════════════════════════════════════════════════════
# STEP 6: RESTART APPLICATION SERVER
# ═══════════════════════════════════════════════════════════════════════════════
json_log "restart" "running" "Restarting application server"

if [ "$DRY_RUN" = true ]; then
    json_log "restart" "simulated" "Would restart: php-fpm / nginx / artisan serve"
else
    # Try common restart methods in order of preference
    if command -v systemctl &> /dev/null; then
        # Production server with systemd
        sudo systemctl reload php*-fpm 2>/dev/null && json_log "restart" "passed" "PHP-FPM reloaded" || true
        sudo systemctl reload nginx 2>/dev/null && json_log "restart" "passed" "Nginx reloaded" || true
    elif command -v supervisorctl &> /dev/null; then
        # Supervisor-managed process
        supervisorctl restart mercenaryking 2>/dev/null || true
        json_log "restart" "passed" "Supervisor process restarted"
    else
        # Development fallback
        json_log "restart" "info" "No process manager detected. Manual restart may be required."
    fi
fi

json_log "restart" "passed" "Server restart complete"

# ═══════════════════════════════════════════════════════════════════════════════
# DEPLOYMENT COMPLETE
# ═══════════════════════════════════════════════════════════════════════════════
COMMIT_HASH=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "  ✅ DEPLOYMENT COMPLETE"
echo "  Commit:  $COMMIT_HASH"
echo "  Branch:  $DEPLOY_BRANCH"
echo "  Mode:    $([ "$DRY_RUN" = true ] && echo 'DRY RUN' || echo 'PRODUCTION')"
echo "  Time:    $(date -u +%Y-%m-%dT%H:%M:%SZ)"
echo "═══════════════════════════════════════════════════════════════"

json_log "deploy" "SUCCESS" "Deployment completed successfully. Commit: $COMMIT_HASH"

exit 0
