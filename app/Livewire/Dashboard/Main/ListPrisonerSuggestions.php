<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\ArrestedSide;
use App\Enums\ArrestType;
use App\Enums\DefaultEnum;
use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\SocialType;
use App\Enums\SpecialCase;
use App\Enums\SuggestionStatus;
use App\Enums\WifeType;
use App\Models\Arrest;
use App\Models\ArrestConfirm;
use App\Models\Belong;
use App\Models\City;
use App\Models\FamilyIDNumber;
use App\Models\FamilyIDNumberConfirm;
use App\Models\FamilyIDNumberSuggestion;
use App\Models\OldArrest;
use App\Models\OldArrestConfirm;
use App\Models\OldArrestSuggestion;
use App\Models\Prisoner;
use App\Models\PrisonerConfirm;
use App\Models\PrisonerSuggestion;
use App\Models\PrisonerType;
use App\Models\Relationship;
use App\Models\Town;
use App\Models\User;
use App\Rules\PalestineIdValidationRule;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListPrisonerSuggestions extends Component
{
    use WithPagination;

    public array $old_arrests = [
        []
    ];

    public $old_errors = null;
    public ?string $new_town_name = null;
    public object $Prisoners_;

    public $fileFormUrl;
    public $googleUrl;
    public ?string $new_town_id = null;

    public object $Suggestions_;
    public object $Prisoner_;
    public bool $Exist = false;

    public $convert_number = null;
    public bool $SelectAllPrisoners = false;
    public bool $SelectAllPrisonersArrest = false;
    public bool $showEditModel = false;

    public ?string $Search = null;
    public $switch_city_id = null;
    public ?string $change_prisoner_id = null;
    public ?string $prisoner_search = null;
    public ?string $sortBy = null;
    public ?array $state = [];

    public $suggestion;
    public array $selectAccepted = [];
    public array $selectAcceptedArrest = [];
    public array $selectAcceptedSuggestionOldArrest = [];
    public array $selectAcceptedPrisonerOldArrest = [];

    public array $prisonerColumns = [];
    public array $arrestColumns = [];
    public array $oldArrestColumns = [];
    public array $familyIDNumberColumns = [];

    protected string $paginationTheme = 'bootstrap';

    public function makeItMain($change_prisoner_id): void
    {
        $prisoner_id = $change_prisoner_id;

        $arrest = Arrest::query()->where('prisoner_id', $prisoner_id)->first();

        $suggestion_id = $this->Suggestions_->id;

        if ($arrest) {
            $arrest_id = $arrest->id;

            $this->Suggestions_->update(['prisoner_id' => $prisoner_id]);

            $this->Suggestions_->ArrestSuggestion->update(['prisoner_id' => $prisoner_id, 'arrest_id' => $arrest_id]);

            $OldArrestSuggestion = $this->Suggestions_->OldArrestSuggestion;

            $OldArrestSuggestion->each(function ($old) use ($prisoner_id) {
                $old->update(['prisoner_id' => $prisoner_id]);
            });

            $suggestions = PrisonerSuggestion::query()->where('id', $suggestion_id)->first();
            if (isset($suggestions))
                $this->Accept($suggestions);
        }
    }

    public function Accept(PrisonerSuggestion $prisonerSuggestion): void
    {
        $this->switch_city_id = null;
        $this->selectAccepted = [];
        $this->selectAcceptedArrest = [];
        $this->selectAcceptedSuggestionOldArrest = [];
        $this->selectAcceptedPrisonerOldArrest = [];

        $this->prisonerColumns = [];
        $this->arrestColumns = [];
        $this->oldArrestColumns = [];

        $this->prisoner_search = null;
        $this->change_prisoner_id = null;
        $this->SelectAllPrisoners = false;
        $this->SelectAllPrisonersArrest = false;

        $this->Suggestions_ = $prisonerSuggestion;
        if (isset($this->Suggestions_->prisoner_id))
            $this->Prisoner_ = Prisoner::query()->with('OldArrest', 'Arrest', 'Arrest.Belong', 'Town', 'City', 'FamilyIDNumber')->where('id', $this->Suggestions_->prisoner_id)->first() ?? null;

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
            'اسم آخر للعائلة:' =>
                [
                    'name' => 'nick_name',
                    'suggestion' => $this->Suggestions_->nick_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->nick_name ?? 'لا يوجد',
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
            'ملاحظات النظام:' =>
                [
                    'name' => 'admin_notes',
                    'suggestion' => $this->Suggestions_->admin_notes ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->admin_notes ?? 'لا يوجد',
                ],
        ];

        if (isset($this->Suggestions_->ArrestSuggestion->arrest_type) && $this->Suggestions_->ArrestSuggestion->arrest_type === "موقوف") {
            $judgment_in_lifetime = "الحكم المتوقع مؤبدات:";
            $judgment_in_years = "الحكم المتوقع سنوات:";
            $judgment_in_months = "الحكم المتوقع شهور:";
        } else {
            $judgment_in_lifetime = "الحكم مؤبدات:";
            $judgment_in_years = "الحكم سنوات:";
            $judgment_in_months = "الحكم شهور:";
        }

        $this->arrestColumns = [
            'تاريخ الاعتقال:' =>
                [
                    'name' => 'arrest_start_date',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->arrest_start_date ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_start_date ?? 'لا يوجد',
                ],
            'نوع الاعتقال:' =>
                [
                    'name' => 'arrest_type',
                    'suggestion' => $this->Suggestions_->ArrestSuggestion->arrest_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_type ?? 'لا يوجد',
                ],
            $judgment_in_lifetime =>
                [
                    'name' => 'judgment_in_lifetime',
                    'suggestion' => !empty($this->Suggestions_->ArrestSuggestion->judgment_in_lifetime) ? $this->Suggestions_->ArrestSuggestion->judgment_in_lifetime : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_lifetime) ? $this->Prisoner_->Arrest->judgment_in_lifetime : 'لا يوجد',
                ],
            $judgment_in_years =>
                [
                    'name' => 'judgment_in_years',
                    'suggestion' => !empty($this->Suggestions_->ArrestSuggestion->judgment_in_years) ? $this->Suggestions_->ArrestSuggestion->judgment_in_years : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_years) ? $this->Prisoner_->Arrest->judgment_in_years : 'لا يوجد',

                ],
            $judgment_in_months =>
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
                    'suggestion' => isset($this->Suggestions_) && $this->Suggestions_->ArrestSuggestion != null
                        ? $this->Suggestions_->ArrestSuggestion->father_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->father_arrested ? 'نعم' : 'لا')
                        : null,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->father_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->father_arrested ? 'نعم' : 'لا') : null,
                ],
            'أم معتقله:' =>
                [
                    'name' => 'mother_arrested',
                    'suggestion' => isset($this->Suggestions_) && $this->Suggestions_->ArrestSuggestion != null
                        ? $this->Suggestions_->ArrestSuggestion->mother_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->mother_arrested ? 'نعم' : 'لا')
                        : null,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->mother_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->mother_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوج معتقل:' =>
                [
                    'name' => 'husband_arrested',
                    'suggestion' => isset($this->Suggestions_) && $this->Suggestions_->ArrestSuggestion != null
                        ? $this->Suggestions_->ArrestSuggestion->husband_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->husband_arrested ? 'نعم' : 'لا')
                        : null,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->husband_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->husband_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوجة معتقله:' =>
                [
                    'name' => 'wife_arrested',
                    'suggestion' => isset($this->Suggestions_) && $this->Suggestions_->ArrestSuggestion != null
                        ? $this->Suggestions_->ArrestSuggestion->wife_arrested == null ? 'لا يوجد' : ($this->Suggestions_->ArrestSuggestion->wife_arrested ? 'نعم' : 'لا')
                        : null,
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
            'مفرج عنه حالياً ؟:' =>
                [
                    'name' => 'is_released',
                    'suggestion' => isset($this->Suggestions_->ArrestSuggestion->is_released) && $this->Suggestions_->ArrestSuggestion->is_released ? 'نعم' : 'لا',
                    'prisoner' => isset($this->Prisoner_->Arrest->is_released) && $this->Prisoner_->Arrest->is_released ? 'نعم' : 'لا',
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
                'id' => $oldPrisoner->id ?? null,
                'old_arrest_start_date' => $oldPrisoner->old_arrest_start_date,
                'old_arrest_end_date' => $oldPrisoner->old_arrest_end_date,
                'arrested_side' => $oldPrisoner->arrested_side,
            ];
        }
        foreach ($oldSuggestions->where('suggestion_status', 'يحتاج مراجعة') as $oldSuggestion) {
            $this->oldArrestColumns['suggestion'][] = [
                'id' => $oldSuggestion->id ?? null,
                'old_arrest_start_date' => $oldSuggestion->old_arrest_start_date,
                'old_arrest_end_date' => $oldSuggestion->old_arrest_end_date,
                'arrested_side' => $oldSuggestion->arrested_side,
            ];
        }


        function formatArrestedSuggestionValues($prisonerSuggestion, $relationship): array
        {
            if (isset($prisonerSuggestion->FamilyIDNumberSuggestion)) {
                $values = $prisonerSuggestion->FamilyIDNumberSuggestion->where('relationship_name', $relationship)->pluck('id_number', 'id')->toArray();
                $formattedIds = [];

                foreach ($values as $index => $value) {
                    $formattedIds[$index] = ["idn" => $value, "relationship_name" => $relationship];
                }

                return $formattedIds;
            }
            return [];
        }

        $father_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'اب');
        $mother_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'ام');
        $husband_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'زوج');
        $wife_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'زوجة');
        $brother_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'اخ');
        $sister_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'اخت');
        $son_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'ابن');
        $daughter_arrested_suggestion_ids = formatArrestedSuggestionValues($prisonerSuggestion, 'ابنه');

        if (!empty($this->Prisoner_))
            $prisonerIdn = $this->Prisoner_;
        else $prisonerIdn = null;
        function formatArrestedPrisonerValues($prisonerIdn, $relationship): array
        {
            if (isset($prisonerIdn->FamilyIDNumber)) {
                $values = $prisonerIdn->FamilyIDNumber->where('relationship_name', $relationship)->pluck('id_number', 'id')->toArray();
                $formattedIds = [];

                foreach ($values as $index => $value) {
                    $formattedIds[$index] = ["idn" => $value, "relationship_name" => $relationship];
                }

                return $formattedIds;
            }
            return [];
        }

        $father_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'اب');
        $mother_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'ام');
        $husband_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'زوج');
        $wife_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'زوجة');
        $brother_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'اخ');
        $sister_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'اخت');
        $son_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'ابن');
        $daughter_arrested_prisoner_ids = formatArrestedPrisonerValues($prisonerIdn, 'ابنه');

        $this->familyIDNumberColumns = [
            "prisoner" => [
                "father_arrested_prisoner_ids" => $father_arrested_prisoner_ids,
                "mother_arrested_prisoner_ids" => $mother_arrested_prisoner_ids,
                "husband_arrested_prisoner_ids" => $husband_arrested_prisoner_ids,
                "wife_arrested_prisoner_ids" => $wife_arrested_prisoner_ids,
                "brother_arrested_prisoner_ids" => $brother_arrested_prisoner_ids,
                "sister_arrested_prisoner_ids" => $sister_arrested_prisoner_ids,
                "son_arrested_prisoner_ids" => $son_arrested_prisoner_ids,
                "daughter_arrested_prisoner_ids" => $daughter_arrested_prisoner_ids,
            ],
            "suggestion" => [
                "father_arrested_suggestion_ids" => $father_arrested_suggestion_ids,
                "mother_arrested_suggestion_ids" => $mother_arrested_suggestion_ids,
                "husband_arrested_suggestion_ids" => $husband_arrested_suggestion_ids,
                "wife_arrested_suggestion_ids" => $wife_arrested_suggestion_ids,
                "brother_arrested_suggestion_ids" => $brother_arrested_suggestion_ids,
                "sister_arrested_suggestion_ids" => $sister_arrested_suggestion_ids,
                "son_arrested_suggestion_ids" => $son_arrested_suggestion_ids,
                "daughter_arrested_suggestion_ids" => $daughter_arrested_suggestion_ids,
            ],
        ];


        $this->dispatch('ShowAcceptModal');
    }

    public function showNumberConverter(): void
    {
        $this->convert_number = null;
        $this->dispatch('showNumberConverter');
    }

    public function addNew(): void
    {
        $this->showEditModel = false;
        $this->state = [];
        $this->dispatch('show_create_update_modal');
    }

    public function edit($suggestion_id): void
    {

        $this->suggestion = PrisonerSuggestion::query()
            ->with('FamilyIDNumberSuggestion', 'ArrestSuggestion', 'OldArrestSuggestion')
            ->where('id', $suggestion_id)
            ->first();

        $father_arrested_id = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'اب')->pluck('id_number')->first() ?? null;
        $mother_arrested_id = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'ام')->pluck('id_number')->first() ?? null;
        $husband_arrested_id = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'زوج')->pluck('id_number')->first() ?? null;
        $wife_arrested_id = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'زوجة')->pluck('id_number')->first() ?? null;

        $brother_arrested_values = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'اخ')->pluck('id_number')->toArray();
        $brother_arrested_ids = [];
        foreach ($brother_arrested_values as $key => $value) {
            $brother_arrested_ids[$key + 1] = $value;
        }

        $sister_arrested_values = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'اخت')->pluck('id_number')->toArray();
        $sister_arrested_ids = [];
        foreach ($sister_arrested_values as $key => $value) {
            $sister_arrested_ids[$key + 1] = $value;
        }

        $son_arrested_values = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'ابن')->pluck('id_number')->toArray();
        $son_arrested_ids = [];
        foreach ($son_arrested_values as $key => $value) {
            $son_arrested_ids[$key + 1] = $value;
        }

        $daughter_arrested_values = $this->suggestion->FamilyIDNumberSuggestion->where('relationship_name', 'ابنه')->pluck('id_number')->toArray();
        $daughter_arrested_ids = [];
        foreach ($daughter_arrested_values as $key => $value) {
            $daughter_arrested_ids[$key + 1] = $value;
        }


        $data = $this->suggestion->toArray();
        $this->state = [
            "id" => $data['id'] ?? null,
            "prisoner_id" => $data['prisoner_id'] ?? null,
            "identification_number" => $data['identification_number'] ?? null,
            "first_name" => $data['first_name'] ?? null,
            "second_name" => $data['second_name'] ?? null,
            "third_name" => $data['third_name'] ?? null,
            "last_name" => $data['last_name'] ?? null,
            "mother_name" => $data['mother_name'] ?? null,
            "nick_name" => $data['nick_name'] ?? null,
            "date_of_birth" => $data['date_of_birth'] ?? null,
            "gender" => $data['gender'] ?? null,
            "city_id" => $data['city_id'] ?? null,
            "town_id" => (int)$data['town_id'] ?? null,
            "notes" => $data['notes'] ?? null,

            "arrest_start_date" => $data['arrest_suggestion']['arrest_start_date'] ?? null,
            "arrest_type" => $data['arrest_suggestion']['arrest_type'] ?? null,
            "judgment_in_lifetime" => $data['arrest_suggestion']['judgment_in_lifetime'] ?? null,
            "judgment_in_years" => $data['arrest_suggestion']['judgment_in_years'] ?? null,
            "judgment_in_months" => $data['arrest_suggestion']['judgment_in_months'] ?? null,
            "belong_id" => $data['arrest_suggestion']['belong_id'] ?? null,
            "special_case" => array_fill_keys(explode(',' ?? null, $data['arrest_suggestion']['special_case']) ?? null, true) ?? null,
            "health_note" => $data['arrest_suggestion']['health_note'] ?? null,
            "social_type" => $data['arrest_suggestion']['social_type'] ?? null,
            "wife_type" => $data['arrest_suggestion']['wife_type'] ?? null,
            "number_of_children" => $data['arrest_suggestion']['number_of_children'] ?? null,
            "education_level" => $data['arrest_suggestion']['education_level'] ?? null,
            "father_arrested" => (bool)$data['arrest_suggestion']['father_arrested'] ?? null,
            "mother_arrested" => (bool)$data['arrest_suggestion']['mother_arrested'] ?? null,
            "husband_arrested" => (bool)$data['arrest_suggestion']['husband_arrested'] ?? null,
            "wife_arrested" => (bool)$data['arrest_suggestion']['wife_arrested'] ?? null,
            "brother_arrested" => $data['arrest_suggestion']['brother_arrested'] ?? null,
            "sister_arrested" => $data['arrest_suggestion']['sister_arrested'] ?? null,
            "son_arrested" => $data['arrest_suggestion']['son_arrested'] ?? null,
            "daughter_arrested" => $data['arrest_suggestion']['daughter_arrested'] ?? null,
            "first_phone_owner" => $data['arrest_suggestion']['first_phone_owner'] ?? null,
            "first_phone_number" => $data['arrest_suggestion']['first_phone_number'] ?? null,
            "second_phone_owner" => $data['arrest_suggestion']['second_phone_owner'] ?? null,
            "second_phone_number" => $data['arrest_suggestion']['second_phone_number'] ?? null,
            "is_released" => $data['arrest_suggestion']['is_released'] ?? null,
            "email" => $data['arrest_suggestion']['email'] ?? null,

            "father_arrested_id" => $father_arrested_id,
            "mother_arrested_id" => $mother_arrested_id,
            "husband_arrested_id" => $husband_arrested_id,
            "wife_arrested_id" => $wife_arrested_id,
            "brother_arrested_id" => $brother_arrested_ids,
            "sister_arrested_id" => $sister_arrested_ids,
            "son_arrested_id" => $son_arrested_ids,
            "daughter_arrested_id" => $daughter_arrested_ids,
        ];
        $this->old_arrests = $data['old_arrest_suggestion'];

        $this->showEditModel = true;
        $this->dispatch('show_create_update_modal');
    }

    public function addOldArrest(): void
    {
        $this->old_arrests[] = [];
        $this->old_errors = null;
        $this->dispatch('scroll-to-bottom');
    }

    public function removeOldArrest($index): void
    {
        unset($this->old_arrests[$index]);
        $this->old_errors = null;
        $this->old_arrests = array_values($this->old_arrests);
    }

    public function switchCity(): void
    {
        $user_name = Auth::user()->name;
        if (!empty($this->Prisoner_)) {
            Prisoner::query()->where('id', $this->Prisoner_->id)->update([
                'city_id' => (int)$this->switch_city_id,
                'admin_notes' => "تم تحويل هذا الطلب من مراجع المنطقة $user_name"
            ]);
        } elseif (!empty($this->Suggestions_)) {
            PrisonerSuggestion::query()->where('id', $this->Suggestions_->id)->update([
                'city_id' => (int)$this->switch_city_id,
                'admin_notes' => "تم تحويل هذا الطلب من مراجع المنطقة $user_name"
            ]);
        }
        $suggestions = PrisonerSuggestion::query()->where('id', $this->Suggestions_->id)->first();
        if (isset($suggestions))
            $this->Accept($suggestions);
    }

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
                'nick_name' => true,
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
                'is_released' => true,
                'email' => true,
            ];
        } else  $this->selectAcceptedArrest = [];
    }

    public function FamilyIdnPrisonerDeleted($index, $key): void
    {
        // Find the item in "suggestion" and move it to "suggestion_accepted"
        $this->moveItemTo('prisoner', 'prisoner_deleted', $index, $key);
    }

    private function moveItemTo($sourceKey, $destinationKey, $index, $key): void
    {
        foreach ($this->familyIDNumberColumns[$sourceKey] as $index_ => $row) {
            if ($index_ == $index) {
                if (!empty($row) && array_key_exists($key, $row)) {
                    foreach ($row as $key_ => $inside) {
                        if (!empty($inside) && $key_ == $key) {
                            $itemToMove = $inside;
                            // Move the item from source to destination
                            $this->familyIDNumberColumns[$destinationKey][$index][$key] = $itemToMove;
                            // Remove the item from the source
                            unset($this->familyIDNumberColumns[$sourceKey][$index][$key]);

                            if (empty($this->familyIDNumberColumns[$sourceKey][$index]))
                                unset($this->familyIDNumberColumns[$sourceKey][$index]);
                            break; // Assuming each ID is unique and we found the item
                        }
                    }
                }
            }
        }
    }

    public function FamilyIdnPrisonerRestore($index, $key): void
    {
        // Find the item in "suggestion_accepted" and move it back to "suggestion"
        $this->moveItemTo('prisoner_deleted', 'prisoner', $index, $key);
    }

    public function FamilyIdnSuggestionAccepted($index, $key): void
    {
        // Find the item in "suggestion" and move it to "suggestion_accepted"
        $this->moveItemTo('suggestion', 'suggestion_accepted', $index, $key);
    }

    public function FamilyIdnSuggestionUnaccepted($index, $key): void
    {
        // Find the item in "suggestion_accepted" and move it back to "suggestion"
        $this->moveItemTo('suggestion_accepted', 'suggestion', $index, $key);
    }

    public function removeFromPrisonerList($id): void
    {
        $this->moveItem($this->oldArrestColumns['prisoner'], $this->oldArrestColumns['prisoner_deleted'], $id);
    }

    private function moveItem(&$source, &$destination, $id): void
    {
        $item = array_filter($source, function ($row) use ($id) {
            return $row['id'] == $id;
        });

        $destination[] = reset($item);
        $source = array_values(array_diff_key($source, $item));
    }

    public function removeFromPrisonerDeletedList($id): void
    {
        $this->moveItem($this->oldArrestColumns['prisoner_deleted'], $this->oldArrestColumns['prisoner'], $id);
    }

    public function addToSuggestionAcceptedList($id): void
    {
        $this->moveItem($this->oldArrestColumns['suggestion'], $this->oldArrestColumns['suggestion_accepted'], $id);
    }

    public function removeFromSuggestionAcceptedList($id): void
    {
        $this->moveItem($this->oldArrestColumns['suggestion_accepted'], $this->oldArrestColumns['suggestion'], $id);
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
        $Suggestions = $this->getSuggestionsProperty()->paginate(10);

        if (!empty($this->prisoner_search) || !empty($this->Suggestions_)) {
            $PrisonerSearch = Prisoner::query()
                ->where(function ($q) {
                    if (isset($this->prisoner_search)) {
                        $q->where(function ($q) {
                            $searchTerms = explode(' ', $this->prisoner_search);
                            $q->where('identification_number', $this->prisoner_search)
                                ->orWhere(function ($subQuery) use ($searchTerms) {
                                    foreach ($searchTerms as $term) {
                                        $subQuery->where(function ($nameSubQuery) use ($term) {
                                            $nameSubQuery->where('first_name', 'LIKE', $term)
                                                ->orWhere('second_name', 'LIKE', $term)
                                                ->orWhere('third_name', 'LIKE', $term)
                                                ->orWhere('last_name', 'LIKE', $term);
                                        });
                                    }
                                });
                        });
                    }
                    if (isset($this->Suggestions_)) {
                        $q->orWhere(function ($q) {
                            $searchTerms_ = explode(' ', $this->Suggestions_->full_name);
                            $q->where('identification_number', $this->Suggestions_->identification_number)
                                ->orWhere(function ($subQuery) use ($searchTerms_) {
                                    foreach ($searchTerms_ as $term) {
                                        $subQuery->where(function ($nameSubQuery) use ($term) {
                                            $nameSubQuery->where('first_name', 'LIKE', $term)
                                                ->orWhere('second_name', 'LIKE', $term)
                                                ->orWhere('third_name', 'LIKE', $term)
                                                ->orWhere('last_name', 'LIKE', $term);
                                        });
                                    }
                                });
                        });
                    }
                })->get();

            $PrisonerSearch = $PrisonerSearch->pluck('full_name', 'id');
        } else {
            $PrisonerSearch = null;
        }


        $value = !$this->Exist ? 12 : 11;
        if (count(array_filter($this->selectAccepted)) < $value)
            $this->SelectAllPrisoners = false;
        else $this->SelectAllPrisoners = true;

        if (count(array_filter($this->selectAcceptedArrest)) < 27)
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

        $Belongs = Belong::all()->sortBy('belong_name');
        $Relationships = Relationship::all()->sortBy('id');

        $Cities = City::query()
            ->orderBy('city_name')
            ->get();

        $Towns = Town::query()
            ->when(!empty($this->state['city_id']) , function ($q) {
                $q->where('city_id', $this->state['city_id']);
            })
            ->orderBy('town_name')
            ->get();

        return view('livewire.dashboard.main.list-prisoner-suggestions', compact('Belongs', 'Cities', 'Towns', 'Relationships', 'Suggestions', 'PrisonerSearch', 'ASOAStatus', 'APOAStatus'));
    }

    public function addNewTown($city_id): void
    {
        if (!empty($this->new_town_name)) {
            $this->validate(['new_town_name' => 'unique:towns,town_name'], ['new_town_name.unique' => 'هذه البلدة موجودة مسبقاً']);
            $town = Town::query()->create(['city_id' => $city_id, 'town_name' => $this->new_town_name]);
            $this->state['town_id'] = (string)$town->id;
            $this->new_town_name = null;
        }
    }

    public function getSuggestionsProperty(): \Illuminate\Database\Eloquent\Builder
    {
        $CurrentUserCities = User::query()
            ->where('id', Auth::user()->id)
            ->with('City')
            ->first()
            ->toArray()['city'] ?? [];

        $cityIdArray = [];
        foreach ($CurrentUserCities as $subArray) {
            if (isset($subArray['pivot']['city_id'])) {
                $cityIdArray[] = $subArray['pivot']['city_id'];
            }
        }

        return PrisonerSuggestion::query()
            ->with(['City', 'Relationship'])
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('city_id', $cityIdArray)
                    ->orWhereNull('city_id');
            })
            ->where('suggestion_status', 'يحتاج مراجعة')
            ->where(function ($q) {
                $q->when(isset($this->Search), function ($query) {
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

                        // City name search within the city_id check
                        $subQuery->orWhereHas('City', function ($q) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $q->where('city_name', 'like', '%' . $term . '%');
                            }
                        });
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
                        ->orWhereHas('Prisoner', function ($q) {
                            $q->where('identification_number', 'LIKE', $this->Search);
                        })
                        ->orWhereHas('Prisoner', function ($q) {
                            $q->where('id', 'LIKE', $this->Search);
                        })
                        ->orWhere('suggester_name', 'LIKE', '%' . $this->Search . '%')
                        ->orWhere('suggester_identification_number', 'LIKE', $this->Search)
                        ->orWhere('identification_number', 'LIKE', $this->Search)
                        ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                        ->orWhereHas('City', function ($q) {
                            $q->where('city_name', 'like', '%' . $this->Search . '%');
                        })
                        ->orWhereHas('Relationship', function ($q) {
                            $q->where('relationship_name', 'LIKE', '%' . $this->Search . '%');
                        });
                });
                $q->when(isset($this->sortBy), function ($query) {
                    if ($this->sortBy === "الإضافات") {
                        $query->whereNull('prisoner_id');
                    } elseif ($this->sortBy === "التعديلات") {
                        $query->whereNotNull('prisoner_id');
                    } else {
                        $query->whereNull('prisoner_id')->orWhereNotNull('prisoner_id');
                    }
                });
            });

    }

    public function SortBy($sort): void
    {
        $this->resetPage();
        $this->Search = null;
        $this->sortBy = $sort;
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

        $validation = Validator::make($SuggestionsArray, [
            'identification_number' => "nullable",
            'first_name' => "required",
            'second_name' => "nullable",
            'third_name' => "nullable",
            'last_name' => "required",
            'mother_name' => "nullable",
            'nick_name' => "nullable",
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
            'is_released' => "nullable|in:0,1",
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
            'nick_name' => $finalData['nick_name'] ?? $PrisonerArray['nick_name'] ?? null,
            'date_of_birth' => $finalData['date_of_birth'] ?? $PrisonerArray['date_of_birth'] ?? null,
            'gender' => $finalData['gender'] ?? $PrisonerArray['gender'] ?? null,
            'city_id' => $finalData['city_id'] ?? $PrisonerArray['city_id'] ?? null,
            'town_id' => $finalData['town_id'] ?? $PrisonerArray['town_id'] ?? null,
            'notes' => $finalData['notes'] ?? $PrisonerArray['notes'] ?? null,
        ]);

        $is_released = null;

        if (isset($finalArrestData["is_released"])) {
            $is_released = (boolean)$finalArrestData["is_released"];
        } elseif (isset($Arrest["is_released"])) {
            $is_released = (boolean)$Arrest["is_released"];
        } elseif (isset($PrisonerArrestArray['is_released'])) {
            $is_released = (boolean)$PrisonerArrestArray['is_released'];
        }

        ArrestConfirm::query()->create([
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
            "is_released" => $is_released,
            "email" => $finalArrestData["email"] ?? $Arrest["email"] ?? $PrisonerArrestArray['email'] ?? null,
        ]);

        if (isset($this->oldArrestColumns['prisoner_deleted'])) {
            foreach ($this->oldArrestColumns['prisoner_deleted'] as $old) {
                OldArrest::query()->find($old['id'])->delete();
            }
        }

        if (isset($this->oldArrestColumns['suggestion_accepted'])) {
            foreach ($this->oldArrestColumns['suggestion_accepted'] as $old) {
                OldArrestConfirm::query()->create([
                    'confirm_status' => "يحتاج مراجعة",
                    'prisoner_id' => $SuggestionsArrestArray['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $Prisoner->id ?? null,
                    'old_arrest_start_date' => $old['old_arrest_start_date'] ?? null,
                    'old_arrest_end_date' => $old['old_arrest_end_date'] ?? null,
                    'arrested_side' => $old['arrested_side'] ?? null,
                ]);
                OldArrestSuggestion::query()
                    ->find($old['id'])
                    ->update(['suggestion_status' => 'تم القبول']);
            }
        }

        if (isset($this->familyIDNumberColumns['prisoner_deleted'])) {
            foreach ($this->familyIDNumberColumns['prisoner_deleted'] as $index => $family_arrested) {
                foreach ($family_arrested as $key => $row)
                    FamilyIDNumber::query()->find($key)->delete();
            }
        }

        if (isset($this->familyIDNumberColumns['suggestion_accepted'])) {
            foreach ($this->familyIDNumberColumns['suggestion_accepted'] as $index => $family_arrested) {
                foreach ($family_arrested as $key => $row) {
                    FamilyIDNumberConfirm::query()->create([
                        'confirm_status' => "يحتاج مراجعة",
                        'prisoner_id' => $SuggestionsArrestArray['prisoner_id'] ?? null,
                        'prisoner_confirm_id' => $Prisoner->id ?? null,
                        'id_number' => $row['idn'] ?? null,
                        'relationship_name' => $row['relationship_name'] ?? null,
                    ]);
                    FamilyIDNumberSuggestion::query()
                        ->find($key)
                        ->update(['suggestion_status' => 'تم القبول']);
                }
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
            'Belongs' => Belong::query()->pluck('id')->implode(','),
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
            'ArrestedSide' => join(",", array_column(ArrestedSide::cases(), 'value')),
        ];
    }

    /**
     * @throws ValidationException
     */
    public function CreateAcceptAdmin(): void
    {
        $this->Review();

        $PrisonerConfirm = PrisonerConfirm::query()->create([
            'confirm_status' => "يحتاج مراجعة",
            'prisoner_id' => $this->state['prisoner_id'] ?? null,
            'identification_number' => $this->state['identification_number'] ?? null,
            'first_name' => $this->state['first_name'] ?? null,
            'second_name' => $this->state['second_name'] ?? null,
            'third_name' => $this->state['third_name'] ?? null,
            'last_name' => $this->state['last_name'] ?? null,
            'mother_name' => $this->state['mother_name'] ?? null,
            'nick_name' => $this->state['nick_name'] ?? null,
            'date_of_birth' => $this->state['date_of_birth'] ?? null,
            'gender' => $this->state['gender'] ?? null,
            'city_id' => $this->state['city_id'] ?? null,
            'town_id' => $this->state['town_id'] ?? null,
            'notes' => $this->state['notes'] ?? null,
        ]);

        ArrestConfirm::query()->create([
            "confirm_status" => "يحتاج مراجعة",
            "prisoner_id" => $this->state['prisoner_id'] ?? null,
            "prisoner_confirm_id" => $PrisonerConfirm->id,
            "arrest_start_date" => $this->state['arrest_start_date'] ?? null,
            "arrest_type" => $this->state['arrest_type'] ?? null,
            "judgment_in_lifetime" => $this->state['judgment_in_lifetime'] ?? null,
            "judgment_in_years" => $this->state['judgment_in_years'] ?? null,
            "judgment_in_months" => $this->state['judgment_in_months'] ?? null,
            "belong_id" => $this->state['belong_id'] ?? null,
            'special_case' => !empty($this->state['special_case']) ? implode(',', array_keys(array_filter($this->state['special_case']))) : null,
            "social_type" => $this->state['social_type'] ?? null,
            "wife_type" => $this->state['wife_type'] ?? null,
            "number_of_children" => $this->state['number_of_children'] ?? null,
            "education_level" => $this->state['education_level'] ?? null,
            "health_note" => $this->state['health_note'] ?? null,
            "father_arrested" => $this->state['father_arrested'] ?? null,
            "mother_arrested" => $this->state['mother_arrested'] ?? null,
            "husband_arrested" => $this->state['husband_arrested'] ?? null,
            "wife_arrested" => $this->state['wife_arrested'] ?? null,
            "brother_arrested" => $this->state['brother_arrested'] ?? null,
            "sister_arrested" => $this->state['sister_arrested'] ?? null,
            "son_arrested" => $this->state['son_arrested'] ?? null,
            "daughter_arrested" => $this->state['daughter_arrested'] ?? null,
            "first_phone_owner" => $this->state['first_phone_owner'] ?? null,
            "first_phone_number" => $this->state['first_phone_number'] ?? null,
            "second_phone_owner" => $this->state['second_phone_owner'] ?? null,
            "second_phone_number" => $this->state['second_phone_number'] ?? null,
            'is_released' => isset($this->state['is_released']) ? (boolean)$this->state['is_released'] : 0,
            "email" => $this->state['email'] ?? null,
        ]);

        if (!empty($this->old_arrests)) {
            foreach (array_filter($this->old_arrests) as $old) {
                if (!empty($old)) {
                    if (!empty($old['old_arrest_start_date']) && !empty($old['old_arrest_end_date']) && !empty($old['arrested_side'])) {
                        OldArrestConfirm::query()->create([
                            'confirm_status' => "يحتاج مراجعة",
                            'prisoner_id' => $this->state['prisoner_id'] ?? null,
                            'prisoner_confirm_id' => $PrisonerConfirm->id ?? null,
                            'old_arrest_start_date' => $old['old_arrest_start_date'] ?? null,
                            'old_arrest_end_date' => $old['old_arrest_end_date'] ?? null,
                            'arrested_side' => $old['arrested_side'] ?? null,
                        ]);
                        if (!empty($old['id'])) {
                            OldArrestSuggestion::query()
                                ->find($old['id'])
                                ->update(['suggestion_status' => 'تم القبول']);
                        }
                    }
                }
            }
        }

        if (isset($this->state) && isset($this->state['father_arrested_id'])) {
            FamilyIDNumberSuggestion::query()->create([
                'id_number' => $this->state['father_arrested_id'],
                'relationship_name' => "اب",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_confirm_id' => $PrisonerConfirm->id,
                'confirm_status' => "يحتاج مراجعة",
            ]);
        }

        if (isset($this->state) && isset($this->state['mother_arrested_id'])) {
            FamilyIDNumberConfirm::query()->create([
                'id_number' => $this->state['mother_arrested_id'],
                'relationship_name' => "ام",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_confirm_id' => $PrisonerConfirm->id,
                'confirm_status' => "يحتاج مراجعة",
            ]);
        }

        if (isset($this->state) && isset($this->state['husband_arrested_id'])) {
            FamilyIDNumberConfirm::query()->create([
                'id_number' => $this->state['husband_arrested_id'],
                'relationship_name' => "زوج",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_confirm_id' => $PrisonerConfirm->id,
                'confirm_status' => "يحتاج مراجعة",
            ]);
        }

        if (isset($this->state) && isset($this->state['wife_arrested_id'])) {
            FamilyIDNumberConfirm::query()->create([
                'id_number' => $this->state['wife_arrested_id'],
                'relationship_name' => "زوجة",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_confirm_id' => $PrisonerConfirm->id,
                'confirm_status' => "يحتاج مراجعة",
            ]);
        }

        if (isset($this->state) && isset($this->state['brother_arrested_id'])) {
            foreach ($this->state['brother_arrested_id'] as $row) {
                FamilyIDNumberConfirm::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "اخ",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $PrisonerConfirm->id,
                    'confirm_status' => "يحتاج مراجعة",
                ]);
            }
        }

        if (isset($this->state) && isset($this->state['sister_arrested_id'])) {
            foreach ($this->state['sister_arrested_id'] as $row) {
                FamilyIDNumberConfirm::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "اخت",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $PrisonerConfirm->id,
                    'confirm_status' => "يحتاج مراجعة",
                ]);
            }
        }

        if (isset($this->state) && isset($this->state['son_arrested_id'])) {
            foreach ($this->state['son_arrested_id'] as $row) {
                FamilyIDNumberConfirm::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "ابن",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $PrisonerConfirm->id,
                    'confirm_status' => "يحتاج مراجعة",
                ]);
            }
        }

        if (isset($this->state) && isset($this->state['daughter_arrested_id'])) {
            foreach ($this->state['daughter_arrested_id'] as $row) {
                FamilyIDNumberConfirm::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "ابنه",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_confirm_id' => $PrisonerConfirm->id,
                    'confirm_status' => "يحتاج مراجعة",
                ]);
            }
        }

        $this->dispatch('show_admin_create_massage');
        $this->dispatch('hide_create_update_modal');
    }

    /**
     * @throws ValidationException
     */
    public function Review(): void
    {
        $this->oldArrestManipulateData();

        $this->validateData();

        $this->manipulateData();
    }

    public function oldArrestManipulateData(): void
    {
        foreach ($this->old_arrests as &$old) {
            if (isset($old['old_arrest_start_date']) && $old['old_arrest_start_date'] === "") {
                $old['old_arrest_start_date'] = null;
            }
            if (isset($old['old_arrest_end_date']) && $old['old_arrest_end_date'] === "") {
                $old['old_arrest_end_date'] = null;
            }
            if (isset($old['old_arrest_start_date']) && $old['old_arrest_start_date'] !== "") {
                $old['old_arrest_start_date'] = Carbon::parse($old['old_arrest_start_date'])->format('Y-m-d');
            }
            if (isset($old['old_arrest_end_date']) && $old['old_arrest_end_date'] !== "") {
                $old['old_arrest_end_date'] = Carbon::parse($old['old_arrest_end_date'])->format('Y-m-d');
            }
            if (isset($old['arrested_side']) && $old['arrested_side'] === "اختر...") {
                $old['arrested_side'] = null;
            }
        }
        unset($old);

    }

    /**
     * @throws ValidationException
     */
    private function validateData(): void
    {
        $rule = ["required", "min:9", "max:9", new PalestineIdValidationRule];
        if (isset($this->state['arrest_type']) && ($this->state['arrest_type'] == "محكوم")) {
            $judgment_in_lifetime_rule = ["nullable", "integer", "required_without_all:judgment_in_years,judgment_in_months"];
            $judgment_in_years_rule = ["nullable", "integer", "required_without_all:judgment_in_lifetime,judgment_in_months"];
            $judgment_in_months_rule = ["nullable", "integer", "required_without_all:judgment_in_years,judgment_in_lifetime"];

        } else {
            $judgment_in_lifetime_rule = ["nullable", "integer"];
            $judgment_in_years_rule = ["nullable", "integer"];
            $judgment_in_months_rule = ["nullable", "integer"];
        }

        $validation = Validator::make($this->state, [
            //Prisoner
            'identification_number' => $rule,
            'first_name' => 'required',
            'second_name' => 'nullable',
            'third_name' => 'nullable',
            'last_name' => 'required',
            'mother_name' => "nullable",
            'nick_name' => "nullable",
            'date_of_birth' => "required",
            'gender' => "required|in:" . $this->subTables()['Gender'],
            'city_id' => "required|in:" . $this->subTables()['City'],
            'town_id' => "required|in:" . $this->subTables()['Town'],
            'notes' => "nullable",
            //Arrest
            "arrest_start_date" => 'required',
            "arrest_type" => 'required|in:' . $this->subTables()['ArrestType'],
            "judgment_in_lifetime" => $judgment_in_lifetime_rule,
            "judgment_in_years" => $judgment_in_years_rule,
            "judgment_in_months" => $judgment_in_months_rule,
            "education_level" => 'nullable|in:' . $this->subTables()['EducationLevel'],
            "health_note" => (isset($this->state['special_case']['مريض / جريح']) && $this->state['special_case']['مريض / جريح']) ? "required" : "nullable",
            "father_arrested" => 'nullable|boolean',
            "mother_arrested" => 'nullable|boolean',
            "husband_arrested" => 'nullable|boolean',
            "wife_arrested" => 'nullable|boolean',
            "brother_arrested" => 'nullable|integer',
            "sister_arrested" => 'nullable|integer',
            "son_arrested" => 'nullable|integer',
            "daughter_arrested" => 'nullable|integer',
            'belong_id' => "nullable|in:" . $this->subTables()['Belongs'],
            "special_case" => 'nullable',
            'social_type' => "nullable|in:" . $this->subTables()['SocialType'],
            'wife_type' => "nullable|in:" . $this->subTables()['WifeType'],
            'number_of_children' => "nullable|integer",
            'first_phone_owner' => "required",
            'first_phone_number' => "required",
            'second_phone_owner' => "nullable",
            'second_phone_number' => "nullable",
            'is_released' => "nullable|in:0,1",
            'email' => "nullable",
        ]);
        $oldArrestsValidation = Validator::make($this->old_arrests, [
            '*.old_arrest_start_date' => 'nullable|required_with:*.old_arrest_end_date,*.arrested_side',
            '*.old_arrest_end_date' => 'nullable|required_with:*.old_arrest_start_date,*.arrested_side',
            '*.arrested_side' => 'nullable|in:' . $this->subTables()['ArrestedSide'] . '|required_with:*.old_arrest_start_date,*.old_arrest_end_date',
        ]);

        if ($validation->fails() || $oldArrestsValidation->fails()) {
            $validation->validate();
            $this->old_errors = $oldArrestsValidation->getMessageBag()->toArray();
            $oldArrestsValidation->validate();
        }
    }

    public function manipulateData(): array
    {

        if (isset($this->state['date_of_birth']) && $this->state['date_of_birth'] === "") {
            $this->state['date_of_birth'] = null;
        }

        if (isset($this->state['date_of_birth']) && $this->state['date_of_birth'] !== "") {
            $this->state['date_of_birth'] = Carbon::parse($this->state['date_of_birth'])->format('Y-m-d');
        }

        if (isset($this->state['arrest_start_date']) && $this->state['arrest_start_date'] === "") {
            $this->state['arrest_start_date'] = null;
        }

        if (isset($this->state['arrest_start_date']) && $this->state['arrest_start_date'] !== "") {
            $this->state['arrest_start_date'] = Carbon::parse($this->state['arrest_start_date'])->format('Y-m-d');
        }

        if (isset($this->state['is_released']) && $this->state['is_released'] == "اختر...") {
            $this->state['is_released'] = null;
        }

        if (isset($this->state['gender']) && $this->state['gender'] == "اختر...") {
            $this->state['gender'] = null;
        }

        if (isset($this->state['city_id']) && $this->state['city_id'] == "اختر...") {
            $this->state['city_id'] = null;
        }

        if (isset($this->state['town_id']) && $this->state['town_id'] == "اختر...") {
            $this->state['town_id'] = null;
        }

        if (isset($this->state['belong_id']) && $this->state['belong_id'] == "اختر...") {
            $this->state['belong_id'] = null;
        }

        if (isset($this->state['social_type']) && $this->state['social_type'] == "اختر...") {
            $this->state['social_type'] = null;
        }

        if (isset($this->state['education_level']) && $this->state['education_level'] == "اختر...") {
            $this->state['education_level'] = null;
        }


        if (isset($this->state['social_type']) && $this->state['social_type'] == "أعزب") {
            $this->state['wife_type'] = null;
            $this->state['number_of_children'] = null;
        }
        if (isset($this->state['gender']) && $this->state['gender'] == "انثى") {
            $this->state['wife_type'] = null;
        }
        if (isset($this->state['social_type']) && $this->state['social_type'] == "مطلق") {
            $this->state['wife_type'] = null;
        }
        if (isset($this->state['arrest_type']) && $this->state['arrest_type'] == "إداري") {
            $this->state['judgment_in_lifetime'] = null;
            $this->state['judgment_in_years'] = null;
            $this->state['judgment_in_months'] = null;
        }
        if (isset($this->state['special_case']) && !in_array('أقارب معتقلين', array_filter(array_keys($this->state['special_case'])))) {
            $this->state['father_arrested'] = null;
            $this->state['mother_arrested'] = null;
            $this->state['husband_arrested'] = null;
            $this->state['wife_arrested'] = null;
            $this->state['brother_arrested'] = null;
            $this->state['sister_arrested'] = null;
            $this->state['son_arrested'] = null;
            $this->state['daughter_arrested'] = null;
        }

        if (!isset($this->state['father_arrested'])) {
            $this->state['father_arrested_id'] = null;
        }
        if (!isset($this->state['mother_arrested'])) {
            $this->state['mother_arrested_id'] = null;
        }
        if (!isset($this->state['husband_arrested'])) {
            $this->state['husband_arrested_id'] = null;
        }
        if (!isset($this->state['wife_arrested'])) {
            $this->state['wife_arrested_id'] = null;
        }
        if (!isset($this->state['sister_arrested']) || $this->state['sister_arrested'] == 0) {
            $this->state['sister_arrested_id'] = null;
        }
        if (!isset($this->state['brother_arrested']) || $this->state['brother_arrested'] == 0) {
            $this->state['brother_arrested_id'] = null;
        }
        if (!isset($this->state['son_arrested']) || $this->state['son_arrested'] == 0) {
            $this->state['son_arrested_id'] = null;
        }
        if (!isset($this->state['daughter_arrested']) || $this->state['daughter_arrested'] == 0) {
            $this->state['daughter_arrested_id'] = null;
        }
        if (isset($this->state['special_case']) && !in_array('مريض / جريح', array_filter(array_keys($this->state['special_case'])))) {
            $this->state['health_note'] = null;
        }
        if (isset($this->state['special_case']) && in_array('حامل', array_filter(array_keys($this->state['special_case']))) && isset($this->state['gender']) && $this->state['gender'] == 'ذكر') {
            $this->state['special_case']['حامل'] = false;
        }

        return $this->state;
    }

    /**
     * @throws ValidationException
     */
    public function UpdateAcceptAdmin(): void
    {
        $this->Review();
        $this->suggestion->update([
            'identification_number' => $this->state['identification_number'] ?? null,
            'first_name' => $this->state['first_name'] ?? null,
            'second_name' => $this->state['second_name'] ?? null,
            'third_name' => $this->state['third_name'] ?? null,
            'last_name' => $this->state['last_name'] ?? null,
            'mother_name' => $this->state['mother_name'] ?? null,
            'nick_name' => $this->state['nick_name'] ?? null,
            'date_of_birth' => $this->state['date_of_birth'] ?? null,
            'gender' => $this->state['gender'] ?? null,
            'city_id' => $this->state['city_id'] ?? null,
            'town_id' => $this->state['town_id'] ?? null,
            'notes' => $this->state['notes'] ?? null,
        ]);

        $this->suggestion->ArrestSuggestion->update([
            "arrest_start_date" => $this->state['arrest_start_date'] ?? null,
            "arrest_type" => $this->state['arrest_type'] ?? null,
            "judgment_in_lifetime" => $this->state['judgment_in_lifetime'] ?? null,
            "judgment_in_years" => $this->state['judgment_in_years'] ?? null,
            "judgment_in_months" => $this->state['judgment_in_months'] ?? null,
            "belong_id" => $this->state['belong_id'] ?? null,
            'special_case' => !empty($this->state['special_case']) ? implode(',', array_keys(array_filter($this->state['special_case']))) : null,
            "social_type" => $this->state['social_type'] ?? null,
            "wife_type" => $this->state['wife_type'] ?? null,
            "number_of_children" => $this->state['number_of_children'] ?? null,
            "education_level" => $this->state['education_level'] ?? null,
            "health_note" => $this->state['health_note'] ?? null,
            "father_arrested" => $this->state['father_arrested'] ?? null,
            "mother_arrested" => $this->state['mother_arrested'] ?? null,
            "husband_arrested" => $this->state['husband_arrested'] ?? null,
            "wife_arrested" => $this->state['wife_arrested'] ?? null,
            "brother_arrested" => $this->state['brother_arrested'] ?? null,
            "sister_arrested" => $this->state['sister_arrested'] ?? null,
            "son_arrested" => $this->state['son_arrested'] ?? null,
            "daughter_arrested" => $this->state['daughter_arrested'] ?? null,
            "first_phone_owner" => $this->state['first_phone_owner'] ?? null,
            "first_phone_number" => $this->state['first_phone_number'] ?? null,
            "second_phone_owner" => $this->state['second_phone_owner'] ?? null,
            "second_phone_number" => $this->state['second_phone_number'] ?? null,
            'is_released' => isset($this->state['is_released']) ? (boolean)$this->state['is_released'] : 0,
            "email" => $this->state['email'] ?? null,
        ]);

        $old_ = OldArrestSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->pluck('id')->toArray();

        foreach ($old_ as $id) {
            OldArrestSuggestion::query()->find($id)->forceDelete();
        }
        if (!empty($this->old_arrests)) {
            foreach (array_filter($this->old_arrests) as $old) {
                if (!empty($old)) {
                    if (!empty($old['old_arrest_start_date']) && !empty($old['old_arrest_end_date']) && !empty($old['arrested_side'])) {
                        OldArrestSuggestion::query()->create([
                            'suggestion_status' => "يحتاج مراجعة",
                            'prisoner_id' => $this->state['prisoner_id'] ?? null,
                            'prisoner_suggestion_id' => $this->suggestion->id ?? null,
                            'old_arrest_start_date' => $old['old_arrest_start_date'] ?? null,
                            'old_arrest_end_date' => $old['old_arrest_end_date'] ?? null,
                            'arrested_side' => $old['arrested_side'] ?? null,
                        ]);
                    }
                }
            }
        }

        $father_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'اب')
            ->pluck('id')->toArray();


        foreach ($father_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }
        if (!empty($this->state['father_arrested_id']))
            FamilyIDNumberSuggestion::query()->create([
                'id_number' => $this->state['father_arrested_id'],
                'relationship_name' => "اب",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_suggestion_id' => $this->suggestion->id,
                'suggestion_status' => "يحتاج مراجعة",
            ]);

        $mother_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'ام')
            ->pluck('id')->toArray();


        foreach ($mother_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }
        if (!empty($this->state['mother_arrested_id']))
            FamilyIDNumberSuggestion::query()->create([
                'id_number' => $this->state['mother_arrested_id'],
                'relationship_name' => "ام",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_suggestion_id' => $this->suggestion->id,
                'suggestion_status' => "يحتاج مراجعة",
            ]);


        $husband_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'زوج')
            ->pluck('id')->toArray();


        foreach ($husband_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }

        if (!empty($this->state['husband_arrested_id']))
            FamilyIDNumberSuggestion::query()->create([
                'id_number' => $this->state['husband_arrested_id'],
                'relationship_name' => "زوج",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_suggestion_id' => $this->suggestion->id,
                'suggestion_status' => "يحتاج مراجعة",
            ]);

        $wife_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'زوجة')
            ->pluck('id')->toArray();


        foreach ($wife_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }
        if (!empty($this->state['wife_arrested_id']))
            FamilyIDNumberSuggestion::query()->create([
                'id_number' => $this->state['wife_arrested_id'],
                'relationship_name' => "زوجة",
                'prisoner_id' => $this->state['prisoner_id'] ?? null,
                'prisoner_suggestion_id' => $this->suggestion->id,
                'suggestion_status' => "يحتاج مراجعة",
            ]);

        $brother_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'اخ')
            ->pluck('id')->toArray();


        foreach ($brother_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }

        if (!empty($this->state['brother_arrested_id']))
            foreach ($this->state['brother_arrested_id'] as $row) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "اخ",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $this->suggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

        $sister_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'اخت')
            ->pluck('id')->toArray();


        foreach ($sister_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }

        if (!empty($this->state['sister_arrested_id']))
            foreach ($this->state['sister_arrested_id'] as $row) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "اخت",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $this->suggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

        $son_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'ابن')
            ->pluck('id')->toArray();


        foreach ($son_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }

        if (!empty($this->state['son_arrested_id']))
            foreach ($this->state['son_arrested_id'] as $row) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "ابن",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $this->suggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

        $daughter_arrested_id_ = FamilyIDNumberSuggestion::query()
            ->where('prisoner_suggestion_id', $this->suggestion->id)
            ->where('relationship_name', 'ابنه')
            ->pluck('id')->toArray();


        foreach ($daughter_arrested_id_ as $id) {
            FamilyIDNumberSuggestion::query()->find($id)->forceDelete();
        }

        if (!empty($this->state['daughter_arrested_id']))
            foreach ($this->state['daughter_arrested_id'] as $row) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $row,
                    'relationship_name' => "ابنه",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $this->suggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

        $this->dispatch('show_admin_update_massage');
        $this->dispatch('hide_create_update_modal');
    }

    public
    function SearchFor($search): void
    {
        $this->resetPage();
        $this->sortBy = null;
        $this->Search = $search;
    }

    private
    function removeAndMoveToFamilyIDNumberSuggestionDeletedList($id): void
    {
        $this->moveItem($this->familyIDNumberColumns['prisoner'], $this->familyIDNumberColumns['prisoner_deleted'], $id);
    }
}
