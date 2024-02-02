<?php

namespace App\Livewire\Dashboard\Main;

use App\Exports\PrisonerExport;
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
use Maatwebsite\Excel\Facades\Excel;

class ListPrisoners extends Component
{
    use WithPagination, WithFileUploads;

    public $ImportFile = null;
    public $failures = null;
    public ?string $file_message = null;
    public object $Prisoners_;
    public ?string $Search = null;
    public ?string $town_search = null;
    public array $ExportData = [];

    public bool $SelectAllPrisoner = false;
    public bool $SelectAllArrest = false;

    public array $PrisonerColumn = [
        'id' => 'الرقم الاساسي',
        'identification_number' => 'رقم الهوية',
        'full_name' => 'الاسم بالكامل',
        'first_name' => 'الاسم الاول',
        'second_name' => 'اسم الاب',
        'third_name' => 'اسم الجد',
        'last_name' => 'اسم العائلة',
        'mother_name' => 'اسم الأم',
        'nick_name' => 'اسم آخر للعائلة',
        'age' => 'العمر',
        'date_of_birth' => 'تاريخ الميلاد',
        'gender' => 'الجنس',
        'city_id' => 'المحافظة',
        'town_id' => 'البلدة',
        'prisoner_type' => 'تصنيف الأسير',
        'notes' => 'الملاحظات',

    ];
    public array $ArrestColumn = [
        'arrest_start_date' => 'تاريخ الاعتقال',
        'arrest_type' => 'نوع الاعتقال',
        'judgment_in_lifetime' => 'الحكم مؤبدات',
        'judgment_in_years' => 'الحكم سنوات',
        'judgment_in_months' => 'الحكم شهور',
        'belong_id' => 'الانتماء',
        'special_case' => 'حالات خاصة',
        'health_note' => 'وصف المرض',
        'social_type' => 'الحالة الإجتماعية',
        'wife_type' => 'عدد الزوجات',
        'number_of_children' => 'عدد الأبناء',
        'education_level' => 'المستوى التعليمي',
        'father_arrested' => 'أب معتقل',
        'mother_arrested' => 'أم معتقله',
        'husband_arrested' => 'زوج معتقل',
        'wife_arrested' => 'زوجة معتقله',
        'brother_arrested' => 'أخ معتقل',
        'sister_arrested' => 'أخت معتقله',
        'son_arrested' => 'ابن معتقل',
        'daughter_arrested' => 'ابنه معتقله',
        'first_phone_number' => 'رقم التواصل (واتس/تلجرام)',
        'first_phone_owner' => 'اسم صاحب الرقم (واتس/تلجرام)',
        'second_phone_number' => 'رقم التواصل الإضافي',
        'second_phone_owner' => 'اسم صاحب الرقم',
        'is_released' => 'مفرج عنه حالياً؟',
        'email' => 'البريد الإلكتروني',
    ];
    public array $AdvanceSearch = [];
    protected string $paginationTheme = 'bootstrap';

    public function updatedSelectAllPrisoner(): void
    {
        if ($this->SelectAllPrisoner) {
            $this->ExportData['selectPrisoner'] = [
                'id' => true,
                'identification_number' => true,
                'first_name' => true,
                'second_name' => true,
                'third_name' => true,
                'last_name' => true,
                'mother_name' => true,
                'nick_name' => true,
                'date_of_birth' => true,
                'gender' => true,
                'city_id' => true,
                'town_id' => true,
                'prisoner_type' => true,
                'notes' => true,
            ];
        } else $this->ExportData['selectPrisoner'] = [];
    }

    public function updatedSelectAllArrest(): void
    {
        if ($this->SelectAllArrest) {
            $this->ExportData['selectArrest'] = [
                'arrest_start_date' => true,
                'arrest_type' => true,
                'judgment_in_lifetime' => true,
                'judgment_in_years' => true,
                'judgment_in_months' => true,
                'belong_id' => true,
                'special_case' => true,
                'health_note' => true,
                'social_type' => true,
                'wife_type' => true,
                'number_of_children' => true,
                'education_level' => true,
                'father_arrested' => true,
                'mother_arrested' => true,
                'husband_arrested' => true,
                'wife_arrested' => true,
                'brother_arrested' => true,
                'sister_arrested' => true,
                'son_arrested' => true,
                'daughter_arrested' => true,
                'first_phone_number' => true,
                'first_phone_owner' => true,
                'second_phone_number' => true,
                'second_phone_owner' => true,
                'is_released' => true,
                'email' => true,
            ];
        } else $this->ExportData['selectArrest'] = [];
    }

    public function show(Prisoner $prisoner): void
    {
        $this->Prisoners_ = $prisoner;
        $this->dispatch('showPrisoner');
    }

