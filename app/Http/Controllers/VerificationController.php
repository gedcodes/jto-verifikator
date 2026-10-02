<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (
            !hash_equals((string) $request->route('id'), (string) $user->getKey())
            || !hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))
        ) {

            return response()->json(['message' => __('message.VER_EMAIL_GAGAL')], 500);
        }


        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => __('message.VER_EMAIL_READY')], 200);
        }

        if ($user->markEmailAsVerified()) {
            return response()->json(['message' => __('message.VER_EMAIL_BERHASIL')], 200);
        }

        return response()->json(['verified' => true]);
    }


    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function resend(Request $request)
    {

        $this->validate($request, ['id' => 'required']);

        $user = User::find($request->id);

        if (!$user)  return response()->json(['message' => __('message.VER_EMAIL_GAGAL')], 500);


        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => __('message.VER_EMAIL_READY')], 200);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['message' => __('message.VER_EMAIL_BERHASIL'), 200]);
    }
}
