<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Member')]
#[Layout('layouts.master')]
class KelolaMember extends Component
{
    public function render()
    {
        return view('livewire.admin.kelola-member');
    }
}
