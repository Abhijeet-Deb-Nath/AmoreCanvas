# Quick Test Script for Delayed Delivery

## Test 1: Verify Queue Configuration

```powershell
# Check .env file
Get-Content .env | Select-String "QUEUE"
```

**Expected Output:**
```
QUEUE_CONNECTION=database
```

If it shows `sync`, run:
```powershell
(Get-Content .env) -replace 'QUEUE_CONNECTION=sync', 'QUEUE_CONNECTION=database' | Set-Content .env
```

## Test 2: Check Jobs Table Exists

```powershell
php artisan tinker
```

Then in tinker:
```php
DB::table('jobs')->count();
// Should return 0 or a number (not an error)
exit
```

## Test 3: Start Queue Worker

```powershell
php artisan queue:work --verbose
```

**Leave this running in a separate terminal!**

## Test 4: Create Test Letter (1 Minute Delay)

1. Edit `config/app.php` temporarily:
```php
'love_letter_min_delivery_days' => 1/1440, // 1 minute
```

2. Clear config cache:
```powershell
php artisan config:clear
```

3. Create a love letter via the UI
   - Set delivery time to 1 minute from now
   - Note the letter ID

4. Watch the queue worker terminal - should show:
```
INFO  Scheduling love letter 1 for delivery
```

## Test 5: Verify Job in Database

```powershell
php artisan tinker
```

```php
// Check if job is queued
DB::table('jobs')->orderBy('id', 'desc')->first();

// Check the scheduled time (available_at)
$job = DB::table('jobs')->orderBy('id', 'desc')->first();
echo date('Y-m-d H:i:s', $job->available_at);

// Check letter status
$letter = App\Models\LoveLetter::latest()->first();
echo "Delivered: " . ($letter->delivered_at ? 'YES' : 'NO');

exit
```

## Test 6: Wait and Verify

**Wait 1 minute, then check:**

1. **Queue Worker Terminal** - Should show:
```
INFO  Processing: App\Jobs\SendLoveLetterDeliveryEmail
INFO  Processed:  App\Jobs\SendLoveLetterDeliveryEmail
```

2. **Check Letter Status:**
```powershell
php artisan tinker
```

```php
$letter = App\Models\LoveLetter::latest()->first();
echo "Delivered at: " . $letter->delivered_at;
// Should show current timestamp
exit
```

3. **Check Logs:**
```powershell
Get-Content storage/logs/laravel.log -Tail 10
```

Should contain:
```
Love letter 1 delivered successfully to user@example.com at 2025-10-17 XX:XX:XX
```

## Test 7: Verify in Browser

1. Log in as the receiver
2. Go to Love Letters / Letter Box
3. The letter should now appear as "delivered"
4. Click to open and read it

## Common Issues & Solutions

### Issue: "Class 'App\Jobs\SendLoveLetterDeliveryEmail' not found"

**Solution:**
```powershell
composer dump-autoload
php artisan optimize:clear
```

### Issue: Job processes immediately

**Check queue connection:**
```powershell
php artisan config:cache
php artisan queue:restart
```

### Issue: Queue worker stops processing

**Restart it:**
```powershell
# Stop with Ctrl+C
php artisan queue:work --verbose --tries=3
```

### Issue: Jobs stuck in queue

**Check failed jobs:**
```powershell
php artisan queue:failed

# Retry them
php artisan queue:retry all
```

## Success Checklist

- [ ] `.env` has `QUEUE_CONNECTION=database`
- [ ] Queue worker is running (terminal shows "Processing jobs")
- [ ] Job appears in `jobs` table after creating letter
- [ ] `available_at` timestamp is in the future
- [ ] Letter `delivered_at` is NULL initially
- [ ] After scheduled time, queue worker processes job
- [ ] Letter `delivered_at` is set to scheduled time
- [ ] Logs show successful delivery message
- [ ] Receiver can see and open the letter

## Production Configuration

After testing, restore the normal delay:

```php
// In config/app.php
'love_letter_min_delivery_days' => 3, // Back to 3 days
```

```powershell
php artisan config:clear
```

---

**If all tests pass, your delayed delivery system is working! 🎉**
