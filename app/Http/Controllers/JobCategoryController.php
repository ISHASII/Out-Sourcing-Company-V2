<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
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

    public function index(Request $request)
    {
        $query = JobCategory::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10);

        // Calculate job posting count per category name
        $postingsCount = JobPosting::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return view('hrd.kategori.index', [
            'categories' => $categories,
            'postingsCount' => $postingsCount,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:job_categories,name']
        ], [
            'name.required' => 'Nama kategori pekerjaan wajib diisi.',
            'name.max' => 'Nama kategori pekerjaan maksimal 100 karakter.',
            'name.unique' => 'Nama kategori pekerjaan ini sudah terdaftar.'
        ]);

        JobCategory::create([
            'name' => trim($request->input('name')),
            'is_active' => true
        ]);

        return back()->with('success', 'Kategori Pekerjaan "' . $request->input('name') . '" berhasil ditambahkan.');
    }

    public function destroy(JobCategory $category)
    {
        $name = $category->name;
        $category->delete();

        return back()->with('success', 'Kategori Pekerjaan "' . $name . '" berhasil dihapus.');
    }
}
