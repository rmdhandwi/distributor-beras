<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProdusenModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AuthenticatedController extends Controller
{

    public function registerPage()
    {
        return Inertia::render('Auth/Register');
    }

    public function submitRegister(Request $req)
    {
         $validated = $req->validate([
            'nama'     => 'required|string|max:255|unique:users,name',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|min:8',
            'alamat'   => 'required|string|max:255',
            'no_telp'  => 'required|numeric|min_digits:12|unique:tb_produsen,no_telp',
            'email'    => 'required|email|max:255|unique:tb_produsen,email',
        ],[
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'min_digits' => ':attribute tidak boleh kurang dari :min digit.',
            'max' => ':attribute tidak boleh lebih dari :max.',
        ]);

        // Simpan ke tabel users dan tandai sebagai Produsen
        $user = User::create([
            'name'     => $validated['nama'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'Produsen',
        ]);

        // Simpan ke tb_produsen
        if($user)
        {
            ProdusenModel::create([
                'user_id' => $user->user_id,
                'nama_produsen' => $validated['nama'],
                'alamat'  => $validated['alamat'],
                'no_telp' => $validated['no_telp'],
                'email'   => $validated['email'],
                'status'  => false,
            ]);

            $notification = [
                'notif_status' => 'success',
                'notif_message' => 'Berhasil Registrasi! Menunggu validasi Pemilik untuk Login.',
            ];

            return redirect()->route('login')->with($notification);

        }
    }

    public function submitLogin(Request $req)
    {
        $loginForm = $req->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ],[
            'username.required' => 'Harap isi username',
            'username.exists' => $req->username.' tidak terdaftar',
            'password.required' => 'Harap isi password',
        ]);

        if(Auth::attempt($loginForm))
        {
            $req->session()->regenerate();


            $user = auth()->guard()->user();

            // Tambahan: Cek role = Produsen dan status = false
            if ($user->role === 'Produsen') {
                $produsen = ProdusenModel::where('user_id', $user->user_id)->first();

                if (!$produsen || !$produsen->status) {
                     $notification = [
                        'notif_status' => 'error',
                        'notif_message' => 'Login Gagal ! Akun anda belum diverifikasi',
                    ];
                    Auth::logout();
                    return redirect()->route('login')->with($notification);
                }
            }

            $notification = [
                'notif_status' => 'success',
                'notif_message' => 'Selamat Datang '.$req->username,
            ];

            switch($user->role)
            {
                case 'Admin' : return redirect()->route('admin.dashboard')->with($notification);
                break;
                case 'Pemilik' : return redirect()->route('pemilik.dashboard')->with($notification);
                break;
                case 'Produsen' : return redirect()->route('produsen.dashboard')->with($notification);
                break;
            }

        }
        else
        {
            $notification = [
                'notif_status' => 'error',
                'notif_message' => 'Login Gagal',
            ];

            return redirect()->back()->with($notification)->withErrors([
                'password' => 'Password Salah'
            ]);
        }
    }

    public function logout()
    {
        $notification = [
                'notif_status' => 'success',
                'notif_message' => 'Berhasil Logout',
        ];
        Auth::logout();
        Session::flush();
        return redirect()->route('login')->with($notification);
    }
}
