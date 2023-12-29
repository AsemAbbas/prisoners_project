<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class NotFound extends Component
{
    public function render()
    {
        return view('livewire.dashboard.not-found')
            ->layout('components.layouts.main');
    }
}
