<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Statistic;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListStatistics extends Component
{
    use WithPagination;

    public object $Statistics_;
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
    public function createStatistic(): void
    {
        $validation = Validator::make($this->state, [
            "statistic_number" => 'required',
            "statistic_type" => 'required'
        ])->validate();

        Statistic::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(Statistic $statistic): void
    {
        $this->showEdit = true;
        $this->Statistics_ = $statistic;
        $this->state = $statistic->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateStatistic(): void
    {
        $validation = Validator::make($this->state, [
            "statistic_number" => 'required',
            "statistic_type" => 'required'
        ])->validate();

        $this->Statistics_->update($validation);

        $this->dispatch('hideForm');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Statistics_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Statistic $statistic): void
    {
        $this->Statistics_ = $statistic;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Statistics = $this->getStatisticsProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-statistics', compact('Statistics'));
    }

    public function getStatisticsProperty()
    {
        return Statistic::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('statistic_number', 'LIKE', '%' . $this->Search . '%');
                $query->orWhere('statistic_type', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
