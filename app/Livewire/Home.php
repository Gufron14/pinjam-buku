<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Taman Baca Balarea')]

class Home extends Component
{   
    public $buku;

    public function mount()
    {
        $this->buku = \App\Models\Book::with(['category', 'genre', 'type'])->latest()->take(3)->get();
    }

    public function render()
    {
        return view('livewire.home');
    }
}
