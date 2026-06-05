$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$backupDir = ".\backups"
$filename = "sellsync_backup_${timestamp}.sql"

New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

Write-Host "Backing up Railway PostgreSQL..." -ForegroundColor Yellow

railway run -- pg_dump -U pos_user pos_system > "$backupDir\$filename"

if ($LASTEXITCODE -eq 0) {
    $size = (Get-Item "$backupDir\$filename").Length / 1KB
    Write-Host "Backup complete: $filename ($([math]::Round($size, 1)) KB)" -ForegroundColor Green
} else {
    Write-Host "Backup failed" -ForegroundColor Red
}