<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $subjectLabels = [
            'project' => 'Project Inquiry',
            'collaboration' => 'Collaboration Opportunity',
            'job' => 'Job Opportunity',
            'consultation' => 'Consultation Request',
            'other' => 'Other',
        ];
        $subjectText = $subjectLabels[$validated['subject']] ?? $validated['subject'];

        ContactMessage::create([
            'name' => trim($validated['firstName'] . ' ' . $validated['lastName']),
            'email' => $validated['email'],
            'subject' => $subjectText,
            'message' => $validated['message'],
        ]);

        ActivityLog::recordFromRequest(
            'View',
            'New contact message',
            $subjectText
        );

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'Thank you for your message! I\'ll get back to you within 24 hours.']);
        }

        return back()->with('success', 'Thank you for your message! I\'ll get back to you within 24 hours.');
        } catch (ValidationException $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['message' => 'Please fix the errors below.', 'errors' => $e->errors()], 422);
            }
            throw $e;
        }
    }
}
