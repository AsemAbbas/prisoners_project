<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\PrisonerType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListPrisonerTypes extends Component
{
    use WithPagination;

    public object $PrisonerTypes_;
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
    public function createPrisonerType(): void
    {
        $validation = Validator::make($this->state, [
            "prisoner_type_name" => 'required'
        ])->validate();

        PrisonerType::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(PrisonerType $prisonerType): void
    {
        $this->showEdit = true;
        $this->PrisonerTypes_ = $prisonerType;
        $this->state = $prisonerType->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updatePrisonerType(): void
    {
        $validation = Validator::make($this->state, [
            "prisoner_type_name" => 'required'
        ])->validate();

        $this->PrisonerTypes_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->PrisonerTypes_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(PrisonerType $prisonerType): void
    {
        $this->PrisonerTypes_ = $prisonerType;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $PrisonerTypes = $this->getPrisonerTypesProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-prisoner-types', compact('PrisonerTypes'));
    }

    public function getPrisonerTypesProperty()
    {
        return PrisonerType::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('prisoner_type_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
