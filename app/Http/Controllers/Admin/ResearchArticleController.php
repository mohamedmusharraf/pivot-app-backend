<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResearchArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Research::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('summary', 'like', "%{$search}%")
                  ->orWhere('fun_facts', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.research-articles.index', compact('articles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fun_facts'  => 'nullable|string',
            'summary'    => 'required|string',
            'video_link' => 'nullable|url|max:255',
            'files'      => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('files')) {
            $validated['files'] = $request->file('files')->store('research_files', 'public');
        }

        Research::create($validated);

        return redirect()->route('admin.research-articles.index')->with('success', 'Research article created successfully.');
    }

    public function update(Request $request, Research $researchArticle)
    {
        $validated = $request->validate([
            'fun_facts'  => 'nullable|string',
            'summary'    => 'required|string',
            'video_link' => 'nullable|url|max:255',
            'files'      => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('files')) {
            if ($researchArticle->files && Storage::disk('public')->exists($researchArticle->files)) {
                Storage::disk('public')->delete($researchArticle->files);
            }
            $validated['files'] = $request->file('files')->store('research_files', 'public');
        }

        $researchArticle->update($validated);

        return redirect()->route('admin.research-articles.index')->with('success', 'Research article updated successfully.');
    }

    public function destroy(Research $researchArticle)
    {
        if ($researchArticle->files && Storage::disk('public')->exists($researchArticle->files)) {
            Storage::disk('public')->delete($researchArticle->files);
        }

        $researchArticle->delete();

        return redirect()->route('admin.research-articles.index')->with('success', 'Research article deleted successfully.');
    }
}