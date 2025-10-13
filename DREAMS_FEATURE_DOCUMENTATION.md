# Shared Dreams Feature - Complete Implementation Guide

## 🌟 Overview

The **Shared Dreams** feature allows couples in AmoreCanvas to create, validate, and schedule dreams they wish to live together. It includes a complete workflow from dream creation to fulfillment, with automated email notifications and a sophisticated date negotiation system.

## 📋 Feature Architecture

### Database Schema

#### 1. **dreams** table
- Stores all dream entries with their details and status
- Fields: heading, title (optional), description, place, status, destiny_date, timestamps
- Status flow: `solo` → `shared` → `planning` → `scheduled` → `cherished` → `fulfilled`

#### 2. **dream_destiny_negotiations** table
- Tracks all date/time proposals and responses
- Records messages exchanged during planning
- Statuses: pending, accepted, rejected, edited

#### 3. **dream_notifications** table
- Manages email notification scheduling
- Types: 24_hours, 1_hour, 10_minutes, exact_time
- Statuses: pending, queued, sent, failed

### Key Components

#### Models
- **Dream.php** - Main dream model with relationships and state management methods
- **DreamDestinyNegotiation.php** - Handles date proposal negotiations
- **DreamNotification.php** - Manages notification tracking
- **User.php** - Extended with dream-related methods

#### Controller
- **DreamController.php** - Complete CRUD and workflow management (30+ methods)

#### Jobs & Mail
- **SendDreamReminderEmail.php** - Queue job for sending timed notifications
- **DreamReminderMail.php** - Beautiful email template for reminders

#### Console Command
- **CheckOverdueDreams.php** - Hourly check to move past dreams to cherished memories

## 🎯 User Workflow

### 1. **Shared Dreams Page** (`/shared-dreams`)
Three categories displayed:
- **My Dreams** - Solo dreams created by current user (blue tag)
- **Partner's Dreams** - Solo dreams created by partner (pink tag)
- **Our Shared Dreams** - Validated dreams (red tag)

Actions:
- Create new dream
- View dream details
- Validate partner's dream → "Let's Dream This Together!"
- Edit/Delete own dreams

### 2. **Dream Creation** (`/dreams/create`)
Required fields:
- Heading (required)
- Title (optional)
- Description (required)
- Place (required)

### 3. **Dream Validation**
- Partner can validate any solo dream
- Upon validation, dream moves to "Our Shared Dreams" section
- Status changes: `solo` → `shared`

### 4. **Planning (Destiny Date Negotiation)** (`/dreams/{id}/plan-destiny`)
- Either partner can propose a destiny date
- Date selection: Year, Month, Day, Hour, Minute
- Optional message with each proposal
- Partner can:
  - **Accept** - Dream moves to Bucket List with confirmed date
  - **Decline** - Negotiation rejected, can propose new date
  - **Counter-propose** - Suggest different time with message

Planning history is logged and displayed at bottom of page.

### 5. **Bucket List** (`/bucket-list`)
Shows all dreams with confirmed destiny dates:
- Displays countdown timer (X days, Y hours, Z minutes to go)
- Sorted by nearest date first
- Auto-notifications scheduled:
  - 24 hours before
  - 1 hour before
  - 10 minutes before
  - Exact time

### 6. **Cherished Memories** (`/bucket-list/cherished-memories`)
Dreams whose destiny date has passed:
- Options for each dream:
  - **"We Lived This Dream!"** - Move to Fulfilled section
  - **Reschedule** - Move back to Shared Dreams, remove date
  - **Delete** - Permanently remove

### 7. **Lived in the Dream** (`/lived-in-the-dream`)
Fulfilled dreams:
- Shows all dreams marked as lived
- Option: **"Add to Memory Lane"** - Create Memory Lane entry with additional details
- Original dream details preserved

### 8. **Create Memory Lane Entry** (`/dreams/{id}/create-memory`)
Transform fulfilled dream into memory:
- Optional fields:
  - Actual date (if different from planned)
  - Extended description
  - Photos/images upload
  - Special notes
- Dream data remains in "Lived in the Dream" section

## 🔔 Email Notification System

### Implementation Strategy: **Queue-based with Delayed Jobs**

**Why not just a scheduler?**
- ✅ Precise timing (no 1-minute polling overhead)
- ✅ Scalable (no constant database queries)
- ✅ Laravel's native delayed job dispatch

### How It Works:

1. When dream is scheduled (both partners agree on date):
   ```php
   scheduleDreamNotifications($dream) {
       // Calculate 4 notification times
       // Queue each with delay() to exact timestamp
       SendDreamReminderEmail::dispatch($dream, '24_hours')->delay($sendAt);
       SendDreamReminderEmail::dispatch($dream, '1_hour')->delay($sendAt);
       // etc...
   }
   ```

2. **Queue Worker** executes jobs at precise times
3. **Emails sent** to both partners simultaneously
4. **Notification records** updated (pending → queued → sent/failed)

### Email Content:
- Beautiful gradient design
- Dream details (heading, description, place)
- Destiny date prominently displayed
- Different messages for each timing:
  - 24h: "Tomorrow is the day!"
  - 1h: "One hour until your dream comes true"
  - 10min: "Get ready! Just minutes away"
  - Exact: "Go live your dream together!"

### Scheduler Backup:
- Hourly command (`dreams:check-overdue`) checks for:
  - Dreams whose date passed → move to cherished
  - Failed notifications → retry or log

## 🎨 Design Philosophy

