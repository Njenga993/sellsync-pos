#!/bin/bash

# Set Railway CLI context (install Railway CLI first if needed)
# npm install -g @railway/cli

TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_DIR="./backups"
FILENAME="sellsync_backup_${TIMESTAMP}.sql"

mkdir -p $BACKUP_DIR

echo "Backing up Railway PostgreSQL to ${BACKUP_DIR}/${FILENAME}..."

railway connect postgres -- pg_dump -U pos_user pos_system > "${BACKUP_DIR}/${FILENAME}"

if [ $? -eq 0 ]; then
    echo "✅ Backup complete: ${BACKUP_DIR}/${FILENAME}"
    echo "File size: $(du -h ${BACKUP_DIR}/${FILENAME} | cut -f1)"
else
    echo "❌ Backup failed"
fi