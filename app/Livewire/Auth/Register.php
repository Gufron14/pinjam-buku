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
    public $email;
    public $ktp;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|min:3',
        'alamat' => 'required|min:5',
        'no_telepon' => 'required|numeric|digits_between:10,15',
        'ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        // Unggah gambar KTP
        $ktpPath = $this->ktp->store('ktp', 'public');

        try {
            User::create([
                'name' => $this->name,
                'alamat' => $this->alamat,
                'no_telepon' => $this->no_telepon,
                'ktp' => $ktpPath,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Assign role 'user' to the new user
            $user = User::where('email', $this->email)->first();
            $user->assignRole('user');

            session()->flash('success', 'Pendaftaran berhasil! Silahkan login.');
            return redirect()->route('login');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
