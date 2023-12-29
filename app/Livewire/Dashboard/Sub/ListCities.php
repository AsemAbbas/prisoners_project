<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\City;
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
            ->when(isset($this->Search), function ($query) {
                $query->where('city_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
