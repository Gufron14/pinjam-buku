<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Profil')]

class Profil extends Component
{
    public function render()
    {
        return view('livewire.auth.profil');
    }
}
