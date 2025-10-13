# Bucket List Enhancement Features Documentation

## Overview
This document describes the enhanced Bucket List features added to AmoreCanvas, including reschedule functionality, removal process, cherished memories management, and the new "Lived in the Dream" section.

## Feature Summary

### 1. **Reschedule Functionality**
- Users can propose a new date/time for scheduled dreams
- Mandatory comment explaining the reason
- Partner must approve, reject, or counter-propose
- Works like the initial planning negotiation

### 2. **Remove from Bucket List**
- Two-way confirmation required
- User A requests removal with mandatory comment
- User B must confirm with their own comment
- Dream moves back to Shared Dreams (no schedule)

### 3. **Automatic Move to Cherished Memories**
- Happens automatically when exact-time email is sent
- No console command or scheduler needed
- Dream status changes from 'scheduled' to 'cherished' at exact destiny time
- Tightly coupled with notification system (same moment)

### 4. **Mark as Fulfilled**
- Single user confirmation (no partner approval needed)
- Mandatory comment about the experience
- Dream moves to "Lived in the Dream" section
- Status changes: cherished → fulfilled

### 5. **Mark as Missed**
- Single user confirmation
- Mandatory comment explaining what happened
- Dream returns to Shared Dreams (no schedule)
- Logged in planning history

### 6. **Lived in the Dream Section**
- New separate card on shared canvas
- Shows all fulfilled dreams
- Option to create Memory Lane entry from fulfilled dreams

### 7. **Enhanced Planning History**
- Shows all negotiation types (proposals, reschedules, removals, missed)
- Comments for every action
- Accumulates multiple missed attempts
- Clear status badges for each action type

### 8. **Smart Navigation**
- Detail pages detect dream status
- Scheduled/Cherished dreams show "Back to Bucket List"
- Other dreams show "Back to Shared Dreams"

---

## Dream Status Flow

```
solo → shared → planning → scheduled → cherished → fulfilled
                              ↓           ↓
                         (reschedule)  (missed)
                              ↓           ↓
                          planning     shared
```

### Status Definitions:
- **solo**: Created by one partner only
- **shared**: Validated by both partners
- **planning**: Destiny date negotiation in progress
- **scheduled**: Destiny date confirmed (future date) - **IN BUCKET LIST**
- **cherished**: Date passed but not marked - **IN CHERISHED MEMORIES**
- **fulfilled**: Marked as lived - **IN LIVED IN THE DREAM**

### Key Transition: scheduled → cherished
**This transition is automated and coupled with the exact-time email notification.**

**How it works:**
1. Dream is scheduled with a destiny_date
2. Four email notifications are queued (24h, 1h, 10min, exact time)
3. When the "exact time" email job runs:
   - Sends reminder email to both partners
   - **Immediately updates dream status to 'cherished'**
   - Sets cherished_at timestamp
4. Dream appears in Cherished Memories section

**Why coupled?**
- Both events represent the same moment: "The dream's time has arrived"
- Ensures perfect timing synchronization
- More efficient than separate polling/checking
- Single source of truth (the queue system)
- Email sent = status updated (atomic operation)

**Failure handling:**
- If email fails but job runs: Status still updates (dream time has passed)
- If job retries: Status update is idempotent (checks current status first)
- Transaction-safe: Both operations succeed or job retries

---

## Database Schema Updates

### Dreams Table (Updated)
```php
enum('status'): [
    'solo',
    'shared',
    'planning',
    'scheduled',  // In Bucket List
    'cherished',  // In Cherished Memories
    'fulfilled',  // In Lived in the Dream
    'deleted'
]
```

### Dream Destiny Negotiations Table (Updated)
```php
enum('status'): [
    'pending',           // Awaiting response
    'accepted',          // Accepted
    'rejected',          // Rejected
    'edited',            // Counter-proposed
    'rescheduled',       // Reschedule request
    'remove_requested',  // Removal request
    'remove_confirmed',  // Removal confirmed by both
    'missed'             // Marked as missed
]
```

---

## New Controller Methods

### DreamController

#### 1. `requestReschedule($id)`
- **Purpose**: Show reschedule request form
- **Access**: Scheduled dreams only
- **Returns**: `dreams.request-reschedule` view

#### 2. `submitReschedule(Request $request, $id)`
- **Purpose**: Submit reschedule proposal
- **Validation**:
  - year, month, day, hour, minute (required)
  - message (required, max 500 chars)
