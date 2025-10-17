<?php

namespace App\Http\Controllers;

use App\Models\MemoryLane;
use App\Models\MemoryReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MemoryLaneController extends Controller
{
    /**
     * Display a listing of all memories in the shared space
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Only bonded users can access Memory Lane
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access Memory Lane');
        }

        $partner = $currentUser->partner();
        
        // Get filter type
        $filter = $request->get('type', 'all');
        
        // Build query for memories from both users
        $query = MemoryLane::where(function($q) use ($currentUser, $partner) {
            $q->where('user_id', $currentUser->id)
              ->orWhere('user_id', $partner->id);
        });
        
        // Apply filter if needed
        if ($filter !== 'all') {
            $query->where('media_type', $filter);
        }
        
        $memories = $query->orderBy('story_date', 'desc')
            ->with('user', 'reviews.reviewer')
            ->get();

        return view('memory-lane.index', compact('memories', 'partner', 'filter'));
    }

    /**
     * Show the form for creating a new memory
     */
    public function create()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to create memories');
        }

        return view('memory-lane.create');
    }

    /**
     * Store a newly created memory
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to create memories');
        }

        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'story_date' => 'required|date',
            'media_type' => 'required|in:audio,video,text,image',
            'media_file' => 'nullable|file|max:51200', // 50MB max
        ]);

        $mediaPath = null;
        if ($request->hasFile('media_file')) {
            $mediaPath = $request->file('media_file')->store('memories', 'public');
        }

        $memory = MemoryLane::create([
            'user_id' => $currentUser->id,
            'heading' => $request->heading,
            'title' => $request->title,
            'description' => $request->description,
            'story_date' => $request->story_date,
            'media_type' => $request->media_type,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('memory-lane.show', $memory->id)
            ->with('success', 'Memory created successfully! 💕');
    }

    /**
     * Display a specific memory with all its reviews
     */
    public function show($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to view memories');
        }

        $memory = MemoryLane::with('user', 'reviews.reviewer')->findOrFail($id);
        $partner = $currentUser->partner();

        // Ensure this memory belongs to the bonded couple
        if ($memory->user_id !== $currentUser->id && $memory->user_id !== $partner->id) {
            abort(403, 'This memory does not belong to your Eternal Bond');
        }

        return view('memory-lane.show', compact('memory', 'partner'));
    }

    /**
     * Show the form for editing a memory
     */
    public function edit($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $memory = MemoryLane::findOrFail($id);

        // Only the creator can edit the memory
        if ($memory->user_id !== $currentUser->id) {
            abort(403, 'You can only edit your own memories');
        }

        return view('memory-lane.edit', compact('memory'));
    }

    /**
     * Update the specified memory
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $memory = MemoryLane::findOrFail($id);

        // Only the creator can update the memory
        if ($memory->user_id !== $currentUser->id) {
            abort(403, 'You can only edit your own memories');
        }

        $request->validate([
            'heading' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'story_date' => 'required|date',
            'media_type' => 'required|in:audio,video,text,image',
            'media_file' => 'nullable|file|max:51200',
        ]);

        $mediaPath = $memory->media_path;
        if ($request->hasFile('media_file')) {
            // Delete old file if exists
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            $mediaPath = $request->file('media_file')->store('memories', 'public');
        }

        $memory->update([
            'heading' => $request->heading,
            'title' => $request->title,
            'description' => $request->description,
            'story_date' => $request->story_date,
            'media_type' => $request->media_type,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('memory-lane.show', $memory->id)
            ->with('success', 'Memory updated successfully! 💕');
    }

    /**
     * Remove the specified memory
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $memory = MemoryLane::findOrFail($id);

        // Only the creator can delete the memory
        if ($memory->user_id !== $currentUser->id) {
            abort(403, 'You can only delete your own memories');
        }

        // Delete media file if exists
        if ($memory->media_path) {
            Storage::disk('public')->delete($memory->media_path);
        }

        // Delete all review media files
        foreach ($memory->reviews as $review) {
            if ($review->media_path) {
                Storage::disk('public')->delete($review->media_path);
            }
        }

        $memory->delete();

        return redirect()->route('memory-lane.index')
            ->with('success', 'Memory deleted successfully');
    }

    /**
     * Store a review for a memory
     */
    public function storeReview(Request $request, $memoryId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to review memories');
        }

        $memory = MemoryLane::findOrFail($memoryId);
        $partner = $currentUser->partner();

        // Ensure this memory belongs to the bonded couple
        if ($memory->user_id !== $currentUser->id && $memory->user_id !== $partner->id) {
            abort(403, 'You can only review memories from your Eternal Bond');
        }

        $request->validate([
            'review' => 'required|string',
            'media_file' => 'nullable|file|max:51200',
        ]);

        $mediaPath = null;
        if ($request->hasFile('media_file')) {
            Log::info('Review media file received', [
                'original_name' => $request->file('media_file')->getClientOriginalName(),
                'size' => $request->file('media_file')->getSize()
            ]);
            $mediaPath = $request->file('media_file')->store('reviews', 'public');
            Log::info('Review media stored', ['path' => $mediaPath]);
        } else {
            Log::info('No review media file received', [
                'all_files' => array_keys($request->allFiles())
            ]);
        }

        MemoryReview::create([
            'memory_lane_id' => $memoryId,
            'reviewer_id' => $currentUser->id,
            'review' => $request->review,
            'media_path' => $mediaPath,
        ]);

        $successMessage = $mediaPath 
            ? '💕 Review added successfully with media attachment!' 
            : '💕 Review added successfully!';

        return redirect()->route('memory-lane.show', $memoryId)
            ->with('review_success', $successMessage);
    }

    /**
     * Update a review
     */
    public function updateReview(Request $request, $reviewId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $review = MemoryReview::findOrFail($reviewId);

        // Only the reviewer can edit their review
        if ($review->reviewer_id !== $currentUser->id) {
            abort(403, 'You can only edit your own reviews');
        }

        $request->validate([
            'review' => 'required|string',
            'media_file' => 'nullable|file|max:51200',
        ]);

        $mediaPath = $review->media_path;
        if ($request->hasFile('media_file')) {
            // Delete old file if exists
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            $mediaPath = $request->file('media_file')->store('reviews', 'public');
        }

        $review->update([
            'review' => $request->review,
            'media_path' => $mediaPath,
        ]);

        return redirect()->route('memory-lane.show', $review->memory_lane_id)
            ->with('success', 'Review updated successfully! 💕');
    }

    /**
     * Delete a review
     */
    public function destroyReview($reviewId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        $review = MemoryReview::findOrFail($reviewId);

        // Only the reviewer can delete their review
        if ($review->reviewer_id !== $currentUser->id) {
            abort(403, 'You can only delete your own reviews');
        }

        $memoryId = $review->memory_lane_id;

        // Delete media file if exists
        if ($review->media_path) {
            Storage::disk('public')->delete($review->media_path);
        }

        $review->delete();

        return redirect()->route('memory-lane.show', $memoryId)
            ->with('success', 'Review deleted successfully');
    }

    /**
     * Store a new memory created from a fulfilled dream
     */
    public function storeFromDream(Request $request, $dreamId)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Debug logging
        Log::info('storeFromDream called', [
            'dreamId' => $dreamId,
            'user_id' => $currentUser->id,
            'request_data' => $request->all()
        ]);
        
        if (!$currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to create memories');
        }

        // Get the dream
        $dream = \App\Models\Dream::findOrFail($dreamId);
        $bond = $currentUser->eternalBond();

        // Security check
        if (!$bond || $dream->connection_id !== $bond->id) {
            abort(403, 'Unauthorized access to this dream');
        }

        if ($dream->status !== 'fulfilled') {
            return redirect()->back()->with('error', 'Only fulfilled dreams can become Memory Lane entries');
        }

        $request->validate([
            'actual_date' => 'nullable|date',
            'extended_description' => 'nullable|string',
            'special_notes' => 'nullable|string',
            'photos.*' => 'nullable|image|max:51200', // Validate each photo
        ]);

        // Determine the story date (use actual_date if provided, otherwise use destiny_date)
        $storyDate = $request->actual_date ?? $dream->destiny_date->format('Y-m-d');

        // Build description (combine extended_description and special_notes)
        $description = $request->extended_description ?? $dream->description;
        if ($request->special_notes) {
            $description .= "\n\n**Special Notes:**\n" . $request->special_notes;
        }

        // Handle multiple photo uploads (use first photo as main media)
        $mediaPath = null;
        $mediaType = 'text'; // Default to text if no photos
        
        Log::info('Checking for photo uploads', [
            'has_photos' => $request->hasFile('photos'),
            'all_files' => array_keys($request->allFiles())
        ]);
        
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            Log::info('Photos received', ['count' => count($photos)]);
            
            if (count($photos) > 0 && $photos[0]->isValid()) {
                $mediaPath = $photos[0]->store('memories', 'public');
                $mediaType = 'image';
                Log::info('Photo stored', ['path' => $mediaPath]);
            }
        }

        try {
            // Create the memory
            $memory = MemoryLane::create([
                'user_id' => $currentUser->id,
                'heading' => $dream->heading,
                'title' => $dream->place, // Use place as title
                'description' => $description,
                'story_date' => $storyDate,
                'media_type' => $mediaType,
                'media_path' => $mediaPath,
            ]);

            Log::info('Memory created successfully', ['memory_id' => $memory->id]);

            return redirect()->route('memory-lane.show', $memory->id)
                ->with('success', '🎉 Your dream has been transformed into a beautiful memory!');
                
        } catch (\Exception $e) {
            Log::error('Failed to create memory from dream', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to create memory: ' . $e->getMessage())
                ->withInput();
        }
    }
}
