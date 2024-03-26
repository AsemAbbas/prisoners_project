<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\City;
use App\Models\Town;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListCities extends Component
{
    use WithPagination;

    public object $Cities_;
    public object $Towns;
    public ?object $EditTown;
    public bool $showEditTown = false;
    public bool $showCreateTown = false;
    public int $city_id;
    public ?string $townKey = null;
    public ?string $newTownName = null;
    public ?string $TownSearch = null;
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
    public function createCity(): void
    {
        $validation = Validator::make($this->state, [
            "city_name" => 'required'
        ])->validate();

        City::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(City $city): void
    {
        $this->showEdit = true;
        $this->Cities_ = $city;
        $this->state = $city->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateCity(): void
    {
        $validation = Validator::make($this->state, [
            "city_name" => 'required'
        ])->validate();

        $this->Cities_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTownSearch(): void
    {
        $this->showTowns($this->city_id);
    }

    public function showTowns($id): void
    {
        $this->showEditTown = false;
        $this->EditTown = null;
        $this->newTownName = null;

        $this->city_id = $id;
        $Towns = Town::query()
            ->where('city_id', $id)
            ->when(isset($this->TownSearch), function ($q) {
                $q->where('town_name', 'LIKE', '%' . $this->TownSearch . '%');
            })
            ->orderBy('town_name')
            ->get();
        $this->Towns = $Towns;

        $this->dispatch('showTowns');
    }

    public function confirmDelete(): void
    {
        $this->Cities_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(City $city): void
    {
        $this->Cities_ = $city;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Cities = $this->getCitiesProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-cities', compact('Cities'));
    }

    public function getCitiesProperty()
    {
        return City::query()
            ->with('Town')
            ->when(isset($this->Search), function ($query) {
                $query->where('city_name', 'LIKE', '%' . $this->Search . '%');
            })
            ->orderBy('city_name');
    }

    public function deleteTown($town_id, $city_id): void
    {
        $city = $city_id;
        Town::query()->find($town_id)->delete();
        $this->reset($this->Towns);
        $this->Towns = Town::query()->where('city_id', $city)->get();
    }

    public function editTown($town_id): void
    {
        $this->showEditTown = true;
        $this->EditTown = Town::query()->where('id', $town_id)->first();
        $this->newTownName = $this->EditTown->town_name;
    }

    public function newTown(): void
    {
        $this->showCreateTown = true;
        $this->newTownName = null;
    }

    public function updateTown(): void
    {
        $this->validate(['newTownName' => 'unique:towns,town_name,NULL,id,deleted_at,NULL'], ['newTownName.unique' => 'هذه البلدة موجودة مسبقاً']);
        $this->EditTown->update(['town_name' => $this->newTownName]);
        $this->Towns = Town::query()->where('city_id', $this->EditTown->city_id)->get();
        $this->newTownName = null;
        $this->EditTown = null;
        $this->showEditTown = false;
    }

    public function createTown(): void
    {
        $this->validate(['newTownName' => 'required|string|unique:towns,town_name,NULL,id,deleted_at,NULL'], ['newTownName.required' => 'حقل اسم البلدة مطلوب','newTownName.unique' => 'هذه البلدة موجودة مسبقاً']);
        Town::query()->create(['town_name' => $this->newTownName, 'city_id' => $this->city_id]);
        $this->Towns = Town::query()->where('city_id', $this->city_id)->get();
        $this->newTownName = null;
        $this->showCreateTown = false;
    }
}
