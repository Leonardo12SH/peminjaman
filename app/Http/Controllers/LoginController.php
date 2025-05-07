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
        $role = "customer";
        $name = $request->name;
        $email = $request->email;
        $telephone = $request->telephone;
        $password = Hash::make($request->password);
        $request->validate([
            'email' => 'required|unique:users_212102',
            'password' => 'required',
            'name' => 'required',
            'telephone' => 'required'
        ]);

        $user = new User;
        $user->name_212102 = $name;
        $user->email_212102 = $email;
        $user->role_212102 = $role;
        $user->telephone_212102 = $telephone;
        $user->password_212102 = $password;

        $user->save();

        return redirect()->route('login')->with('success', 'Silakan loggin');
    }
}