- **Actions**:
  - Creates negotiation with status 'rescheduled'
  - Changes dream status to 'planning'
- **Redirect**: Dream detail page with success message

#### 3. `requestRemove(Request $request, $id)`
- **Purpose**: Request to remove dream from bucket list
- **Validation**:
  - message (required, max 500 chars)
- **Actions**:
  - Creates negotiation with status 'remove_requested'
- **Redirect**: Dream detail with "waiting for partner" message

#### 4. `confirmRemove(Request $request, $dreamId, $negotiationId)`
- **Purpose**: Partner confirms removal
- **Validation**:
  - message (required, max 500 chars) - partner's comment
- **Actions**:
  - Updates original negotiation to 'remove_confirmed'
  - Creates new negotiation with partner's comment
  - Changes dream status: scheduled → shared
  - Removes destiny_date
  - Cancels pending notifications
- **Redirect**: Shared Dreams index

#### 5. `markFulfilled(Request $request, $id)` (Updated)
- **Purpose**: Mark dream as fulfilled
- **Validation**:
  - message (required, max 500 chars)
- **Actions**:
  - Creates negotiation with fulfilled message
  - Changes status: cherished → fulfilled
  - Sets fulfilled_at timestamp
- **Redirect**: Lived in the Dream section

#### 6. `markMissed(Request $request, $id)`
- **Purpose**: Mark dream as missed
- **Validation**:
  - message (required, max 500 chars)
- **Actions**:
  - Creates negotiation with "Missed Schedule" prefix
  - Changes status: cherished → shared
  - Removes destiny_date and timestamps
- **Redirect**: Shared Dreams index

---

## New Console Command

~~**This section is obsolete - status update now happens automatically with exact-time email**~~

### Previous Approach (Removed):
- Console command `dreams:move-to-cherished` has been removed
- Scheduler is no longer needed for this feature
- Status update is now handled by `SendDreamReminderEmail` job

### Current Approach:
- When the exact-time notification email is sent
- The same job also updates the dream status to 'cherished'
- This ensures both events happen at the exact same moment
- More efficient and reliable than polling

---

## New Routes

### Reschedule Routes
```php
GET  /dreams/{id}/request-reschedule    → DreamController@requestReschedule
POST /dreams/{id}/submit-reschedule     → DreamController@submitReschedule
```

### Removal Routes
```php
POST /dreams/{id}/request-remove                           → DreamController@requestRemove
POST /dreams/{dreamId}/confirm-remove/{negotiationId}      → DreamController@confirmRemove
```

### Cherished Actions
```php
POST /dreams/{id}/mark-fulfilled    → DreamController@markFulfilled
POST /dreams/{id}/mark-missed       → DreamController@markMissed
```

### Lived in the Dream
```php
GET /lived-in-the-dream                  → DreamController@lived
GET /dreams/{id}/create-memory           → DreamController@createMemoryFromDream
```

---

## New/Updated Views

### 1. `resources/views/dreams/request-reschedule.blade.php` (NEW)
**Purpose**: Form to request reschedule with new date/time

**Features**:
- Shows current schedule
- Date/time inputs with AM/PM toggle
- Mandatory comment field
- Validation error display
- Cancel button

**Styling**: Purple gradient theme matching app design

### 2. `resources/views/dreams/show.blade.php` (UPDATED)
**New Features**:
- Smart back navigation (detects if from bucket list or shared dreams)
- Action buttons for scheduled dreams:
  - 📅 Reschedule button
  - ❌ Remove from Bucket List button (with inline form)
- Action buttons for cherished dreams:
  - ✨ Mark as Fulfilled button (with inline form)
  - 📅 We Missed It button (with inline form)
- Enhanced Planning History section:
  - Shows all negotiation types
  - Handles removal requests with approve button
  - Handles reschedule requests with accept/counter buttons
  - Better status badge formatting