    public function showAdvanceSearch(): void
    {
        $this->AdvanceSearch = [];
        $this->dispatch('showAdvanceSearch');
    }

    public function hideAdvanceSearch(): void
    {
        $this->getPrisonersProperty();
        $this->updatedSearch();
        $this->dispatch('hideAdvanceSearch');
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
            ->orderBy('id','ASC')
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('city_id', $cityIdArray)
                    ->orWhereNull('city_id');
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
                })
                    ->orWhere('identification_number', 'LIKE', $this->Search)
                    ->orWhere('id', 'LIKE', $this->Search)
                    ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                    ->orWhereHas('City', function ($q) {
                        $q->where('city_name', 'LIKE', '%' . $this->Search . '%');
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
            })
            ->when(isset($this->AdvanceSearch), function ($query) {
                $query->when(isset($this->AdvanceSearch['dob_from']) && isset($this->AdvanceSearch['dob_to']), function ($subQuery) {
                    $subQuery->whereBetween('date_of_birth', [$this->AdvanceSearch['dob_from'], $this->AdvanceSearch['dob_to']]);
                });
                $query->when(isset($this->AdvanceSearch['doa_from']) && isset($this->AdvanceSearch['doa_to']), function ($subQuery) {

                    $subQuery->whereHas('LastArrest', function ($q) {
                        $q->whereBetween('arrest_start_date', [
                            $this->AdvanceSearch['doa_from'],
                            $this->AdvanceSearch['doa_to']
                        ]);
                    });
                });
                $query->when(isset($this->AdvanceSearch['judgment_in_lifetime_from']) && isset($this->AdvanceSearch['judgment_in_lifetime_to']), function ($subQuery) {
                    $subQuery->whereHas('Arrest', function ($q) {
                        $q->whereRaw(
                            "CAST(judgment_in_lifetime AS UNSIGNED) BETWEEN ? AND ?",
                            [
                                (int)$this->AdvanceSearch['judgment_in_lifetime_from'],
                                (int)$this->AdvanceSearch['judgment_in_lifetime_to']
                            ]
                        );
                    });
                });
                $query->when(isset($this->AdvanceSearch['judgment_in_years_from']) && isset($this->AdvanceSearch['judgment_in_years_to']), function ($subQuery) {
                    $subQuery->whereHas('Arrest', function ($q) {
                        $q->whereRaw(
                            "CAST(judgment_in_years AS UNSIGNED) BETWEEN ? AND ?",
                            [
                                (int)$this->AdvanceSearch['judgment_in_years_from'],
                                (int)$this->AdvanceSearch['judgment_in_years_to']
                            ]
                        );
                    });
                });
                $query->when(!empty($this->AdvanceSearch['gender']), function ($subQuery) {
                    $filteredGender = array_filter($this->AdvanceSearch['gender']);
                    if (!empty($filteredGender)) {
                        $subQuery->whereIn('gender', array_keys($filteredGender));
                    }
                });
                $query->when(!empty($this->AdvanceSearch['city']), function ($subQuery) {
                    $filteredCity = array_filter($this->AdvanceSearch['city']);
                    if (!empty($filteredCity)) {
                        $subQuery->whereIn('city_id', array_keys($filteredCity));
                    }
                });
                $query->when(!empty($this->AdvanceSearch['town']), function ($subQuery) {
                    $filteredTown = array_filter($this->AdvanceSearch['town']);
                    if (!empty($filteredTown)) {
                        $subQuery->whereIn('town_id', array_keys($filteredTown));
                    }
                });
                $query->when(!empty($this->AdvanceSearch['prisoner_type']), function ($subQuery) {
                    $filteredPrisonerType = array_filter($this->AdvanceSearch['prisoner_type']);
                    if (!empty($filteredPrisonerType)) {
                        $subQuery->where(function ($query) use ($filteredPrisonerType) {
                            foreach ($filteredPrisonerType as $key => $case) {
                                $query->orWhereHas('PrisonerType', function ($query) use ($key) {
                                    $query->where('prisoner_type_id', $key);
                                });
                            }
                        });
                    }
                });
                $query->when(isset($this->AdvanceSearch['belong']), function ($subQuery) {
                    $filteredBelong = array_filter($this->AdvanceSearch['belong']);
                    if (!empty($filteredBelong)) {
                        $subQuery->whereHas('Arrest', function ($q) use ($filteredBelong) {
                            $q->whereIn('belong_id', array_keys($filteredBelong));
                        });
                    }
                });
                $query->when(isset($this->AdvanceSearch['social_type']), function ($subQuery) {
                    $filteredSocialType = array_filter($this->AdvanceSearch['social_type']);
                    if (!empty($filteredSocialType)) {
                        $subQuery->whereHas('Arrest', function ($q) use ($filteredSocialType) {
                            $q->whereIn('social_type', array_keys($filteredSocialType));
                        });
                    }
                });
                $query->when(isset($this->AdvanceSearch['special_case']), function ($subQuery) {
                    $filteredSpecialCase = array_filter($this->AdvanceSearch['special_case']);
                    if (!empty($filteredSpecialCase)) {
                        $subQuery->where(function ($query) use ($filteredSpecialCase) {
                            foreach ($filteredSpecialCase as $key => $case) {
                                $query->orWhereHas('Arrest', function ($query) use ($key) {
                                    $query->where('special_case', 'LIKE', '%' . $key . '%');
                                });
                            }
                        });
                    }
                });
            });
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedAdvanceSearch(): void
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
        $CurrentUserCities = User::query()
            ->where('id', Auth::user()->id)
            ->with('City')->first()->toArray()['city'] ?? [];
        $cityIdArray = [];
        foreach ($CurrentUserCities as $subArray) {
            if (isset($subArray['pivot']['city_id'])) {
                $cityIdArray[] = $subArray['pivot']['city_id'];
            }
        }
        $Prisoners = $this->getPrisonersProperty()->paginate(10);

        $Cities = City::all();
        $Towns = Town::query()->when(isset($this->town_search), function ($q) {
            $q->where('town_name', 'LIKE', '%' . $this->town_search . '%');
        })->get();
        $Belongs = Belong::all();
        $PrisonerTypes = PrisonerType::all();

        if (isset($this->ExportData['selectPrisoner']))
            if (count(array_filter($this->ExportData['selectPrisoner'])) < 15)
                $this->SelectAllPrisoner = false;
            else $this->SelectAllPrisoner = true;

        if (isset($this->ExportData['selectArrest']))
            if (count(array_filter($this->ExportData['selectArrest'])) < 26)
                $this->SelectAllArrest = false;
            else $this->SelectAllArrest = true;

        return view('livewire.dashboard.main.list-prisoners', compact('Prisoners', 'Belongs', 'PrisonerTypes', 'Cities', 'Towns'));
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

    public function ExportFile_(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->validate([
            'ExportData' => 'required'
        ]);
        $selectPrisoner = null;
        $selectArrest = null;

        if (isset($this->ExportData)) {

            if (isset($this->ExportData['selectPrisoner'])) {
                $selectPrisoner = array_filter(array_keys($this->ExportData['selectPrisoner'])) ?? null;
            }
            if (isset($this->ExportData['selectArrest'])) {
                $selectArrest = array_filter(array_keys($this->ExportData['selectArrest'])) ?? null;
            }
        }


        $Prisoner = Prisoner::query()
            ->with('Arrest', 'Town', 'City', 'PrisonerType', 'RelativesPrisoner')
            ->when(isset($this->ExportData), function ($query) use ($selectArrest, $selectPrisoner) {
                $query->when(isset($this->ExportData['dob_from']) && isset($this->ExportData['dob_to']), function ($subQuery) {
                    $subQuery->whereBetween('date_of_birth', [$this->ExportData['dob_from'], $this->ExportData['dob_to']]);
                });
                $query->when(isset($this->ExportData['doa_from']) && isset($this->ExportData['doa_to']), function ($subQuery) {
                    $subQuery->whereHas('Arrest', function ($q) {
                        $q->whereBetween('arrest_start_date', [
                            $this->ExportData['doa_from'],
                            $this->ExportData['doa_to']
                        ]);
                    });
                });
                $query->when(isset($this->ExportData['judgment_in_lifetime_from']) && isset($this->ExportData['judgment_in_lifetime_to']), function ($subQuery) {
                    $subQuery->whereHas('Arrest', function ($q) {
                        $q->whereRaw(
                            "CAST(judgment_in_lifetime AS UNSIGNED) BETWEEN ? AND ?",
                            [
                                (int)$this->ExportData['judgment_in_lifetime_from'],
                                (int)$this->ExportData['judgment_in_lifetime_to']
                            ]
                        );
                    });
                });
                $query->when(isset($this->ExportData['judgment_in_years_from']) && isset($this->ExportData['judgment_in_years_to']), function ($subQuery) {
                    $subQuery->whereHas('Arrest', function ($q) {
                        $q->whereRaw(
                            "CAST(judgment_in_years AS UNSIGNED) BETWEEN ? AND ?",
                            [
                                (int)$this->ExportData['judgment_in_years_from'],
                                (int)$this->ExportData['judgment_in_years_to']
                            ]
                        );
                    });
                });
                $query->when(!empty($this->ExportData['gender']), function ($subQuery) {
                    $filteredGender = array_filter($this->ExportData['gender']);
                    if (!empty($filteredGender)) {
                        $subQuery->whereIn('gender', array_keys($filteredGender));
                    }
                });
                $query->when(!empty($this->ExportData['city']), function ($subQuery) {
                    $filteredCity = array_filter($this->ExportData['city']);
                    if (!empty($filteredCity)) {
                        $subQuery->whereIn('city_id', array_keys($filteredCity));
                    }
                });
                $query->when(!empty($this->ExportData['town']), function ($subQuery) {
                    $filteredTown = array_filter($this->ExportData['town']);
                    if (!empty($filteredTown)) {
                        $subQuery->whereIn('town_id', array_keys($filteredTown));
                    }
                });
                $query->when(isset($this->ExportData['belong']), function ($subQuery) {
                    $filteredBelong = array_filter($this->ExportData['belong']);
                    if (!empty($filteredBelong)) {
                        $subQuery->whereHas('Arrest', function ($q) use ($filteredBelong) {
                            $q->whereIn('belong_id', array_keys($filteredBelong));
                        });
                    }
                });
                $query->when(isset($this->ExportData['social_type']), function ($subQuery) {
                    $filteredSocialType = array_filter($this->ExportData['social_type']);
                    if (!empty($filteredSocialType)) {
                        $subQuery->whereHas('Arrest', function ($q) use ($filteredSocialType) {
                            $q->whereIn('social_type', array_keys($filteredSocialType));
                        });
                    }
                });
                $query->when(isset($this->ExportData['special_case']), function ($subQuery) {
                    $filteredSpecialCase = array_filter($this->ExportData['special_case']);
                    if (!empty($filteredSpecialCase)) {
                        $subQuery->where(function ($query) use ($filteredSpecialCase) {
                            foreach ($filteredSpecialCase as $key => $case) {
                                $query->orWhereHas('Arrest', function ($query) use ($key) {
                                    $query->where('special_case', 'LIKE', '%' . $key . '%');
                                });
                            }
                        });
                    }
                });
                $query->when(!empty($this->ExportData['prisoner_type']), function ($subQuery) {
                    $filteredPrisonerType = array_filter($this->ExportData['prisoner_type']);
                    if (!empty($filteredPrisonerType)) {
                        $subQuery->where(function ($query) use ($filteredPrisonerType) {
                            foreach ($filteredPrisonerType as $key => $case) {
                                $query->orWhereHas('PrisonerType', function ($query) use ($key) {
                                    $query->where('prisoner_type_id', $key);
                                });
                            }
                        });
                    }
                });
            })
            ->get()
            ->map(function ($prisoner) use ($selectPrisoner, $selectArrest) {
                $mappedData = [];
                if (isset($selectPrisoner))
                    foreach ($selectPrisoner as $key) {
                        if ($key === 'city_id') {
                            $mappedData[$key] = $prisoner->City->city_name ?? null;
                        } elseif ($key === 'town_id') {
                            $mappedData[$key] = $prisoner->Town->town_name ?? null;
                        } elseif ($key === 'prisoner_type') {
                            $mappedData[$key] = implode(',', $prisoner->PrisonerType->pluck('prisoner_type_name')->toArray()) ?? null;
                        } elseif ($key === 'age') {
                            $mappedData[$key] = \Carbon\Carbon::parse($prisoner->date_of_birth)->diffInYears() ?? null;
                        } else {
                            $mappedData[$key] = $prisoner->$key ?? null;
                        }
                    }
                if (isset($selectArrest))
                    foreach ($selectArrest as $key) {
                        if ($key === 'belong_id') {
                            $mappedData[$key] = $prisoner->Arrest->Belong->belong_name ?? null;
                        } else {
                            $mappedData[$key] = $prisoner->Arrest->$key ?? null;
                        }
                    }

                return $mappedData;
            });

        if (!empty($selectPrisoner) && !empty($selectArrest))
            $selectedColumns = array_merge($selectPrisoner, $selectArrest);
        elseif (!empty($selectPrisoner))
            $selectedColumns = $selectPrisoner;
        elseif (!empty($selectArrest))
            $selectedColumns = $selectArrest;
        else $selectedColumns = null;

        $Export = Excel::download(new PrisonerExport($Prisoner, $selectedColumns), 'Prisoner.xlsx');

        $this->ExportData = [];
        $this->SelectAllPrisoner = false;
        $this->SelectAllArrest = false;
        $this->dispatch('hideImportExport');
        return $Export;
    }
}
