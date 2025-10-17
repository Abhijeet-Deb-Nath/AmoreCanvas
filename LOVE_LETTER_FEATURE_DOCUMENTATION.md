# Love Letter Feature Documentation

## Overview
The Love Letter feature allows users in an Eternal Bond to send romantic love letters to each other with scheduled delivery dates. Letters are composed using a rich text editor and must wait a minimum configurable period before delivery.

## Features Implemented

### 1. Configuration
- **File**: `config/app.php`
- **Setting**: `love_letter_min_delivery_days` (default: 3 days)
- **Environment Variable**: `LOVE_LETTER_MIN_DELIVERY_DAYS`
- **Purpose**: For demo purposes, you can change this to `0.000694` (1 minute) in the config file

### 2. Database
- **Migration**: `2025_10_17_115326_create_love_letters_table.php`
- **Table**: `love_letters`
- **Fields**:
  - `id` - Primary key
  - `connection_id` - Foreign key to connections
  - `sender_id` - Foreign key to users (sender)
  - `receiver_id` - Foreign key to users (receiver)
  - `title` - Letter title (visible in letter box)
  - `content` - HTML content from rich text editor
  - `scheduled_delivery_at` - When the letter should be delivered
  - `delivered_at` - When the letter was actually delivered (nullable)
  - `read_at` - When the letter was read (nullable)
  - `is_in_memory_lane` - Boolean flag for Memory Lane integration
  - `created_at`, `updated_at` - Timestamps

### 3. Model
- **File**: `app/Models/LoveLetter.php`
- **Key Methods**:
  - `isDelivered()` - Check if letter is delivered
  - `isRead()` - Check if letter is read
  - `markAsDelivered()` - Mark letter as delivered
  - `markAsRead()` - Mark letter as read
- **Scopes**:
  - `delivered()` - Get only delivered letters
  - `unread()` - Get only unread letters
  - `readyForDelivery()` - Get letters ready for delivery

### 4. Controller
- **File**: `app/Http/Controllers/LoveLetterController.php`
- **Routes & Actions**:
  - `GET /love-letters` - index() - List received letters
  - `GET /love-letters/create` - create() - Show compose form
  - `POST /love-letters` - store() - Save new letter
  - `GET /love-letters/{id}` - show() - Display letter
  - `POST /love-letters/{id}/mark-as-read` - markAsRead() - Mark as read
  - `GET /love-letters/{id}/download` - download() - Download as HTML
  - `POST /love-letters/{id}/add-to-memory-lane` - addToMemoryLane() - Add to Memory Lane
  - `DELETE /love-letters/{id}` - destroy() - Delete letter (requires Memory Lane first)

### 5. Email & Queue System
- **Job**: `app/Jobs/SendLoveLetterDeliveryEmail.php`
  - Dispatched when a letter is ready for delivery
  - Sends email notification to receiver
  - Marks letter as delivered
  
- **Mailable**: `app/Mail/LoveLetterDelivered.php`
  - Romantic email template
  - Subject: "💌 A Love Letter Has Arrived For You!"
  - View: `emails.love-letter-delivered`

- **Email Template**: `resources/views/emails/love-letter-delivered.blade.php`
  - Beautiful romantic design with gradient background
  - Animated hearts
  - Link to open letter in web app

### 6. Scheduled Command
- **Command**: `app/Console/Commands/DeliverLoveLetters.php`
- **Signature**: `letters:deliver`
- **Schedule**: Runs every minute (configured in `routes/console.php`)
- **Function**: Checks for letters ready for delivery and dispatches email jobs

### 7. Views

#### Letter Box (Index)
- **File**: `resources/views/love-letters/index.blade.php`
- **Features**:
  - Floating envelope animations
  - Filter tabs (Unread/All Letters)
  - Grid layout of letter cards
  - Unread badges
  - Empty state design

#### Compose Letter
- **File**: `resources/views/love-letters/create.blade.php`
- **Features**:
  - Rich text editor (Quill.js) with romantic theme
  - Formatting tools: headers, bold, italic, underline, colors, fonts, lists, etc.
  - Title input field (mandatory)
  - Delivery date picker with minimum date validation
  - Romantic quote display

#### View Letter
- **File**: `resources/views/love-letters/show.blade.php`
- **Features**:
  - Beautiful letter display with metadata (from, written date, delivered date)
  - Download as HTML button
  - Add to Memory Lane modal (with mandatory note)
  - Delete warning modal (must add to Memory Lane first)
  - Letter disappears from Letter Box after adding to Memory Lane

