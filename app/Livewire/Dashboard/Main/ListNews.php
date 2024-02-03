<?php

namespace App\Livewire\Dashboard\Main;

use App\Models\News;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ListNews extends Component
{
    use WithPagination;

    public ?string $Search = null;
    public ?string $sortBy = null;
    public ?object $News_ = null;
    public ?int $news_id;
    public ?int $news_key;
    public ?string $order_by;
    protected string $paginationTheme = 'bootstrap';

    public function NewsOrderBy($news_id, $news_key): void
    {
        $this->news_id = $news_id;
        $this->news_key = $news_key;
    }

    public function ChangeOrderBy(): void
    {
        News::query()->find($this->news_id)->update([
            'order_by' => $this->order_by
        ]);

        $this->news_id = null;
        $this->news_key = null;
        $this->order_by = null;
    }

    public function onSlider(News $news): void
    {
        if ($news->on_slider)
            $news->update(['on_slider' => false]);
        else $news->update(['on_slider' => true]);
    }

    public function showNewsDescription(News $news): void
    {
        $this->News_ = $news;
        $this->dispatch('showNewsDescription');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->News_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(News $news): void
    {
        $this->News_ = $news;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $NewsCount = [
            'all' => News::query()->count(),
            'on_slider' => News::query()->where('on_slider', true)->count(),
        ];

        $News = $this->getNewsProperty()->latest()->paginate(10);
        return view('livewire.dashboard.main.list-news', compact('News', 'NewsCount'));
    }

    public function getNewsProperty()
    {
        return News::query()
            ->orderBy('order_by')
            ->when(isset($this->Search), function ($query) {
                $query->where('news_title', 'LIKE', '%' . $this->Search . '%');
                $query->orWhereHas('NewsType', function ($q) {
                    $q->where('news_type_name', 'LIKE', '%' . $this->Search . '%');
                });
            })
            ->when(isset($this->sortBy), function ($q) {
                if ($this->sortBy == "الأخبار الظاهرة")
                    $q->where('on_slider', true);
                else $q->whereIn('on_slider', [true, false]);
            });
    }

    public function SortBy($sort): void
    {
        $this->resetPage();
        $this->Search = null;
        $this->sortBy = $sort;
    }

}
