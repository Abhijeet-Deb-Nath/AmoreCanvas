# Check if review media uploads are working
Write-Host "`n=== REVIEW UPLOAD DIAGNOSTICS ===" -ForegroundColor Cyan

# Check latest review in database
Write-Host "`n1. Latest Review from Database:" -ForegroundColor Yellow
php artisan tinker --execute="print_r(App\Models\MemoryReview::latest()->first(['id', 'review', 'media_path', 'created_at']));"

# Check log for upload attempts
Write-Host "`n2. Recent Upload Logs:" -ForegroundColor Yellow
Get-Content "storage\logs\laravel.log" -Tail 100 | Select-String -Pattern "Review media" -Context 1

# Check files in reviews directory
Write-Host "`n3. Files in Reviews Directory:" -ForegroundColor Yellow
Get-ChildItem "storage\app\public\reviews" -ErrorAction SilentlyContinue | Select-Object Name, Length, LastWriteTime

Write-Host "`n=== END DIAGNOSTICS ===" -ForegroundColor Cyan
