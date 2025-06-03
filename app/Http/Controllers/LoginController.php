<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Session;

class LoginController extends Controller
{

    public function login()
    {
        return view('login');
    }

    public function loginaksi(Request $request)
    {
        $credentials = [
            'email_212102' => $request->input('email_212102'),
            'password_212102' => $request->input('password_212102'),
        ];

        $user = User::where('email_212102', $credentials['email_212102'])->first();

        if (!$user) {
            Session::flash('error', 'User tidak terdaftar');
            return redirect('/');
        }

        if (Hash::check($credentials['password_212102'], $user->password_212102)) {
            Auth::login($user); // manual login
            if ($user->role_212102 === "admin") {
                return redirect()->route('home');
            } elseif ($user->role_212102 === "customer") {
                return redirect()->route('customer');
            }
        }

        Session::flash('error', 'Email atau password salah');
        return redirect('/');
    }



    public function logoutaksi()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function viewForgot()
    {
        return view('auth.forgot');
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('role_212102', 'customer')->where('email_212102', $request->email)->firstOrFail();
        $token = base64_encode(Uuid::uuid4());
        $tokenUser = Token::create([
            'user' => json_encode($user),
            'token' => $token,
            'expired_date' => Carbon::now()->add(4, 'hour'),
            'status' => 0
        ]);
        Mail::to($request->email)->send(new ResetPassword($tokenUser));
        return redirect()->route('view-verify');
    }

    public function token()
    {
        return view('mail.verify');
    }

    public function verifyToken(Request $request)
    {
        $token = Token::where('token', $request->token)->where('expired_date', '>', Carbon::now())->first();
        if (!$token) {
            return redirect()->route('forgot')->with('error', 'Generate kembali token');
        }
        $token->status = 1;
        $token->save();

        $user = json_decode($token->user);
        return view('auth.verify', [
            'user_id' => $user->id_212102
        ]);
    }

    public function updatePassword(Request $request)
    {
        User::where('id_212102', $request->user_id)->update([
            'password_212102' => Hash::make($request->password)
        ]);
        return redirect()->route('login')->with('success', 'Silakan loggin kembali');
    }

    public function register(Request $request)
    {
        // 1. Validasi input request dengan nama field yang sesuai dari form
        $validatedData = $request->validate([
            // Kunci array harus sesuai dengan atribut 'name' pada input HTML
            'name_212102'      => 'required|string|max:255',
            'email_212102'     => 'required|string|email|max:255|unique:users_212102,email_212102', // Asumsi: tabel 'users_212102', kolom 'email_212102'
            'password_212102'  => 'required', // Pertimbangkan untuk menambahkan rule 'confirmed' jika ada field konfirmasi password
            'telephone_212102' => 'required|max:20', // Sesuaikan max length jika perlu
        ], [
            // Pesan kustom jika diperlukan (opsional)
            'name_212102.required' => 'Nama lengkap tidak boleh kosong.',
            'email_212102.required' => 'Email tidak boleh kosong.',
            'email_212102.email' => 'Format email tidak valid.',
            'email_212102.unique' => 'Email sudah terdaftar.',
            'password_212102.required' => 'Password tidak boleh kosong.',
            'password_212102.min' => 'Password minimal 6 karakter.',
            'telephone_212102.required' => 'Nomor handphone tidak boleh kosong.',
        ]);

        // 2. Buat instance User baru dan isi dengan data yang sudah divalidasi
        $user = new User;
        $user->name_212102 = $validatedData['name_212102'];
        $user->email_212102 = $validatedData['email_212102'];
        $user->telephone_212102 = $validatedData['telephone_212102'];
        $user->password_212102 = Hash::make($validatedData['password_212102']); // Hashing password dilakukan di sini
        $user->role_212102 = 'customer'; // Penetapan role

        $user->save();

        // Opsional: Login pengguna secara otomatis setelah registrasi
        // Auth::login($user);

        // 3. Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
