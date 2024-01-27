<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\ArrestType;
use App\Enums\DefaultEnum;
use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\SocialType;
use App\Enums\SpecialCase;
use App\Enums\SuggestionStatus;
use App\Enums\WifeType;
use App\Models\ArrestConfirm;
use App\Models\Belong;
use App\Models\City;
use App\Models\OldArrestConfirm;
use App\Models\OldArrestSuggestion;
use App\Models\Prisoner;
use App\Models\PrisonerConfirm;
use App\Models\PrisonerSuggestion;
use App\Models\PrisonerType;
use App\Models\Relationship;
use App\Models\Town;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListPrisonerSuggestions extends Component
{
    use WithPagination;

    public object $Suggestions_;
    public object $Prisoner_;
    public bool $Exist = false;
    public bool $SelectAllPrisoners = false;
    public bool $SelectAllPrisonersArrest = false;

    public ?string $Search = null;
    public ?string $sortBy = null;
    public array $selectAccepted = [];
    public array $selectAcceptedArrest = [];
    public array $selectAcceptedSuggestionOldArrest = [];
    public array $selectAcceptedPrisonerOldArrest = [];

    public array $prisonerColumns = [];
    public array $arrestColumns = [];
    public array $oldArrestColumns = [];


    protected string $paginationTheme = 'bootstrap';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAllPrisoners(): void
    {
        if ($this->SelectAllPrisoners) {
            $this->selectAccepted = [
                'identification_number' => !$this->Exist,
                'first_name' => true,
                'second_name' => true,
                'third_name' => true,
                'last_name' => true,
                'mother_name' => true,
                'date_of_birth' => true,
                'gender' => true,
                'city_id' => true,
                'town_id' => true,
                'notes' => true,
            ];
        } else  $this->selectAccepted = [];
    }

    public function updatedSelectAllPrisonersArrest(): void
    {
        if ($this->SelectAllPrisonersArrest) {
            $this->selectAcceptedArrest = [
                'prisoner_id' => true,
                'arrest_start_date' => true,
                'arrest_type' => true,
                'judgment_in_lifetime' => true,
                'judgment_in_years' => true,
                'judgment_in_months' => true,
                'belong_id' => true,
                'special_case' => true,
                'social_type' => true,
                'wife_type' => true,
                'number_of_children' => true,
                'education_level' => true,
                'health_note' => true,
                'father_arrested' => true,
                'mother_arrested' => true,
                'husband_arrested' => true,
                'wife_arrested' => true,
                'brother_arrested' => true,
                'sister_arrested' => true,
                'son_arrested' => true,
                'daughter_arrested' => true,
                'first_phone_owner' => true,
                'first_phone_number' => true,
                'second_phone_owner' => true,
                'second_phone_number' => true,
                'email' => true,
            ];
        } else  $this->selectAcceptedArrest = [];
    }

    public function Accept(PrisonerSuggestion $prisonerSuggestion): void
    {

        $this->selectAccepted = [];
        $this->selectAcceptedArrest = [];
        $this->selectAcceptedSuggestionOldArrest = [];
        $this->selectAcceptedPrisonerOldArrest = [];

        $this->prisonerColumns = [];
        $this->arrestColumns = [];
        $this->oldArrestColumns = [];

        $this->SelectAllPrisoners = false;
        $this->SelectAllPrisonersArrest = false;

        $this->Suggestions_ = $prisonerSuggestion;
        if (isset($this->Suggestions_->prisoner_id))
            $this->Prisoner_ = Prisoner::query()->with('OldArrest', 'Arrest', 'Arrest.Belong', 'Town', 'City')->where('id', $this->Suggestions_->prisoner_id)->first() ?? null;

        $Suggestion_identification_number = $prisonerSuggestion->identification_number;

        $this->Exist = Prisoner::query()
            ->where('identification_number', $Suggestion_identification_number)
            ->exists();

        $this->prisonerColumns = [
            'رقم الهوية:' =>
                [
                    'name' => 'identification_number',
                    'suggestion' => $this->Suggestions_->identification_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->identification_number ?? 'لا يوجد',
                ],
            'الاسم الأول:' =>
                [
                    'name' => 'first_name',
                    'suggestion' => $this->Suggestions_->first_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->first_name ?? 'لا يوجد',
                ],
            'اسم الأب:' =>
                [
                    'name' => 'second_name',
                    'suggestion' => $this->Suggestions_->second_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->second_name ?? 'لا يوجد',
                ],
            'اسم الجد:' =>
                [
                    'name' => 'third_name',
                    'suggestion' => $this->Suggestions_->third_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->third_name ?? 'لا يوجد',
                ],
            'اسم العائلة:' =>
                [
                    'name' => 'last_name',
                    'suggestion' => $this->Suggestions_->last_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->last_name ?? 'لا يوجد',
                ],
            'اسم الأم:' =>
                [
                    'name' => 'mother_name',
                    'suggestion' => $this->Suggestions_->mother_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->mother_name ?? 'لا يوجد',
                ],
            'تاريخ الميلاد:' =>
                [
                    'name' => 'date_of_birth',
                    'suggestion' => $this->Suggestions_->date_of_birth ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->date_of_birth ?? 'لا يوجد',
                ],
            'الجنس:' =>
                [
                    'name' => 'gender',
                    'suggestion' => $this->Suggestions_->gender ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->gender ?? 'لا يوجد',
                ],
            'المحافظة:' =>
                [
                    'name' => 'city_id',
                    'suggestion' => $this->Suggestions_->City->city_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->City->city_name ?? 'لا يوجد',
                ],
            'البلدة:' =>
                [
                    'name' => 'town_id',
                    'suggestion' => $this->Suggestions_->Town->town_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Town->town_name ?? 'لا يوجد',
                ],
            'الملاحظات:' =>
                [
                    'name' => 'notes',
                    'suggestion' => $this->Suggestions_->notes ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->notes ?? 'لا يوجد',
                ],
        ];


        $this->arrestColumns = [
            'تاريخ الإعتقال:' =>
                [
                    'name' => 'arrest_start_date',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->arrest_start_date ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_start_date ?? 'لا يوجد',
                ],
            'نوع الإعتقال:' =>
                [
                    'name' => 'arrest_type',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->arrest_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_type ?? 'لا يوجد',
                ],
            'الحكم مؤبدات:' =>
                [
                    'name' => 'judgment_in_lifetime',
                    'suggestion' => !empty($this->Suggestions_->ArrestSuggestion->judgment_in_lifetime) ? $this->Suggestions_->ArrestSuggestion->judgment_in_lifetime : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_lifetime) ? $this->Prisoner_->Arrest->judgment_in_lifetime : 'لا يوجد',
                ],
            'الحكم سنوات:' =>
                [
                    'name' => 'judgment_in_years',
                    'suggestion' => !empty($this->Suggestions_->ArrestSuggestion->judgment_in_years) ? $this->Suggestions_->ArrestSuggestion->judgment_in_years : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_years) ? $this->Prisoner_->Arrest->judgment_in_years : 'لا يوجد',

                ],
            'الحكم أشهر:' =>
                [
                    'name' => 'judgment_in_months',
                    'suggestion' => !empty($this->Suggestions_->ArrestSuggestion->judgment_in_months) ? $this->Suggestions_->ArrestSuggestion->judgment_in_months : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_months) ? $this->Prisoner_->Arrest->judgment_in_months : 'لا يوجد',

                ],
            'الإنتماء:' =>
                [
                    'name' => 'belong_id',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->Belong->belong_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->Belong->belong_name ?? 'لا يوجد',
                ],
            'حالة خاصة:' =>
                [
                    'name' => 'special_case',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->special_case ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->special_case ?? 'لا يوجد',
                ],
            'الحالة الإجتماعية:' =>
                [
                    'name' => 'social_type',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->social_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->social_type ?? 'لا يوجد',
                ],
            'عدد الزوجات:' =>
                [
                    'name' => 'wife_type',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->wife_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->wife_type ?? 'لا يوجد',
                ],
            'عدد الأبناء:' =>
                [
                    'name' => 'number_of_children',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->number_of_children ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->number_of_children ?? 'لا يوجد',
                ],
            'المستوى التعليمي:' =>
                [
                    'name' => 'education_level',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->education_level ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->education_level ?? 'لا يوجد',
                ],
            'وصف المرض:' =>
                [
                    'name' => 'health_note',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->health_note ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->health_note ?? 'لا يوجد',
                ],
            'أب معتقل:' =>
                [
                    'name' => 'father_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->father_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->father_arrested ? 'نعم' : 'لا'),
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->father_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->father_arrested ? 'نعم' : 'لا') : null,
                ],
            'أم معتقله:' =>
                [
                    'name' => 'mother_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->mother_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->mother_arrested ? 'نعم' : 'لا'),
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->mother_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->mother_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوج معتقل:' =>
                [
                    'name' => 'husband_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->husband_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->husband_arrested ? 'نعم' : 'لا'),
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->husband_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->husband_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوجة معتقله:' =>
                [
                    'name' => 'wife_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->wife_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->wife_arrested ? 'نعم' : 'لا'),
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->wife_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->wife_arrested ? 'نعم' : 'لا') : null,
                ],
            'أخ معتقل:' =>
                [
                    'name' => 'brother_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->brother_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->brother_arrested ?? 'لا يوجد',
                ],
            'أخت معتقله:' =>
                [
                    'name' => 'sister_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->sister_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->sister_arrested ?? 'لا يوجد',
                ],
            'ابن معتقل:' =>
                [
                    'name' => 'son_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->son_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->son_arrested ?? 'لا يوجد',
                ],
            'ابنه معتقله:' =>
                [
                    'name' => 'daughter_arrested',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->daughter_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->daughter_arrested ?? 'لا يوجد',
                ],
            'رقم التواصل (تلجرام/واتس):' =>
                [
                    'name' => 'first_phone_number',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->first_phone_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->first_phone_number ?? 'لا يوجد',
                ],
            'اسم صاحب الرقم (تلجرام/واتس):' =>
                [
                    'name' => 'first_phone_owner',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->first_phone_owner ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->first_phone_owner ?? 'لا يوجد',
                ],
            'رقم التواصل الإضافي:' =>
                [
                    'name' => 'second_phone_number',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->second_phone_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->second_phone_number ?? 'لا يوجد',
                ],
            'اسم صاحب الرقم:' =>
                [
                    'name' => 'second_phone_owner',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->second_phone_owner ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->second_phone_owner ?? 'لا يوجد',
                ],
            'البريد الإلكتروني:' =>
                [
                    'name' => 'email',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->email ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->email ?? 'لا يوجد',
                ],
        ];

        $oldPrisoners = $this->Prisoner_->OldArrest ?? [];
        $oldSuggestions = $this->Suggestions_->OldArrestSuggestion ?? [];


        foreach ($oldPrisoners as $oldPrisoner) {
            $this->oldArrestColumns['prisoner'][] = [
                'الرقم الأساسي:' =>
                    [
                        'name' => 'id',
                        'prisoner' => $oldPrisoner->id ?? 'لا يوجد',
                    ],
                'بداية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_start_date',
                        'prisoner' => $oldPrisoner->old_arrest_start_date ?? 'لا يوجد',
                    ],
                'نهاية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_end_date',
                        'prisoner' => $oldPrisoner->old_arrest_end_date ?? 'لا يوجد',
                    ],
            ];
        }
        foreach ($oldSuggestions->where('suggestion_status', 'يحتاج مراجعة') as $oldSuggestion) {
            $this->oldArrestColumns['suggestion'][] = [
                'الرقم الأساسي:' =>
                    [
                        'name' => 'id',
                        'suggestion' => $oldSuggestion->id ?? 'لا يوجد',

                    ],
                'بداية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_start_date',
                        'suggestion' => $oldSuggestion->old_arrest_start_date ?? 'لا يوجد',
                    ],
                'نهاية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_end_date',
                        'suggestion' => $oldSuggestion->old_arrest_end_date ?? 'لا يوجد',
                    ],
            ];
        }


        $this->dispatch('ShowAcceptModal');
    }

    /**
     * @throws ValidationException
     */
    public function ConfirmAccept(): void
    {
        $SuggestionsArray = isset($this->Suggestions_) ? $this->Suggestions_->toArray() : null;
        $PrisonerArray = isset($this->Prisoner_) ? $this->Prisoner_->toArray() : null;
        $SuggestionsArrestArray = isset($this->Suggestions_->ArrestSuggestion) ? $this->Suggestions_->ArrestSuggestion->toArray() : null;
        $PrisonerArrestArray = isset($this->Prisoner_->Arrest) ? $this->Prisoner_->Arrest->toArray() : null;
        $PrisonerOldArrestArray = isset($this->Prisoner_->OldArrest) ? $this->Prisoner_->OldArrest->toArray() : null;
        $SuggestionsOldArrestArray = isset($this->Suggestions_->OldArrestSuggestion) ? $this->Suggestions_->OldArrestSuggestion->toArray() : null;

        $validation = Validator::make($SuggestionsArray, [
            'identification_number' => "nullable",
            'first_name' => "required",
            'second_name' => "nullable",
            'third_name' => "nullable",
            'last_name' => "required",
            'mother_name' => "required",
            'date_of_birth' => "nullable",
            'gender' => "required|in:" . $this->subTables()['Gender'],
            'city_id' => "nullable|in:" . $this->subTables()['City'],
            'town_id' => "nullable|in:" . $this->subTables()['Town'],
            'notes' => "nullable",
        ])->validate();
        $finalData = [];
        foreach (array_filter($this->selectAccepted) as $key => $row) {
            if (array_search($row, $validation)) {
                $finalData += [$key => $validation[$key]];
            }
        }
        $validationArrest = Validator::make($SuggestionsArrestArray, [
            "prisoner_suggestion_id" => "nullable",
            "prisoner_id" => "nullable",
            "arrest_id" => "nullable",
            "arrest_start_date" => "nullable|date",
            "arrest_type" => "nullable|in:" . $this->subTables()['ArrestType'],
            'judgment_in_lifetime' => "nullable|integer",
            "judgment_in_years" => "nullable|integer",
            'judgment_in_months' => "nullable|integer",
            'belong_id' => "nullable|in:" . $this->subTables()['Belong'],
            'special_case' => "nullable",
            'social_type' => "nullable|in:" . $this->subTables()['SocialType'],
            'wife_type' => "nullable|in:" . $this->subTables()['WifeType'],
            'number_of_children' => "nullable",
            'education_level' => "nullable|in:" . $this->subTables()['EducationLevel'],
            'health_note' => "nullable",
            'father_arrested' => "nullable|boolean",
            'mother_arrested' => "nullable|boolean",
            'husband_arrested' => "nullable|boolean",
            'wife_arrested' => "nullable|boolean",
            'brother_arrested' => "nullable|integer",
            'sister_arrested' => "nullable|integer",
            'son_arrested' => "nullable|integer",
            'daughter_arrested' => "nullable|integer",
            'first_phone_owner' => "nullable",
            'first_phone_number' => "nullable",
            'second_phone_owner' => "nullable",
            'second_phone_number' => "nullable",
            'email' => "nullable|email",
        ])->validate();

        $finalArrestData = [];
        foreach (array_filter($this->selectAcceptedArrest) as $key => $row) {
            if (array_search($row, $validationArrest)) {
                $finalArrestData += [$key => $validationArrest[$key]];
            }
        }
        $Prisoner = PrisonerConfirm::query()->create([
            'confirm_status' => "يحتاج مراجعة",
            'prisoner_id' => $SuggestionsArray['prisoner_id'] ?? null,
            'identification_number' => $finalData['identification_number'] ?? $PrisonerArray['identification_number'] ?? null,
            'first_name' => $finalData['first_name'] ?? $PrisonerArray['first_name'] ?? null,
            'second_name' => $finalData['second_name'] ?? $PrisonerArray['second_name'] ?? null,
            'third_name' => $finalData['third_name'] ?? $PrisonerArray['third_name'] ?? null,
            'last_name' => $finalData['last_name'] ?? $PrisonerArray['last_name'] ?? null,
            'mother_name' => $finalData['mother_name'] ?? $PrisonerArray['mother_name'] ?? null,
            'date_of_birth' => $finalData['date_of_birth'] ?? $PrisonerArray['date_of_birth'] ?? null,
            'gender' => $finalData['gender'] ?? $PrisonerArray['gender'] ?? null,
            'city_id' => $finalData['city_id'] ?? $PrisonerArray['city_id'] ?? null,
            'town_id' => $finalData['town_id'] ?? $PrisonerArray['town_id'] ?? null,
            'notes' => $finalData['notes'] ?? $PrisonerArray['notes'] ?? null,
        ]);
        $Arrest = ArrestConfirm::query()->create([
            'confirm_status' => "يحتاج مراجعة",
            "prisoner_id" => $SuggestionsArrestArray['prisoner_id'] ?? null,
            "prisoner_confirm_id" => $Prisoner->id,
            "arrest_start_date" => $finalArrestData["arrest_start_date"] ?? $Arrest["arrest_start_date"] ?? $PrisonerArrestArray['arrest_start_date'] ?? null,
            "arrest_type" => $finalArrestData["arrest_type"] ?? $Arrest["arrest_type"] ?? $PrisonerArrestArray['arrest_type'] ?? null,
            "judgment_in_lifetime" => $finalArrestData["judgment_in_lifetime"] ?? $Arrest["judgment_in_lifetime"] ?? $PrisonerArrestArray['judgment_in_lifetime'] ?? null,
            "judgment_in_years" => $finalArrestData["judgment_in_years"] ?? $Arrest["judgment_in_years"] ?? $PrisonerArrestArray['judgment_in_years'] ?? null,
            "judgment_in_months" => $finalArrestData["judgment_in_months"] ?? $Arrest["judgment_in_months"] ?? $PrisonerArrestArray['judgment_in_months'] ?? null,
            "belong_id" => $finalArrestData["belong_id"] ?? $Arrest["belong_id"] ?? $PrisonerArrestArray['belong_id'] ?? null,
            "special_case" => $finalArrestData["special_case"] ?? $Arrest["special_case"] ?? $PrisonerArrestArray['special_case'] ?? null,
            "social_type" => $finalArrestData["social_type"] ?? $Arrest["social_type"] ?? $PrisonerArrestArray['social_type'] ?? null,
            "wife_type" => $finalArrestData["wife_type"] ?? $Arrest["wife_type"] ?? $PrisonerArrestArray['wife_type'] ?? null,
            "number_of_children" => $finalArrestData["number_of_children"] ?? $Arrest["number_of_children"] ?? $PrisonerArrestArray['number_of_children'] ?? null,
            "education_level" => $finalArrestData["education_level"] ?? $Arrest["education_level"] ?? $PrisonerArrestArray['education_level'] ?? null,
            "health_note" => $finalArrestData["health_note"] ?? $Arrest["health_note"] ?? $PrisonerArrestArray['health_note'] ?? null,
            "father_arrested" => $finalArrestData["father_arrested"] ?? $Arrest["father_arrested"] ?? $PrisonerArrestArray['father_arrested'] ?? null,
            "mother_arrested" => $finalArrestData["mother_arrested"] ?? $Arrest["mother_arrested"] ?? $PrisonerArrestArray['mother_arrested'] ?? null,
            "husband_arrested" => $finalArrestData["husband_arrested"] ?? $Arrest["husband_arrested"] ?? $PrisonerArrestArray['husband_arrested'] ?? null,
            "wife_arrested" => $finalArrestData["wife_arrested"] ?? $Arrest["wife_arrested"] ?? $PrisonerArrestArray['wife_arrested'] ?? null,
            "brother_arrested" => $finalArrestData["brother_arrested"] ?? $Arrest["brother_arrested"] ?? $PrisonerArrestArray['brother_arrested'] ?? null,
            "sister_arrested" => $finalArrestData["sister_arrested"] ?? $Arrest["sister_arrested"] ?? $PrisonerArrestArray['sister_arrested'] ?? null,
            "son_arrested" => $finalArrestData["son_arrested"] ?? $Arrest["son_arrested"] ?? $PrisonerArrestArray['son_arrested'] ?? null,
            "daughter_arrested" => $finalArrestData["daughter_arrested"] ?? $Arrest["daughter_arrested"] ?? $PrisonerArrestArray['daughter_arrested'] ?? null,
            "first_phone_owner" => $finalArrestData["first_phone_owner"] ?? $Arrest["first_phone_owner"] ?? $PrisonerArrestArray['first_phone_owner'] ?? null,
            "first_phone_number" => $finalArrestData["first_phone_number"] ?? $Arrest["first_phone_number"] ?? $PrisonerArrestArray['first_phone_number'] ?? null,
            "second_phone_owner" => $finalArrestData["second_phone_owner"] ?? $Arrest["second_phone_owner"] ?? $PrisonerArrestArray['second_phone_owner'] ?? null,
            "second_phone_number" => $finalArrestData["second_phone_number"] ?? $Arrest["second_phone_number"] ?? $PrisonerArrestArray['second_phone_number'] ?? null,
            "email" => $finalArrestData["email"] ?? $Arrest["email"] ?? $PrisonerArrestArray['email'] ?? null,
        ]);
        $finalOldArrestData = [];

        if (isset($PrisonerOldArrestArray)) {
            foreach ($PrisonerOldArrestArray as $old) {
                foreach ($this->selectAcceptedPrisonerOldArrest as $key => $selected) {
                    if ($old['id'] == $key) {
                        $finalOldArrestData[] = [
                            "id" => $old['id'],
                            "prisoner_id" => $SuggestionsArrestArray['prisoner_id'] ?? null,
                            "prisoner_confirm_id" => $Prisoner->id,
                            "old_arrest_start_date" => in_array('old_arrest_start_date', array_keys($selected)) ? $old['old_arrest_start_date'] : null,
                            "old_arrest_end_date" => in_array('old_arrest_end_date', array_keys($selected)) ? $old['old_arrest_end_date'] : null,
                        ];
                    }
                }
            }
        }
        foreach ($SuggestionsOldArrestArray as $old) {
            foreach ($this->selectAcceptedSuggestionOldArrest as $key => $selected) {
                if ($old['id'] == $key) {
                    $finalOldArrestData[] = [
                        "id" => $old['id'],
                        "prisoner_id" => $SuggestionsArrestArray['prisoner_id'] ?? null,
                        "prisoner_confirm_id" => $Prisoner->id,
                        "old_arrest_start_date" => in_array('old_arrest_start_date', array_keys($selected)) ? $old['old_arrest_start_date'] : null,
                        "old_arrest_end_date" => in_array('old_arrest_end_date', array_keys($selected)) ? $old['old_arrest_end_date'] : null,
                    ];
                }
            }
        }
        if (!empty($finalOldArrestData)) {

            foreach ($finalOldArrestData as $row) {
                OldArrestConfirm::query()->create([
                    'confirm_status' => "يحتاج مراجعة",
                    'prisoner_id' => $row['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $row['prisoner_confirm_id'] ?? null,
                    'old_arrest_start_date' => $row['old_arrest_start_date'] ?? null,
                    'old_arrest_end_date' => $row['old_arrest_end_date'] ?? null,
                ]);
                OldArrestSuggestion::query()
                    ->where('id', $row['id'])
                    ->update(['suggestion_status' => 'تم القبول']);
            }
        }

        $this->Suggestions_->update(['suggestion_status' => 'تم القبول']);
        $this->Suggestions_->ArrestSuggestion->update(['suggestion_status' => 'تم القبول']);

        $this->dispatch('HideAcceptModal');
    }

    function subTables(): array
    {
        return [
            'Relationship' => Relationship::query()->pluck('id')->implode(','),
            'ArrestType' => join(",", array_column(ArrestType::cases(), 'value')),
            'Belong' => Belong::query()->pluck('id')->implode(','),
            'SocialType' => join(",", array_column(SocialType::cases(), 'value')),
            'WifeType' => join(",", array_column(WifeType::cases(), 'value')),
            'City' => City::query()->pluck('id')->implode(','),
            'Town' => Town::query()->pluck('id')->implode(','),
            'PrisonerType' => PrisonerType::query()->pluck('id')->implode(','),
            'Gender' => join(",", array_column(Gender::cases(), 'value')),
            'DefaultEnum' => join(",", array_column(DefaultEnum::cases(), 'value')),
            'SpecialCase' => join(",", array_column(SpecialCase::cases(), 'value')),
            'SuggestionStatus' => join(",", array_column(SuggestionStatus::cases(), 'value')),
            'EducationLevel' => join(",", array_column(EducationLevel::cases(), 'value')),
        ];
    }

    public function confirmDelete(): void
    {
        $this->Suggestions_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(PrisonerSuggestion $prisonerSuggestion): void
    {
        $this->Suggestions_ = $prisonerSuggestion;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Suggestions = $this->getSuggestionsProperty()->when(isset($this->sortBy), function ($q) {
            if ($this->sortBy == "تم القبول")
                $q->where('suggestion_status', "تم القبول");
            elseif ($this->sortBy == "يحتاج مراجعة")
                $q->where('suggestion_status', 'يحتاج مراجعة');
            else   $q->whereIn('suggestion_status', ['تم القبول', 'يحتاج مراجعة']);
        })->orderBy('suggestion_status')->paginate(10);

        $value = !$this->Exist ? 11 : 10;

        if (count(array_filter($this->selectAccepted)) < $value)
            $this->SelectAllPrisoners = false;
        else $this->SelectAllPrisoners = true;

        if (count(array_filter($this->selectAcceptedArrest)) < 26)
            $this->SelectAllPrisonersArrest = false;
        else $this->SelectAllPrisonersArrest = true;

        $ASOAStatus = null;
        if (isset($this->selectAcceptedSuggestionOldArrest))
            foreach ($this->selectAcceptedSuggestionOldArrest as $selected) {
                if (array_filter($selected)) {
                    if (isset($selected))
                        $ASOAStatus = true;
                    else $ASOAStatus = false;
                }
            }

        $APOAStatus = null;
        if (isset($this->selectAcceptedPrisonerOldArrest))
            foreach ($this->selectAcceptedPrisonerOldArrest as $selected) {
                if (array_filter($selected)) {
                    if (isset($selected))
                        $ASOAStatus = true;
                    else $ASOAStatus = false;
                }
            }

        $SuggestionCount = [
            'all' => PrisonerSuggestion::query()->count(),
            'accepted' => PrisonerSuggestion::query()->where('suggestion_status', 'تم القبول')->count(),
            'needReview' => PrisonerSuggestion::query()->where('suggestion_status', 'يحتاج مراجعة')->count(),
        ];

        return view('livewire.dashboard.main.list-prisoner-suggestions', compact('Suggestions', 'SuggestionCount', 'ASOAStatus', 'APOAStatus'));
    }

    public function getSuggestionsProperty()
    {
        return PrisonerSuggestion::query()
            ->with(['City', 'Relationship'])
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
                    ->orWhereHas('Prisoner', function ($subQuery_) use ($searchTerms) {
                        foreach ($searchTerms as $term_) {
                            $subQuery_->where(function ($nameSubQuery_) use ($term_) {
                                $nameSubQuery_->where('first_name', 'LIKE', '%' . $term_ . '%')
                                    ->orWhere('second_name', 'LIKE', '%' . $term_ . '%')
                                    ->orWhere('third_name', 'LIKE', '%' . $term_ . '%')
                                    ->orWhere('last_name', 'LIKE', '%' . $term_ . '%');
                            });
                        }
                    })
                    ->orWhere('suggester_name', 'LIKE', '%' . $this->Search . '%')
                    ->orWhere('suggester_identification_number', 'LIKE', $this->Search)
                    ->orWhere('identification_number', 'LIKE', $this->Search)
                    ->orWhereHas('Prisoner', function ($q) {
                        $q->where('identification_number', 'LIKE', $this->Search);
                    })
                    ->orWhereHas('Prisoner', function ($q) {
                        $q->where('id', 'LIKE', $this->Search);
                    })
                    ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                    ->orWhereHas('City', function ($q) {
                        $q->where('city_name', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('Relationship', function ($q) {
                        $q->where('relationship_name', 'LIKE', '%' . $this->Search . '%');
                    });
            });
    }

    public function SearchFor($prisoner_id): void
    {
        $this->Search = $prisoner_id;
    }

    public function SortBy($sort): void
    {
        $this->resetPage();
        $this->Search = null;
        $this->sortBy = $sort;
    }

}
