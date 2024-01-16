<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\Statistic;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListStatistics extends Component
{
    use WithPagination, WithFileUploads;

    public object $Statistics_;
    public ?string $Search = null;
    public array $state = [];
    public bool $showEdit = false;
    public ?int $statistics_id;
    public ?int $statistics_key;
    public ?string $order_by;

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
        $lastStatistic = Statistic::query()->orderByDesc('order_by')->pluck('order_by')->first() ?? null;
        $order_by = !empty($lastStatistic) ? $lastStatistic + 1 : 1;

        $validation = Validator::make($this->state, [
            "statistic_number" => 'required',
            "statistic_type" => 'required'
        ])->validate();

        $validation['order_by'] = $order_by;

        if (isset($this->state['statistic_photo']))
            $validation['statistic_photo'] = $this->state['statistic_photo']->store('/', 'statistic_photo');

        Statistic::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function StatisticOrderBy($statistics_id, $statistics_key): void
    {
        $this->statistics_id = $statistics_id;
        $this->statistics_key = $statistics_key;
    }

    public function ChangeOrderBy(): void
    {
        Statistic::query()->find($this->statistics_id)->update([
            'order_by' => $this->order_by
        ]);

        $this->statistics_id = null;
        $this->statistics_key = null;
        $this->order_by = null;
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
        $rules = [
            "statistic_number" => 'required',
            "statistic_type" => 'required'
        ];

        if ($this->state['statistic_photo'] && $this->state['statistic_photo'] !== $this->Statistics_->statistic_photo) {
            $rules['statistic_photo'] = 'required|image';
        }

        $validation = Validator::make($this->state, $rules)->validate();

        if ($this->state['statistic_photo'] && $this->state['statistic_photo'] !== $this->Statistics_->statistic_photo) {
            $validation['statistic_photo'] = $this->state['statistic_photo']->store('/', 'statistic_photo');
        }
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
            ->orderBy('order_by')
            ->when(isset($this->Search), function ($query) {
                $query->where('statistic_number', 'LIKE', '%' . $this->Search . '%');
                $query->orWhere('statistic_type', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
