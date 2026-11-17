<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;


use App\Models\Project;
use App\Models\Certificate;
use App\Models\Profile;
use App\Models\Blog;

Route::get('/', function (Request $request) {
    $filters = $request->only(['q', 'technology', 'role', 'sort']);

    $projects = Project::query()
        ->published()
        ->filter($filters)
        ->sorted($filters['sort'] ?? null)
        ->get();

    $blogFilters = [
        'q' => trim((string) $request->query('blog_q', '')),
        'tag' => trim((string) $request->query('blog_tag', '')),
        'sort' => (string) $request->query('blog_sort', 'latest'),
    ];

    if (! in_array($blogFilters['sort'], ['latest', 'oldest'], true)) {
        $blogFilters['sort'] = 'latest';
    }

    $blogTags = Blog::query()
        ->published()
        ->get(['tags'])
        ->flatMap(fn (Blog $blog) => $blog->tagList())
        ->unique()
        ->sort()
        ->values();

    $latestBlogs = Blog::query()
        ->published()
        ->when($blogFilters['q'] !== '', function ($query) use ($blogFilters) {
            $query->where(function ($query) use ($blogFilters) {
                $query->where('title', 'like', '%' . $blogFilters['q'] . '%')
                    ->orWhere('excerpt', 'like', '%' . $blogFilters['q'] . '%')
                    ->orWhere('content', 'like', '%' . $blogFilters['q'] . '%');
            });
        })
        ->when($blogFilters['tag'] !== '', function ($query) use ($blogFilters) {
            $query->whereJsonContains('tags', $blogFilters['tag']);
        })
        ->orderByRaw('COALESCE(published_at, created_at) ' . ($blogFilters['sort'] === 'oldest' ? 'ASC' : 'DESC'))
        ->take(3)
        ->get();

    $certificates = Certificate::latest()->get();
    $profile = Profile::first();
    $technologies = Project::technologyOptions(true);
    $roles = Project::roleOptions(true);

    return view('welcome', compact('projects', 'certificates', 'profile', 'technologies', 'roles', 'filters', 'latestBlogs', 'blogFilters', 'blogTags'));
});

Route::get('/about', function () {
    $profile = Profile::first();

    return view('about', compact('profile'));
})->name('about');

use App\Http\Controllers\AuthController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Contact
use App\Http\Controllers\ContactController;

Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Kesan & Pesan (buku tamu publik)
use App\Http\Controllers\GuestMessageController;

Route::get('/kesan-pesan', [GuestMessageController::class, 'index'])->name('guestbook.index');
Route::post('/kesan-pesan', [GuestMessageController::class, 'store'])->name('guestbook.store');

// Download & preview CV
Route::get('/download-cv', [ProfileController::class, 'downloadCv'])->name('cv.download');
Route::get('/cv/preview', [ProfileController::class, 'previewCv'])->name('cv.preview');

// Gambar sertifikat hasil upload admin, disajikan langsung dari storage
// supaya tidak bergantung pada symlink public/storage.
Route::get('/certificates/{certificate}/image', [CertificateController::class, 'image'])->name('certificates.image');

// Gambar project hasil upload admin, disajikan langsung dari storage
Route::get('/projects/{project}/image', [ProjectController::class, 'image'])->name('projects.image');

// Detail project publik
Route::get('/projects/{project}', [ProjectController::class, 'showPublic'])->name('projects.show');

// Blog publik
Route::get('/blog', [BlogController::class, 'publicIndex'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'publicShow'])->name('blog.show');

// Admin only
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Projects
    Route::patch('projects/{project}/toggle', [ProjectController::class, 'toggleVisibility'])->name('projects.toggle');
    Route::resource('projects', ProjectController::class);

    // CRUD Certificates
    Route::resource('certificates', CertificateController::class)->except(['show']);

    // CRUD Blog
    Route::patch('blogs/update-visibility', [BlogController::class, 'updateVisibility'])->name('blogs.visibility');
    Route::patch('blogs/{blog}/toggle', [BlogController::class, 'toggleVisibility'])->name('blogs.toggle');
    Route::resource('blogs', BlogController::class)->except(['show']);

    // Kelola Pesan Masuk
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{message}/toggle', [MessageController::class, 'toggleRead'])->name('messages.toggle');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Kelola Kesan & Pesan (buku tamu)
    Route::get('/guest-messages', [GuestMessageController::class, 'adminIndex'])->name('guest-messages.index');
    Route::delete('/guest-messages/{guestMessage}', [GuestMessageController::class, 'destroy'])->name('guest-messages.destroy');

    // Kelola Informasi Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Kelola CV
    Route::post('/cv', [ProfileController::class, 'uploadCv'])->name('cv.upload');
    Route::delete('/cv', [ProfileController::class, 'deleteCv'])->name('cv.delete');
});
