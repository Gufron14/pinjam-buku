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

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->alamat = $user->alamat;
        $this->no_telepon = $user->no_telepon;
        $this->avatar = $user->avatar;
    }

    public function updateProfile()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telepon' => 'required|numeric|digits_between:1,12',
            'alamat' => 'required',
            'newAvatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
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
}
