<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\NewsType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
class ListNewsTypes extends Component
{
    use WithPagination;

    public object $NewsTypes_;
    public ?string $Search = null;
    public array $state = [];
    public bool $showEdit = false;

    protected string $paginationTheme = 'bootstrap';

    public function addNew(): void
    {
        $this->showEdit = false;
        $this->state = [];
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function createNewsType(): void
    {
        $validation = Validator::make($this->state, [
            "news_type_name" => 'required',
            "news_type_color" => 'required|unique:news_types',
        ])->validate();

        NewsType::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(NewsType $newsType): void
    {
        $this->showEdit = true;
        $this->NewsTypes_ = $newsType;
        $this->state = $newsType->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateNewsType(): void
    {
        $validation = Validator::make($this->state, [
            "news_type_name" => 'required',
            "news_type_color" => "required|unique:news_types,news_type_color,{$this->state["id"]},id,deleted_at,NULL",
        ])->validate();

        $this->NewsTypes_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->NewsTypes_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(NewsType $type): void
    {
        $this->NewsTypes_ = $type;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $NewsTypes = $this->getNewsTypesProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-news-types', compact('NewsTypes'));
    }

    public function getNewsTypesProperty()
    {
        return NewsType::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('news_type_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
