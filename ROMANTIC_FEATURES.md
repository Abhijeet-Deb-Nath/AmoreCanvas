# AmoreCanvas - Romantic Connection System

## Elegant Terminology

We've created a beautiful, romantic connection system with elegant naming:

### Terms Used:
- **Heart Invitation** - Instead of "match request"
- **Eternal Bond** - Instead of "match" 
- **Love Sanctuary** - Instead of "couple space"

## Features Implemented

### 1. Connection System
- Users can search for and find other users
- Send "Heart Invitations" to potential soulmates
- Accept or decline incoming invitations
- Only ONE eternal bond allowed per user (exclusive relationship)

### 2. Database Structure
**connections table:**
- `sender_id` - User who sent the invitation
- `receiver_id` - User who received the invitation
- `status` - pending/accepted/declined
- `bonded_at` - Timestamp when the eternal bond was formed

### 3. User Flow

#### For Users Without a Bond:
1. Login to dashboard
2. Click "Find Your Soulmate"
3. Search for users by name or email
4. Send Heart Invitations
5. View incoming Heart Invitations on dashboard
6. Accept an invitation to form an Eternal Bond

#### For Users With an Eternal Bond:
1. Dashboard shows their partner
2. "Enter Your Love Sanctuary" button
3. Access to shared couple space (Love Sanctuary)

### 4. Views Created

**Dashboard (`/dashboard`)**
- Shows pending Heart Invitations
- Button to find soulmate (if no bond)
- Shows partner and sanctuary access (if bonded)

**Find Soulmate (`/find-soulmate`)**
- Search functionality
- Grid of available users
- Send invitation buttons

**Love Sanctuary (`/sanctuary`)**
- Beautiful shared space for bonded couples
- Shows both partner names
- Bond date
- Floating hearts animation
- Ready for future features

### 5. Routes Available

```
GET  /dashboard - User's personal space
GET  /find-soulmate - Find and search for users
POST /send-invitation/{user} - Send Heart Invitation
POST /accept-invitation/{connection} - Accept invitation
POST /decline-invitation/{connection} - Decline invitation
GET  /sanctuary - Access Love Sanctuary (requires Eternal Bond)
```

### 6. Business Rules

✅ A user can only have ONE Eternal Bond at a time
✅ Both users must not have existing bonds to form new one
✅ Can't send multiple invitations to same user
✅ Only the receiver can accept/decline invitations
✅ Love Sanctuary is only accessible to bonded couples

## Design Theme

All pages feature:
- Romantic pink/peach/coral gradients
- Floating hearts animations
- Soft, elegant styling
- Georgia serif font
- Smooth transitions and hover effects

## Next Steps

The foundation is ready for you to add more features to the Love Sanctuary:
- Shared photo albums
- Love letters/messages
- Timeline of memories
- Special date reminders
- Shared wishlists
- And more romantic features!

---

**Tagline:** "a living canvas of love"
