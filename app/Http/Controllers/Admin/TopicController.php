<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * All topics list
     */
    public function index()
    {
        $topics = Topic::latest()->paginate(20)->withQueryString();

        return view('admin.topic.index', compact('topics'));
    }

    /**
     * Quick store via AJAX
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $baseSlug = $request->filled('slug') 
            ? $this->makeSlug($request->slug) 
            : $this->makeSlug($request->name);

        $slug = $baseSlug;
        $originalSlug = $slug;
        $i = 1;
        while (Topic::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $topic = Topic::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return response()->json([
            'success' => true,
            'topic' => $topic
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:topics,slug',
        ]);

        $baseSlug = $request->filled('slug')
            ? $this->makeSlug($request->slug)
            : $this->makeSlug($request->name);

        $slug = $baseSlug;
        $originalSlug = $slug;
        $i = 1;
        while (Topic::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        Topic::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('admin.topics.index')->with('success', 'Topic added successfully!');
    }

    /**
     * Update an existing topic
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:topics,slug,' . $id,
        ]);

        if ($request->filled('slug')) {
            $baseSlug = $this->makeSlug($request->slug);
        } elseif ($topic->name !== $request->name) {
            $baseSlug = $this->makeSlug($request->name);
        } else {
            $baseSlug = null;
        }

        if ($baseSlug !== null) {
            $slug = $baseSlug;
            $originalSlug = $slug;
            $i = 1;
            while (Topic::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }
            $topic->slug = $slug;
        }

        $topic->name = $request->name;
        $topic->save();

        return redirect()->route('admin.topics.index')->with('success', 'Topic updated successfully!');
    }

    /**
     * Delete a topic
     */
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);

        if (!$topic->can_delete) {
            return redirect()->route('admin.topics.index')->with('error', 'This topic (Division) is permanent and cannot be deleted.');
        }

        $topic->delete();
        return redirect()->route('admin.topics.index')->with('success', 'Topic deleted successfully!');
    }

    /**
     * Build slug with Unicode support (e.g. Bangla)
     */
    private function makeSlug(string $value): string
    {
        $slug = trim($value);
        $slug = preg_replace('/\s+/u', '-', $slug);
        $slug = preg_replace('/-+/u', '-', $slug);
        $slug = trim($slug, '-');
        return mb_strtolower($slug, 'UTF-8');
    }
}
