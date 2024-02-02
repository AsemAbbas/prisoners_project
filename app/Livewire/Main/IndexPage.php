<?php

namespace App\Livewire\Main;

use App\Models\City;
use App\Models\News;
use App\Models\Prisoner;
use App\Models\SocialMedia;
use App\Models\Statistic;
use App\Models\Town;
use App\Rules\PalestineIdValidationRule;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public array $search = [
        'identification_number' => '',
        'first_name' => '',
        'second_name' => '',
        'last_name' => '',
    ];

    public array $CitySearch = [
        'city_id' => '',
        'town_id' => '',
        'prisoner_name' => '',
    ];
    public ?object $Prisoners = null;

    public ?string $error_ms = null;

    public bool $show = false;


    protected string $paginationTheme = 'bootstrap';

    public function rules(): array
    {
        return [
            'search.identification_number' => ['required_without_all:search.first_name,search.second_name,search.last_name', new PalestineIdValidationRule],
            'search.first_name' => 'required_without_all:search.identification_number',
            'search.second_name' => 'required_without_all:search.identification_number',
            'search.last_name' => 'required_without_all:search.identification_number',
        ];
    }

    public function SearchPrisoners(): void
    {
        $this->validate();

        if (!empty(array_filter($this->search))) {
            $this->Prisoners = Prisoner::query()
                ->with('City', 'Arrest', 'RelativesPrisoner')
                ->where('identification_number', $this->search['identification_number'])
                ->orWhere(function ($q) {
                    $q->where('first_name', $this->search['first_name'])
                        ->where('second_name', 'like', '%' . $this->search['second_name'] . '%')
                        ->where(function ($q) {
                            $q->where('last_name', 'like', '%' . $this->search['last_name'] . '%')
                                ->orWhere('nick_name', 'like', '%' . $this->search['last_name'] . '%');
                        });
                })
                ->get();

            if ($this->Prisoners->isEmpty()) {
                $this->Prisoners = null;
                $this->error_ms = 'لا يوجد بيانات مشابهة';
                $this->addError('search', $this->error_ms);
            } else {
                $this->dispatch('show_prisoners_modal');
            }
        }

    }

    public function showSearchCityPrisoners(): void
    {
        $this->dispatch('show_city_prisoners_modal');
    }

    public function showDetails(): void
    {
        $this->show = true;
    }

    public function hideDetails(): void
    {
        $this->show = false;
    }

    public function render()
    {
        $News = News::query()
            ->orderBy('order_by')
            ->get();
        $Statistics = Statistic::query()
            ->orderBy('order_by')
            ->get();
        $SocialMedia = SocialMedia::all();

        $Cities = City::all()->sortBy('city_name');
        $Towns = Town::query()->where('city_id', $this->CitySearch['city_id'])->orderBy('town_name')->get();

        $CityPrisoners = Prisoner::query()
            ->whereHas('Arrest',function ($q){
                $q->where('is_released',false);
            })
            ->where('city_id', $this->CitySearch['city_id'])
            ->where('town_id', $this->CitySearch['town_id'])
            ->when(!empty($this->CitySearch['prisoner_name']), function ($query) {
                $searchTerms = explode(' ', $this->CitySearch['prisoner_name']);
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
            })
            ->orderBy('first_name')
            ->paginate(15);



        return view('livewire.main.index-page', compact('News', 'Statistics', 'SocialMedia', 'CityPrisoners', 'Towns', 'Cities'))
            ->layout('components.layouts.main');
    }

    public function updatedCitySearch(): void
    {
        $this->resetPage();
    }
}
