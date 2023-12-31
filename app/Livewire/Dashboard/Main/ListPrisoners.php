<?php

namespace App\Livewire\Dashboard\Main;

use App\Exports\PrisonerExport;
use App\Imports\PrisonerImport;
use App\Models\Belong;
use App\Models\City;
use App\Models\Health;
use App\Models\Prisoner;
use App\Models\PrisonerType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    public array $ExportData = [];

    public bool $SelectAllPrisoner = false;
    public bool $SelectAllArrest = false;

    public array $PrisonerColumn = [
        'id' => 'الرقم الاساسي',
        'identification_number' => 'رقم الهوية',
        'first_name' => 'الاسم الاول',
        'second_name' => 'اسم الاب',
        'third_name' => 'اسم الجد',
        'last_name' => 'اسم العائلة',
        'date_of_birth' => 'تاريخ الميلاد',
        'gender' => 'الجنس',
        'city_id' => 'المحافظة',
        'prisoner_type_id' => 'تصنيف الاسير',
        'single_parents' => 'وحيد والديه',
        'notes' => 'الملاحظات',

    ];
    public array $ArrestColumn = [
        'arrest_start_date' => 'بداية الاعتقال',
        'arrest_type' => 'نوع الاعتقال',
        'judgment_in_lifetime' => 'الحكم مؤبدات',
        'judgment_in_years' => 'الحكم سنوات',
        'judgment_in_months' => 'الحكم شهور',
        'belong_id' => 'الانتماء',
        'health_note' => ',وصف المرض',
        'social_type' => 'الحالة الإجتماعية',
        'wife_type' => 'عدد الزوجات',
        'number_of_children' => 'عدد الأبناء',
        'first_phone_number' => 'رقم التواصل (واتس/تلجرام)',
        'second_phone_number' => 'رقم التواصل الإضافي',
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
                'date_of_birth' => true,
                'gender' => true,
                'city_id' => true,
                'prisoner_type_id' => true,
                'single_parents' => true,
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
                'health_note' => true,
                'social_type' => true,
                'wife_type' => true,
                'number_of_children' => true,
                'first_phone_number' => true,
                'second_phone_number' => true,
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

        if (isset($this->Search)) {
            $this->Search = $this->replaceHamza($this->Search);
            $this->Search = $this->replaceTaMarbuta($this->Search);
            $this->Search = $this->removeDiacritics($this->Search);
        }

        return Prisoner::query()
            ->with(['City', 'PrisonerType', 'Arrest', 'RelativesPrisoner'])
            ->orderByDesc('created_at')
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
                    ->orWhere('identification_number', 'LIKE', '%' . $this->Search . '%')
                    ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                    ->orWhereHas('City', function ($q) {
                        $q->where('city_name', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('PrisonerType', function ($q) {
                        $q->where('prisoner_type_name', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('Arrest', function ($q) {
                        $q->where('arrest_type', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('Arrest', function ($q) {
                        $q->where('social_type', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('Arrest', function ($q) {
                        $q->where('wife_type', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->whereHas('Arrest.Belong', function ($q) {
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
                $query->when(!empty($this->AdvanceSearch['prisoner_type']), function ($subQuery) {
                    $filteredPrisonerType = array_filter($this->AdvanceSearch['prisoner_type']);
                    if (!empty($filteredPrisonerType)) {
                        $subQuery->whereIn('prisoner_type_id', array_keys($filteredPrisonerType));
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
            });
    }

    private function replaceHamza($text): array|string
    {
        return str_replace('أ', 'ا', $text);
    }

    private function replaceTaMarbuta($text): array|string
    {
        return str_replace('ة', 'ه', $text);
    }

    function removeDiacritics($text): array|string
    {
        $diacritics = [
            'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ّ', 'ْ', 'ٓ', 'ٰ', 'ٔ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ', '۟', 'ۦ', 'ۧ', 'ۨ', '۪', '۫', '۬', 'ۭ', 'ࣧ', '࣪', 'ࣱ', 'ࣲ', 'ࣳ', 'ࣴ', 'ࣵ', 'ࣶ', 'ࣷ', 'ࣸ', 'ࣹ', 'ࣻ', 'ࣼ', 'ࣽ', 'ࣾ', 'ؐ', 'ؑ', 'ؒ', 'ؓ', 'ؔ', 'ؕ', 'ؖ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ'
        ];

        return str_replace($diacritics, '', $text);
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
        $Cities = City::all();
        $Belongs = Belong::all();
        $PrisonerTypes = PrisonerType::all();
        $Prisoners = $this->getPrisonersProperty()->paginate(10);

        if (isset($this->ExportData['selectPrisoner']))
            if (count(array_filter($this->ExportData['selectPrisoner'])) < 12)
                $this->SelectAllPrisoner = false;
            else $this->SelectAllPrisoner = true;

        if (isset($this->ExportData['selectArrest']))
            if (count(array_filter($this->ExportData['selectArrest'])) < 12)
                $this->SelectAllArrest = false;
            else $this->SelectAllArrest = true;

        return view('livewire.dashboard.main.list-prisoners', compact('Prisoners', 'Belongs', 'PrisonerTypes', 'Cities'));
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
            ->with('Arrest', 'City', 'PrisonerType', 'RelativesPrisoner')
            ->when(isset($this->ExportData), function ($query) use ($selectArrest, $selectPrisoner) {
                $query->when(isset($this->ExportData['dob_from']) && isset($this->ExportData['dob_to']), function ($subQuery) {
                    $subQuery->whereBetween('date_of_birth', [$this->ExportData['dob_from'], $this->ExportData['dob_to']]);
                });
                $query->when(isset($this->ExportData['doa_from']) && isset($this->ExportData['doa_to']), function ($subQuery) {
                    $subQuery->whereHas('LastArrest', function ($q) {
                        $q->whereBetween('arrest_start_date', [
                            $this->ExportData['doa_from'],
                            $this->ExportData['doa_to']
                        ]);
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
                $query->when(!empty($this->ExportData['prisoner_type']), function ($subQuery) {
                    $filteredPrisonerType = array_filter($this->ExportData['prisoner_type']);
                    if (!empty($filteredPrisonerType)) {
                        $subQuery->whereIn('prisoner_type_id', array_keys($filteredPrisonerType));
                    }
                });
                $query->when(isset($this->ExportData['belong']), function ($subQuery) {
                    $filteredBelong = array_filter($this->ExportData['belong']);
                    if (!empty($filteredBelong)) {
                        $subQuery->whereHas('LastArrest', function ($q) use ($filteredBelong) {
                            $q->whereIn('belong_id', array_keys($filteredBelong));
                        });
                    }
                });
                $query->when(isset($this->ExportData['social_type']), function ($subQuery) {
                    $filteredSocialType = array_filter($this->ExportData['social_type']);
                    if (!empty($filteredSocialType)) {
                        $subQuery->whereHas('LastArrest', function ($q) use ($filteredSocialType) {
                            $q->whereIn('social_type', array_keys($filteredSocialType));
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
                        } elseif ($key === 'prisoner_type_id') {
                            $mappedData[$key] = $prisoner->PrisonerType->prisoner_type_name ?? null;
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


        $selectedColumns = array_merge($selectPrisoner, $selectArrest);

        $Export = Excel::download(new PrisonerExport($Prisoner, $selectedColumns), 'Prisoner.xlsx');

        $this->ExportData = [];
        $this->SelectAllPrisoner = false;
        $this->SelectAllArrest = false;
        $this->dispatch('hideImportExport');
        return $Export;
    }

}
