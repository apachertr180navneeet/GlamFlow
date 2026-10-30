<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('web.home.index');
    }

    public function about()
    {
        return view('web.about.index');
    }

    public function services()
    {
        return view('web.services.index');
    }

    public function gallery()
    {
        return view('web.gallery.index');
    }

    public function contact()
    {
        return view('web.contact.index');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // You can send email or store contact form database here if desired.
        // For now, redirect back with success message.
        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
