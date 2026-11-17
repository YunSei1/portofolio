<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = Certificate::latest()->get();
        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'issuer' => 'nullable',
            'issue_date' => 'nullable|date',
            'description' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('certificates', 'public');
        }

        Certificate::create([
            'title' => $request->title,
            'issuer' => $request->issuer,
            'issue_date' => $request->issue_date,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Certificate $certificate)
    {
        return view('certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'title' => 'required',
            'issuer' => 'nullable',
            'issue_date' => 'nullable|date',
            'description' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        $certificate->title = $request->title;
        $certificate->issuer = $request->issuer;
        $certificate->issue_date = $request->issue_date ?: null;
        $certificate->description = $request->description;

        // Cek jika user mengupload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari folder storage agar tidak menumpuk
            if ($certificate->image) {
                Storage::disk('public')->delete($certificate->image);
            }

            // Simpan gambar baru
            $certificate->image = $request->file('image')->store('certificates', 'public');
        }

        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil diperbarui!');
    }

    /**
     * Sajikan gambar sertifikat hasil upload langsung dari storage.
     */
    public function image(Certificate $certificate)
    {
        $path = $certificate->image;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'public, max-age=31536000',
        ], 'inline');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        // Hapus file gambar dari storage jika ada
        if ($certificate->image) {
            Storage::disk('public')->delete($certificate->image);
        }

        // Hapus data dari database
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dihapus!');
    }
}
