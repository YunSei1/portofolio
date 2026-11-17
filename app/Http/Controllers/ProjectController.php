<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'technology', 'role', 'status', 'sort']);

        $projects = Project::query()
            ->filter($filters)
            ->sorted($filters['sort'] ?? null)
            ->get();

        $technologies = Project::technologyOptions();
        $roles = Project::roleOptions();
        $blogs = Blog::latest()->get();

        return view('projects.index', compact('projects', 'technologies', 'roles', 'filters', 'blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validatedProject($request);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan!');
    }

    /**
     * Display the specified resource (admin).
     */
    public function show(Project $project)
    {
        return redirect()->route('admin.projects.index');
    }

    /**
     * Display the project detail page for visitors.
     */
    public function showPublic(Project $project)
    {
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        abort_if(! $project->is_published && ! $isAdmin, 404);

        $profile = Profile::first();

        return view('projects.show', compact('project', 'profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $this->validatedProject($request);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }

            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui!');
    }

    /**
     * Ubah status publikasi project dengan cepat dari halaman Kelola Proyek.
     */
    public function toggleVisibility(Project $project)
    {
        $project->update(['is_published' => ! $project->is_published]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Status project "' . $project->title . '" diubah menjadi ' . $project->visibilityLabel() . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus!');
    }

    /**
     * Validasi seluruh detail project.
     */
    private function validatedProject(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'required|string',
            'problem' => 'nullable|string',
            'goal' => 'nullable|string',
            'technologies' => 'nullable|string|max:1000',
            'features' => 'nullable|string',
            'documentation' => 'nullable|string',
            'detail' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'source_url' => 'nullable|url|max:255',
            'feedback' => 'nullable|string',
            'feedback_author' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'title.required' => 'Nama project wajib diisi.',
            'description.required' => 'Deskripsi project wajib diisi.',
            'demo_url.url' => 'Link demo harus berupa URL yang valid.',
            'source_url.url' => 'Link source code harus berupa URL yang valid.',
            'image.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        unset($data['image']);

        foreach ($data as $key => $value) {
            if (is_string($value) && trim($value) === '') {
                $data[$key] = null;
            }
        }

        return $data;
    }
}
