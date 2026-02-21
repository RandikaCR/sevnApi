<?php

namespace App\Http\Controllers;

use App\Mail\testMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class testController extends Controller
{
    public function testMail(){

        $mailData = [
            'email_subject' => 'you have received a contact request from the Funeral Officiants Authority FOA Ltd.',
        ];


        /*return view('Mails.test', ['data' => $mailData]);
        exit();*/

        $send = Mail::to('randika@motordat.com')->send(new testMail($mailData));

        dd($send);
    }
}
