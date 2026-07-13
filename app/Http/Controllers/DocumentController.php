<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::ordered()->paginate(10);
        return view('admin.pages.my-files', compact('documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'file_path' => 'nullable|file|max:20480',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('documents'), $filename);
            $validated['file_path'] = 'documents/' . $filename;
        }

        $document = Document::create($validated);

        ActivityLog::recordFromRequest(
            'Admin',
            'Added document',
            $document->title
        );

        return back()->with('success', 'Document added.');
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'file_path' => 'nullable|file|max:20480',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
            $file->move(public_path('documents'), $filename);
            $validated['file_path'] = 'documents/' . $filename;
        } else {
            unset($validated['file_path']);
        }

        $document->update($validated);

        ActivityLog::recordFromRequest(
            'Admin',
            'Updated document',
            $document->title
        );

        return back()->with('success', 'Document updated.');
    }

    public function destroy(Document $document)
    {
        $title = $document->title;
        $document->delete();
        ActivityLog::recordFromRequest(
            'Admin',
            'Deleted document',
            $title
        );
        return back()->with('success', 'Document deleted.');
    }

    public function download(Document $document)
    {
        if (!$document->file_path) {
            abort(404, 'File not found');
        }

        $path = public_path(ltrim($document->file_path, '/'));
        
        if (!file_exists($path)) {
            abort(404, 'File not found on server');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = \Illuminate\Support\Str::slug($document->title) . '.' . $extension;

        ActivityLog::recordFromRequest(
            'Download',
            'Downloaded document',
            $document->title
        );

        return response()->download($path, $filename);
    }
}
