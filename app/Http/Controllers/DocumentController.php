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
            $file->storeAs('documents', $filename);
            $validated['file_path'] = 'storage/documents/' . $filename;
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
            $file->storeAs('documents', $filename);
            $validated['file_path'] = 'storage/documents/' . $filename;
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

        $isStorage = str_starts_with($document->file_path, 'storage/');
        $relativePath = $isStorage ? substr($document->file_path, 8) : null;
        
        try {
            if ($isStorage && !\Storage::disk()->exists($relativePath)) {
                abort(404, 'File not found on server');
            }
        } catch (\Exception $e) {
            // Ignore existence check errors if S3 is not configured properly
        } else if (!$isStorage) {
            $path = public_path(ltrim($document->file_path, '/'));
            if (!file_exists($path)) {
                abort(404, 'File not found on server');
            }
        }

        // Get extension to formulate the download filename
        if ($isStorage) {
            $extension = pathinfo($relativePath, PATHINFO_EXTENSION);
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
        }
        
        $filename = \Illuminate\Support\Str::slug($document->title) . '.' . $extension;

        ActivityLog::recordFromRequest(
            'Download',
            'Downloaded document',
            $document->title
        );

        if ($isStorage) {
            try {
                return \Storage::disk()->download($relativePath, $filename);
            } catch (\Exception $e) {
                abort(500, 'Could not download from S3. Please verify your AWS credentials.');
            }
        } else {
            return response()->download($path, $filename);
        }
    }
}
