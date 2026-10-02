<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendDemoMail;
use Mail;

class ContactController extends Controller
{
    public function sendDemoMail()
    {
        $email = 'noviehp@gmail.com';

        $maildata = [
            'title' => 'Laravel Mail Sending Example with Markdown',
            'url' => 'https://www.positronx.io'
        ];

        Mail::to($email)->send(new SendDemoMail($maildata));

        dd("Mail has been sent successfully");
    }
}
