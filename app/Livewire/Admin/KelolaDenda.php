<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Denda')]
#[Layout('layouts.master')]
class KelolaDenda extends Component
{
    public function render()
    {
        return view('livewire.admin.kelola-denda');
    }
}
