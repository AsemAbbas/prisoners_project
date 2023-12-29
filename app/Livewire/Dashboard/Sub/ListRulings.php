<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Ruling;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;
class ListRulings extends Component
{
    use WithPagination;

    public object $Rulings_;
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
    public function createRuling(): void
    {
        $validation = Validator::make($this->state, [
            "ruling_name" => 'required'
        ])->validate();

        Ruling::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(Ruling $ruling): void
    {
        $this->showEdit = true;
        $this->Rulings_ = $ruling;
        $this->state = $ruling->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateRuling(): void
    {
        $validation = Validator::make($this->state, [
            "ruling_name" => 'required'
        ])->validate();

        $this->Rulings_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Rulings_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Ruling $ruling): void
    {
        $this->Rulings_ = $ruling;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Rulings = $this->getRulingsProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-rulings', compact('Rulings'));
    }

    public function getRulingsProperty()
    {
        return Ruling::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('ruling_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
