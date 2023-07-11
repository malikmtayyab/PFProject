<?php

namespace App\Http\Controllers;

use App\Models\UnverifiedUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\User;


class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $userId = $request->route('id');
        $user = User::find($userId);
    
        if (!$user || !URL::hasValidSignature($request)) {
            return response()->json(['error' => 'Invalid verification link'], 400);
        }
    
        $user->markEmailAsVerified();
        event(new Verified($user));
    
        return response()->json(['message' => 'Email verified successfully']);
    }
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent']);
    }
}
