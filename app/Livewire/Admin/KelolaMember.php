<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\LoanHistory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kelola Member')]
#[Layout('layouts.master')]
class KelolaMember extends Component
{
    use WithPagination;

    public $selectedUser = null;
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showDetail($userId)
    {
        $this->selectedUser = User::with('roles')
        ->where('id', $userId)
        ->first();
        
        $this->dispatch('show-modal');
    }

    public function closeModal()
    {
        $this->selectedUser = null;
    }

    public function render()
    {
        $users = User::role('user')->paginate(10);

        return view('livewire.admin.kelola-member', compact('users'));
    }
}
