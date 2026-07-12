<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrdPartnerController extends Controller
{
    /**
     * Display list of all partners (paginated).
     */
    public function index()
    {
        if (auth()->user()->role !== 'hrd') {
            return redirect()->route('pelamar.dashboard');
        }

        $partners = Mitra::latest()->paginate(12);
        return view('hrd.partners.index', compact('partners'));
    }

    /**
     * Store a newly created partner with logo upload.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'hrd') {
            return redirect()->route('pelamar.dashboard');
        }

        $request->validate([
            'name'  => 'required|string|max:100',
            'logo'  => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ], [
            'name.required'  => 'Nama mitra wajib diisi.',
            'logo.required'  => 'Logo mitra wajib diunggah.',
            'logo.image'     => 'File harus berupa gambar.',
            'logo.mimes'     => 'Format gambar harus JPG, PNG, SVG, atau WebP.',
            'logo.max'       => 'Ukuran logo tidak boleh melebihi 2MB.',
        ]);

        $path = $request->file('logo')->store('partners', 'public');

        Mitra::create([
            'name'      => $request->name,
            'logo_path' => $path,
        ]);

        return back()->with('success', 'Mitra berhasil ditambahkan.');
    }

    /**
     * Update an existing partner (name and optionally a new logo).
     */
    public function update(Request $request, Mitra $partner)
    {
        if (auth()->user()->role !== 'hrd') {
            return redirect()->route('pelamar.dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ], [
            'name.required' => 'Nama mitra wajib diisi.',
            'logo.image'    => 'File harus berupa gambar.',
            'logo.mimes'    => 'Format gambar harus JPG, PNG, SVG, atau WebP.',
            'logo.max'      => 'Ukuran logo tidak boleh melebihi 2MB.',
        ]);

        $data = ['name' => $request->name];

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($partner->logo_path && Storage::disk('public')->exists($partner->logo_path)) {
                Storage::disk('public')->delete($partner->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        return back()->with('success', 'Data mitra berhasil diperbarui.');
    }

    /**
     * Delete a partner and its logo file from storage.
     */
    public function destroy(Mitra $partner)
    {
        if (auth()->user()->role !== 'hrd') {
            return redirect()->route('pelamar.dashboard');
        }

        if ($partner->logo_path && Storage::disk('public')->exists($partner->logo_path)) {
            Storage::disk('public')->delete($partner->logo_path);
        }

        $partner->delete();

        return back()->with('success', 'Mitra berhasil dihapus.');
    }
}