**Romantic & Classy Naming:**
- "Shared Dreams" (not "Goals")
- "Destiny Date" (not "Schedule")
- "Planning" (not "Negotiations")
- "Cherished Memories" (not "Past Events")
- "Lived in the Dream" (not "Completed")
- "Whisper a Dream" (create action)
- "Let's Dream This Together!" (validate action)

**Visual Theme:**
- Gradient backgrounds (purple/pink romantic tones)
- Emoji accents (💫✨💖🌟)
- Soft card shadows
- Color-coded tags (blue=mine, pink=partner's, red=shared)
- Romantic fonts (Georgia serif)

## 🚀 Setup Instructions

### 1. Queue Configuration

**`.env` file:**
```env
QUEUE_CONNECTION=database
# Or use redis for production:
# QUEUE_CONNECTION=redis
```

**Create queue jobs table:**
```bash
php artisan queue:table
php artisan migrate
```

### 2. Start Queue Worker

**Development:**
```bash
php artisan queue:work
```

**Production (with supervisor):**
```ini
[program:amorecanvas-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/amorecanvas/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/logs/worker.log
```

### 3. Start Scheduler

Add to crontab:
```bash
* * * * * cd /path/to/amorecanvas && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs hourly check: `dreams:check-overdue`

### 4. Mail Configuration

Ensure `.env` has mail settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@amorecanvas.com
MAIL_FROM_NAME="AmoreCanvas"
```

## 📍 Routes Reference

### Shared Dreams
- `GET /shared-dreams` - View all dreams (3 categories)
- `GET /dreams/create` - Create dream form
- `POST /dreams` - Store dream
- `GET /dreams/{id}` - View dream details
- `GET /dreams/{id}/edit` - Edit dream
- `PUT /dreams/{id}` - Update dream
- `DELETE /dreams/{id}` - Delete dream
- `POST /dreams/{id}/validate` - Validate partner's dream

### Planning
- `GET /dreams/{id}/plan-destiny` - Destiny date planning page
- `POST /dreams/{id}/propose-date` - Propose new date
- `POST /dreams/{dreamId}/accept-date/{negotiationId}` - Accept proposal
- `POST /dreams/{dreamId}/reject-date/{negotiationId}` - Reject proposal
- `POST /dreams/{dreamId}/edit-date/{negotiationId}` - Counter-propose

### Bucket List
- `GET /bucket-list` - View scheduled dreams
- `GET /bucket-list/cherished-memories` - Past dreams
- `POST /dreams/{id}/reschedule` - Move back to shared
- `POST /dreams/{id}/mark-fulfilled` - Mark as lived

### Lived in the Dream
- `GET /lived-in-the-dream` - View fulfilled dreams
- `GET /dreams/{id}/create-memory` - Memory Lane creation form

## 🔧 Extending the Feature

### Adding New Notification Times

In `DreamController::scheduleDreamNotifications()`:
```php
$notifications = [
    ['type' => '24_hours', 'minutes_before' => 24 * 60],
    ['type' => '1_hour', 'minutes_before' => 60],
    ['type' => '10_minutes', 'minutes_before' => 10],
    ['type' => 'exact_time', 'minutes_before' => 0],
    // Add new:
    ['type' => '1_week', 'minutes_before' => 7 * 24 * 60],
];
```

Update migration enum and email template accordingly.

### Custom Email Templates

Edit: `resources/views/emails/dream-reminder.blade.php`

### Dream Categories/Tags

Add `category` field to dreams table migration:
```php
$table->enum('category', ['travel', 'adventure', 'romantic', 'achievement'])->nullable();
```

## 🐛 Troubleshooting

### Emails Not Sending
1. Check queue is running: `php artisan queue:work`
2. Check mail config in `.env`
3. View failed jobs: `php artisan queue:failed`
4. Check logs: `storage/logs/laravel.log`

### Dreams Not Moving to Cherished
1. Verify scheduler is running (cron job)
2. Manually run: `php artisan dreams:check-overdue`
3. Check dream status and destiny_date in database

### Notifications Not Scheduled
1. Verify `QUEUE_CONNECTION=database` in `.env`
2. Check `jobs` table for queued entries
3. Ensure destiny_date is in future when scheduling

## 📊 Database Queries for Admin

### View all scheduled notifications:
```sql
SELECT d.heading, dn.notification_type, dn.scheduled_for, dn.status 
FROM dream_notifications dn 
JOIN dreams d ON dn.dream_id = d.id 
WHERE dn.status = 'queued';
```

### View active negotiations:
```sql
SELECT d.heading, u.name as proposer, ddn.proposed_date, ddn.status 
FROM dream_destiny_negotiations ddn
JOIN dreams d ON ddn.dream_id = d.id
JOIN users u ON ddn.proposed_by = u.id
WHERE ddn.status = 'pending';
```

## ✨ Features Implemented

✅ Solo dream creation
✅ Partner validation system
✅ Dream status workflow (6 states)
✅ Destiny date negotiation with messaging
✅ Automated email notifications (4 timing options)
✅ Bucket List with countdown timers
✅ Cherished Memories management
✅ Fulfilled dreams tracking
✅ Memory Lane integration
✅ Responsive romantic UI
✅ Queue-based notification system
✅ Scheduler for cleanup tasks
✅ Complete CRUD operations
✅ Security checks (connection validation)
✅ Edit/Delete/Reschedule capabilities

## 🎉 Success!

Your **Shared Dreams** feature is fully implemented and ready to use! Couples can now:
- Dream together
- Plan their future
- Get reminded at perfect times
- Cherish fulfilled moments
- Build lasting memories

Enjoy building beautiful moments with AmoreCanvas! 💖✨
