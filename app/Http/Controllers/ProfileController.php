<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile information.
     */
    public function edit()
    {
        // Ambil profil pertama, atau buat default jika belum ada
        $profile = Profile::first();

        if (! $profile) {
            $profile = Profile::create([
                'name' => auth()->user()->name,
                'role' => 'Front-End Developer',
                'bio' => 'Tulis deskripsi tentang kamu di sini.',
                'photo' => null,
                'badge_text' => 'Available for New Projects',
                'instagram_url' => null,
                'whatsapp_number' => null,
                'email' => null,
            ]);
        }

        return view('profile.edit', compact('profile'));
    }

    /**
     * Update the profile information in storage.
     */
    public function update(Request $request)
    {
        // Ambil profil pertama, atau buat default jika belum ada
        $profile = Profile::firstOrCreate(
            [],
            [
                'name' => auth()->user()->name,
                'role' => 'Front-End Developer',
            ]
        );

        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'bio' => 'nullable',
            'badge_text' => 'nullable',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'instagram_url' => 'nullable|url',
            'whatsapp_number' => 'nullable',
            'email' => 'nullable|email',
        ]);

        $profile->name = $request->name;
        $profile->role = $request->role;
        $profile->bio = $request->bio;
        $profile->badge_text = $request->badge_text;
        $profile->instagram_url = $request->instagram_url ?: null;
        $profile->whatsapp_number = preg_replace('/\D/', '', $request->whatsapp_number ?? '') ?: null;
        $profile->email = $request->email ?: null;

        // Cek jika user mengupload foto profil baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama dari folder storage agar tidak menumpuk
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }

            // Simpan foto baru
            $profile->photo = $request->file('photo')->store('profile', 'public');
        }

        // Hapus foto jika diminta
        if ($request->has('remove_photo') && $profile->photo) {
            Storage::disk('public')->delete($profile->photo);
            $profile->photo = null;
        }

        $profile->save();

        return redirect()->route('admin.profile.edit')->with('success', 'Informasi profil berhasil diperbarui!');
    }

    /**
     * Upload or replace the CV file.
     */
    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'cv.required' => 'Pilih file CV terlebih dahulu.',
            'cv.file' => 'File CV tidak valid.',
            'cv.mimes' => 'CV harus berupa file PDF, DOC, atau DOCX.',
            'cv.max' => 'Ukuran file CV maksimal 10 MB.',
        ]);

        $profile = Profile::firstOrCreate(
            [],
            [
                'name' => auth()->user()->name,
                'role' => 'Front-End Developer',
            ]
        );

        // Hapus CV lama agar file tidak menumpuk di storage
        if ($profile->cv_file) {
            Storage::disk('public')->delete($profile->cv_file);
        }

        $profile->cv_file = $request->file('cv')->store('cv', 'public');
        $profile->save();

        return redirect()->route('admin.dashboard')->with('success', 'CV berhasil diunggah!');
    }

    /**
     * Delete the CV file.
     */
    public function deleteCv()
    {
        $profile = Profile::first();

        if ($profile && $profile->cv_file) {
            Storage::disk('public')->delete($profile->cv_file);
            $profile->cv_file = null;
            $profile->save();
        }

        return redirect()->route('admin.dashboard')->with('success', 'CV berhasil dihapus.');
    }

    /**
     * Resolve path CV dan nama file ramah untuk response publik.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveCv(): array
    {
        $profile = Profile::first();
        $cvPath = Profile::availableCvPath();

        if (! $cvPath) {
            abort(404, 'CV belum tersedia.');
        }

        // Perbaiki data yang basi supaya admin dashboard menampilkan CV yang sama.
        if ($profile && $profile->cv_file !== $cvPath) {
            $profile->cv_file = $cvPath;
            $profile->save();
        }

        $extension = pathinfo($cvPath, PATHINFO_EXTENSION);
        $filename = 'CV-' . Str::slug($profile?->name ?: 'Portofolio') . ($extension ? '.' . $extension : '');

        return [$cvPath, $filename];
    }

    /**
     * Download the CV for public visitors.
     */
    public function downloadCv()
    {
        [$cvPath, $filename] = $this->resolveCv();

        return Storage::disk('public')->download($cvPath, $filename);
    }

    /**
     * Tampilkan CV PDF langsung di browser tanpa mengunduhnya.
     */
    public function previewCv()
    {
        [$cvPath, $filename] = $this->resolveCv();

        // Browser tidak bisa merender DOC/DOCX secara inline.
        if (strtolower(pathinfo($cvPath, PATHINFO_EXTENSION)) !== 'pdf') {
            return redirect()->route('cv.download');
        }

        return Storage::disk('public')->response($cvPath, $filename, [], 'inline');
    }
}
