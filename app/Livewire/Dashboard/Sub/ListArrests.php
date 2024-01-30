<?php
//
//namespace App\Livewire\Dashboard\Sub;
//
//use App\Enums\ArrestType;
//use App\Enums\RulingByMonths;
//use App\Enums\SocialType;
//use App\Enums\WifeType;
//use App\Models\Arrest;
//use App\Models\Belong;
//use App\Models\Health;
//use App\Models\Ruling;
//use Illuminate\Contracts\View\Factory;
//use Illuminate\Contracts\View\View;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\ValidationException;
//use Livewire\Component;
//use Livewire\WithPagination;
//
//class ListArrests extends Component
//{
//    use WithPagination;
//
//    public object $Arrests_;
//    public ?string $Search = null;
//    public array $state = [];
//    public bool $showEdit = false;
//    public ?int $prisoner_id = null;
//
//    protected string $paginationTheme = 'bootstrap';
//
//    public function addNew(): void
//    {
//        $this->showEdit = false;
//        $this->state = [];
//        $this->dispatch('showForm');
//    }
//
//    public function mount($prisoner_id = null): void
//    {
//        $this->prisoner_id = $prisoner_id;
//    }
//
//    /**
//     * @throws ValidationException
//     */
//    public function createArrest(): void
//    {
//        if (isset($this->state['social_type']) && $this->state['social_type'] == "أعزب") {
//            $this->state['wife_type'] = null;
//            $this->state['number_of_children'] = null;
//        }
//        if (isset($this->state['social_type']) && $this->state['social_type'] == "مطلق") {
//            $this->state['wife_type'] = null;
//        }
//
//        if (isset($this->state['arrest_type']) && $this->state['arrest_type'] != "محكوم") {
//            $this->state['judgment_in_lifetime'] = null;
//            $this->state['judgment_in_years'] = null;
//            $this->state['judgment_in_months'] = null;
//        }
//
//        $this->state['prisoner_id'] = $this->prisoner_id;
//        $validation = Validator::make($this->state, [
//            "prisoner_id" => 'required',
//            "arrest_start_date" => 'required',
//            "arrest_end_date" => 'nullable',
//            "arrest_type" => 'nullable|in:' . $this->subTables()['ArrestType'],
//            "judgment_in_lifetime" => 'nullable|integer|required_without_all:judgment_in_years,judgment_in_months',
//            "judgment_in_years" => 'nullable|integer|required_without_all:judgment_in_lifetime,judgment_in_months',
//            "judgment_in_months" => 'nullable|integer|required_without_all:judgment_in_lifetime,judgment_in_years',
//            'belong_id' => "nullable|in:" . $this->subTables()['Belongs'],
//            "health_id" => 'nullable|in:' . $this->subTables()['Health'],
//            'social_type' => "nullable|in:" . $this->subTables()['SocialType'],
//            'wife_type' => "nullable|in:" . $this->subTables()['WifeType'],
//            'number_of_children' => "nullable|integer",
//            'first_phone_number' => "nullable",
//            'second_phone_number' => "nullable",
//            "isReleased" => 'nullable',
//            "notes" => 'nullable',
//        ])->validate();
//
//        Arrest::query()->create($validation);
//        $this->dispatch('hideForm');
//    }
//
//    function subTables(): array
//    {
//        return [
//            'Health' => Health::query()->pluck('id')->implode(','),
//            'ArrestType' => join(",", array_column(ArrestType::cases(), 'value')),
//            'Belongs' => Belong::query()->pluck('id')->implode(','),
//            'SocialType' => join(",", array_column(SocialType::cases(), 'value')),
//            'WifeType' => join(",", array_column(WifeType::cases(), 'value')),
//        ];
//    }
//
//    public function edit(Arrest $arrest): void
//    {
//        $this->showEdit = true;
//        $this->Arrests_ = $arrest;
//        $this->state = $arrest->toArray();
//        $this->dispatch('showForm');
//    }
//
//    /**
//     * @throws ValidationException
//     */
//    public function updateArrest(): void
//    {
//
//        if (isset($this->state['social_type']) && $this->state['social_type'] == "أعزب") {
//            $this->state['wife_type'] = null;
//            $this->state['number_of_children'] = null;
//        }
//        if (isset($this->state['social_type']) && $this->state['social_type'] == "مطلق") {
//            $this->state['wife_type'] = null;
//        }
//
//        if (isset($this->state['arrest_type']) && $this->state['arrest_type'] != "محكوم") {
//            $this->state['judgment_in_lifetime'] = null;
//            $this->state['judgment_in_years'] = null;
//            $this->state['judgment_in_months'] = null;
//        }
//
//        $validation = Validator::make($this->state, [
//            "prisoner_id" => 'required',
//            "arrest_start_date" => 'required',
//            "arrest_end_date" => 'nullable',
//            "arrest_type" => 'nullable|in:' . $this->subTables()['ArrestType'],
//            "judgment_in_lifetime" => 'nullable|integer|required_without_all:judgment_in_years,judgment_in_months',
//            "judgment_in_years" => 'nullable|integer|required_without_all:judgment_in_lifetime,judgment_in_months',
//            "judgment_in_months" => 'nullable|integer|required_without_all:judgment_in_lifetime,judgment_in_years',
//            'belong_id' => "nullable|in:" . $this->subTables()['Belongs'],
//            "health_id" => 'nullable|in:' . $this->subTables()['Health'],
//            'social_type' => "nullable|in:" . $this->subTables()['SocialType'],
//            'wife_type' => "nullable|in:" . $this->subTables()['WifeType'],
//            'number_of_children' => "nullable",
//            'first_phone_number' => "nullable",
//            'second_phone_number' => "nullable",
//            "isReleased" => 'nullable',
//            "notes" => 'nullable',
//        ])->validate();
//
//        $this->Arrests_->update($validation);
//
//        $this->dispatch('hideForm');
//    }
//
//    public function updatedSearch(): void
//    {
//        $this->resetPage();
//    }
//
//    public function confirmDelete(): void
//    {
//        $this->Arrests_->delete();
//        $this->dispatch('hide_delete_modal');
//    }
//
//    public function delete(Arrest $arrest): void
//    {
//        $this->Arrests_ = $arrest;
//
//        $this->dispatch('show_delete_modal');
//    }
//
//    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
//    {
//        $Belongs = Belong::all();
//        $Arrests = $this->getArrestsProperty()->orderBy('arrest_start_date')->paginate(10);
//        return view('livewire.dashboard.sub.list-arrests', compact('Arrests', 'Belongs'));
//    }
//
//    public function getArrestsProperty()
//    {
//        if (isset($this->Search)) {
//            $this->Search = $this->replaceHamza($this->Search);
//            $this->Search = $this->replaceTaMarbuta($this->Search);
//        }
//
//        return Arrest::query()
//            ->with('Belong', 'Prisoner')
//            ->when(isset($this->prisoner_id), function ($query) {
//                $query->where('prisoner_id', $this->prisoner_id);
//            })
//            ->when(isset($this->Search), function ($query) {
//                $query->where('arrest_start_date', 'LIKE', '%' . $this->Search . '%');
//                $query->orWhere('arrest_end_date', 'LIKE', '%' . $this->Search . '%');
//                $query->orWhere('arrest_type', 'LIKE', '%' . $this->Search . '%');
//                $query->orWhere('wife_type', 'LIKE', '%' . $this->Search . '%');
//                $query->orWhere('social_type', 'LIKE', '%' . $this->Search . '%');
//                $query->orWhereHas('Prisoner', function ($q) {
//                    $searchTerms = explode(' ', $this->Search);
//                    $q->where(function ($subQuery) use ($searchTerms) {
//                        foreach ($searchTerms as $term) {
//                            $subQuery->where(function ($nameSubQuery) use ($term) {
//                                $nameSubQuery->where('first_name', 'LIKE', '%' . $term . '%')
//                                    ->orWhere('second_name', 'LIKE', '%' . $term . '%')
//                                    ->orWhere('third_name', 'LIKE', '%' . $term . '%')
//                                    ->orWhere('last_name', 'LIKE', '%' . $term . '%');
//                            });
//                        }
//                    });
//                });
//                $query->orWhereHas('Belong', function ($q) {
//                    $q->where('belong_name', 'LIKE', '%' . $this->Search . '%');
//                });
//            });
//    }
//
//    private function replaceHamza($text): array|string
//    {
//        return str_replace('أ', 'ا', $text);
//    }
//
//    private function replaceTaMarbuta($text): array|string
//    {
//        return str_replace('ة', 'ه', $text);
//    }
//
//}
