<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Health;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListHealths extends Component
{

    use WithPagination;

    public object $Healths_;
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
    public function createHealth(): void
    {
        $validation = Validator::make($this->state, [
            "health_name" => 'required'
        ])->validate();

        Health::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(Health $health): void
    {
        $this->showEdit = true;
        $this->Healths_ = $health;
        $this->state = $health->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateHealth(): void
    {
        $validation = Validator::make($this->state, [
            "health_name" => 'required'
        ])->validate();

        $this->Healths_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Healths_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Health $health): void
    {
        $this->Healths_ = $health;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Healths = $this->getHealthsProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-healths', compact('Healths'));
    }

    public function getHealthsProperty()
    {
        return Health::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('health_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
