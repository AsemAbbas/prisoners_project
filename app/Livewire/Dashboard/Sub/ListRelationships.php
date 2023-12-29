<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Relationship;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListRelationships extends Component
{
    use WithPagination;

    public object $Relationships_;
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
    public function createRelationship(): void
    {
        $validation = Validator::make($this->state, [
            "relationship_name" => 'required'
        ])->validate();

        Relationship::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(Relationship $relationship): void
    {
        $this->showEdit = true;
        $this->Relationships_ = $relationship;
        $this->state = $relationship->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateRelationship(): void
    {
        $validation = Validator::make($this->state, [
            "relationship_name" => 'required'
        ])->validate();

        $this->Relationships_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Relationships_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Relationship $relationship): void
    {
        $this->Relationships_ = $relationship;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Relationships = $this->getRelationshipsProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-relationships', compact('Relationships'));
    }

    public function getRelationshipsProperty()
    {
        return Relationship::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('relationship_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