**New Status Tags**:
- `tag-cherished`: Purple (#9c27b0)
- `tag-fulfilled`: Pink (#ff6b9d)

**New Status Badges**:
- `status-rescheduled`: Light blue (#29b6f6)
- `status-remove_requested`: Orange (#ff9800)
- `status-remove_confirmed`: Brown (#8d6e63)
- `status-missed`: Gray (#757575)

### 3. `resources/views/connections/shared-canvas.blade.php` (UPDATED)
**Changes**:
- Added "Lived in the Dream" navigation card
- Icon: 🌟
- Description: "Fulfilled dreams that became reality"

---

## User Workflows

### Workflow 1: Reschedule a Bucket List Item

1. User opens scheduled dream detail page
2. Clicks "📅 Reschedule" button
3. Redirected to reschedule form with current date pre-filled
4. Enters new date/time and mandatory comment
5. Submits form
6. Dream status changes to 'planning'
7. Partner receives notification (sees in Planning History)
8. Partner can:
   - Accept new date → Dream scheduled again
   - Suggest different time → Back to negotiation
   - Reject → Can discuss and propose again

### Workflow 2: Remove from Bucket List

1. User A opens scheduled dream detail page
2. Clicks "❌ Remove from Bucket List"
3. Inline form appears
4. Enters mandatory comment explaining why
5. Submits removal request
6. User B opens same dream
7. Sees removal request in yellow box with User A's comment
8. Enters their own comment
9. Clicks "✓ Agree to Remove"
10. Dream moved back to Shared Dreams
11. destiny_date removed
12. Both can re-plan it later if needed

### Workflow 3: Dream Becomes Cherished

1. Scheduled dream has destiny_date in the future
2. Console command runs every 15 minutes
3. When destiny_date passes:
   - Status automatically changes to 'cherished'
   - Appears in Cherished Memories section (inside Bucket List)
4. Both users can now:
   - Mark as Fulfilled (if they lived it)
   - Mark as Missed (if they couldn't make it)

### Workflow 4: Mark Dream as Fulfilled

1. User opens cherished dream detail page
2. Clicks "✨ Mark as Fulfilled"
3. Inline form appears
4. Enters mandatory comment about the experience
5. Submits form
6. Dream status changes to 'fulfilled'
7. Dream appears in "Lived in the Dream" section
8. Can create Memory Lane entry from it

### Workflow 5: Mark Dream as Missed

1. User opens cherished dream detail page
2. Clicks "📅 We Missed It"
3. Inline form appears
4. Enters mandatory comment explaining what happened
5. Submits form
6. Dream status changes to 'shared'
7. destiny_date removed
8. Dream returns to Shared Dreams
9. Missed entry logged in Planning History
10. Both can re-plan it anytime

---

## Planning History Display

The Planning History section now shows all types of negotiations with proper formatting:

### Display Format:

**Proposal/Negotiation**:
```
[User] proposed:
📅 Sunday, October 13, 2025 at 6:00 PM
💭 "Let's make this evening special!"
```

**Reschedule Request**:
```
[User] requested reschedule:
📅 Monday, October 14, 2025 at 7:00 PM
💭 "Work meeting got extended, sorry!"
[Accept Button] [Suggest Different Time]
```

**Removal Request**:
```
[User] requested removal:
💭 "Plans changed, let's do this another time"
[Approve Form with Comment Field]
```

**Removal Confirmed**:
```
[User] confirmed removal:
💭 "Agreed to remove: Yeah, let's reschedule later"
```

**Missed Schedule**:
```
[User] marked as missed:
📅 Sunday, October 13, 2025 at 6:00 PM
💭 "📅 Missed Schedule: Car broke down, couldn't make it"
```

**Fulfilled**:
```
[User] marked as fulfilled:
💭 "✨ Marked as fulfilled: It was absolutely magical! Best evening ever!"
```

---

## Shared Canvas Navigation

### Updated Layout:
```
🌟 Explore Your Shared Space 🌟

[📸 Memory Lane]     [✨ Shared Dreams]
[📋 Bucket List]     [🌟 Lived in the Dream]
[💬 Love Chat]       [📅 Special Dates]
(Coming Soon)        (Coming Soon)
```

---

## Validation Rules

### All Comment Fields:
- **Required**: Yes
- **Type**: String
- **Max Length**: 500 characters
- **Purpose**: Ensure meaningful communication between partners

### Reschedule Date/Time:
- **Year**: Integer, minimum current year
- **Month**: Integer, 1-12
- **Day**: Integer, 1-31
- **Hour**: Integer, 0-23 (after AM/PM conversion)
- **Minute**: Integer, 0-59

---

## Security & Access Control

### All Actions:
1. **Authentication**: User must be logged in
2. **Bond Verification**: User must have eternal bond
3. **Ownership**: Dream must belong to user's connection
4. **Status Validation**: Action only available for appropriate status

### Specific Rules:
- **Reschedule**: Only 'scheduled' dreams
- **Remove**: Only 'scheduled' dreams
- **Confirm Remove**: Cannot confirm own request
- **Mark Fulfilled**: Only 'cherished' dreams
- **Mark Missed**: Only 'cherished' dreams

---

## Notifications

### Cancelled Notifications:
When a dream is removed from bucket list:
- All pending DreamNotification records are cancelled
- Status updated to 'cancelled'
- No reminder emails will be sent

### Rescheduling:
- When rescheduling is accepted, new notifications are scheduled
- Old notifications are automatically cancelled (dream goes to 'planning' status)

---

## Testing Checklist

### Reschedule Feature:
- [ ] Request reschedule from scheduled dream
- [ ] Partner receives notification in Planning History
- [ ] Partner can accept new date
- [ ] Partner can counter-propose
- [ ] Dream status changes correctly
- [ ] Old notifications cancelled, new ones scheduled

### Remove from Bucket List:
- [ ] Request removal with comment
- [ ] Partner sees removal request
- [ ] Partner can confirm with their comment
- [ ] Dream moves to Shared Dreams
- [ ] destiny_date is null
- [ ] Both users can see it in Shared Dreams
- [ ] Planning history shows both comments

### Auto-Move to Cherished:
- [ ] Create scheduled dream (or use existing one)
- [ ] Wait for exact-time email to be sent (or simulate with queue worker)
- [ ] Verify exact-time email is sent to both partners
- [ ] Dream status automatically changes to 'cherished'
- [ ] cherished_at timestamp is set
- [ ] Dream appears in Cherished Memories section
- [ ] Process happens atomically (email + status update)

### Mark as Fulfilled:
- [ ] Open cherished dream
- [ ] Click Mark as Fulfilled
- [ ] Enter comment
- [ ] Dream moves to Lived in the Dream
- [ ] Status is 'fulfilled'
- [ ] fulfilled_at timestamp is set
- [ ] Comment appears in Planning History

### Mark as Missed:
- [ ] Open cherished dream
- [ ] Click We Missed It
- [ ] Enter comment
- [ ] Dream moves to Shared Dreams
- [ ] destiny_date is null
- [ ] Missed entry in Planning History shows date and comment
- [ ] Can re-plan the dream

### Navigation:
- [ ] Scheduled dream shows "Back to Bucket List"
- [ ] Cherished dream shows "Back to Bucket List"
- [ ] Shared dream shows "Back to Shared Dreams"
- [ ] Lived in the Dream card on shared canvas works

---

## Future Enhancements

### Potential Improvements:
1. **Notifications**: Email when partner requests reschedule/removal
2. **Statistics**: Show missed dreams count, fulfillment rate
3. **Reminders**: Remind about cherished dreams (not yet fulfilled/missed)
4. **Batch Actions**: Mark multiple cherished as fulfilled/missed
5. **Export**: Download Planning History as PDF
6. **Photos**: Attach photos when marking as fulfilled
7. **Ratings**: Rate fulfilled dreams (1-5 stars)
8. **Anniversary**: Show "1 year ago today" for fulfilled dreams

---

## Known Limitations

1. **Auto-Move**: Requires queue worker to be running (`php artisan queue:work`)
2. **Single Comment**: Each action allows one comment only
3. **No Edit**: Cannot edit reschedule request after submission
4. **No Cancellation**: Cannot cancel removal request after submission
5. **Email Dependency**: Status update happens with exact-time email (tightly coupled by design)

---

## Troubleshooting

### Dream Not Moving to Cherished:
- **Check**: Is the queue worker running? (`php artisan queue:work`)
- **Check**: Has the exact-time email been sent?
- **Verify**: destiny_date has passed
- **Check logs**: `storage/logs/laravel.log` for job execution
- **Manual trigger**: Process the queued job manually via Horizon/queue dashboard

### Reschedule Not Working:
- **Check**: Dream status is 'scheduled'
- **Verify**: Date is in the future
- **Check**: Comment field is filled

### Cannot Remove from Bucket List:
- **Check**: Dream status is 'scheduled'
- **Verify**: Partner has confirmed if you requested
- **Check**: You're not confirming your own request

### Email Sent But Status Not Updated:
- **Check logs**: Look for "Failed to update dream status" in logs
- **Verify**: Dream still exists in database
- **Check**: No database constraints blocking the update
- **Note**: Job will retry 3 times by default

---

**Version**: 2.0  
**Last Updated**: October 2025  
**Author**: GitHub Copilot  
**Related Features**: Dreams, Bucket List, Cherished Memories, Planning History, Queue System
