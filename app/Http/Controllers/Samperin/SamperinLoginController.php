<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SamperinLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        if (session()->has('samperin_user_id')) {
            return redirect()->route('samperin.dashboard');
        }

        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

        $request->validate(
            [
                'login' => ['required', 'string'],
                'password' => ['required', 'string'],
            ],
            [
                'login.required' => 'NIP, NIK atau email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ],
        );

        /*
    |--------------------------------------------------------------------------
    | INPUT LOGIN
    |--------------------------------------------------------------------------
    */

        $login = trim($request->input('login'));

        /*
    |--------------------------------------------------------------------------
    | CARI USER
    |--------------------------------------------------------------------------
    */

        $user = SamperinUser::query()
            ->where(function ($query) use ($login) {
                $query->where('user_nip', $login)->orWhere('user_nik', $login)->orWhere('user_email', $login);
            })
            ->first();

        /*
    |--------------------------------------------------------------------------
    | USER TIDAK DITEMUKAN
    |--------------------------------------------------------------------------
    */

        if (!$user) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'NIP, NIK/email atau password salah.',
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | CEK STATUS USER
    |--------------------------------------------------------------------------
    */

        if ((int) $user->user_status !== 1) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Akun Anda sudah tidak aktif.',
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | CEK PASSWORD
    |--------------------------------------------------------------------------
    */

        if (!Hash::check($request->input('password'), $user->user_password)) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'NIP, NIK/email atau password salah.',
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | AMBIL ROLE USER
    |--------------------------------------------------------------------------
    */

        $roles = DB::table('samperin_user_role')->join('samperin_role', 'samperin_role.role_uid', '=', 'samperin_user_role.user_role_role_uid')->where('samperin_user_role.user_role_user_uid', $user->user_uid)->where('samperin_role.role_status', 1)->select('samperin_role.role_uid', 'samperin_role.role_nama', 'samperin_role.role_slug')->get();

        /*
    |--------------------------------------------------------------------------
    | TENTUKAN ROLE DEFAULT
    |--------------------------------------------------------------------------
    */

        $activeRole = $this->resolveDefaultRole($roles);

        /*
    |--------------------------------------------------------------------------
    | USER TIDAK MEMILIKI ROLE
    |--------------------------------------------------------------------------
    */

        if (!$activeRole) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors([
                    'login' => 'Akun Anda belum memiliki role. Silakan hubungi Administrator.',
                ]);
        }

        /*
    |--------------------------------------------------------------------------
    | REGENERATE SESSION
    |--------------------------------------------------------------------------
    */

        $request->session()->regenerate();

        /*
    |--------------------------------------------------------------------------
    | SIMPAN USER
    |--------------------------------------------------------------------------
    */

        session([
            'samperin_user_id' => $user->user_id,
            'samperin_user_uid' => $user->user_uid,

            'samperin_role_uid' => $activeRole->role_uid,
            'samperin_role_nama' => $activeRole->role_nama,
            'samperin_role_slug' => $activeRole->role_slug,
        ]);

        /*
    |--------------------------------------------------------------------------
    | REDIRECT SESUAI ROLE
    |--------------------------------------------------------------------------
    */

        $roleSlug = strtolower(trim((string) $activeRole->role_slug));

        /*
    |--------------------------------------------------------------------------
    | PEGAWAI
    |--------------------------------------------------------------------------
    */

        if ($roleSlug === 'pegawai') {
            return redirect()->route('pegawai.index');
        }

        /*
    |--------------------------------------------------------------------------
    | ROLE LAIN
    |--------------------------------------------------------------------------
    */

        return redirect()->route('samperin.dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ROLE
    |--------------------------------------------------------------------------
    */

    private function resolveDefaultRole($roles)
    {
        if ($roles->isEmpty()) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 1 - ADMINISTRATOR
        |--------------------------------------------------------------------------
        */

        $administrator = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['administrator', 'admin', 'admin-full'], true);
        });

        if ($administrator) {
            return $administrator;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 2 - PEGAWAI
        |--------------------------------------------------------------------------
        */

        $pegawai = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['pegawai'], true);
        });

        if ($pegawai) {
            return $pegawai;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 3 - KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */

        $kepegawaian = $roles->first(function ($role) {
            return in_array(strtolower(trim($role->role_slug)), ['kepegawaian'], true);
        });

        if ($kepegawaian) {
            return $kepegawaian;
        }

        /*
        |--------------------------------------------------------------------------
        | PRIORITAS 4 - ROLE LAINNYA
        |--------------------------------------------------------------------------
        */

        return $roles->sortBy('role_nama')->first();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('samperin.login');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */
    public function showForgotPassword()
    {
        return view('auth.forgot_password', [
            'showConfirmation' => false,
            'resetUser' => null,
            'maskedEmail' => null,
            'email' => null,
        ]);
    }
    public function checkResetIdentity(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

        $request->validate([
            'identifier' => [
                'required',
                'string',
            ],
        ], [
            'identifier.required' => 'NIP, NIK, atau Email wajib diisi.',
        ]);

        $identifier = trim($request->identifier);

        /*
    |--------------------------------------------------------------------------
    | CARI USER
    |--------------------------------------------------------------------------
    |
    | Pencarian bisa berdasarkan:
    |
    | 1. NIP
    | 2. NIK
    | 3. Email
    |
    */

        $user = SamperinUser::query()
            ->where('user_nip', $identifier)
            ->orWhere('user_nik', $identifier)
            ->orWhere('user_email', $identifier)
            ->first();

        /*
    |--------------------------------------------------------------------------
    | USER TIDAK DITEMUKAN
    |--------------------------------------------------------------------------
    */

        if (!$user) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data NIP, NIK, atau Email tidak ditemukan.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CEK STATUS
    |--------------------------------------------------------------------------
    */

        if ((int) $user->user_status !== 1) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Akun Anda tidak aktif. Silakan hubungi admin.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CEK EMAIL
    |--------------------------------------------------------------------------
    */

        if (empty($user->user_email)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Email belum terdaftar pada akun Anda. Silakan hubungi admin.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDASI EMAIL
    |--------------------------------------------------------------------------
    */

        if (!filter_var($user->user_email, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Email akun tidak valid. Silakan hubungi admin.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CEK COOLDOWN
    |--------------------------------------------------------------------------
    */

        if (
            $user->user_reset_expired &&
            now()->lt($user->user_reset_expired)
        ) {
            $sisa = now()->diffInMinutes(
                $user->user_reset_expired
            );

            if ($sisa < 1) {
                $sisa = 1;
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Permintaan reset password sudah pernah dilakukan. '
                        . 'Silakan coba kembali dalam '
                        . $sisa
                        . ' menit.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | SIMPAN USER YANG DITEMUKAN KE SESSION
    |--------------------------------------------------------------------------
    |
    | Belum membuat token.
    | Belum mengirim email.
    |
    */

        session([
            'password_reset_user_id' => $user->user_id,
        ]);

        /*
    |--------------------------------------------------------------------------
    | MASK EMAIL
    |--------------------------------------------------------------------------
    */

        $maskedEmail = $this->maskEmail(
            $user->user_email
        );

        /*
    |--------------------------------------------------------------------------
    | TAMPILKAN KONFIRMASI
    |--------------------------------------------------------------------------
    */

        return view('auth.forgot_password', [
            'resetUser' => $user,
            'maskedEmail' => $maskedEmail,
            'showConfirmation' => true,
            'email' => $user->user_email,
        ]);
    }
    private function maskEmail(string $email): string
    {
        if (!str_contains($email, '@')) {
            return $email;
        }

        [$name, $domain] = explode('@', $email, 2);

        $length = strlen($name);

        if ($length <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } elseif ($length <= 4) {
            $maskedName =
                substr($name, 0, 1)
                . str_repeat('*', $length - 2)
                . substr($name, -1);
        } else {
            $maskedName =
                substr($name, 0, 2)
                . str_repeat('*', $length - 4)
                . substr($name, -2);
        }

        return $maskedName . '@' . $domain;
    }
    public function sendResetLink(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | AMBIL USER DARI SESSION
    |--------------------------------------------------------------------------
    */

        $userId = session(
            'password_reset_user_id'
        );

        if (!$userId) {

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Sesi reset password tidak ditemukan. Silakan ulangi.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | AMBIL DATA USER TERBARU
    |--------------------------------------------------------------------------
    */

        $user = SamperinUser::find($userId);

        if (!$user) {

            session()->forget(
                'password_reset_user_id'
            );

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Data user tidak ditemukan.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CEK STATUS
    |--------------------------------------------------------------------------
    */

        if ((int) $user->user_status !== 1) {

            session()->forget(
                'password_reset_user_id'
            );

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Akun Anda tidak aktif. Silakan hubungi admin.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | CEK EMAIL
    |--------------------------------------------------------------------------
    */

        if (
            empty($user->user_email) ||
            !filter_var(
                $user->user_email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            session()->forget(
                'password_reset_user_id'
            );

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Email akun tidak valid. Silakan hubungi admin.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | COOLDOWN
    |--------------------------------------------------------------------------
    */

        if (
            $user->user_reset_expired &&
            now()->lt($user->user_reset_expired)
        ) {

            $sisa = now()->diffInMinutes(
                $user->user_reset_expired
            );

            if ($sisa < 1) {
                $sisa = 1;
            }

            session()->forget(
                'password_reset_user_id'
            );

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Reset password hanya dapat dilakukan setiap 4 jam. '
                        . 'Silakan coba lagi dalam '
                        . $sisa
                        . ' menit.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | GENERATE TOKEN
    |--------------------------------------------------------------------------
    */

        $token = Str::random(64);

        $link = url(
            '/password/reset/' . $token
        );

        /*
    |--------------------------------------------------------------------------
    | KIRIM EMAIL
    |--------------------------------------------------------------------------
    */

        try {

            Mail::send(
                'auth.email_reset_password',
                [
                    'link' => $link,
                    'user' => $user,
                ],
                function ($mail) use ($user) {

                    $mail->to(
                        $user->user_email
                    );

                    $mail->subject(
                        'Reset Password SAMPERIN'
                    );
                }
            );

            /*
        |--------------------------------------------------------------------------
        | SIMPAN TOKEN SETELAH EMAIL BERHASIL
        |--------------------------------------------------------------------------
        */

            $user->update([
                'user_reset_token' => $token,
                'user_reset_expired' => now()->addHours(4),
            ]);

            /*
        |--------------------------------------------------------------------------
        | HAPUS SESSION
        |--------------------------------------------------------------------------
        */

            session()->forget(
                'password_reset_user_id'
            );
        } catch (\Throwable $e) {

            $msg = $e->getMessage();

            if (
                str_contains(
                    $msg,
                    'Daily user sending limit exceeded'
                ) ||
                str_contains(
                    $msg,
                    'sending limit'
                )
            ) {

                return redirect()
                    ->route('password.forgot')
                    ->with(
                        'error',
                        'Batas pengiriman email hari ini sudah tercapai. '
                            . 'Silakan coba kembali besok.'
                    );
            }

            if (
                str_contains($msg, '550') ||
                str_contains($msg, '551') ||
                str_contains($msg, '552') ||
                str_contains($msg, '553') ||
                str_contains($msg, 'mailbox unavailable') ||
                str_contains($msg, 'recipient address rejected')
            ) {

                return redirect()
                    ->route('password.forgot')
                    ->with(
                        'error',
                        'Email tidak aktif atau tidak dapat menerima pesan. '
                            . 'Silakan hubungi admin.'
                    );
            }

            return redirect()
                ->route('password.forgot')
                ->with(
                    'error',
                    'Gagal mengirim email reset password. Silakan coba kembali.'
                );
        }

        return redirect()
            ->route('samperin.login')
            ->with(
                'success',
                'Link reset password telah dikirim ke email '
                    . $this->maskEmail($user->user_email)
                    . '.'
            );
    }
    public function formReset($token)
    {
        $user = SamperinUser::where(
            'user_reset_token',
            $token
        )->first();

        if (!$user) {

            return redirect()
                ->route('samperin.login')
                ->with(
                    'error',
                    'Link reset password tidak valid atau sudah digunakan.'
                );
        }

        if (
            !$user->user_reset_expired ||
            now()->gte($user->user_reset_expired)
        ) {

            $user->update([
                'user_reset_token' => null,
                'user_reset_expired' => null,
            ]);

            return redirect()
                ->route('samperin.login')
                ->with(
                    'error',
                    'Link reset password sudah kadaluarsa. '
                        . 'Silakan meminta link baru.'
                );
        }

        return view('auth.reset_password', [
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function savePassword(Request $request)
    {
        $request->validate([
            'token' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'token.required' =>
            'Token reset password tidak ditemukan.',

            'password.required' =>
            'Password baru wajib diisi.',

            'password.min' =>
            'Password minimal 8 karakter.',

            'password.confirmed' =>
            'Konfirmasi password tidak sesuai.',
        ]);

        $user = SamperinUser::where(
            'user_reset_token',
            $request->token
        )->first();

        if (!$user) {

            return redirect()
                ->route('samperin.login')
                ->with(
                    'error',
                    'Link reset password tidak valid atau sudah digunakan.'
                );
        }

        if (
            !$user->user_reset_expired ||
            now()->gte($user->user_reset_expired)
        ) {

            $user->update([
                'user_reset_token' => null,
                'user_reset_expired' => null,
            ]);

            return redirect()
                ->route('samperin.login')
                ->with(
                    'error',
                    'Link reset password sudah kadaluarsa.'
                );
        }

        $user->update([
            'user_password' => bcrypt(
                $request->password
            ),

            'user_reset_token' => null,

            'user_reset_expired' => null,
        ]);

        return redirect()
            ->route('samperin.login')
            ->with(
                'success',
                'Password berhasil diperbarui. Silakan login menggunakan password baru.'
            );
    }
}