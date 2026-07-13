<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeSubscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(10);
        return view('admin.pages.subscribers', compact('subscribers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        Subscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        Mail::to($request->email)->send(new WelcomeSubscriber($request->email));

        return response()->json(['success' => true, 'message' => 'Successfully subscribed!']);
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted successfully.');
    }

    public function unsubscribe($email)
    {
        $subscriber = Subscriber::where('email', $email)->first();
        if ($subscriber) {
            $subscriber->update(['is_active' => false]);
            // Optional: delete them entirely with $subscriber->delete();
        }
        
        return view('unsubscribe');
    }
}
