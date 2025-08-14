<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Title('Profil')]

class Profil extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $alamat;
    public $no_telepon;
    public $avatar;
    public $newAvatar;
    public $ktp;
    public $newKtp;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->alamat = $user->alamat;
        $this->no_telepon = $user->no_telepon;
        $this->avatar = $user->avatar;
        $this->ktp = $user->ktp;
    }

    public function updateProfile()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telepon' => 'required|numeric|digits_between:1,12',
            'alamat' => 'required',
            'newAvatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'newKtp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil ulang user dari database
        $user = User::find(Auth::id());

        if ($user) {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->no_telepon = $validatedData['no_telepon'];
            $user->alamat = $validatedData['alamat'];

            // Handle avatar upload
            if ($this->newAvatar) {
                // Delete old avatar if exists and it's not the default avatar
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Store new avatar
                $avatarPath = $this->newAvatar->store('avatars', 'public');
                $user->avatar = $avatarPath;
                $this->avatar = $avatarPath; // Update the current avatar property
            }

            if ($this->newKtp) {
                // Delete old ktp if exists
                if ($user->ktp && Storage::disk('public')->exists($user->ktp)) {
                    Storage::disk('public')->delete($user->ktp);
                }

                // Store new ktp
                $ktpPath = $this->newKtp->store('ktp', 'public');
                $user->ktp = $ktpPath;
                $this->ktp = $ktpPath; // Update the current ktp property
            }

            $user->save(); // Simpan data baru ke database

            // Refresh user data
            $this->mount();

            session()->flash('success', 'Berhasil update profil');
            $this->newAvatar = null; // Reset file input
        } else {
            session()->flash('error', 'User tidak ditemukan');
        }
    }

    public function render()
    {
        return view('livewire.auth.profil');
    }

    public function sendEmailVerification()
    {
        $user = Auth::user();
        if ($user && is_null($user->email_verified_at)) {
            $user->sendEmailVerificationNotification();
            session()->flash('success', 'Email verifikasi telah dikirim ke alamat email Anda. Silakan cek inbox Gmail Anda.');
        } else {
            session()->flash('error', 'Email sudah terverifikasi atau user tidak ditemukan.');
        }
    }
}