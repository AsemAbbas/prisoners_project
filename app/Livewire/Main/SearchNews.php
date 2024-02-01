<?php

namespace App\Livewire\Main;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class SearchNews extends Component
{
    use WithPagination;

    public ?string $searchTerm = null;
    protected string $paginationTheme = 'bootstrap';

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        if (!empty($this->searchTerm)) {
            $News = News::query()
                ->where('news_title', 'like', '%' . $this->searchTerm . '%')
                ->latest()
                ->paginate(10);
        } else $News = null;

        return view('livewire.main.search-news', compact('News'));

    }
}
