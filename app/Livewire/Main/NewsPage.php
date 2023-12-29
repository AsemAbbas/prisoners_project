<?php

namespace App\Livewire\Main;

use App\Models\News;
use App\Models\NewsType;
use Livewire\Component;
use Livewire\WithPagination;

class NewsPage extends Component
{
    use WithPagination;

    public int|null $NewsType = null;
    public string|null $Search = null;
    protected string $paginationTheme = 'bootstrap';
    public $news_type = null;

    public function mount($news_type = null)
    {
        $this->news_type = $news_type;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $News = News::query()
            ->when(isset($this->news_type),function ($query){
                $query->whereHas('NewsType',function ($q){
                    $q->where('news_type_name',$this->news_type);
                });
            })
            ->when(isset($this->NewsType), function ($query) {
                $query->where('news_type_id', $this->NewsType);
            })
            ->when(isset($this->Search), function ($query) {
                $query->where('news_title', 'LIKE', '%' . $this->Search . '%');
            })
            ->latest()
            ->paginate(10);
        $NewsTypes = NewsType::all();
        return view('livewire.main.news-page', compact('News', 'NewsTypes'))
            ->layout('components.layouts.main');
    }
}
