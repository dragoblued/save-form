<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;
use App\Models\Feedback;


class FeedbackController extends Controller
{
    public function send(Request $request) {
        $feedback = Feedback::create($request->all());
		Mail::to("qwemee@gmail.com")->send(new FeedbackMail($feedback));
		// Mail::to("ab@acruxcs.com")->send(new FeedbackMail($feedback));
		// Mail::to("info@acruxcs.com")->send(new FeedbackMail($feedback));
    }
}
