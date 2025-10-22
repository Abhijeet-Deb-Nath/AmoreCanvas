<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    

    /**
     * Send a heart invitation by email
     */
    public function sendInvitationByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Check if current user already has an eternal bond
        if ($currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You already have an Eternal Bond.');
        }

        // Find user by email
        $receiver = User::where('email', $request->email)->first();

        if (!$receiver) {
            return redirect()->back()->with('error', 'No user found with that email address.');
        }

        if ($receiver->id === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot send an invitation to yourself.');
        }

        // Check if receiver already has an eternal bond
        if ($receiver->hasEternalBond()) {
            return redirect()->back()->with('error', 'This user already has an Eternal Bond.');
        }

        // Check if invitation already exists
        $existingConnection = Connection::where(function ($query) use ($currentUser, $receiver) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($currentUser, $receiver) {
            $query->where('sender_id', $receiver->id)
                  ->where('receiver_id', $currentUser->id);
        })->first();

        if ($existingConnection) {
            if ($existingConnection->status === 'pending') {
                return redirect()->back()->with('error', 'A pending invitation already exists.');
            } elseif ($existingConnection->status === 'declined') {
                // Allow sending a new invitation after decline
                $existingConnection->delete();
            } else {
                return redirect()->back()->with('error', 'A connection already exists with this user.');
            }
        }

        // Create the heart invitation
        Connection::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Heart Invitation sent successfully!');
    }

    

    /**
     * Accept a heart invitation
     */
    public function acceptInvitation($connectionId)
    {
        $connection = Connection::findOrFail($connectionId);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Verify the current user is the receiver
        if ($connection->receiver_id !== $currentUser->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Check if current user already has an eternal bond
        if ($currentUser->hasEternalBond()) {
            return redirect()->route('dashboard')->with('error', 'You already have an Eternal Bond.');
        }

        // Update connection status
        $connection->update([
            'status' => 'accepted',
            'bonded_at' => now(),
        ]);

        return redirect()->route('shared.canvas')->with('success', 'Eternal Bond formed! Welcome to your Shared Canvas.');
    }

    /**
     * Decline a heart invitation
     */
    public function declineInvitation($connectionId)
    {
        $connection = Connection::findOrFail($connectionId);
        $currentUser = Auth::user();

        // Verify the current user is the receiver
        if ($connection->receiver_id !== $currentUser->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $connection->update(['status' => 'declined']);

        return redirect()->back()->with('success', 'Heart Invitation declined.');
    }

    /**
     * Show the Shared Canvas (shared couple space)
     */
    public function sharedCanvas()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $bond = $currentUser->eternalBond();

        if (!$bond) {
            return redirect()->route('dashboard')->with('error', 'You need an Eternal Bond to access the Shared Canvas.');
        }

        $partner = $currentUser->partner();

        return view('connections.shared-canvas', compact('partner', 'bond'));
    }
}
