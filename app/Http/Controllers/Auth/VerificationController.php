<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/auth');
    }

    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response()->json(['msg' => 'Email already verified.'], 400);
        }

        Auth::user()->sendEmailVerificationNotification();

        return response()->json(['msg' => 'Email verification link sent on your email id']);
    }
}
