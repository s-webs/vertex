<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Document;
use App\Support\StoresUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('admin.documents.index', [
            'documents' => Document::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.documents.form');
    }

    public function store(StoreDocumentRequest $request, StoresUploads $uploads): RedirectResponse
    {
        $document = $this->persist(new Document, $request, $uploads);

        return redirect()
            ->route('admin.documents.edit', $document)
            ->with('status', 'Документ создан.');
    }

    public function edit(Document $document): View
    {
        return view('admin.documents.form', [
            'document' => $document,
        ]);
    }

    public function update(UpdateDocumentRequest $request, Document $document, StoresUploads $uploads): RedirectResponse
    {
        $this->persist($document, $request, $uploads);

        return back()->with('status', 'Документ обновлён.');
    }

    public function destroy(Document $document, StoresUploads $uploads): RedirectResponse
    {
        $uploads->delete($document->file_path);
        $uploads->delete($document->preview_path);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('status', 'Документ удалён.');
    }

    private function persist(Document $document, StoreDocumentRequest|UpdateDocumentRequest $request, StoresUploads $uploads): Document
    {
        $document->fill([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'sort_order' => (int) ($request->validated()['sort_order'] ?? 0),
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('file')) {
            $document->file_path = $uploads->replace($document->file_path, $request->file('file'), 'documents');
        }

        if ($request->hasFile('preview')) {
            $document->preview_path = $uploads->replace($document->preview_path, $request->file('preview'), 'documents');
        }

        $document->save();

        return $document;
    }
}
