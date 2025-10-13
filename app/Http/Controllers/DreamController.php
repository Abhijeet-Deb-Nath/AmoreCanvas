<?php

namespace App\Http\Controllers;

use App\Models\Dream;
use App\Models\DreamDestinyNegotiation;
use App\Models\DreamNotification;
use App\Jobs\SendDreamReminderEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DreamController extends Controller
{
    /**
     * Show all Shared Dreams page (with 3 tags: user's solo, partner's solo, shared)
     */
    public function index()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access Shared Dreams.');
        }

        $partner = $currentUser->partner();

        // Get dreams categorized
        $myDreams = $currentUser->soloDreams();
        $partnerDreams = $currentUser->partnerSoloDreams();
        $sharedDreams = $currentUser->sharedDreams();

        return view('dreams.shared-dreams', compact('myDreams', 'partnerDreams', 'sharedDreams', 'partner'));
    }

    /**
     * Show create dream form
     */
    public function create()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to create dreams.');
        }

        return view('dreams.create');
    }

    /**
     * Store a new dream
     */
    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'place' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to create dreams.');
        }

        Dream::create([
            'creator_id' => $currentUser->id,
            'connection_id' => $bond->id,
            'heading' => $request->heading,
            'title' => $request->title,
            'description' => $request->description,
            'place' => $request->place,
            'status' => 'solo',
        ]);

        return redirect()->route('dreams.index')->with('success', 'Dream whispered successfully! Share it with your partner.');
    }

    /**
     * Show single dream details with planning section
     */
    public function show($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $partner = $currentUser->partner();
        $negotiations = $dream->negotiations;

        return view('dreams.show', compact('dream', 'partner', 'negotiations'));
    }

    /**
     * Show edit dream form
     */
    public function edit($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Can only edit if not scheduled or fulfilled
        if (in_array($dream->status, ['scheduled', 'fulfilled'])) {
            return redirect()->route('dreams.show', $dream->id)
                ->with('error', 'Cannot edit a scheduled or fulfilled dream.');
        }

        return view('dreams.edit', compact('dream'));
    }

    /**
     * Update dream
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'place' => 'required|string|max:255',
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Can only edit if not scheduled or fulfilled
        if (in_array($dream->status, ['scheduled', 'fulfilled'])) {
            return redirect()->route('dreams.show', $dream->id)
                ->with('error', 'Cannot edit a scheduled or fulfilled dream.');
        }

        $dream->update([
            'heading' => $request->heading,
            'title' => $request->title,
            'description' => $request->description,
            'place' => $request->place,
        ]);

        return redirect()->route('dreams.show', $dream->id)
            ->with('success', 'Dream updated beautifully!');
    }

    /**
     * Delete a dream
     */
    public function destroy($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Cancel all pending notifications
        $dream->notifications()->where('status', 'pending')->delete();

        $dream->delete();

        return redirect()->route('dreams.index')->with('success', 'Dream deleted.');
    }

    /**
     * Validate dream (partner agreement to share the dream)
     */
    public function validate($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Partner cannot be the creator
        if ($dream->creator_id === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot validate your own dream.');
        }

        // Mark as validated
        $dream->markAsValidated();

        return redirect()->route('dreams.show', $dream->id)
            ->with('success', 'Dream validated! You both share this dream now. ✨');
    }

    /**
     * Show destiny date planning page
     */
    public function planDestiny($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Check if dream is shared
        if ($dream->status !== 'shared' && $dream->status !== 'planning') {
            return redirect()->route('dreams.show', $dream->id)
                ->with('error', 'This dream needs to be validated by both partners first.');
        }

        $partner = $currentUser->partner();
        $negotiations = $dream->negotiations;
        $pendingNegotiation = $dream->latestPendingNegotiation();

        return view('dreams.plan-destiny', compact('dream', 'partner', 'negotiations', 'pendingNegotiation'));
    }

    /**
     * Propose a destiny date
     */
    public function proposeDate(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer|min:' . date('Y'),
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
            'message' => 'nullable|string|max:1000',
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Create proposed date
        try {
            $proposedDate = Carbon::create(
                $request->year,
                $request->month,
                $request->day,
                $request->hour,
                $request->minute
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date/time provided.');
        }

        // Check if date is in the future
        if ($proposedDate->isPast()) {
            return redirect()->back()->with('error', 'Destiny date must be in the future.');
        }

        // Update dream status to planning
        if ($dream->status === 'shared') {
            $dream->update(['status' => 'planning']);
        }

        // Create negotiation entry
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => $proposedDate,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('dreams.plan-destiny', $dream->id)
            ->with('success', 'Destiny date proposed! Waiting for your partner\'s response. 💫');
    }

    /**
     * Accept proposed destiny date
     */
    public function acceptDate($dreamId, $negotiationId)
    {
        $dream = Dream::findOrFail($dreamId);
        $negotiation = DreamDestinyNegotiation::findOrFail($negotiationId);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security checks
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($negotiation->proposed_by === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot accept your own proposal.');
        }

        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'This proposal is no longer pending.');
        }

        // Accept the negotiation
        $negotiation->accept($currentUser);

        // Schedule the dream
        $dream->markAsScheduled($negotiation->proposed_date);

        // Schedule notification emails
        $this->scheduleDreamNotifications($dream);

        return redirect()->route('bucket-list.index')
            ->with('success', 'Destiny date sealed! Your dream awaits in the Bucket List. 🌟');
    }

    /**
     * Reject proposed destiny date (with optional counter-proposal)
     */
    public function rejectDate($dreamId, $negotiationId)
    {
        $dream = Dream::findOrFail($dreamId);
        $negotiation = DreamDestinyNegotiation::findOrFail($negotiationId);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security checks
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($negotiation->proposed_by === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot reject your own proposal.');
        }

        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'This proposal is no longer pending.');
        }

        // Reject the negotiation
        $negotiation->reject($currentUser);

        return redirect()->route('dreams.plan-destiny', $dream->id)
            ->with('info', 'Proposal declined. Feel free to suggest a new date!');
    }

    /**
     * Edit/Counter-propose a destiny date
     */
    public function editDate(Request $request, $dreamId, $negotiationId)
    {
        $request->validate([
            'year' => 'required|integer|min:' . date('Y'),
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
            'message' => 'nullable|string|max:1000',
        ]);

        $dream = Dream::findOrFail($dreamId);
        $negotiation = DreamDestinyNegotiation::findOrFail($negotiationId);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security checks
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($negotiation->proposed_by === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot edit your own proposal. Wait for your partner\'s response.');
        }

        if ($negotiation->status !== 'pending') {
            return redirect()->back()->with('error', 'This proposal is no longer pending.');
        }

        // Create new proposed date
        try {
            $proposedDate = Carbon::create(
                $request->year,
                $request->month,
                $request->day,
                $request->hour,
                $request->minute
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid date/time provided.');
        }

        // Check if date is in the future
        if ($proposedDate->isPast()) {
            return redirect()->back()->with('error', 'Destiny date must be in the future.');
        }

        // Mark old negotiation as edited
        $negotiation->markAsEdited();

        // Create new counter-proposal
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => $proposedDate,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('dreams.plan-destiny', $dream->id)
            ->with('success', 'Counter-proposal sent! Let\'s find the perfect moment together. 💝');
    }

    /**
     * Show Bucket List (scheduled dreams)
     */
    public function bucketList()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access the Bucket List.');
        }

        $partner = $currentUser->partner();
        $scheduledDreams = $currentUser->bucketListDreams();

        return view('dreams.bucket-list', compact('scheduledDreams', 'partner'));
    }

    /**
     * Show Cherished Memories (dreams whose date has passed)
     */
    public function cherishedMemories()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access Cherished Memories.');
        }

        $partner = $currentUser->partner();
        $cherishedDreams = $currentUser->cherishedMemories();

        return view('dreams.cherished-memories', compact('cherishedDreams', 'partner'));
    }

    /**
     * Reschedule a cherished dream (move back to shared dreams)
     */
    public function reschedule($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'cherished') {
            return redirect()->back()->with('error', 'Only cherished dreams can be rescheduled.');
        }

        // Cancel all notifications
        $dream->notifications()->delete();

        // Reschedule
        $dream->reschedule();

        return redirect()->route('dreams.index')
            ->with('success', 'Dream moved back to Shared Dreams. Plan a new destiny date!');
    }

    /**
     * Mark dream as fulfilled (move to Lived in the Dream)
     */
    public function markFulfilled(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500', // Mandatory comment
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'cherished') {
            return redirect()->back()->with('error', 'Only cherished dreams can be marked as fulfilled.');
        }

        // Log the fulfillment
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => now(),
            'message' => '✨ Marked as fulfilled: ' . $request->message,
            'status' => 'accepted',
        ]);

        // Mark as fulfilled
        $dream->markAsFulfilled();

        return redirect()->route('dreams.lived')
            ->with('success', 'Dream marked as lived! A beautiful memory created. 💖');
    }

    /**
     * Show Lived in the Dream section (fulfilled dreams)
     */
    public function lived()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access this section.');
        }

        $partner = $currentUser->partner();
        $fulfilledDreams = $currentUser->fulfilledDreams();

        return view('dreams.lived', compact('fulfilledDreams', 'partner'));
    }

    /**
     * Show form to create Memory Lane entry from fulfilled dream
     */
    public function createMemoryFromDream($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'fulfilled') {
            return redirect()->back()->with('error', 'Only fulfilled dreams can become Memory Lane entries.');
        }

        return view('dreams.create-memory', compact('dream'));
    }

    /**
     * Schedule notification emails for a dream
     */
    private function scheduleDreamNotifications(Dream $dream)
    {
        $notifications = [
            ['type' => '24_hours', 'minutes_before' => 24 * 60],
            ['type' => '1_hour', 'minutes_before' => 60],
            ['type' => '10_minutes', 'minutes_before' => 10],
            ['type' => 'exact_time', 'minutes_before' => 0],
        ];

        foreach ($notifications as $notification) {
            $sendAt = $dream->destiny_date->copy()->subMinutes($notification['minutes_before']);
            
            // Only schedule if the notification time is in the future
            if ($sendAt->isFuture()) {
                $dreamNotification = DreamNotification::create([
                    'dream_id' => $dream->id,
                    'notification_type' => $notification['type'],
                    'scheduled_for' => $sendAt,
                    'status' => 'pending',
                ]);

                // Queue the email job with delay
                SendDreamReminderEmail::dispatch($dream, $notification['type'], $dreamNotification->id)
                    ->delay($sendAt);
                
                $dreamNotification->markAsQueued();
            }
        }
    }

    /**
     * Request to reschedule a bucket list item
     */
    public function requestReschedule($id)
    {
        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'scheduled') {
            return redirect()->back()->with('error', 'Only scheduled dreams can be rescheduled.');
        }

        return view('dreams.request-reschedule', compact('dream'));
    }

    /**
     * Submit reschedule proposal
     */
    public function submitReschedule(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer|min:' . date('Y'),
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
            'message' => 'required|string|max:500', // Mandatory comment
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $proposedDate = Carbon::create(
            $request->year,
            $request->month,
            $request->day,
            $request->hour,
            $request->minute
        );

        // Create reschedule negotiation
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => $proposedDate,
            'message' => $request->message,
            'status' => 'rescheduled',
        ]);

        // Change dream back to planning status
        $dream->update(['status' => 'planning']);

        return redirect()->route('dreams.show', $dream->id)
            ->with('success', 'Reschedule request sent! Waiting for partner\'s approval.');
    }

    /**
     * Request to remove from bucket list
     */
    public function requestRemove(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500', // Mandatory comment
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'scheduled') {
            return redirect()->back()->with('error', 'Only scheduled dreams can be removed.');
        }

        // Create removal request
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => now(), // Dummy date, not used for removal
            'message' => $request->message,
            'status' => 'remove_requested',
        ]);

        return redirect()->route('dreams.show', $dream->id)
            ->with('success', 'Removal request sent! Waiting for partner\'s confirmation.');
    }

    /**
     * Confirm removal from bucket list (partner approves)
     */
    public function confirmRemove(Request $request, $dreamId, $negotiationId)
    {
        $request->validate([
            'message' => 'required|string|max:500', // Partner's comment
        ]);

        $dream = Dream::findOrFail($dreamId);
        $negotiation = DreamDestinyNegotiation::findOrFail($negotiationId);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if ($negotiation->proposed_by === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot confirm your own removal request.');
        }

        // Update negotiation status
        $negotiation->update([
            'status' => 'remove_confirmed',
            'responded_by' => $currentUser->id,
            'responded_at' => now(),
        ]);

        // Add partner's comment as new negotiation entry
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => now(),
            'message' => 'Agreed to remove: ' . $request->message,
            'status' => 'remove_confirmed',
        ]);

        // Move back to shared dreams
        $dream->update([
            'status' => 'shared',
            'destiny_date' => null,
            'scheduled_at' => null,
        ]);

        // Cancel pending notifications
        DreamNotification::where('dream_id', $dream->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return redirect()->route('dreams.index')
            ->with('success', 'Dream removed from Bucket List and moved back to Shared Dreams.');
    }

    /**
     * Mark dream as missed (from cherished memories)
     */
    public function markMissed(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:500', // Mandatory comment
        ]);

        $dream = Dream::findOrFail($id);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond || $dream->connection_id !== $bond->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        if ($dream->status !== 'cherished') {
            return redirect()->back()->with('error', 'Only cherished dreams can be marked as missed.');
        }

        // Log the missed schedule
        DreamDestinyNegotiation::create([
            'dream_id' => $dream->id,
            'proposed_by' => $currentUser->id,
            'proposed_date' => $dream->destiny_date, // Keep the missed date
            'message' => '📅 Missed Schedule: ' . $request->message,
            'status' => 'missed',
        ]);

        // Move back to shared dreams, remove destiny date
        $dream->update([
            'status' => 'shared',
            'destiny_date' => null,
            'scheduled_at' => null,
            'cherished_at' => null,
        ]);

        return redirect()->route('dreams.index')
            ->with('success', 'Dream moved back to Shared Dreams. You can reschedule it anytime.');
    }
}

