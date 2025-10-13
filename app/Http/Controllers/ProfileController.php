<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show edit profile page
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update name
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Name updated successfully!');
    }

    /**
     * Update email (requires verification)
     */
    public function updateEmail(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // Check if email is actually changing
        if ($user->email === $request->email) {
            return back()->with('info', 'This is already your current email address.');
        }

        // Generate verification token for new email
        $token = bin2hex(random_bytes(32));
        $verificationUrl = route('verify.new-email', ['token' => $token, 'userId' => $user->id]);

        // Store the pending email and token temporarily (we'll use verification_token column)
        $user->verification_token = $token . '|' . $request->email; // Store token|email
        $user->save();

        // Send verification email to NEW email address
        $tempUser = new User();
        $tempUser->name = $user->name;
        $tempUser->email = $request->email;
        
        Mail::to($request->email)->send(new VerifyEmail($tempUser, $verificationUrl));

        return back()->with('success', 'Verification email sent to ' . $request->email . '. Please check your inbox to confirm the change.');
    }

    /**
     * Verify new email
     */
    public function verifyNewEmail($token, $userId)
    {
        $user = User::find($userId);

        if (!$user || !$user->verification_token) {
            return redirect()->route('dashboard')->with('error', 'Invalid verification link.');
        }

        // Parse stored token|email
        $parts = explode('|', $user->verification_token);
        if (count($parts) !== 2 || $parts[0] !== $token) {
            return redirect()->route('dashboard')->with('error', 'Invalid or expired verification link.');
        }

        $newEmail = $parts[1];

        // Check if new email is already taken by another user
        $existingUser = User::where('email', $newEmail)->where('id', '!=', $user->id)->first();
        if ($existingUser) {
            return redirect()->route('dashboard')->with('error', 'This email is already in use by another account.');
        }

        // Update email
        $user->email = $newEmail;
        $user->verification_token = null;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Email updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}
