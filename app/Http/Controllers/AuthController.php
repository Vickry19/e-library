<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==================== SHOW LOGIN ====================
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke halaman sesuai role
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('member.index');
        }
        
        return view('auth.login');
    }

    // ==================== LOGIN ADMIN ====================
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Cek status aktif
            if ($user->is_active != 1) {
                return back()->with('error', 'Akun Anda tidak aktif.');
            }

            // ✅ Cek role admin
            if ($user->role_id != 1) {
                return back()->with('error', 'Anda bukan admin. Silakan login sebagai member.');
            }

            // Coba login
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang, ' . $user->nama . '!');
            }
        }

        return back()->with('error', 'Login gagal. Periksa kembali Email dan Password.');
    }

    // ==================== LOGIN MEMBER ====================
    public function loginMember(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Cek status aktif
            if ($user->is_active != 1) {
                return back()->with('error', 'Akun Anda tidak aktif.');
            }

            // ✅ Cek role member
            if ($user->role_id != 2) {
                return back()->with('error', 'Anda harus login sebagai member.');
            }

            // Coba login
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('member.index'))
                    ->with('success', 'Selamat datang, ' . $user->nama . '!');
            }
        }

        return back()->with('error', 'Login gagal. Periksa kembali Email dan Password.');
    }

    // ==================== LOGOUT ====================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Berhasil logout.');
    }

    // ==================== REGISTER MEMBER ====================
    public function registerMember(Request $request)
    {
        $messages = [
            'nama.required' => 'Nama lengkap harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berformat email yang benar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'konfirmasi_password.required' => 'Konfirmasi Password harus diisi.',
            'konfirmasi_password.min' => 'Konfirmasi Password minimal 8 karakter.',
            'konfirmasi_password.same' => 'Password dan konfirmasi password tidak cocok.',
        ];

        $request->validate([
            'nama' => 'required|string|max:128',
            'alamat' => 'required',
            'email' => 'required|string|email|max:128|unique:users',
            'password' => 'required|string|min:8',
            'konfirmasi_password' => 'required|string|min:8|same:password',
        ], $messages);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'image' => 'profip-pic/default.jpg',
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'is_active' => 1,
        ]);

        return response()->json([
            'success' => 'Akun berhasil didaftarkan. Silakan login.'
        ]);
    }
}