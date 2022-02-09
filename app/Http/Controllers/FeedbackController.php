<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;


class FeedbackController extends Controller
{
    public function send(Request $request) {
        $validator =  Validator::make($request->all(), [
            'email' => 'required',
            'phone' => 'required',
            'name' => 'required',
        ]);
        if(!$validator->fails()) {
            $feedback = Feedback::create($request->all());
            Mail::to("qwemee@gmail.com")->send(new FeedbackMail($feedback));
            // Mail::to("ab@acruxcs.com")->send(new FeedbackMail($feedback));
            // Mail::to("info@acruxcs.com")->send(new FeedbackMail($feedback));
            return response()->json('success');
        } else {
            return response()->json('There are empty fields: email, phone or name');
        }
    }
}
