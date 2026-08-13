<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobPosting;
use App\Models\PimpinanReport;
use App\Models\Mitra;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($user = auth()->user()) {
                if ($user->role !== 'pimpinan') {
                    if ($user->role === 'pelamar') return redirect()->route('pelamar.dashboard');
                    if ($user->role === 'superadmin') return redirect()->route('superadmin.dashboard');
                    if ($user->role === 'hrd') return redirect()->route('hrd.dashboard');
                }
            }
            return $next($request);
        });
    }

    // ----------------------------------------------------------------
    //  DASHBOARD — overview stats + admin list (Read Only)
    // ----------------------------------------------------------------
    public function dashboard()
    {
        $admins        = User::whereIn('role', ['hrd', 'superadmin'])
                             ->latest()
                             ->get();
        $totalAdmin    = $admins->count();
        $activeAdmin   = $admins->where('is_active', true)->count();
        $inactiveAdmin = $admins->where('is_active', false)->count();

        // New stats based on flowchart
        $totalLaporan = PimpinanReport::count();
        $totalLoker = JobPosting::count();
        $totalMitra = Mitra::count();
        $totalPelamar = JobApplication::count();

        // Calculate daily stats for the last 30 days for Chart
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }

        $registrationsRaw = User::where('created_at', '>=', now()->subDays(30)->startOfDay())->get();
        $applicationsRaw = JobApplication::where('created_at', '>=', now()->subDays(30)->startOfDay())->get();

        $registrations = [];
        foreach ($registrationsRaw as $u) {
            $d = $u->created_at->format('Y-m-d');
            $registrations[$d] = ($registrations[$d] ?? 0) + 1;
        }

        $applications = [];
        foreach ($applicationsRaw as $app) {
            $d = $app->created_at->format('Y-m-d');
            $applications[$d] = ($applications[$d] ?? 0) + 1;
        }

        $chartData = [];
        foreach ($dates as $date) {
            $chartData[] = [
                'raw_date' => $date,
                'label' => date('d M', strtotime($date)),
                'registrations' => $registrations[$date] ?? 0,
                'applications' => $applications[$date] ?? 0,
            ];
        }

        return view('pimpinan.dashboard', compact(
            'admins', 'totalAdmin', 'activeAdmin', 'inactiveAdmin',
            'totalLaporan', 'totalLoker', 'totalMitra', 'totalPelamar',
            'chartData'
        ));
    }

    // ----------------------------------------------------------------
    //  MITRA (Read-Only)
    // ----------------------------------------------------------------
    public function partners()
    {
        $partners = Mitra::latest()->paginate(10);
        return view('pimpinan.partners.index', compact('partners'));
    }

    // ----------------------------------------------------------------
    //  DATA PELAMAR TERPILIH (Read-Only)
    // ----------------------------------------------------------------
    public function applicants()
    {
        $acceptedApplicants = JobApplication::with(['user', 'posting'])
            ->where('status', 'accepted')
            ->latest()
            ->paginate(10);
            
        return view('pimpinan.applicants.index', compact('acceptedApplicants'));
    }

    // ----------------------------------------------------------------
    //  LAPORAN ADMIN
    // ----------------------------------------------------------------
    public function laporan()
    {
        $reports = PimpinanReport::with('jobPosting')->latest()->paginate(10);
        return view('pimpinan.laporan', compact('reports'));
    }

    // ----------------------------------------------------------------
    //  HIRING (Read-Only)
    // ----------------------------------------------------------------
    public function hiring()
    {
        $postings = JobPosting::latest()->paginate(5);
        return view('pimpinan.hiring.index', compact('postings'));
    }

    public function hiringShow(JobPosting $jobPosting)
    {
        $allApplications = collect();
        $priorityApplications = collect();
        $nonPriorityApplications = collect();
        $interviewPassedApplications = collect();
        $rejectedApplications = collect();
        $spkDetailsMap = [];

        if ($jobPosting->spk_status === 'completed') {
            $priorityApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('is_priority', true)
                ->where('status', '!=', 'rejected')
                ->where(function($q) {
                    $q->whereNull('interview_status')->orWhere('interview_status', '!=', 'valid');
                })
                ->orderBy('matching_score', 'desc')
                ->orderBy('birth_date', 'desc')
                ->orderBy('experience_years', 'desc')
                ->orderBy('placement_ready', 'desc')
                ->get();

            $nonPriorityApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('is_priority', false)
                ->where('status', '!=', 'rejected')
                ->where(function($q) {
                    $q->whereNull('interview_status')->orWhere('interview_status', '!=', 'valid');
                })
                ->orderBy('matching_score', 'desc')
                ->latest()
                ->get();

            $rejectedApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('status', 'rejected')
                ->latest()
                ->get();

            $allApplications = collect()->merge($priorityApplications)->merge($nonPriorityApplications)->merge($rejectedApplications);
            foreach ($allApplications as $application) {
                $spkDetailsMap[$application->id] = $application->spk_details ?: $jobPosting->calculateSpkScoreDetailed($application);
            }

            $interviewPassedApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('status', 'accepted')
                ->where('interview_status', 'valid')
                ->latest()
                ->get();
        } else {
            $allApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('status', '!=', 'rejected')
                ->latest()
                ->get();

            $rejectedApplications = $jobPosting->applications()
                ->with(['user.profile'])
                ->where('status', 'rejected')
                ->latest()
                ->get();
        }

        return view('pimpinan.hiring.show', [
            'posting' => $jobPosting,
            'allApplications' => $allApplications,
            'priorityApplications' => $priorityApplications,
            'nonPriorityApplications' => $nonPriorityApplications,
            'interviewPassedApplications' => $interviewPassedApplications,
            'rejectedApplications' => $rejectedApplications,
            'spkDetailsMap' => $spkDetailsMap,
        ]);
    }
}
