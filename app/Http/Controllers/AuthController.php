<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showSignup()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            // Check if email is verified
            if (!$user->isEmailVerified()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in. Check your inbox for the verification link.',
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            
            // Check if user has an eternal bond, redirect to Shared Canvas
            if ($user->hasEternalBond()) {
                return redirect()->route('shared.canvas');
            }
            
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate verification token and send email
        $token = $user->generateVerificationToken();
        $verificationUrl = route('verify.email', ['token' => $token]);
        
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return redirect()->route('login')->with('success', 'Account created! Please check your email to verify your account before logging in.');
    }

    /**
     * Verify email address
     */
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        if ($user->isEmailVerified()) {
            return redirect()->route('login')->with('info', 'Email already verified. You can log in now.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->isEmailVerified()) {
            return back()->with('info', 'Email is already verified.');
        }

        $token = $user->generateVerificationToken();
        $verificationUrl = route('verify.email', ['token' => $token]);
        
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return back()->with('success', 'Verification email resent! Please check your inbox.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard(Request $request)
    {
        // If user has eternal bond, redirect to Shared Canvas
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasEternalBond()) {
            return redirect()->route('shared.canvas');
        }
        
        return view('dashboard');
    }
}