### 8. Navigation Integration
- **Shared Canvas**: Added "Letter Box" card in navigation grid
- **Icon**: 💌
- **Description**: "Transmit your emotions through letters. Write and receive love letters with delivery dates."

### 9. Memory Lane Integration
- **Filter Dropdown**: Added filter to show only "Love Letters" (text type)
- **Letter Storage**: When added to Memory Lane, letter is saved as HTML file
- **Visibility**: Both partners can see the letter in Memory Lane after receiver adds it
- **Requirement**: Receiver MUST add letter to Memory Lane before deleting it

## User Flow

### Sending a Letter
1. Navigate to Shared Canvas → Letter Box
2. Click "Write a Letter"
3. Enter letter title (mandatory)
4. Compose letter using rich text editor
5. Select delivery date (minimum 3 days from now)
6. Click "Send Letter with Love"
7. Letter vanishes from sender's view immediately
8. System schedules delivery

### Receiving a Letter
1. When delivery time arrives:
   - Letter is marked as delivered in database
   - Email notification sent to receiver
2. Receiver sees letter in Letter Box (with "NEW" badge)
3. Click to open and read letter
4. Letter is marked as read automatically
5. Options available:
   - Download letter as HTML file
   - Add to Memory Lane (with note)
   - Delete (only after adding to Memory Lane)

### Memory Lane Integration
1. Receiver decides to preserve letter
2. Clicks "Add to Memory Lane"
3. Writes a mandatory note about the memory
4. Letter is saved as HTML file in storage
5. Memory Lane entry is created (visible to both partners)
6. Letter disappears from Letter Box
7. Can now be deleted if desired

## Demo Configuration

To demonstrate the feature to your professor without waiting 3 days:

1. Open `config/app.php`
2. Change line:
   ```php
   'love_letter_min_delivery_days' => env('LOVE_LETTER_MIN_DELIVERY_DAYS', 3),
   ```
   To:
   ```php
   'love_letter_min_delivery_days' => env('LOVE_LETTER_MIN_DELIVERY_DAYS', 0.000694), // ~1 minute
   ```
3. Clear config cache:
   ```bash
   php artisan config:clear
   ```
4. Letters will now deliver after 1 minute

## Queue Worker

To process letter deliveries, run the queue worker:
```bash
php artisan queue:work
```

Or run the scheduler:
```bash
php artisan schedule:work
```

This will check for letters to deliver every minute.

## Manual Testing Commands

```bash
# Check for letters ready for delivery manually
php artisan letters:deliver

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Check database
php artisan tinker
>>> App\Models\LoveLetter::all();
```

## File Structure

```
app/
├── Console/Commands/
│   └── DeliverLoveLetters.php
├── Http/Controllers/
│   └── LoveLetterController.php
├── Jobs/
│   └── SendLoveLetterDeliveryEmail.php
├── Mail/
│   └── LoveLetterDelivered.php
└── Models/
    └── LoveLetter.php

config/
└── app.php (love_letter_min_delivery_days config)

database/migrations/
└── 2025_10_17_115326_create_love_letters_table.php

resources/views/
├── emails/
│   └── love-letter-delivered.blade.php
└── love-letters/
    ├── index.blade.php (Letter Box)
    ├── create.blade.php (Compose Letter)
    └── show.blade.php (View Letter)

routes/
├── web.php (Love letter routes)
└── console.php (Scheduled command)
```

## Key Features Summary

✅ **Rich Text Editor**: Quill.js with romantic customization
✅ **Scheduled Delivery**: Configurable minimum wait time
✅ **Email Notifications**: Beautiful romantic email templates
✅ **Queue System**: Background job processing
✅ **Memory Lane Integration**: Permanent storage with notes
✅ **Mandatory Preservation**: Must save to Memory Lane before deletion
✅ **Download Option**: Export letter as HTML file
✅ **Unread/All Filters**: Easy navigation of received letters
✅ **Sender Invisibility**: Sender cannot see letter after sending
✅ **Romantic UI**: Floating animations, gradients, beautiful design

## Security & Privacy

- Only bonded users can send/receive letters
- Only receiver can view, download, or delete their letters
- Letters in transit are not visible to anyone
- Sender has no access to letter after sending
- All actions are authenticated and authorized

## Notes for Professor Demo

1. Set minimum delivery time to 1 minute before demo
2. Run queue worker or scheduler in background
3. Send a test letter from one account
4. Wait 1 minute
5. Check email for delivery notification
6. Open letter in receiver's Letter Box
7. Demonstrate Memory Lane integration
8. Show mandatory preservation before deletion
9. Filter Memory Lane to show only love letters

---

**Created**: October 17, 2025  
**Feature**: Love Letter System  
**Status**: ✅ Complete and Functional
