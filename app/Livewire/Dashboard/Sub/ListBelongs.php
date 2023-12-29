<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Belong;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListBelongs extends Component
{
    use WithPagination;

    public object $Belongs_;
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
    public function createBelong(): void
    {
        $validation = Validator::make($this->state, [
            "belong_name" => 'required'
        ])->validate();

        Belong::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(Belong $belong): void
    {
        $this->showEdit = true;
        $this->Belongs_ = $belong;
        $this->state = $belong->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateBelong(): void
    {
        $validation = Validator::make($this->state, [
            "belong_name" => 'required'
        ])->validate();

        $this->Belongs_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Belongs_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Belong $belong): void
    {
        $this->Belongs_ = $belong;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Belongs = $this->getBelongsProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-belongs', compact('Belongs'));
    }

    public function getBelongsProperty()
    {
        return Belong::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('belong_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
