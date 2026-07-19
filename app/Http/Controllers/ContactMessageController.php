<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $inboxMessages = ContactMessage::where('folder', 'inbox')->latest()->paginate(20, ['*'], 'inbox_page');
        $sentMessages = ContactMessage::where('folder', 'sent')->latest()->paginate(20, ['*'], 'sent_page');
        return view('admin.pages.messages', compact('inboxMessages', 'sentMessages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();
        return view('admin.pages.message-show', compact('contactMessage'));
    }

    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();
        return response()->json(['ok' => true]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return back()->with('success', 'Message deleted.');
    }

    public function composeSend(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('ComposeSend called with: ', $request->all());

        $request->validate([
            'to' => 'required|email',
            'cc' => 'nullable',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $mail = \Illuminate\Support\Facades\Mail::to($request->to);

        if ($request->filled('cc')) {
            // cc can be multiple emails separated by comma
            $ccEmails = array_map('trim', explode(',', $request->cc));
            $mail->cc($ccEmails);
        }

        $smtpSuccess = true;
        try {
            $mail->send(new \App\Mail\ComposeMessageMail($request->subject, $request->body));
        } catch (\Throwable $e) {
            $smtpSuccess = false;
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
        }

        ContactMessage::create([
            'name' => 'System',
            'email' => $request->to,
            'subject' => $request->subject,
            'message' => $request->body,
            'folder' => 'sent',
            'read_at' => now(),
        ]);

        if (!$smtpSuccess) {
            return back()->with('error', 'Message saved to Sent tab, but failed to deliver (Check your SMTP/Mail settings).');
        }

        return back()->with('success', 'Email sent successfully.');
    }
}
