<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Buku; // Import model Buku
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserRegisteredEmail;
use Illuminate\Support\Facades\Mail;

class LoginRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'dashboard']);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        Mail::to($validatedData['email'])->send(new UserRegisteredEmail($validatedData)); //pertemuan 12

        return redirect()->route('dashboard')->with('success', 'Anda berhasil mendaftar & masuk!');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Anda berhasil masuk!');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang Anda berikan tidak cocok.'
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $data_buku = Buku::all(); // Ambil semua data buku
            return view('auth.dashboard', compact('data_buku'));
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Silakan masuk untuk mengakses dashboard.'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar!');
    }

    // Metode untuk Buku

    public function index()
    {
        $data_buku = Buku::all();
        return view('buku.index', compact('data_buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function storeBuku(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);

        Buku::create($validatedData);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($validatedData);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
