<?php


namespace App\Livewire\Dashboard\Main;

use App\Exports\PrisonerExport;
use App\Models\Belong;
use App\Models\City;
use App\Models\Prisoner;
use App\Models\PrisonerType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class FilteringAndExporting extends Component
{
    use WithPagination, WithFileUploads;

    public ?string $Search = null;
    public string $sortBy_ = 'id';
    public string $direction_ = 'asc';
    public ?string $town_search = null;
    public ?string $Take_ = null;
    public bool $Cubs = false;
    public bool $Elderly = false;
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
                'full_name' => true,
                'first_name' => true,
                'second_name' => true,
                'third_name' => true,
                'last_name' => true,
                'mother_name' => true,
                'nick_name' => true,
                'date_of_birth' => true,
                'age' => true,
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

    public function updatedCubs(): void
    {
        if ($this->Cubs) {
            $this->AdvanceSearch['dob_to'] = now()->format('Y-m-d');
        } else {
            $this->AdvanceSearch['dob_to'] = null;
        }
    }

    public function updatedElderly(): void
    {
        if ($this->Elderly) {
            $this->AdvanceSearch['dob_from'] = date("1900-01-01");
        } else {
            $this->AdvanceSearch['dob_from'] = null;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function emptyField(array $fields): void
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $this->AdvanceSearch)) {
                if ($field === "dob_from") {
                    $this->Elderly = false;
                }
                if ($field === "dob_to") {
                    $this->Cubs = false;
                }
                unset($this->AdvanceSearch[$field]);
            }
        }
    }

    public function updatedAdvanceSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $PrisonersCount = $this->getPrisonersProperty()->count();

        if (!empty($this->Take_)) {
            $Prisoners = $this->getPrisonersProperty()->take($this->Take_ > 500 ? 500 : ($this->Take_ < 0 ? 0 : $this->Take_))->get();
        } else {
            $Prisoners = $this->getPrisonersProperty()->paginate(10);
        }

        $CurrentUserCities = User::query()
            ->where('id', Auth::user()->id)
            ->with('City')->first()->toArray()['city'] ?? [];
        $cityIdArray = [];
        foreach ($CurrentUserCities as $subArray) {
            if (isset($subArray['pivot']['city_id'])) {
                $cityIdArray[] = $subArray['pivot']['city_id'];
            }
        }

        $Cities = City::query()
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('id', $cityIdArray);
            })
            ->with(['Town' => function ($q) {
                $q->when(isset($this->town_search), function ($q) {
                    $q->where('town_name', 'LIKE', '%' . $this->town_search . '%');
                });
                $q->when(!empty($this->AdvanceSearch['city']), function ($q) {
                    $cities = $this->AdvanceSearch['city'] ?? null;
                    $q->where('city_id', $cities);
                });
                $q->orderBy('town_name');
            }])
            ->orderBy('city_name')
            ->get();
        $Belongs = Belong::all();
        $PrisonerTypes = PrisonerType::all();

        if (isset($this->ExportData['selectPrisoner']))
            if (count(array_filter($this->ExportData['selectPrisoner'])) < 16)
                $this->SelectAllPrisoner = false;
            else $this->SelectAllPrisoner = true;

        if (isset($this->ExportData['selectArrest']))
            if (count(array_filter($this->ExportData['selectArrest'])) < 26)
                $this->SelectAllArrest = false;
            else $this->SelectAllArrest = true;

        return view('livewire.dashboard.main.filtering-and-exporting', compact('Prisoners', 'Belongs', 'PrisonerTypes', 'PrisonersCount', 'Cities'));
    }

    public function getPrisonersProperty(): \Illuminate\Database\Eloquent\Builder
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
            ->with(['City', 'PrisonerType', 'Arrest', 'RelativesPrisoner', 'FamilyIDNumber','OldArrest'])
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('city_id', $cityIdArray)
                    ->orWhereNull('city_id');
            })
            ->where(function ($q) {
                $q->when(isset($this->AdvanceSearch), function ($query) {
                    $query->when(!empty($this->AdvanceSearch['city']), function ($subQuery) {
                        $filteredCity = $this->AdvanceSearch['city'];
                        if (!empty($filteredCity)) {
                            $subQuery->where(function ($q) use ($filteredCity) {
                                $q->where('city_id', $filteredCity)
                                    ->when($filteredCity == "not_found", function ($q) {
                                        $q->orWhereNull('city_id');
                                    });
                            });
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['town']), function ($subQuery) {
                        $filteredTown = array_filter($this->AdvanceSearch['town']);
                        if (!empty($filteredTown)) {
                            $subQuery->where(function ($q) use ($filteredTown) {
                                $q->whereIn('town_id', array_keys($filteredTown))
                                    ->when(array_key_exists("not_found", $filteredTown), function ($q) {
                                        $q->orWhereNull('town_id');
                                    });
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
                    $query->when(isset($this->AdvanceSearch['social_type']), function ($subQuery) {
                        $filteredSocialType = array_filter($this->AdvanceSearch['social_type']);
                        if (!empty($filteredSocialType)) {
                            $subQuery->whereHas('Arrest', function ($q) use ($filteredSocialType) {
                                $q->whereIn('social_type', array_keys($filteredSocialType));
                            });
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['gender']), function ($subQuery) {
                        $filteredGender = array_filter($this->AdvanceSearch['gender']);
                        if (!empty($filteredGender)) {
                            $subQuery->whereIn('gender', array_keys($filteredGender));
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['special_case']), function ($subQuery) {
                        $filteredSpecialCase = array_filter($this->AdvanceSearch['special_case']);
                        if (!empty($filteredSpecialCase)) {
                            $subQuery->whereHas('Arrest', function ($subQuery) use ($filteredSpecialCase) {
                                foreach ($filteredSpecialCase as $key => $term) {
                                    $subQuery->where(function ($SubQuery) use ($key) {
                                        $SubQuery->where('special_case', 'LIKE', '%' . $key . '%');
                                    });
                                }
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['is_released']), function ($subQuery) {
                        $filtered_is_released = $this->AdvanceSearch['is_released'];
                        if (!empty($filtered_is_released)) {
                            $subQuery->where(function ($query) {
                                $query->orWhereHas('Arrest', function ($query) {
                                    $query->where('is_released', true);
                                });
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['judgment_in_lifetime']), function ($subQuery) {
                        $filtered_judgment_in_lifetime = $this->AdvanceSearch['judgment_in_lifetime'];
                        if (!empty($filtered_judgment_in_lifetime)) {
                            $subQuery->where(function ($query) {
                                $query->orWhereHas('Arrest', function ($query) {
                                    $query->where('arrest_type', 'محكوم')
                                        ->whereNotNull('judgment_in_lifetime')
                                        ->whereNot('judgment_in_lifetime', '0');
                                });
                            });
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['arrest_type']), function ($subQuery) {
                        $filteredArrestType = array_filter($this->AdvanceSearch['arrest_type']);
                        if (!empty($filteredArrestType)) {
                            $subQuery->whereHas('Arrest', function ($q) use ($filteredArrestType) {
                                $q->whereIn('arrest_type', array_keys($filteredArrestType));
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['dob_from']) && isset($this->AdvanceSearch['dob_to']), function ($subQuery) {
                        $subQuery->whereBetween('date_of_birth', [$this->AdvanceSearch['dob_from'], $this->AdvanceSearch['dob_to']]);
                    });
                    $query->when(isset($this->AdvanceSearch['updated_from']) && isset($this->AdvanceSearch['updated_to']), function ($subQuery) {
                        $subQuery->whereBetween('updated_at', [$this->AdvanceSearch['updated_from'], $this->AdvanceSearch['updated_to']]);
                    });
                    $query->when(isset($this->AdvanceSearch['doa_from']) && isset($this->AdvanceSearch['doa_to']), function ($subQuery) {

                        $subQuery->whereHas('Arrest', function ($q) {
                            $q->whereBetween('arrest_start_date', [
                                $this->AdvanceSearch['doa_from'],
                                $this->AdvanceSearch['doa_to']
                            ]);
                        });
                    });
                    $query->when(isset($this->AdvanceSearch['dor']), function ($subQuery) {
                        $subQuery->whereHas('Arrest', function ($q) {
                            $q->where('arrest_type', 'محكوم')
                                ->whereRaw('
                                    DATE_ADD(
                                        DATE_ADD(
                                            DATE_ADD(
                                                arrest_start_date,
                                                INTERVAL COALESCE(judgment_in_lifetime, 0) * 99 YEAR
                                            ),
                                            INTERVAL COALESCE(judgment_in_years, 0) YEAR
                                        ),
                                        INTERVAL COALESCE(judgment_in_months, 0) MONTH
                                    ) >= ?',
                                    [$this->AdvanceSearch['dor']]
                                );
                        });
                    });
                    $query->when(isset($this->AdvanceSearch['judgment_in_years_from']) && isset($this->AdvanceSearch['judgment_in_years_to']), function ($subQuery) {
                        $subQuery->whereHas('Arrest', function ($q) {
                            $q->where('arrest_type', "محكوم")
                                ->where(function ($query) {
                                    $query->whereNull('judgment_in_lifetime')
                                        ->orWhere('judgment_in_lifetime', '=', 0);
                                })
                                ->whereRaw(
                                    "(CAST(COALESCE(judgment_in_years, 0) AS UNSIGNED) +
                                        CAST(COALESCE(judgment_in_months, 0) AS UNSIGNED) / 12) BETWEEN ? AND ?",
                                    [
                                        (int)$this->AdvanceSearch['judgment_in_years_from'],
                                        (int)$this->AdvanceSearch['judgment_in_years_to']
                                    ]
                                );
                        });
                    });

                    $query->when(isset($this->AdvanceSearch['not_belong']), function ($subQuery) {
                        $filteredNotBelong = array_filter($this->AdvanceSearch['not_belong']);
                        if (!empty($filteredNotBelong)) {
                            $subQuery->whereDoesntHave('Arrest', function ($q) use ($filteredNotBelong) {
                                $q->whereIn('belong_id', array_keys($filteredNotBelong));
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['not_social_type']), function ($subQuery) {
                        $filteredNotSocialType = array_filter($this->AdvanceSearch['not_social_type']);
                        if (!empty($filteredNotSocialType)) {
                            $subQuery->whereDoesntHave('Arrest', function ($q) use ($filteredNotSocialType) {
                                $q->whereIn('social_type', array_keys($filteredNotSocialType));
                            });
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['not_gender']), function ($subQuery) {
                        $filteredNotGender = array_filter($this->AdvanceSearch['not_gender']);
                        if (!empty($filteredNotGender)) {
                            $subQuery->whereNotIn('gender', array_keys($filteredNotGender));
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['not_arrest_type']), function ($subQuery) {
                        $filteredNotArrestType = array_filter($this->AdvanceSearch['not_arrest_type']);
                        if (!empty($filteredNotArrestType)) {
                            $subQuery->whereDoesntHave('Arrest', function ($q) use ($filteredNotArrestType) {
                                $q->whereIn('arrest_type', array_keys($filteredNotArrestType));
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['not_special_case']), function ($subQuery) {
                        $filteredNotSpecialCase = array_filter($this->AdvanceSearch['not_special_case']);
                        if (!empty($filteredNotSpecialCase)) {
                            $subQuery->whereDoesntHave('Arrest', function ($subQuery) use ($filteredNotSpecialCase) {
                                foreach ($filteredNotSpecialCase as $key => $term) {
                                    $subQuery->where(function ($SubQuery) use ($key) {
                                        $SubQuery->where('special_case', 'LIKE', '%' . $key . '%');
                                    });
                                }
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['not_is_released']), function ($subQuery) {
                        $filteredNot_is_released = $this->AdvanceSearch['not_is_released'];
                        if (!empty($filteredNot_is_released)) {
                            $subQuery->where(function ($query) {
                                $query->whereDoesntHave('Arrest', function ($query) {
                                    $query->where('is_released', true);
                                });
                            });
                        }
                    });
                    $query->when(isset($this->AdvanceSearch['not_judgment_in_lifetime']), function ($subQuery) {
                        $filteredNot_judgment_in_lifetime = $this->AdvanceSearch['not_judgment_in_lifetime'];
                        if (!empty($filteredNot_judgment_in_lifetime)) {
                            $subQuery->where(function ($query) {
                                $query->whereDoesntHave('Arrest', function ($query) {
                                    $query->whereNotNull('judgment_in_lifetime');
                                });
                            });
                        }
                    });
                    $query->when(!empty($this->AdvanceSearch['not_prisoner_type']), function ($subQuery) {
                        $filteredNotPrisonerType = array_filter($this->AdvanceSearch['not_prisoner_type']);
                        if (!empty($filteredNotPrisonerType)) {
                            $subQuery->where(function ($query) use ($filteredNotPrisonerType) {
                                foreach ($filteredNotPrisonerType as $key => $case) {
                                    $query->whereDoesntHave('PrisonerType', function ($query) use ($key) {
                                        $query->where('prisoner_type_id', $key);
                                    });
                                }
                            });
                        }
                    });

                    $query->when(!empty($this->AdvanceSearch['missing']), function ($subQuery) {
                        $filteredMissing = array_filter($this->AdvanceSearch['missing']);
                        if (!empty($filteredMissing)) {
                            $subQuery->where(function ($query) use ($filteredMissing) {
                                foreach ($filteredMissing as $key => $missing) {
                                    if ($key == "identification_number") {
                                        $query->orWhereNull('identification_number');
                                    }
                                    if ($key == "dob") {
                                        $query->orWhereNull('date_of_birth');
                                    }
                                    if ($key == "doa") {
                                        $query->orWhereHas('Arrest', function ($q) {
                                            $q->whereNull('arrest_start_date');
                                        });
                                    }
                                    if ($key == "belong") {
                                        $query->orWhereHas('Arrest', function ($q) {
                                            $q->whereNull('belong_id');
                                        });
                                    }
                                    if ($key == "city") {
                                        $query->orWhereNull('city_id');
                                    }
                                    if ($key == "town") {
                                        $query->orWhereNull('town_id');
                                    }
                                }
                            });
                        }
                    });
                });
                $check = array_filter($this->AdvanceSearch);
                $q->when((!empty($check) && !isset($check['is_released'])) || empty($check), function ($query) {
                    $query->whereHas('Arrest', function ($query) {
                        $query->where('is_released', false);
                    });
                });
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
                        ->orWhereHas('Arrest', function ($subQuery) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $subQuery->where(function ($SubQuery) use ($term) {
                                    $SubQuery->where('special_case', 'LIKE', '%' . $term . '%');
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
                            $q->where('town_name', 'LIKE', $this->Search);
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
            ->when(isset($this->sortBy_) && isset($this->direction_), function ($q) {
                $orderDirection = $this->direction_ == 'asc' ? 'asc' : 'desc';
                if ($this->sortBy_ === 'arrest_start_date') {
                    $q->orderByRaw("ISNULL((SELECT $this->sortBy_ FROM arrests WHERE prisoner_id = prisoners.id)),(SELECT $this->sortBy_ FROM arrests WHERE prisoner_id = prisoners.id) $orderDirection");
                } else {
                    $q->orderByRaw("ISNULL($this->sortBy_), $this->sortBy_ $orderDirection");
                }
            });
    }

    public function showAdminExport(): void
    {
        $this->dispatch('Export');
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


        $Prisoner = $this->getPrisonersProperty()
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
                        } elseif ($key === 'father_arrested') {
                            $mappedData[$key] = $prisoner->FamilyIDNumber->where('relationship_name', 'اب')->pluck('id_number') ?? null;
                        } else {
                            $mappedData[$key] = $prisoner->$key ?? null;
                        }
                    }
                if (isset($selectArrest))
                    foreach ($selectArrest as $key) {
                        if ($key === 'belong_id') {
                            $mappedData[$key] = $prisoner->Arrest->Belong->belong_name ?? null;
                        } elseif ($key === 'is_released') {
                            $mappedData[$key] = $prisoner->Arrest->is_released ? 'نعم' : 'لا';
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

        $name = Carbon::parse(now())->format('Y-m-d H-i') . ' Records.xlsx';
        $Export = Excel::download(new PrisonerExport($Prisoner, $selectedColumns), $name);

        $this->ExportData = [];
        $this->SelectAllPrisoner = false;
        $this->SelectAllArrest = false;
        $this->dispatch('hideExport');
        return $Export;
    }

    public function editorExport(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $selectPrisoner = null;
        $selectArrest = null;

        if (isset($this->ExportData)) {

            $this->ExportData['selectPrisoner'] = [
                "identification_number" => true,
                "first_name" => true,
                "second_name" => true,
                "third_name" => true,
                "last_name" => true,
                "date_of_birth" => true,
                "gender" => true,
                "city_id" => true,
                "town_id" => true,
            ];

            if (isset($this->ExportData['selectPrisoner'])) {
                $selectPrisoner = array_filter(array_keys($this->ExportData['selectPrisoner'])) ?? null;
            }

            $this->ExportData['selectArrest'] = [
                "arrest_start_date" => true,
                "arrest_type" => true,
                "belong_id" => true,
                "judgment_in_lifetime" => true,
                "judgment_in_years" => true,
                "judgment_in_months" => true,
            ];
            if (isset($this->ExportData['selectArrest'])) {
                $selectArrest = array_filter(array_keys($this->ExportData['selectArrest'])) ?? null;
            }
        }
        $Prisoner = $this->getPrisonersProperty()->get()
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
                        } elseif ($key === 'father_arrested') {
                            $mappedData[$key] = $prisoner->FamilyIDNumber->where('relationship_name', 'اب')->pluck('id_number') ?? null;
                        } else {
                            $mappedData[$key] = $prisoner->$key ?? null;
                        }
                    }
                if (isset($selectArrest))
                    foreach ($selectArrest as $key) {
                        if ($key === 'belong_id') {
                            $mappedData[$key] = $prisoner->Arrest->Belong->belong_name ?? null;
                        } elseif ($key === 'is_released') {
                            $mappedData[$key] = $prisoner->Arrest->is_released ? 'نعم' : 'لا';
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
        $name = Carbon::parse(now())->format('Y-m-d H-i') . ' Records.xlsx';
        $Export = Excel::download(new PrisonerExport($Prisoner, $selectedColumns), $name);

        $this->ExportData = [];
        $this->SelectAllPrisoner = false;
        $this->SelectAllArrest = false;
        $this->dispatch('hideExport');
        return $Export;
    }
}
