<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{

    public function sendVerificationEmail(Request $request)
    {
        // print($request->user()->hasVerifiedEmail());
        // print($request->user());
        $dataUser = [
            "id" => 2,
            "name" => "Noviehp",
            "email" => "noviehp@gmail.com",
            "email_verified_at" => "",
            "created_at" => "2022-08-25T18:08:33.000000Z",
            "updated_at" => "2022-08-25T18:34:49.000000Z"
        ];

        print json_encode($dataUser, true);

        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return ['status' => 'verification-link-sent'];
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return [
            'message' => 'Email has been verified'
        ];
    }
}
