<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Relationship;
use App\Models\RelativesPrisoner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListRelativesPrisoners extends Component
{
    use WithPagination;

    public object $RelativesPrisoners_;
    public ?string $Search = null;
    public array $state = [];
    public bool $showEdit = false;
    public ?int $prisoner_id = null;

    protected string $paginationTheme = 'bootstrap';

    public function addNew(): void
    {
        $this->showEdit = false;
        $this->state = [];
        $this->dispatch('showForm');
    }

    public function mount($prisoner_id = null): void
    {
        $this->prisoner_id = $prisoner_id;
    }

    /**
     * @throws ValidationException
     */
    public function createRelativesPrisoner(): void
    {
        $this->state['prisoner_id'] = $this->prisoner_id;
        $validation = Validator::make($this->state, [
            "prisoner_id" => 'required',
            "relationship_id" => 'required|in:' . $this->subTables()['Relationship'],
            "first_name" => 'required',
            "second_name" => 'required',
            "third_name" => 'required',
            "last_name" => 'required',
            "identification_number" => "required|unique:relatives_prisoners,identification_number",
        ])->validate();

        RelativesPrisoner::query()->create($validation);
        $this->dispatch('hideForm');
    }

    function subTables(): array
    {
        return [
            'Relationship' => Relationship::query()->pluck('id')->implode(','),
        ];
    }

    public function edit(RelativesPrisoner $relativesPrisoner): void
    {
        $this->showEdit = true;
        $this->RelativesPrisoners_ = $relativesPrisoner;
        $this->state = $relativesPrisoner->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateRelativesPrisoner(): void
    {
        $validation = Validator::make($this->state, [
            "prisoner_id" => 'required',
            "relationship_id" => 'required|in:' . $this->subTables()['Relationship'],
            "first_name" => 'required',
            "second_name" => 'required',
            "third_name" => 'required',
            "last_name" => 'required',
            "identification_number" => "required|unique:relatives_prisoners,identification_number,{$this->state['id']},id",
        ])->validate();

        $this->RelativesPrisoners_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->RelativesPrisoners_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(RelativesPrisoner $relativesPrisoner): void
    {
        $this->RelativesPrisoners_ = $relativesPrisoner;

        $this->dispatch('show_delete_modal');
    }

    public function render()
    {
        $Relationships = Relationship::all();
        $RelativesPrisoners = $this->getRelativesPrisonersProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-relatives-prisoners', compact('RelativesPrisoners', 'Relationships'));
    }

    public function getRelativesPrisonersProperty()
    {
        return RelativesPrisoner::query()
            ->with('Relationship')
            ->when(isset($this->prisoner_id), function ($query) {
                $query->where('prisoner_id', $this->prisoner_id);
            })
            ->when(isset($this->Search), function ($query) {
                $searchTerms = explode(' ', $this->Search);
                $query->where(function ($subQuery) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $subQuery->where(function ($nameSubQuery) use ($term) {
                            $nameSubQuery->where('first_name', 'LIKE', '%' . $term . '%')
                                ->orWhere('second_name', 'LIKE', '%' . $term . '%')
                                ->orWhere('third_name', 'LIKE', '%' . $term . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $term . '%');
                        });
                    }
                });
                $query->orWhereHas('Prisoner', function ($q) {
                    $searchTerms = explode(' ', $this->Search);
                    $q->where(function ($subQuery) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $subQuery->where(function ($nameSubQuery) use ($term) {
                                $nameSubQuery->where('first_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('second_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('third_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('last_name', 'LIKE', '%' . $term . '%');
                            });
                        }
                    });
                });
                $query->orWhere('identification_number', 'LIKE', '%' . $this->Search . '%');
                $query->orWhereHas('Relationship', function ($q) {
                    $q->where('relationship_name', 'LIKE', '%' . $this->Search . '%');
                });
            });
    }
}
