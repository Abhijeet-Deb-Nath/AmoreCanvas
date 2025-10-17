<?php

namespace App\Http\Controllers;

use App\Models\LoveLetter;
use App\Models\Connection;
use App\Models\MemoryLane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoveLetterController extends Controller
{
    /**
     * Display the letter box (list of received letters)
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Only bonded users can access Letter Box
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access the Letter Box');
        }

        $partner = $currentUser->partner();
        
        // Get filter option (unread or all)
        $filter = $request->get('filter', 'unread');
        
        // Get letters sent to current user
        $lettersQuery = LoveLetter::where('receiver_id', $currentUser->id)
            ->delivered()
            ->orderBy('delivered_at', 'desc');
        
        if ($filter === 'unread') {
            $lettersQuery->unread();
        }
        
        $letters = $lettersQuery->with('sender')->get();
        
        return view('love-letters.index', compact('letters', 'partner', 'filter'));
    }

    /**
     * Show the form for creating a new love letter
     */
    public function create()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to send love letters');
        }

        $partner = $currentUser->partner();
        
        // Get minimum delivery days from config
        $minDeliveryDays = config('app.love_letter_min_delivery_days', 3);
        $minDeliveryDateTime = now()->addDays($minDeliveryDays);
        
        // Format for datetime-local input (Y-m-d\TH:i)
        $minDeliveryDateTimeString = $minDeliveryDateTime->format('Y-m-d\TH:i');
        
        // Format the minimum delivery time for display
        if ($minDeliveryDays < 1) {
            // Less than a day - convert to minutes or hours
            $minutes = round($minDeliveryDays * 1440); // 1440 minutes in a day
            if ($minutes < 60) {
                $minDeliveryText = $minutes . ' minute' . ($minutes != 1 ? 's' : '');
            } else {
                $hours = round($minutes / 60, 1);
                $minDeliveryText = $hours . ' hour' . ($hours != 1 ? 's' : '');
            }
        } else {
            $minDeliveryText = round($minDeliveryDays, 1) . ' day' . ($minDeliveryDays != 1 ? 's' : '');
        }
        
        return view('love-letters.create', compact('partner', 'minDeliveryDateTimeString', 'minDeliveryDays', 'minDeliveryText'));
    }

    /**
     * Store a newly created love letter
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to send love letters');
        }

        $minDeliveryDays = config('app.love_letter_min_delivery_days', 3);
        $minDeliveryDateTime = now()->addDays($minDeliveryDays);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scheduled_delivery_at' => 'required|date|after_or_equal:' . $minDeliveryDateTime->toDateTimeString(),
        ]);

        $partner = $currentUser->partner();
        $connection = Connection::where(function ($query) use ($currentUser, $partner) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $partner->id);
        })->orWhere(function ($query) use ($currentUser, $partner) {
            $query->where('sender_id', $partner->id)
                  ->where('receiver_id', $currentUser->id);
        })->where('status', 'accepted')->first();

        if (!$connection) {
            return redirect()->route('dashboard')->with('error', 'Connection not found');
        }

        $letter = LoveLetter::create([
            'connection_id' => $connection->id,
            'sender_id' => $currentUser->id,
            'receiver_id' => $partner->id,
            'title' => $request->title,
            'content' => $request->content,
            'scheduled_delivery_at' => $request->scheduled_delivery_at,
        ]);

        // Dispatch delayed job to deliver the letter at scheduled time
        $deliveryTime = Carbon::parse($request->scheduled_delivery_at);
        $delay = $deliveryTime->diffInSeconds(now());
        
        // Dispatch the job with delay
        \App\Jobs\SendLoveLetterDeliveryEmail::dispatch($letter)->delay($delay);

        return redirect()->route('love-letters.index')
            ->with('success', 'Your love letter has been sent! It will be delivered on ' . Carbon::parse($request->scheduled_delivery_at)->format('F j, Y \a\t g:i A') . ' 💌');
    }

    /**
     * Display a specific love letter
     */
    public function show($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        // Only the receiver can view the letter
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403, 'You are not authorized to view this letter');
        }
        
        // Letter must be delivered
        if (!$letter->isDelivered()) {
            abort(403, 'This letter has not been delivered yet');
        }
        
        // Mark as read if not already read
        $letter->markAsRead();
        
        return view('love-letters.show', compact('letter'));
    }

    /**
     * Mark a letter as read
     */
    public function markAsRead($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403);
        }
        
        $letter->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Download a letter as HTML file
     */
    public function download($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        // Only the receiver can download the letter
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403);
        }
        
        if (!$letter->isDelivered()) {
            abort(403, 'This letter has not been delivered yet');
        }
        
        // Create HTML content with styling
        $html = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>{$letter->title}</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
        }
        .letter-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 20px;
        }
        .letter-title {
            font-size: 28px;
            color: #e91e63;
            margin-bottom: 10px;
        }
        .letter-meta {
            color: #666;
            font-size: 14px;
        }
        .letter-content {
            line-height: 1.8;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class='letter-header'>
        <h1 class='letter-title'>{$letter->title}</h1>
        <div class='letter-meta'>
            From: {$letter->sender->name}<br>
            Delivered: {$letter->delivered_at->format('F j, Y')}
        </div>
    </div>
    <div class='letter-content'>
        {$letter->content}
    </div>
</body>
</html>";
        
        $filename = 'love-letter-' . $letter->id . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Add letter to Memory Lane
     */
    public function addToMemoryLane(Request $request, $id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        // Only the receiver can add to Memory Lane
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403);
        }
        
        if ($letter->is_in_memory_lane) {
            return redirect()->back()->with('error', 'This letter is already in Memory Lane');
        }
        
        $request->validate([
            'memory_note' => 'required|string|max:500',
        ]);
        
        // Create Memory Lane entry with full letter content
        MemoryLane::create([
            'user_id' => $currentUser->id,
            'love_letter_id' => $letter->id,
            'letter_content' => $letter->content, // Store the full HTML content
            'heading' => $letter->title,
            'title' => 'From ' . $letter->sender->name,
            'description' => $request->memory_note,
            'story_date' => $letter->delivered_at,
            'media_type' => 'text',
            'media_path' => null, // No separate file needed, content is in DB
        ]);
        
        // Mark letter as added to Memory Lane
        $letter->update(['is_in_memory_lane' => true]);
        
        return redirect()->route('memory-lane.index')
            ->with('success', 'Love letter added to Memory Lane! 💕');
    }

    /**
     * Permanently delete a letter (without adding to Memory Lane)
     */
    public function permanentDelete($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        // Only the receiver can delete the letter
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403);
        }
        
        // Cannot delete if already in Memory Lane
        if ($letter->is_in_memory_lane) {
            return redirect()->back()->with('error', 'This letter is in Memory Lane. Use the Memory Lane delete option instead.');
        }
        
        $letter->delete();
        
        return redirect()->route('love-letters.index')
            ->with('success', 'Love letter deleted permanently');
    }

    /**
     * Delete a letter that's in Memory Lane (also removes from Memory Lane)
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $letter = LoveLetter::findOrFail($id);
        
        // Only the receiver can delete the letter
        if ($letter->receiver_id !== $currentUser->id) {
            abort(403);
        }
        
        // Must be in Memory Lane to use this method
        if (!$letter->is_in_memory_lane) {
            return redirect()->back()->with('error', 'Use permanent delete for letters not in Memory Lane');
        }
        
        // Delete the Memory Lane entry
        MemoryLane::where('love_letter_id', $letter->id)->delete();
        
        // Delete the letter
        $letter->delete();
        
        return redirect()->route('love-letters.index')
            ->with('success', 'Love letter and Memory Lane entry deleted successfully');
    }
}
