#!/bin/bash

# Hardcoded PHP path (adjust this if yours is different)
PHP_PATH="/c/xampp/php/php.exe"
PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
CRON_FILE="$PROJECT_DIR/cron.php"

CRON_JOB="0 * * * * $PHP_PATH $CRON_FILE"

# Try to add cron job if crontab is available
if command -v crontab >/dev/null 2>&1; then
    # Prevent duplicate
    (crontab -l 2>/dev/null | grep -F "$CRON_FILE") && echo "Cron job already exists." && exit 0
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    echo "✅ Cron job added: $CRON_JOB"
else
    echo "⚠️  crontab not found. Running cron.php manually for test..."
    "$PHP_PATH" "$CRON_FILE"
    echo "✅ cron.php executed manually."
fi
