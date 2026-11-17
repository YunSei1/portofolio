<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Halaman blog publik.
     */
    public function publicIndex(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $blogs = Blog::query()
            ->published()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', '%' . $q . '%')
                        ->orWhere('excerpt', 'like', '%' . $q . '%')
                        ->orWhere('content', 'like', '%' . $q . '%');
                });
            })
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->paginate(9)
            ->withQueryString();

        $profile = Profile::first();

        return view('blog.index', compact('blogs', 'q', 'profile'));
    }

    /**
     * Detail artikel blog publik.
     */
    public function publicShow(Blog $blog)
    {
        abort_unless($blog->is_published, 404);
        abort_if($blog->published_at && $blog->published_at->isFuture(), 404);

        $related = Blog::query()
            ->published()
            ->where('id', '!=', $blog->id)
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->take(3)
            ->get();

        $profile = Profile::first();

        return view('blog.show', compact('blog', 'related', 'profile'));
    }

    /**
     * Daftar artikel di dashboard admin.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $sort = (string) $request->query('sort', 'latest');

        if (! in_array($status, ['published', 'draft', 'scheduled'], true)) {
            $status = '';
        }

        if (! in_array($sort, ['latest', 'oldest', 'title_asc', 'title_desc'], true)) {
            $sort = 'latest';
        }

        $blogs = Blog::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', '%' . $q . '%')
                        ->orWhere('excerpt', 'like', '%' . $q . '%')
                        ->orWhere('content', 'like', '%' . $q . '%');
                });
            })
            ->when($status === 'published', function ($query) {
                $query->where('is_published', true)
                    ->where(function ($query) {
                        $query->whereNull('published_at')
                            ->orWhere('published_at', '<=', now());
                    });
            })
            ->when($status === 'scheduled', function ($query) {
                $query->where('is_published', true)
                    ->where('published_at', '>', now());
            })
            ->when($status === 'draft', function ($query) {
                $query->where('is_published', false);
            });

        match ($sort) {
            'oldest' => $blogs->oldest(),
            'title_asc' => $blogs->orderBy('title'),
            'title_desc' => $blogs->orderByDesc('title'),
            default => $blogs->latest(),
        };

        $blogs = $blogs->paginate(10)->withQueryString();

        return view('blogs.index', compact('blogs', 'q', 'status', 'sort'));
    }

    /**
     * Form tambah artikel.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Simpan artikel baru.
     */
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title']);
        $data['tags'] = $this->parseTags($data['tags'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $this->resolvePublishedAt($request, $data['is_published']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil ditambahkan!');
    }

    /**
     * Form edit artikel.
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Perbarui artikel.
     */
    public function update(Request $request, Blog $blog)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title'], $blog->id);
        $data['tags'] = $this->parseTags($data['tags'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $this->resolvePublishedAt($request, $data['is_published']);

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('blogs', 'public');
        } else {
            unset($data['cover_image']);
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil diperbarui!');
    }

    /**
     * Ubah status publikasi artikel dengan cepat.
     */
    public function toggleVisibility(Blog $blog)
    {
        $willPublish = ! $blog->is_published;

        $blog->update([
            'is_published' => $willPublish,
            'published_at' => $willPublish
                ? (($blog->published_at && $blog->published_at->isPast()) ? $blog->published_at : now())
                : $blog->published_at,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Artikel "' . $blog->title . '" diubah menjadi ' . ($willPublish ? 'Publikasi' : 'Private') . '.');
    }

    /**
     * Ubah status publikasi artikel yang dipilih dari halaman Kelola Proyek.
     */
    public function updateVisibility(Request $request)
    {
        $data = $request->validate([
            'blog_id' => ['required', 'exists:blogs,id'],
            'is_published' => ['required', 'boolean'],
        ], [
            'blog_id.required' => 'Pilih blog yang ingin diatur.',
            'blog_id.exists' => 'Blog yang dipilih tidak ditemukan.',
            'is_published.required' => 'Pilih status publikasi blog.',
        ]);

        $blog = Blog::findOrFail($data['blog_id']);
        $willPublish = (bool) $data['is_published'];

        $blog->update([
            'is_published' => $willPublish,
            'published_at' => $willPublish
                ? (($blog->published_at && $blog->published_at->isPast()) ? $blog->published_at : now())
                : $blog->published_at,
        ]);

        $back = $request->input('redirect_to') === 'projects'
            ? route('admin.projects.index')
            : route('admin.blogs.index');

        return redirect()
            ->to($back)
            ->with('success', 'Status blog "' . $blog->title . '" diubah menjadi ' . ($willPublish ? 'Publikasi' : 'Private') . '.');
    }

    /**
     * Hapus artikel.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->cover_image) {
            Storage::disk('public')->delete($blog->cover_image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Artikel blog berhasil dihapus!');
    }

    /**
     * Validasi input artikel.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'tags' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    /**
     * Ubah input tag yang dipisahkan koma menjadi array unik.
     *
     * @return array<int, string>|null
     */
    private function parseTags(?string $tags): ?array
    {
        $parsed = collect(explode(',', (string) $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->map(fn ($tag) => Str::limit($tag, 30, ''))
            ->unique(fn ($tag) => Str::lower($tag))
            ->take(8)
            ->values()
            ->all();

        return $parsed === [] ? null : $parsed;
    }

    /**
     * Buat slug unik dari judul atau slug yang diisi admin.
     */
    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'artikel';
        $slug = $base;
        $counter = 2;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Tentukan waktu publikasi: jadwal dari admin atau sekarang saat langsung dipublikasikan.
     */
    private function resolvePublishedAt(Request $request, bool $isPublished): ?Carbon
    {
        if ($request->filled('published_at')) {
            return Carbon::parse($request->input('published_at'));
        }

        return $isPublished ? now() : null;
    }
}
