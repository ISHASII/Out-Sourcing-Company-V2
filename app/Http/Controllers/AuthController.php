<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\OtpCode;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // ================================================================
    //  REGISTRATION FLOW (with OTP Verification)
    // ================================================================

    /**
     * Step 1: Validate registration data, store in session, send OTP, redirect to verification page.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'kewarganegaraan' => ['required', 'in:WNI,WNA'],
            'nik' => ['nullable', 'string', 'max:20'],
            'paspor' => ['nullable', 'string', 'max:20'],
            'asal_negara' => ['nullable', 'string', 'max:80'],
            'tempat_lahir' => ['required', 'string', 'max:80'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string', 'max:500'],
            'provinsi' => ['required', 'string', 'max:80'],
            'kota' => ['required', 'string', 'max:80'],
            'kode_pos' => ['required', 'string', 'max:10'],
            'pendidikan' => ['required', 'string', 'max:20'],
            'tahun_lulus' => ['required', 'integer', 'min:1980', 'max:' . date('Y')],
            'sekolah' => ['required', 'string', 'max:120'],
            'jurusan' => ['required', 'string', 'max:120'],
            'has_experience' => ['nullable'],
            'perusahaan' => ['array'],
            'perusahaan.*' => ['nullable', 'string', 'max:120'],
            'posisi' => ['array'],
            'posisi.*' => ['nullable', 'string', 'max:120'],
            'tanggal_mulai' => ['array'],
            'tanggal_mulai.*' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'tanggal_selesai' => ['array'],
            'tanggal_selesai.*' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'deskripsi_pekerjaan' => ['array'],
            'deskripsi_pekerjaan.*' => ['nullable', 'string', 'max:1000'],
            'file_cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'file_foto' => ['required', 'image', 'max:2048'],
            'role' => ['nullable', 'string'],
        ]);

        if ($data['kewarganegaraan'] === 'WNI' && empty($data['nik'])) {
            return back()->withErrors(['nik' => 'NIK wajib diisi untuk WNI.'])->withInput();
        }

        if ($data['kewarganegaraan'] === 'WNA' && (empty($data['paspor']) || empty($data['asal_negara']))) {
            return back()->withErrors(['paspor' => 'Paspor dan asal negara wajib diisi untuk WNA.'])->withInput();
        }

        // Store uploaded files to temporary location
        $cvPath = $request->file('file_cv')->store('temp/cv', 'public');
        $photoPath = $request->file('file_foto')->store('temp/photos', 'public');

        // Build experience payload
        $experiences = $this->buildExperiencePayload($data);
        $experienceYears = $this->estimateExperienceYears(array_column($experiences, 'duration'));

        // Store ALL registration data in session
        $registrationData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'kewarganegaraan' => $data['kewarganegaraan'],
            'nik' => $data['nik'] ?? null,
            'paspor' => $data['paspor'] ?? null,
            'asal_negara' => $data['asal_negara'] ?? null,
            'tempat_lahir' => $data['tempat_lahir'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'alamat' => $data['alamat'],
            'provinsi' => $data['provinsi'],
            'kota' => $data['kota'],
            'kode_pos' => $data['kode_pos'],
            'pendidikan' => $data['pendidikan'],
            'tahun_lulus' => $data['tahun_lulus'],
            'sekolah' => $data['sekolah'],
            'jurusan' => $data['jurusan'],
            'has_experience' => $request->boolean('has_experience'),
            'experiences' => $experiences,
            'experience_years' => $experienceYears,
            'cv_path' => $cvPath,
            'photo_path' => $photoPath,
        ];

        $request->session()->put('registration_data', $registrationData);
        $request->session()->put('otp_email', $data['email']);

        // Generate OTP and send email
        $otp = OtpCode::generate($data['email'], 'registration');
        $userName = trim($data['first_name'] . ' ' . $data['last_name']);

        Mail::to($data['email'])->send(new OtpMail($otp->code, 'registration', $userName));

        return redirect()->route('register.verify')
            ->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    /**
     * Step 2: Show registration OTP verification page.
     */
    public function showRegistrationVerify(Request $request)
    {
        if (!$request->session()->has('registration_data')) {
            return redirect()->route('register')
                ->with('error', 'Silakan isi form pendaftaran terlebih dahulu.');
        }

        return view('auth.verify-registration');
    }

    /**
     * Step 3: Verify registration OTP code. If valid, create user account.
     */
    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->with('error', 'Sesi pendaftaran telah berakhir. Silakan daftar ulang.');
        }

        $email = $registrationData['email'];
        $otpRecord = OtpCode::verify($email, $request->input('otp'), 'registration');

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP tidak sesuai atau sudah kadaluarsa. Mohon pastikan kode yang benar.');
        }

        // OTP valid — mark as used
        $otpRecord->markUsed();

        // Create the user account
        $user = User::create([
            'name' => trim($registrationData['first_name'] . ' ' . $registrationData['last_name']),
            'email' => $email,
            'role' => 'pelamar',
            'password' => Hash::make($registrationData['password']),
            'email_verified_at' => now(),
        ]);

        // Move files from temp to permanent location
        $permanentCvPath = str_replace('temp/cv', 'profiles/cv', $registrationData['cv_path']);
        $permanentPhotoPath = str_replace('temp/photos', 'profiles/photos', $registrationData['photo_path']);

        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        if ($storage->exists($registrationData['cv_path'])) {
            $storage->move($registrationData['cv_path'], $permanentCvPath);
        }
        if ($storage->exists($registrationData['photo_path'])) {
            $storage->move($registrationData['photo_path'], $permanentPhotoPath);
        }

        // Create profile
        UserProfile::create([
            'user_id' => $user->id,
            'phone' => $registrationData['phone'],
            'birth_place' => $registrationData['tempat_lahir'],
            'birth_date' => $registrationData['tanggal_lahir'],
            'gender' => $this->normalizeGender($registrationData['jenis_kelamin']),
            'education_level' => $this->normalizeEducation($registrationData['pendidikan']),
            'experience_years' => $registrationData['experience_years'],
            'address' => $registrationData['alamat'],
            'province' => $registrationData['provinsi'],
            'city' => $registrationData['kota'],
            'postal_code' => $registrationData['kode_pos'],
            'cv_path' => $permanentCvPath,
            'photo_path' => $permanentPhotoPath,
            'extras' => [
                'citizenship' => $registrationData['kewarganegaraan'],
                'nik' => $registrationData['nik'],
                'paspor' => $registrationData['paspor'],
                'asal_negara' => $registrationData['asal_negara'],
                'graduation_year' => $registrationData['tahun_lulus'],
                'school_name' => $registrationData['sekolah'],
                'major' => $registrationData['jurusan'],
                'has_experience' => $registrationData['has_experience'],
                'experiences' => $registrationData['experiences'],
            ],
        ]);

        // Cleanup session
        $request->session()->forget(['registration_data', 'otp_email']);

        Auth::login($user);

        return redirect()->route('pelamar.dashboard')->with('success', 'Pendaftaran berhasil! Email Anda telah terverifikasi. Selamat datang!');
    }

    /**
     * Resend registration OTP.
     */
    public function resendRegistrationOtp(Request $request)
    {
        $registrationData = $request->session()->get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->with('error', 'Sesi pendaftaran telah berakhir. Silakan daftar ulang.');
        }

        $email = $registrationData['email'];
        $userName = trim($registrationData['first_name'] . ' ' . $registrationData['last_name']);

        $otp = OtpCode::generate($email, 'registration');
        Mail::to($email)->send(new OtpMail($otp->code, 'registration', $userName));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // ================================================================
    //  PASSWORD RESET FLOW (with OTP Verification)
    // ================================================================

    /**
     * Step 1: Send password reset OTP to email.
     */
    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem kami.'])->withInput();
        }

        $otp = OtpCode::generate($email, 'password_reset');
        Mail::to($email)->send(new OtpMail($otp->code, 'password_reset', $user->name));

        $request->session()->put('reset_email', $email);

        return redirect()->route('password.verify')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Step 2: Show password reset OTP verification page.
     */
    public function showPasswordVerify(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan masukkan email terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Step 3: Verify password reset OTP.
     */
    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');
        $otpRecord = OtpCode::verify($email, $request->input('otp'), 'password_reset');

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP tidak sesuai atau sudah kadaluarsa. Mohon pastikan kode yang benar.');
        }

        // OTP valid — mark as used and allow password reset
        $otpRecord->markUsed();
        $request->session()->put('password_reset_verified', true);

        return redirect()->route('password.reset')
            ->with('success', 'Verifikasi berhasil! Silakan buat password baru Anda.');
    }

    /**
     * Step 4: Show reset password form.
     */
    public function showResetPassword(Request $request)
    {
        if (!$request->session()->get('password_reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    /**
     * Step 5: Save new password.
     */
    public function resetPassword(Request $request)
    {
        if (!$request->session()->get('password_reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi reset password tidak valid. Silakan ulangi proses.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['required', 'email'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Email tidak ditemukan.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Cleanup session
        $request->session()->forget(['reset_email', 'password_reset_verified']);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    /**
     * Resend password reset OTP.
     */
    public function resendPasswordOtp(Request $request)
    {
        $email = $request->session()->get('reset_email') ?? $request->input('email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan masukkan email terlebih dahulu.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $otp = OtpCode::generate($email, 'password_reset');
        Mail::to($email)->send(new OtpMail($otp->code, 'password_reset', $user->name));

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // ================================================================
    //  LOGIN & LOGOUT
    // ================================================================

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Block deactivated accounts immediately
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi Superadmin untuk informasi lebih lanjut.',
                ])->onlyInput('email');
            }

            // Redirect based on role
            if ($user->role === 'superadmin') {
                return redirect()->route('superadmin.dashboard')->with('success', 'Selamat datang, Super Administrator!');
            }

            if ($user->role === 'hrd') {
                return redirect()->route('hrd.dashboard')->with('success', 'Selamat datang HRD, Anda berhasil masuk!');
            }

            return redirect()->route('pelamar.dashboard')->with('success', 'Selamat datang Pelamar, Anda berhasil masuk!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    // ================================================================
    //  PROFILE UPDATES
    // ================================================================

    public function updatePelamarProfile(Request $request)
    {
        if (Auth::user()->role !== 'pelamar') {
            abort(403);
        }        $data = $request->validate([
            'nama_depan' => ['required', 'string', 'max:80'],
            'nama_belakang' => ['nullable', 'string', 'max:80'],
            'no_hp' => ['required', 'string', 'max:20'],
            'tempat_lahir' => ['required', 'string', 'max:80'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'max:20'],
            'pendidikan' => ['required', 'string', 'max:20'],
            'jurusan' => ['nullable', 'string', 'max:120'],
            'alamat' => ['required', 'string', 'max:500'],
            'status_kewarganegaraan' => ['required', 'in:WNI,WNA'],
            'nik' => ['nullable', 'string', 'max:20'],
            'passport' => ['nullable', 'string', 'max:20'],
            'negara_asal' => ['nullable', 'string', 'max:80'],
            'provinsi_wna' => ['nullable', 'string', 'max:80'],
            'kota_wna' => ['nullable', 'string', 'max:80'],
            'punya_pengalaman' => ['required', 'in:TIDAK,IYA'],
            'pengalaman' => ['nullable', 'array'],
            'pengalaman.*.nama_perusahaan' => ['nullable', 'string', 'max:120'],
            'pengalaman.*.posisi_pekerjaan' => ['nullable', 'string', 'max:120'],
            'pengalaman.*.tanggal_mulai' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'pengalaman.*.tanggal_selesai' => ['nullable', 'string', 'regex:/^\d{4}-\d{2}$/'],
            'pengalaman.*.deskripsi_pekerjaan' => ['nullable', 'string', 'max:1000'],
            'file_cv' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'file_foto' => ['nullable', 'image', 'max:2048'],
        ]);
        if ($data['status_kewarganegaraan'] === 'WNI' && empty($data['nik'])) {
            return back()->withErrors(['nik' => 'NIK wajib diisi untuk WNI.'])->withInput();
        }

        if ($data['status_kewarganegaraan'] === 'WNA' && (empty($data['passport']) || empty($data['negara_asal']))) {
            return back()->withErrors(['passport' => 'Paspor dan asal negara wajib diisi untuk WNA.'])->withInput();
        }

        $user = Auth::user();
        $user->name = trim($data['nama_depan'] . ' ' . ($data['nama_belakang'] ?? ''));
        $user->save();

        // Process experiences
        $experiences = [];
        $experienceYears = 0;

        if ($data['punya_pengalaman'] === 'IYA' && !empty($data['pengalaman'])) {
            $inputExps = $data['pengalaman'];
            foreach ($inputExps as $exp) {
                $comp = $exp['nama_perusahaan'] ?? null;
                $pos = $exp['posisi_pekerjaan'] ?? null;
                $startVal = $exp['tanggal_mulai'] ?? null;
                $endVal = $exp['tanggal_selesai'] ?? null;
                $desc = $exp['deskripsi_pekerjaan'] ?? null;

                if (!$comp && !$pos && !$startVal && !$endVal && !$desc) {
                    continue;
                }

                $durationString = '';
                if ($startVal && $endVal) {
                    try {
                        $start = \Carbon\Carbon::createFromFormat('Y-m', $startVal);
                        $end = \Carbon\Carbon::createFromFormat('Y-m', $endVal);

                        $monthsDiff = (($end->year - $start->year) * 12 + ($end->month - $start->month)) + 1;
                        if ($monthsDiff < 0) $monthsDiff = 0;

                        $y = floor($monthsDiff / 12);
                        $m = $monthsDiff % 12;

                        $parts = [];
                        if ($y > 0) $parts[] = "$y Tahun";
                        if ($m > 0 || empty($parts)) $parts[] = "$m Bulan";

                        $durationString = implode(' ', $parts);
                    } catch (\Exception $e) {
                        $durationString = '';
                    }
                }

                $experiences[] = [
                    'company' => $comp,
                    'position' => $pos,
                    'start_date' => $startVal,
                    'end_date' => $endVal,
                    'duration' => $durationString,
                    'description' => $desc,
                ];
            }

            $computedDurations = array_column($experiences, 'duration');
            $experienceYears = $this->estimateExperienceYears($computedDurations);
        }

        $profile = $user->profile;
        if (!$profile) {
            $profile = new UserProfile(['user_id' => $user->id]);
        }

        $profile->phone = $data['no_hp'];
        $profile->birth_place = $data['tempat_lahir'];
        $profile->birth_date = $data['tanggal_lahir'];
        $profile->gender = $this->normalizeGender($data['jenis_kelamin']);
        $profile->education_level = $this->normalizeEducation($data['pendidikan']);
        $profile->major = $data['jurusan'] ?? null;
        $profile->experience_years = $experienceYears;
        $profile->address = $data['alamat'];

        if ($data['status_kewarganegaraan'] === 'WNA') {
            $profile->province = $data['provinsi_wna'] ?? null;
            $profile->city = $data['kota_wna'] ?? null;
        }

        $extras = $profile->extras ?? [];
        $extras['citizenship'] = $data['status_kewarganegaraan'];
        $extras['nik'] = $data['nik'] ?? null;
        $extras['paspor'] = $data['passport'] ?? null;
        $extras['asal_negara'] = $data['negara_asal'] ?? null;
        $extras['has_experience'] = ($data['punya_pengalaman'] === 'IYA');
        $extras['experiences'] = $experiences;

        $profile->extras = $extras;

        // Process file uploads
        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        
        if ($request->hasFile('file_cv')) {
            // Delete old CV file if it exists
            if ($profile->cv_path && $storage->exists($profile->cv_path)) {
                $storage->delete($profile->cv_path);
            }
            // Store new CV
            $profile->cv_path = $request->file('file_cv')->store('profiles/cv', 'public');
        }

        if ($request->hasFile('file_foto')) {
            // Delete old Photo file if it exists
            if ($profile->photo_path && $storage->exists($profile->photo_path)) {
                $storage->delete($profile->photo_path);
            }
            // Store new Photo
            $profile->photo_path = $request->file('file_foto')->store('profiles/photos', 'public');
        }

        $profile->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update HRD Profile/Account or password.
     */
    public function updateHrdProfile(Request $request)
    {
        if (Auth::user()->role !== 'hrd') {
            abort(403);
        }

        $type = $request->input('update_type', 'profile');

        if ($type === 'password') {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'password.required' => 'Password baru wajib diisi.',
                'password.min' => 'Password baru harus minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            ]);

            $user = Auth::user();
            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
            $user->save();

            return back()->with('success', 'Password akun HRD berhasil diperbarui.');
        }

        // Default: update profile details
        $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email,' . Auth::id()],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh pengguna lain.',
        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return back()->with('success', 'Profil akun HRD berhasil diperbarui.');
    }

    // ================================================================
    //  PRIVATE HELPERS
    // ================================================================

    private function normalizeGender(string $value): string
    {
        $lower = strtolower($value);
        if (str_contains($lower, 'laki')) {
            return 'male';
        }

        return 'female';
    }

    private function normalizeEducation(string $value): string
    {
        $normalized = strtoupper(trim($value));

        if (str_contains($normalized, 'SMA') || str_contains($normalized, 'SMK')) {
            return 'SMA/SMK';
        }

        if ($normalized === 'D3') {
            return 'D3';
        }

        if (str_contains($normalized, 'D4') || str_contains($normalized, 'S1')) {
            return 'S1';
        }

        if (str_contains($normalized, 'S2')) {
            return 'S2';
        }

        if (str_contains($normalized, 'S3')) {
            return 'S3';
        }

        return 'SMA/SMK';
    }

    private function estimateExperienceYears(array $durations): int
    {
        $totalYears = 0.0;

        foreach ($durations as $duration) {
            if (!$duration) {
                continue;
            }

            $text = strtolower($duration);
            $years = 0;
            $months = 0;

            if (preg_match('/(\d+)\s*tahun/', $text, $matches)) {
                $years = (int) $matches[1];
            }

            if (preg_match('/(\d+)\s*bulan/', $text, $matches)) {
                $months = (int) $matches[1];
            }

            $totalYears += $years + ($months / 12);
        }

        return (int) min(50, floor($totalYears));
    }

    private function buildExperiencePayload(array $data): array
    {
        $companies = $data['perusahaan'] ?? [];
        $positions = $data['posisi'] ?? [];
        $startDates = $data['tanggal_mulai'] ?? [];
        $endDates = $data['tanggal_selesai'] ?? [];
        $descriptions = $data['deskripsi_pekerjaan'] ?? [];

        $payload = [];
        $count = max(count($companies), count($positions), count($startDates), count($endDates), count($descriptions));
        for ($i = 0; $i < $count; $i++) {
            if (!($companies[$i] ?? null) && !($positions[$i] ?? null) && !($startDates[$i] ?? null) && !($endDates[$i] ?? null) && !($descriptions[$i] ?? null)) {
                continue;
            }

            $durationString = '';
            $startVal = $startDates[$i] ?? null;
            $endVal = $endDates[$i] ?? null;

            if ($startVal && $endVal) {
                try {
                    $start = \Carbon\Carbon::createFromFormat('Y-m', $startVal);
                    $end = \Carbon\Carbon::createFromFormat('Y-m', $endVal);

                    $monthsDiff = (($end->year - $start->year) * 12 + ($end->month - $start->month)) + 1;
                    if ($monthsDiff < 0) {
                        $monthsDiff = 0;
                    }

                    $y = floor($monthsDiff / 12);
                    $m = $monthsDiff % 12;

                    $parts = [];
                    if ($y > 0) {
                        $parts[] = "$y Tahun";
                    }
                    if ($m > 0 || empty($parts)) {
                        $parts[] = "$m Bulan";
                    }
                    $durationString = implode(' ', $parts);
                } catch (\Exception $e) {
                    $durationString = '';
                }
            }

            $payload[] = [
                'company' => $companies[$i] ?? null,
                'position' => $positions[$i] ?? null,
                'start_date' => $startVal,
                'end_date' => $endVal,
                'duration' => $durationString,
                'description' => $descriptions[$i] ?? null,
            ];
        }

        return $payload;
    }
}
