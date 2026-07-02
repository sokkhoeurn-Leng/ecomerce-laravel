<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google authentication
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = User::where('social_id', $socialUser->getId())
                ->where('social_type', 'google')
                ->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Update existing user with social login info
                    $user->update([
                        'social_id' => $socialUser->getId(),
                        'social_type' => 'google',
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(16)), // Random password for social users
                        'social_id' => $socialUser->getId(),
                        'social_type' => 'google',
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            // Create API token
            $token = $user->createToken('API Token')->plainTextToken;

            // Redirect to frontend with token
            return redirect()->away(config('app.url') . '/auth/callback?token=' . $token . '&user=' . base64_encode(json_encode($user)));

        } catch (\Exception $e) {
            return redirect()->away(config('app.url') . '/login?error=google_auth_failed');
        }
    }

    /**
     * Redirect to Facebook authentication
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            
            // Find or create user
            $user = User::where('social_id', $socialUser->getId())
                ->where('social_type', 'facebook')
                ->first();

            if (!$user) {
                // Check if user exists with same email
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Update existing user with social login info
                    $user->update([
                        'social_id' => $socialUser->getId(),
                        'social_type' => 'facebook',
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(16)), // Random password for social users
                        'social_id' => $socialUser->getId(),
                        'social_type' => 'facebook',
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            }

            // Create API token
            $token = $user->createToken('API Token')->plainTextToken;

            // Redirect to frontend with token
            return redirect()->away(config('app.url') . '/auth/callback?token=' . $token . '&user=' . base64_encode(json_encode($user)));

        } catch (\Exception $e) {
            return redirect()->away(config('app.url') . '/login?error=facebook_auth_failed');
        }
    }
}