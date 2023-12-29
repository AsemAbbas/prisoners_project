<?php

namespace App\Livewire\Main;

use Livewire\Component;

class AboutUsPage extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.main.about-us-page')->layout('components.layouts.main');
    }
}
