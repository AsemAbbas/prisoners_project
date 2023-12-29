<?php

namespace App\Livewire\Main;

use App\Models\News;
use App\Models\SocialMedia;
use Livewire\Component;

class NewsShowPage extends Component
{
    public string $url;

    public function mount($url): void
    {
        $this->url = $url;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {

        $News = News::query()->where('news_url', $this->url)->first();

        $SocialMedia = SocialMedia::all();

        return view('livewire.main.news-show-page', compact('News','SocialMedia'))
            ->layout('components.layouts.main');
    }
}