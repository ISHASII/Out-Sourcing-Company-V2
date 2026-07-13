<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CriteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'hrd') {
                return redirect()->route('pelamar.dashboard');
            }
            return $next($request);
        });
    }

    /**
     * Display all criteria directly as a table.
     */
    public function index(Request $request)
    {
        $query = Criterion::query();

        if ($request->has('search') && !empty($request->input('search'))) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('key', 'like', "%{$search}%");
            });
        }

        $criteria = $query->orderBy('category')->orderBy('sort_order')->paginate(10)->withQueryString();
        $categories = ['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner', 'Gardener'];
        $masterTemplates = $this->masterTemplates();

        return view('hrd.kriteria.index', compact('criteria', 'categories', 'masterTemplates'));
    }

    /**
     * Show all criteria in a specific category (Redirects to index).
     */
    public function show($category)
    {
        return redirect()->route('hrd.kriteria.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:file,text,checkbox,number'],
            'default_status' => ['required', 'in:core,secondary'],
            'default_weight' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $label = $request->input('label');
        $key = \Illuminate\Support\Str::slug($label, '_');
        $category = 'General';
        
        // Check for duplicates in General category
        $exists = Criterion::where('category', 'General')->where('key', $key)->exists();
        if ($exists) {
            return back()->withErrors(['label' => 'Kriteria dengan nama ini sudah ada.']);
        }

        Criterion::create([
            'category' => $category,
            'key' => $key,
            'label' => $label,
            'type' => $request->input('type'),
            'config' => [],
            'default_status' => $request->input('default_status'),
            'default_weight' => $request->input('default_weight'),
            'sort_order' => Criterion::max('sort_order') + 1
        ]);

        return back()->with('success', 'Kriteria "' . $label . '" berhasil ditambahkan.');
    }

    /**
     * Update a criterion.
     */
    public function update(Request $request, Criterion $criterion)
    {
        $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'default_status' => ['required', 'in:core,secondary'],
            'default_weight' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $criterion->update([
            'label' => $request->input('label'),
            'default_status' => $request->input('default_status'),
            'default_weight' => $request->input('default_weight'),
        ]);

        return back()->with('success', 'Kriteria "' . $criterion->label . '" berhasil diperbarui.');
    }

    /**
     * Destroy a criterion.
     */
    public function destroy(Criterion $criterion)
    {
        $label = $criterion->label;
        $category = $criterion->category;
        $criterion->delete();

        return back()->with('success', 'Kriteria "' . $label . '" berhasil dihapus dari kategori ' . $category);
    }

    /**
     * Master templates definition.
     */
    private function masterTemplates(): array
    {
        return [
            'gender' => [
                'label' => 'Jenis Kelamin',
                'type' => 'select',
                'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]
            ],
            'age' => [
                'label' => 'Batasan Usia',
                'type' => 'range',
                'config' => ['min_default' => 25, 'max_default' => 35]
            ],
            'education' => [
                'label' => 'Pendidikan Minimal',
                'type' => 'select',
                'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]
            ],
            'experience' => [
                'label' => 'Pengalaman Minimum',
                'type' => 'number',
                'config' => ['unit' => 'Tahun', 'min' => 0]
            ],
            'placement_ready' => [
                'label' => 'Kesiapan Penempatan UCI',
                'type' => 'checkbox',
                'config' => ['sub_types' => ['anywhere', 'specific']]
            ],
            'major' => [
                'label' => 'Jurusan',
                'type' => 'text',
                'config' => null
            ],
            'placement_choices' => [
                'label' => 'Pilihan Penempatan',
                'type' => 'text',
                'config' => null
            ],
            // Documents
            'sertifikat_agd_ambulance' => [
                'label' => 'Sertifikat AGD (Ambulance)',
                'type' => 'file',
                'config' => null
            ],
            'lisensi_sim_c_motor' => [
                'label' => 'Lisensi SIM C (Motor)',
                'type' => 'file',
                'config' => null
            ],
            'lisensi_sim_b1_mobil_berat' => [
                'label' => 'Lisensi SIM B1 (Mobil Berat)',
                'type' => 'file',
                'config' => null
            ],
            'str_file' => [
                'label' => 'Surat Tanda Registrasi (STR) / STRTK',
                'type' => 'file',
                'config' => null
            ],
            'sertifikat_kompetensi' => [
                'label' => 'Sertifikat Kompetensi Keperawatan',
                'type' => 'file',
                'config' => null
            ],
            'sim_c_aktif' => [
                'label' => 'SIM C Aktif',
                'type' => 'file',
                'config' => null
            ],
            // Other checkboxes
            'medical_support' => [
                'label' => 'Dukungan Medis',
                'type' => 'checkbox',
                'config' => null
            ],
            'medical_terms' => [
                'label' => 'Istilah-istilah Medis',
                'type' => 'checkbox',
                'config' => null
            ],
            'gardener_tech_understanding' => [
                'label' => 'Memahami Teknis Pertumbuhan Tanaman',
                'type' => 'checkbox',
                'config' => null
            ],
            'gardener_nursery_skill' => [
                'label' => 'Mampu Mengelola Pembibitan Tanaman',
                'type' => 'checkbox',
                'config' => null
            ],
            'gardener_tools_skill' => [
                'label' => 'Menguasai Skill Penggunaan Alat-Alat Teknis',
                'type' => 'checkbox',
                'config' => null
            ],
            // Additional custom document/checkbox options
            'skck' => [
                'label' => 'SKCK Aktif',
                'type' => 'file',
                'config' => null
            ],
            'sertifikat_bhd' => [
                'label' => 'Sertifikat Bantuan Hidup Dasar (BHD)',
                'type' => 'file',
                'config' => null
            ],
            'surat_sehat' => [
                'label' => 'Surat Keterangan Sehat',
                'type' => 'file',
                'config' => null
            ],
            'vaksin_booster' => [
                'label' => 'Sertifikat Vaksin Booster',
                'type' => 'file',
                'config' => null
            ],
            'kartu_keluarga' => [
                'label' => 'Kartu Keluarga',
                'type' => 'file',
                'config' => null
            ],
            'ijazah' => [
                'label' => 'Ijazah / Transkrip Nilai',
                'type' => 'file',
                'config' => null
            ]
        ];
    }
}
