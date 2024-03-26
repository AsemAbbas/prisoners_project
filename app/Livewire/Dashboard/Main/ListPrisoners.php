<?php

namespace App\Livewire\Dashboard\Main;

use App\Imports\PrisonerImport;
use App\Models\Belong;
use App\Models\City;
use App\Models\Prisoner;
use App\Models\PrisonerType;
use App\Models\Town;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListPrisoners extends Component
{
    use WithPagination, WithFileUploads;

    public $ImportFile = null;
    public $failures = null;
    public ?string $file_message = null;
    public object $Prisoners_;
    public ?string $Search = null;
    public bool $IsReleased = false;
    public bool $HasCity = false;
    public ?string $town_search = null;

    protected string $paginationTheme = 'bootstrap';


    public function show(Prisoner $prisoner): void
    {
        $this->Prisoners_ = $prisoner;
        $this->dispatch('showPrisoner');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedHasCity(): void
    {
        $this->resetPage();
    }

    public function updatedIsReleased(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Prisoners_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(Prisoner $prisoner): void
    {
        $this->Prisoners_ = $prisoner;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $Prisoners = $this->getPrisonersProperty()->paginate(10);

        $Cities = City::all();
        $Towns = Town::query()
            ->when(isset($this->town_search), function ($q) {
                $q->where('town_name', 'LIKE', '%' . $this->town_search . '%');
            })
            ->get();
        $Belongs = Belong::all();
        $PrisonerTypes = PrisonerType::all();

        return view('livewire.dashboard.main.list-prisoners', compact('Prisoners', 'Belongs', 'PrisonerTypes', 'Cities', 'Towns'));
    }

    public function getPrisonersProperty()
    {
        $CurrentUserCities = User::query()
            ->where('id', Auth::user()->id)
            ->with('City')->first()->toArray()['city'] ?? [];
        $cityIdArray = [];
        foreach ($CurrentUserCities as $subArray) {
            if (isset($subArray['pivot']['city_id'])) {
                $cityIdArray[] = $subArray['pivot']['city_id'];
            }
        }
        return Prisoner::query()
            ->with(['City', 'PrisonerType', 'Arrest', 'RelativesPrisoner', 'FamilyIDNumber'])
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('city_id', $cityIdArray)
                    ->orWhereNull('city_id');
            })
            ->where(function ($q) {
                $q->when(isset($this->Search), function ($query) {
                    $searchTerms = explode(' ', $this->Search);
                    $query->where(function ($subQuery) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $subQuery->where(function ($nameSubQuery) use ($term) {
                                $nameSubQuery->where('first_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('second_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('third_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('last_name', 'LIKE', '%' . $term . '%')
                                    ->orWhere('nick_name', 'LIKE', '%' . $term . '%');
                            });
                        }
                    })
                        ->orWhere('identification_number', 'LIKE', $this->Search)
                        ->orWhere('id', 'LIKE', $this->Search)
                        ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                        ->orWhereHas('City', function ($q) {
                            $q->where('city_name', 'LIKE', '%' . $this->Search . '%');
                        })
                        ->orWhereHas('Town', function ($q) {
                            $q->where('town_name', 'LIKE', '%' . $this->Search . '%');
                        })
                        ->orWhereHas('PrisonerType', function ($q) {
                            $q->where('prisoner_type_name', 'LIKE', '%' . $this->Search . '%');
                        })
                        ->orWhereHas('Arrest', function ($q) {
                            $q->where('arrest_type', 'LIKE', '%' . $this->Search . '%');
                            $q->orWhere('social_type', 'LIKE', '%' . $this->Search . '%');
                            $q->orWhere('wife_type', 'LIKE', '%' . $this->Search . '%');
                        })
                        ->orWhereHas('Arrest.Belong', function ($q) {
                            $q->where('belong_name', 'LIKE', '%' . $this->Search . '%');
                        });
                });
            })
            ->when($this->IsReleased, function ($q) {
                $q->whereHas('Arrest', function ($query) {
                    $query->where('is_released', true)
                        ->orWhere('is_released', false)
                        ->orWhereNull('is_released');

                });
            })
            ->when(!$this->IsReleased, function ($q) {
                $q->whereHas('Arrest', function ($query) {
                    $query->where('is_released', false);

                });
            })
            ->when($this->HasCity, function ($q) {
                $q->whereNull('city_id');
            })
            ->when(!$this->HasCity, function ($q) {
                $q->whereNotNull('city_id');
            })
            ->orderBy('id', 'ASC');

    }

    public function ImportExport(): void
    {
        $this->dispatch('ImportExport');
    }

    public function ImportFile_()
    {
        $this->validate([
            'ImportFile' => 'required|mimes:xlsx,xls'
        ]);

        $importedFile = $this->ImportFile->store('import');

        $import = new PrisonerImport();
        $import->import($importedFile);
        if ($import->failures()->isNotEmpty()) {
            $this->reset(['ImportFile']);
            return back()->withFailures($import->failures());
        }
        $this->dispatch('hideImportExport', ['message' => 'تم الاستيراد بنجاح!']);
        $this->reset(['ImportFile']);
    }
}
