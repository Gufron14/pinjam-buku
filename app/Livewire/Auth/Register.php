<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

#[Title('Register')]

class Register extends Component
{
    use WithFileUploads;

    public $name;
    public $alamat;
    public $no_telepon;
    public $ttl;
    public $email;
    public $ktp;
    public $password;
    public $password_confirmation;
    public $umur;

    protected $rules = [
        'name' => 'required|min:3',
        'alamat' => 'required|min:5',
        'ttl' => 'required|date',
        'no_telepon' => 'required|numeric|digits_between:10,15',
        'ktp' => '', // will be set dynamically
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        // Set KTP validation rule based on age
        if ($this->umur !== null && $this->umur >= 17) {
            $this->rules['ktp'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $this->rules['ktp'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
        }
        $this->validate();

        // Unggah gambar KTP jika ada
        $ktpPath = null;
        if ($this->ktp) {
            $ktpPath = $this->ktp->store('ktp', 'public');
        }

        try {
            $user = User::create([
                'name' => $this->name,
                'alamat' => $this->alamat,
                'ttl' => $this->ttl,
                'no_telepon' => $this->no_telepon,
                'ktp' => $ktpPath,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole('user');
            $user->sendEmailVerificationNotification();
            session()->flash('success', 'Pendaftaran berhasil! Silahkan cek email untuk verifikasi.');
            return redirect()->route('login');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }

    public function updatedTtl($value)
    {
        if ($value) {
            $tahun_lahir = date('Y', strtotime($value));
            $tahun_sekarang = date('Y');
            $this->umur = $tahun_sekarang - $tahun_lahir;
        } else {
            $this->umur = null;
        }
    }
}