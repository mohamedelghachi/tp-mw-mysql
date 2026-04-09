<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function showContactForm()
    {
        return view('contact');
    }

    public function     storeContactForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'message' => 'required|min:10',
        ]);
        // For example, you can save it to the database or send an email
        return redirect()->route('contact')->with('success', 'Your message has been sent!');
    }
}
