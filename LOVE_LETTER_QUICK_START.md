# Love Letter Feature - Quick Start Guide

## 🚀 Quick Setup for Demo

### Step 1: Configure Minimum Delivery Time
For demo purposes, change the minimum delivery time to 1 minute:

**Option A: Edit config file directly**
```php
// File: config/app.php (around line 127)
'love_letter_min_delivery_days' => 0.000694, // ~1 minute for demo
```

**Option B: Use environment variable**
```env
# Add to .env file
LOVE_LETTER_MIN_DELIVERY_DAYS=0.000694
```

Then clear cache:
```bash
php artisan config:clear
```

### Step 2: Start Queue Worker
Open a terminal and run:
```bash
php artisan queue:work
```

Keep this terminal open during demo.

### Step 3: Start Scheduler (Alternative to Queue Worker)
Or use the scheduler:
```bash
php artisan schedule:work
```

This will automatically check for letters to deliver every minute.

### Step 4: Access Letter Box
1. Login to AmoreCanvas
2. Go to Dashboard
3. Click "Enter Your Shared Canvas"
4. Click on "Letter Box" card (💌 icon)

## 📝 Demo Script

### Part 1: Sending a Letter
1. **Navigate to Letter Box**
   - From Shared Canvas, click "Letter Box"
   
2. **Click "Write a Letter"**
   
3. **Compose Your Letter**
   - Title: "My Dearest Love"
   - Content: Write a romantic message using the rich text editor
   - Try formatting: bold, italic, colors, different fonts
   
4. **Set Delivery Date**
   - The minimum date is automatically set (1 minute from now for demo)
   - You can select any date after the minimum
   
5. **Send the Letter**
   - Click "Send Letter with Love"
   - Notice: Letter disappears from sender's view immediately

### Part 2: Receiving the Letter
1. **Wait for Delivery** (~1 minute)
   - The scheduler/queue worker will process delivery
   - Run manually if needed: `php artisan letters:deliver`

2. **Check Email**
   - Receiver gets email: "💌 A Love Letter Has Arrived For You!"
   - Beautiful romantic template with link to open letter

3. **View in Letter Box**
   - Login as receiver
   - Go to Letter Box
   - See letter with "NEW" badge
   - Click to open and read

4. **Letter Actions**
   - **Read**: Opens full letter with formatting preserved
   - **Download**: Get HTML file to keep offline
   - **Add to Memory Lane**: Preserve with a note
   - **Delete**: Only possible after adding to Memory Lane

### Part 3: Memory Lane Integration
1. **Add Letter to Memory Lane**
   - Click "Add to Memory Lane" button
   - Write a note: "This letter touched my heart..."
   - Submit
   
2. **View in Memory Lane**
   - Navigate to Memory Lane
   - Use dropdown filter: Select "💌 Love Letters"
   - See letter as a memory (visible to both partners)
   
3. **Try to Delete**
   - Before adding to Memory Lane: Shows warning modal
   - After adding: Letter can be deleted (disappears from Letter Box)

## 🎯 Key Points to Demonstrate

### 1. Rich Text Editor
- Show formatting options (bold, italic, colors, fonts)
- Demonstrate headers and lists
- Show text alignment options

### 2. Scheduled Delivery
- Explain minimum waiting period (3 days default, 1 minute for demo)
- Show how sender cannot see letter after sending
- Demonstrate automatic delivery process

### 3. Email Notification
- Show beautiful romantic email design
- Explain receiver-only notification

### 4. Memory Lane Integration
- Mandatory preservation before deletion
- Both partners can see after adding
- Filter to show only love letters

### 5. Download Feature
- Letter exports as HTML with styling
- Can be kept offline

## 🐛 Troubleshooting

### Letter Not Delivering?
```bash
# Check if scheduler is running
php artisan schedule:work

# Or manually trigger delivery
php artisan letters:deliver

# Check queue jobs
php artisan queue:work
```

### Can't See Letter in Letter Box?
- Check if delivery time has passed
- Verify letter was sent (check database)
- Run delivery command manually

### Configuration Not Working?
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📊 Database Queries for Demo

```bash
# Check all letters
php artisan tinker
>>> App\Models\LoveLetter::all();

# Check pending letters
>>> App\Models\LoveLetter::readyForDelivery()->get();

# Check delivered letters
>>> App\Models\LoveLetter::delivered()->get();

# Exit
>>> exit
```

## 🎨 Features Highlight

| Feature | Description |
|---------|-------------|
| 💌 Rich Text Editor | Quill.js with romantic theme |
| ⏰ Scheduled Delivery | Configurable minimum wait time |
| 📧 Email Notification | Beautiful romantic template |
| 💾 Download Option | Export as HTML file |
| 🗂️ Memory Lane | Permanent storage with both partners |
| 🔒 Mandatory Save | Must preserve before deletion |
| 🎯 Filter Options | Unread/All in Letter Box, Type filter in Memory Lane |
| ✨ Romantic UI | Floating animations, gradients |

## ⚙️ Reverting to Production Settings

After demo, change back to 3 days:

```php
// config/app.php
'love_letter_min_delivery_days' => 3, // Back to 3 days
```

Or in .env:
```env
LOVE_LETTER_MIN_DELIVERY_DAYS=3
```

Then:
```bash
php artisan config:clear
```

---

**Ready to Demo! 💕**

The Love Letter feature is fully functional and ready to impress your professor!
