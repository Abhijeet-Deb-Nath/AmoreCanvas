# Love Letter Queue System - Time-Based Automatic Delivery

## 🔧 How It Works Now

### Old System (Manual Trigger):
1. Letter created → Saved to database
2. Scheduler runs `letters:deliver` command every minute
3. Command checks for ready letters
4. Command dispatches jobs to queue
5. Queue worker processes jobs

**Problem:** Required scheduler to run continuously

### New System (Automatic Queue-Based):
1. Letter created → Saved to database
2. **Delayed job dispatched immediately** with delivery time
3. Queue worker holds the job until scheduled time
4. Job executes automatically at exact time
5. Email sent + Letter marked as delivered

**Benefit:** Only need `php artisan queue:work` running!

## 📋 How Delayed Jobs Work

### When Letter is Created:
```php
// In LoveLetterController@store
$letter = LoveLetter::create([...]);

// Calculate delay in seconds
$deliveryTime = Carbon::parse($request->scheduled_delivery_at);
$delay = $deliveryTime->diffInSeconds(now());

// Dispatch job with delay
SendLoveLetterDeliveryEmail::dispatch($letter)->delay($delay);
```

### What Happens:
1. **Job is added to queue table** with `available_at` timestamp
2. Queue worker sees job but **waits** until `available_at` time
3. When time arrives, job becomes "available"
4. Worker picks it up and executes
5. Email sent + Letter marked as delivered

## 🚀 How to Test

### Step 1: Start Queue Worker
```bash
php artisan queue:work
```
**Important:** Keep this running!

### Step 2: Send a Test Letter
1. Login to AmoreCanvas
2. Go to Letter Box → Write a Letter
3. Set delivery time: **Current time + 2 minutes**
4. Submit the letter

### Step 3: Watch the Queue Worker Terminal
You'll see output like:
```
[2025-10-17 13:15:00] Processing: App\Jobs\SendLoveLetterDeliveryEmail
[2025-10-17 13:15:00] Processed:  App\Jobs\SendLoveLetterDeliveryEmail
```

### Step 4: Verify
```bash
# Check if letter was delivered
php artisan tinker
>>> App\Models\LoveLetter::latest()->first()->delivered_at
```

## 🔍 Checking Queue Status

### View Jobs in Queue:
```bash
php artisan tinker
>>> DB::table('jobs')->get()
```

### Check Delayed Jobs:
```bash
php artisan tinker
>>> DB::table('jobs')->select('id', 'available_at')->get()
```

The `available_at` timestamp shows when the job will run.

## 🐛 Troubleshooting

### Issue 1: Letter Not Delivered After Time Passed

**Check if queue worker is running:**
```bash
# In terminal where queue:work is running, you should see:
INFO  Processing jobs from the [default] queue.
```

**If not running:**
```bash
php artisan queue:work
```

### Issue 2: Job Stuck in Queue

**Check jobs table:**
```bash
php artisan tinker
>>> DB::table('jobs')->count()  # Should show pending jobs
```

**Restart queue worker:**
```bash
# Press Ctrl+C to stop
php artisan queue:restart  # Graceful restart
php artisan queue:work     # Start again
```

### Issue 3: Jobs Not Appearing in Queue

**Check if job was dispatched:**
```bash
# Look at storage/logs/laravel.log
# Should see: "Job dispatched with delay of X seconds"
```

**Verify letter was created:**
```bash
php artisan tinker
>>> App\Models\LoveLetter::latest()->first()
```

### Issue 4: Time Already Passed But Job Not Created

**For existing letters (created before this fix):**
```bash
# Manually dispatch them
php artisan letters:deliver
```

**For new letters:**
- They will auto-dispatch when created
- No manual command needed!

## 📊 Queue Worker Options

### Basic (What You Need):
```bash
php artisan queue:work
```

### With Logging:
```bash
php artisan queue:work --verbose
```

### Test Once (Process One Job Only):
```bash
php artisan queue:work --once
```

### With Timeout:
```bash
php artisan queue:work --timeout=60
```

## 🎯 For Demo/Testing

### Test 1-Minute Delivery:
```bash
# 1. Ensure config is set to 1 minute
# config/app.php: 'love_letter_min_delivery_days' => 1/1440

# 2. Start queue worker
php artisan queue:work --verbose

# 3. Send letter with delivery time = now + 1 minute

# 4. Watch terminal - you'll see job process after 1 minute!
```

### Test Immediate Delivery (for demo):
```bash
# Set to 0 minutes for instant delivery
# config/app.php: 'love_letter_min_delivery_days' => 0

php artisan config:clear
php artisan queue:work --verbose

# Send letter - it will deliver within seconds!
```

## 🔄 Migration from Old Letters

If you have letters in database that were created BEFORE this fix:

```bash
# Option 1: Manually trigger delivery for past-due letters
php artisan letters:deliver

# Option 2: Delete old test letters and create new ones
php artisan tinker
>>> App\Models\LoveLetter::whereNull('delivered_at')->delete()
```

## ✅ Verification Checklist

- [ ] Queue worker is running (`php artisan queue:work`)
- [ ] Config cleared (`php artisan config:clear`) 
- [ ] Server restarted after code changes
- [ ] Test letter created with future delivery time
- [ ] Job appears in `jobs` table
- [ ] Queue worker logs show "Processing" at delivery time
- [ ] Letter's `delivered_at` is set in database
- [ ] Receiver sees letter in Letter Box

## 🎉 Benefits of This Approach

✅ **No scheduler needed** - Just queue worker
✅ **Exact time delivery** - Jobs fire at precise moment
✅ **Automatic** - No manual commands
✅ **Scalable** - Can handle many delayed letters
✅ **Reliable** - Laravel's queue system is battle-tested
✅ **Easy testing** - Just keep queue:work running

---

**Updated:** October 17, 2025  
**Status:** ✅ Automatic Queue-Based Time Delivery
