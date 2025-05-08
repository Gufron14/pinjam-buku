<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Hash;

#[Title('Register')]

class Register extends Component
{
    public $name;
    public $alamat;
    public $no_telepon;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|min:3',
        'alamat' => 'required|min:5',
        'no_telepon' => 'required|numeric|digits_between:10,15',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ];

    public function register()
    {
        $this->validate();

        try {
            User::create([
                'name' => $this->name,
                'alamat' => $this->alamat,
                'no_telepon' => $this->no_telepon,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => false, // Default user is not admin
            ]);

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
