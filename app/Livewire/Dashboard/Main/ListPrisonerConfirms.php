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
use App\Models\Belong;
use App\Models\City;
use App\Models\FamilyIDNumber;
use App\Models\FamilyIDNumberConfirm;
use App\Models\OldArrest;
use App\Models\OldArrestConfirm;
use App\Models\Prisoner;
use App\Models\PrisonerConfirm;
use App\Models\PrisonerType;
use App\Models\Relationship;
use App\Models\Town;
use App\Models\User;
use App\Rules\PalestineIdValidationRule;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListPrisonerConfirms extends Component
{
    use WithPagination;

    public object $Confirms_;
    public object $Prisoner_;
    public bool $Exist = false;

    public ?string $Search = null;
    public ?string $sortBy = null;
    public array $prisonerColumns = [];
    public array $arrestColumns = [];
    public array $oldArrestColumns = [];
    public array $familyIDNumberColumns = [];


    protected string $paginationTheme = 'bootstrap';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function Accept(PrisonerConfirm $prisonerConfirm): void
    {
        $this->prisonerColumns = [];
        $this->arrestColumns = [];
        $this->oldArrestColumns = [];

        $this->Confirms_ = $prisonerConfirm;
        if (isset($this->Confirms_->prisoner_id))
            $this->Prisoner_ = Prisoner::query()->with('OldArrest', 'Arrest', 'Arrest.Belong', 'Town', 'City', 'FamilyIDNumber')->where('id', $this->Confirms_->prisoner_id)->first() ?? null;

        $Confirm_identification_number = $prisonerConfirm->identification_number;

        $this->Exist = Prisoner::query()
            ->where('identification_number', $Confirm_identification_number)
            ->exists();

        $this->prisonerColumns = [
            'رقم الهوية:' =>
                [
                    'name' => 'identification_number',
                    'confirm' => $this->Confirms_->identification_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->identification_number ?? 'لا يوجد',
                ],
            'الاسم الأول:' =>
                [
                    'name' => 'first_name',
                    'confirm' => $this->Confirms_->first_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->first_name ?? 'لا يوجد',
                ],
            'اسم الأب:' =>
                [
                    'name' => 'second_name',
                    'confirm' => $this->Confirms_->second_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->second_name ?? 'لا يوجد',
                ],
            'اسم الجد:' =>
                [
                    'name' => 'third_name',
                    'confirm' => $this->Confirms_->third_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->third_name ?? 'لا يوجد',
                ],
            'اسم العائلة:' =>
                [
                    'name' => 'last_name',
                    'confirm' => $this->Confirms_->last_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->last_name ?? 'لا يوجد',
                ],
            'اسم الأم:' =>
                [
                    'name' => 'mother_name',
                    'confirm' => $this->Confirms_->mother_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->mother_name ?? 'لا يوجد',
                ],
            'اسم آخر للعائلة:' =>
                [
                    'name' => 'nick_name',
                    'confirm' => $this->Confirms_->nick_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->nick_name ?? 'لا يوجد',
                ],
            'تاريخ الميلاد:' =>
                [
                    'name' => 'date_of_birth',
                    'confirm' => $this->Confirms_->date_of_birth ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->date_of_birth ?? 'لا يوجد',
                ],
            'الجنس:' =>
                [
                    'name' => 'gender',
                    'confirm' => $this->Confirms_->gender ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->gender ?? 'لا يوجد',
                ],
            'المحافظة:' =>
                [
                    'name' => 'city_id',
                    'confirm' => $this->Confirms_->City->city_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->City->city_name ?? 'لا يوجد',
                ],
            'البلدة:' =>
                [
                    'name' => 'town_id',
                    'confirm' => $this->Confirms_->Town->town_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Town->town_name ?? 'لا يوجد',
                ],
            'الملاحظات:' =>
                [
                    'name' => 'notes',
                    'confirm' => $this->Confirms_->notes ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->notes ?? 'لا يوجد',
                ],
        ];

        $confirm_father = 'لا يوجد'; // Default value if the property isn't set or is null

        if ($this->Confirms_->ArrestConfirm && isset($this->Confirms_->ArrestConfirm->father_arrested)) {
            $confirm_father = $this->Confirms_->ArrestConfirm->father_arrested ? 'نعم' : 'لا';
        }

        $confirm_mother = 'لا يوجد'; // Default value if the property isn't set or is null

        if ($this->Confirms_->ArrestConfirm && isset($this->Confirms_->ArrestConfirm->mother_arrested)) {
            $confirm_mother = $this->Confirms_->ArrestConfirm->mother_arrested ? 'نعم' : 'لا';
        }

        $confirm_husband = 'لا يوجد'; // Default value if the property isn't set or is null

        if ($this->Confirms_->ArrestConfirm && isset($this->Confirms_->ArrestConfirm->husband_arrested)) {
            $confirm_husband = $this->Confirms_->ArrestConfirm->husband_arrested ? 'نعم' : 'لا';
        }

        $confirm_wife = 'لا يوجد'; // Default value if the property isn't set or is null

        if ($this->Confirms_->ArrestConfirm && isset($this->Confirms_->ArrestConfirm->wife_arrested)) {
            $confirm_wife = $this->Confirms_->ArrestConfirm->wife_arrested ? 'نعم' : 'لا';
        }

        $this->arrestColumns = [
            'تاريخ الإعتقال:' =>
                [
                    'name' => 'arrest_start_date',
                    'confirm' => $this->Confirms_->ArrestConfirm->arrest_start_date ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_start_date ?? 'لا يوجد',
                ],
            'نوع الإعتقال:' =>
                [
                    'name' => 'arrest_type',
                    'confirm' => $this->Confirms_->ArrestConfirm->arrest_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->arrest_type ?? 'لا يوجد',
                ],
            'الحكم مؤبدات:' =>
                [
                    'name' => 'judgment_in_lifetime',
                    'confirm' => !empty($this->Confirms_->ArrestConfirm->judgment_in_lifetime) ? $this->Confirms_->ArrestConfirm->judgment_in_lifetime : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_lifetime) ? $this->Prisoner_->Arrest->judgment_in_lifetime : 'لا يوجد',
                ],
            'الحكم سنوات:' =>
                [
                    'name' => 'judgment_in_years',
                    'confirm' => !empty($this->Confirms_->ArrestConfirm->judgment_in_years) ? $this->Confirms_->ArrestConfirm->judgment_in_years : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_years) ? $this->Prisoner_->Arrest->judgment_in_years : 'لا يوجد',

                ],
            'الحكم أشهر:' =>
                [
                    'name' => 'judgment_in_months',
                    'confirm' => !empty($this->Confirms_->ArrestConfirm->judgment_in_months) ? $this->Confirms_->ArrestConfirm->judgment_in_months : 'لا يوجد',
                    'prisoner' => !empty($this->Prisoner_->Arrest->judgment_in_months) ? $this->Prisoner_->Arrest->judgment_in_months : 'لا يوجد',

                ],
            'الإنتماء:' =>
                [
                    'name' => 'belong_id',
                    'confirm' => $this->Confirms_->ArrestConfirm->Belong->belong_name ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->Belong->belong_name ?? 'لا يوجد',
                ],
            'حالة خاصة:' =>
                [
                    'name' => 'special_case',
                    'confirm' => $this->Confirms_->ArrestConfirm->special_case ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->special_case ?? 'لا يوجد',
                ],
            'الحالة الإجتماعية:' =>
                [
                    'name' => 'social_type',
                    'confirm' => $this->Confirms_->ArrestConfirm->social_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->social_type ?? 'لا يوجد',
                ],
            'عدد الزوجات:' =>
                [
                    'name' => 'wife_type',
                    'confirm' => $this->Confirms_->ArrestConfirm->wife_type ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->wife_type ?? 'لا يوجد',
                ],
            'عدد الأبناء:' =>
                [
                    'name' => 'number_of_children',
                    'confirm' => $this->Confirms_->ArrestConfirm->number_of_children ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->number_of_children ?? 'لا يوجد',
                ],
            'المستوى التعليمي:' =>
                [
                    'name' => 'education_level',
                    'confirm' => $this->Confirms_->ArrestConfirm->education_level ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->education_level ?? 'لا يوجد',
                ],
            'وصف المرض:' =>
                [
                    'name' => 'health_note',
                    'confirm' => $this->Confirms_->ArrestConfirm->health_note ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->health_note ?? 'لا يوجد',
                ],
            'أب معتقل:' =>
                [
                    'name' => 'father_arrested',
                    'confirm' => $confirm_father,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->father_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->father_arrested ? 'نعم' : 'لا') : null,
                ],
            'أم معتقله:' =>
                [
                    'name' => 'mother_arrested',
                    'confirm' => $confirm_mother,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->mother_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->mother_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوج معتقل:' =>
                [
                    'name' => 'husband_arrested',
                    'confirm' => $confirm_husband,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->husband_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->husband_arrested ? 'نعم' : 'لا') : null,
                ],
            'زوجة معتقله:' =>
                [
                    'name' => 'wife_arrested',
                    'confirm' => $confirm_wife,
                    'prisoner' => isset($this->Prisoner_) ? $this->Prisoner_->Arrest->wife_arrested == null ? 'لا يوجد' : ($this->Prisoner_->Arrest->wife_arrested ? 'نعم' : 'لا') : null,
                ],
            'أخ معتقل:' =>
                [
                    'name' => 'brother_arrested',
                    'confirm' => $this->Confirms_->ArrestConfirm->brother_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->brother_arrested ?? 'لا يوجد',
                ],
            'أخت معتقله:' =>
                [
                    'name' => 'sister_arrested',
                    'confirm' => $this->Confirms_->ArrestConfirm->sister_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->sister_arrested ?? 'لا يوجد',
                ],
            'ابن معتقل:' =>
                [
                    'name' => 'son_arrested',
                    'confirm' => $this->Confirms_->ArrestConfirm->son_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->son_arrested ?? 'لا يوجد',
                ],
            'ابنه معتقله:' =>
                [
                    'name' => 'daughter_arrested',
                    'confirm' => $this->Confirms_->ArrestConfirm->daughter_arrested ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->daughter_arrested ?? 'لا يوجد',
                ],
            'رقم التواصل (تلجرام/واتس):' =>
                [
                    'name' => 'first_phone_number',
                    'confirm' => $this->Confirms_->ArrestConfirm->first_phone_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->first_phone_number ?? 'لا يوجد',
                ],
            'اسم صاحب الرقم (تلجرام/واتس):' =>
                [
                    'name' => 'first_phone_owner',
                    'confirm' => $this->Confirms_->ArrestConfirm->first_phone_owner ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->first_phone_owner ?? 'لا يوجد',
                ],
            'رقم التواصل الإضافي:' =>
                [
                    'name' => 'second_phone_number',
                    'confirm' => $this->Confirms_->ArrestConfirm->second_phone_number ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->second_phone_number ?? 'لا يوجد',
                ],
            'اسم صاحب الرقم:' =>
                [
                    'name' => 'second_phone_owner',
                    'confirm' => $this->Confirms_->ArrestConfirm->second_phone_owner ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->second_phone_owner ?? 'لا يوجد',
                ],
            'مفرج عنه حالياً؟:' =>
                [
                    'name' => 'is_released',
                    'confirm' => $this->Confirms_->ArrestConfirm->is_released ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->is_released ?? 'لا يوجد',
                ],
            'البريد الإلكتروني:' =>
                [
                    'name' => 'email',
                    'confirm' => $this->Confirms_->ArrestConfirm->email ?? 'لا يوجد',
                    'prisoner' => $this->Prisoner_->Arrest->email ?? 'لا يوجد',
                ],
        ];

        $oldPrisoners = $this->Prisoner_->OldArrest ?? [];
        $oldConfirms = $this->Confirms_->OldArrestConfirm ?? [];


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
                'جهة الإعتقال:' =>
                    [
                        'name' => 'arrested_side',
                        'prisoner' => $oldPrisoner->arrested_side ?? 'لا يوجد',
                    ],
            ];
        }

        foreach ($oldConfirms as $oldConfirm) {
            $this->oldArrestColumns['confirm'][] = [
                'الرقم الأساسي:' =>
                    [
                        'name' => 'id',
                        'confirm' => $oldConfirm->id ?? 'لا يوجد',

                    ],
                'بداية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_start_date',
                        'confirm' => $oldConfirm->old_arrest_start_date ?? 'لا يوجد',
                    ],
                'نهاية الإعتقال:' =>
                    [
                        'name' => 'old_arrest_end_date',
                        'confirm' => $oldConfirm->old_arrest_end_date ?? 'لا يوجد',
                    ],
                'جهة الإعتقال:' =>
                    [
                        'name' => 'arrested_side',
                        'confirm' => $oldConfirm->arrested_side ?? 'لا يوجد',
                    ],
            ];
        }

        if (!empty($this->Prisoner_))
            $prisonerIdn = $this->Prisoner_;
        else $prisonerIdn = null;
        function formatArrestedPrisonerValues($prisonerIdn, $relationship): array
        {
            if (isset($prisonerIdn->FamilyIDNumberConfirm)) {
                $values = $prisonerIdn->FamilyIDNumberConfirm->where('relationship_name', $relationship)->pluck('id_number', 'id')->toArray();
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

        $confirmIdn = $this->Confirms_;
        function formatArrestedConfirmValues($confirmIdn, $relationship): array
        {
            if (isset($confirmIdn->FamilyIDNumberConfirm)) {
                $values = $confirmIdn->FamilyIDNumberConfirm->where('relationship_name', $relationship)->pluck('id_number', 'id')->toArray();
                $formattedIds = [];

                foreach ($values as $index => $value) {
                    $formattedIds[$index] = ["idn" => $value, "relationship_name" => $relationship];
                }

                return $formattedIds;
            }
            return [];
        }

        $father_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'اب');
        $mother_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'ام');
        $husband_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'زوج');
        $wife_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'زوجة');
        $brother_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'اخ');
        $sister_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'اخت');
        $son_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'ابن');
        $daughter_arrested_confirm_ids = formatArrestedConfirmValues($confirmIdn, 'ابنه');


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
            "confirm" => [
                "father_arrested_confirm_ids" => $father_arrested_confirm_ids,
                "mother_arrested_confirm_ids" => $mother_arrested_confirm_ids,
                "husband_arrested_confirm_ids" => $husband_arrested_confirm_ids,
                "wife_arrested_confirm_ids" => $wife_arrested_confirm_ids,
                "brother_arrested_confirm_ids" => $brother_arrested_confirm_ids,
                "sister_arrested_confirm_ids" => $sister_arrested_confirm_ids,
                "son_arrested_confirm_ids" => $son_arrested_confirm_ids,
                "daughter_arrested_confirm_ids" => $daughter_arrested_confirm_ids,
            ],
        ];

        $this->dispatch('ShowAcceptModal');
    }

    /**
     * @throws ValidationException
     */
    public function ConfirmAccept(): void
    {
        $ConfirmsArray = $this->Confirms_->toArray() ?? [];
        $ConfirmsArrestArray = isset($this->Confirms_->ArrestConfirm) ? $this->Confirms_->ArrestConfirm->toArray() : [];
        $PrisonerOldArrestArray = isset($this->Prisoner_) ? $this->Prisoner_->OldArrest->toArray() : [];
        $ConfirmsOldArrestArray = $this->Confirms_->OldArrestConfirm->toArray() ?? [];


        $validation = Validator::make($ConfirmsArray, [
            'identification_number' => ["nullable",new PalestineIdValidationRule],
            'first_name' => "nullable",
            'second_name' => "nullable",
            'third_name' => "nullable",
            'last_name' => "nullable",
            'mother_name' => "nullable",
            'nick_name' => "nullable",
            'date_of_birth' => "nullable",
            'gender' => "nullable|in:" . $this->subTables()['Gender'],
            'city_id' => "nullable|in:" . $this->subTables()['City'],
            'town_id' => "nullable|in:" . $this->subTables()['Town'],
            'notes' => "nullable",
        ])->validate();
        $finalData = $validation;

        $validationArrest = Validator::make($ConfirmsArrestArray, [
            "confirm_status" => "nullable|in:" . $this->subTables()['SuggestionStatus'],
            "prisoner_confirm_id" => "nullable",
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
            'is_released' => "nullable|boolean",
            'email' => "nullable|email",
        ])->validate();
        $finalArrestData = $validationArrest;

        if (isset($ConfirmsArray['prisoner_id'])) {

            $Prisoner = Prisoner::query()->where('id', $ConfirmsArray['prisoner_id'])->first();
            $Arrest = $Prisoner->Arrest ?? null;
            $Prisoner->update([
                'identification_number' => $finalData['identification_number'],
                'first_name' => $finalData['first_name'],
                'second_name' => $finalData['second_name'],
                'third_name' => $finalData['third_name'],
                'last_name' => $finalData['last_name'],
                'mother_name' => $finalData['mother_name'],
                'nick_name' => $finalData['nick_name'],
                'date_of_birth' => $finalData['date_of_birth'],
                'gender' => $finalData['gender'],
                'city_id' => $finalData['city_id'],
                'town_id' => $finalData['town_id'],
                'notes' => $finalData['notes'],
            ]);
            if (isset($Arrest))
                $Arrest->update([
                    "arrest_start_date" => $finalArrestData["arrest_start_date"] ?? $Arrest["arrest_start_date"] ?? null,
                    "arrest_type" => $finalArrestData["arrest_type"] ?? $Arrest["arrest_type"] ?? null,
                    "judgment_in_lifetime" => $finalArrestData["judgment_in_lifetime"] ?? $Arrest["judgment_in_lifetime"] ?? null,
                    "judgment_in_years" => $finalArrestData["judgment_in_years"] ?? $Arrest["judgment_in_years"] ?? null,
                    "judgment_in_months" => $finalArrestData["judgment_in_months"] ?? $Arrest["judgment_in_months"] ?? null,
                    "belong_id" => $finalArrestData["belong_id"] ?? $Arrest["belong_id"] ?? null,
                    "special_case" => $finalArrestData["special_case"] ?? $Arrest["special_case"] ?? null,
                    "social_type" => $finalArrestData["social_type"] ?? $Arrest["social_type"] ?? null,
                    "wife_type" => $finalArrestData["wife_type"] ?? $Arrest["wife_type"] ?? null,
                    "number_of_children" => $finalArrestData["number_of_children"] ?? $Arrest["number_of_children"] ?? null,
                    "education_level" => $finalArrestData["education_level"] ?? $Arrest["education_level"] ?? null,
                    "health_note" => $finalArrestData["health_note"] ?? $Arrest["health_note"] ?? null,
                    "father_arrested" => $finalArrestData["father_arrested"] ?? $Arrest["father_arrested"] ?? null,
                    "mother_arrested" => $finalArrestData["mother_arrested"] ?? $Arrest["mother_arrested"] ?? null,
                    "husband_arrested" => $finalArrestData["husband_arrested"] ?? $Arrest["husband_arrested"] ?? null,
                    "wife_arrested" => $finalArrestData["wife_arrested"] ?? $Arrest["wife_arrested"] ?? null,
                    "brother_arrested" => $finalArrestData["brother_arrested"] ?? $Arrest["brother_arrested"] ?? null,
                    "sister_arrested" => $finalArrestData["sister_arrested"] ?? $Arrest["sister_arrested"] ?? null,
                    "son_arrested" => $finalArrestData["son_arrested"] ?? $Arrest["son_arrested"] ?? null,
                    "daughter_arrested" => $finalArrestData["daughter_arrested"] ?? $Arrest["daughter_arrested"] ?? null,
                    "first_phone_owner" => $finalArrestData["first_phone_owner"] ?? $Arrest["first_phone_owner"] ?? null,
                    "first_phone_number" => $finalArrestData["first_phone_number"] ?? $Arrest["first_phone_number"] ?? null,
                    "second_phone_owner" => $finalArrestData["second_phone_owner"] ?? $Arrest["second_phone_owner"] ?? null,
                    "second_phone_number" => $finalArrestData["second_phone_number"] ?? $Arrest["second_phone_number"] ?? null,
                    "is_released" => (boolean) $finalArrestData["is_released"] ?? $Arrest["is_released"] ?? null,
                    "email" => $finalArrestData["email"] ?? $Arrest["email"] ?? null,
                ]);
            else Arrest::query()->create([
                "prisoner_id" => $Prisoner->id,
                "arrest_start_date" => $finalArrestData["arrest_start_date"] ?? $Arrest["arrest_start_date"] ?? null,
                "arrest_type" => $finalArrestData["arrest_type"] ?? $Arrest["arrest_type"] ?? null,
                "judgment_in_lifetime" => $finalArrestData["judgment_in_lifetime"] ?? $Arrest["judgment_in_lifetime"] ?? null,
                "judgment_in_years" => $finalArrestData["judgment_in_years"] ?? $Arrest["judgment_in_years"] ?? null,
                "judgment_in_months" => $finalArrestData["judgment_in_months"] ?? $Arrest["judgment_in_months"] ?? null,
                "belong_id" => $finalArrestData["belong_id"] ?? $Arrest["belong_id"] ?? null,
                "special_case" => $finalArrestData["special_case"] ?? $Arrest["special_case"] ?? null,
                "social_type" => $finalArrestData["social_type"] ?? $Arrest["social_type"] ?? null,
                "wife_type" => $finalArrestData["wife_type"] ?? $Arrest["wife_type"] ?? null,
                "number_of_children" => $finalArrestData["number_of_children"] ?? $Arrest["number_of_children"] ?? null,
                "education_level" => $finalArrestData["education_level"] ?? $Arrest["education_level"] ?? null,
                "health_note" => $finalArrestData["health_note"] ?? $Arrest["health_note"] ?? null,
                "father_arrested" => $finalArrestData["father_arrested"] ?? $Arrest["father_arrested"] ?? null,
                "mother_arrested" => $finalArrestData["mother_arrested"] ?? $Arrest["mother_arrested"] ?? null,
                "husband_arrested" => $finalArrestData["husband_arrested"] ?? $Arrest["husband_arrested"] ?? null,
                "wife_arrested" => $finalArrestData["wife_arrested"] ?? $Arrest["wife_arrested"] ?? null,
                "brother_arrested" => $finalArrestData["brother_arrested"] ?? $Arrest["brother_arrested"] ?? null,
                "sister_arrested" => $finalArrestData["sister_arrested"] ?? $Arrest["sister_arrested"] ?? null,
                "son_arrested" => $finalArrestData["son_arrested"] ?? $Arrest["son_arrested"] ?? null,
                "daughter_arrested" => $finalArrestData["daughter_arrested"] ?? $Arrest["daughter_arrested"] ?? null,
                "first_phone_owner" => $finalArrestData["first_phone_owner"] ?? $Arrest["first_phone_owner"] ?? null,
                "first_phone_number" => $finalArrestData["first_phone_number"] ?? $Arrest["first_phone_number"] ?? null,
                "second_phone_owner" => $finalArrestData["second_phone_owner"] ?? $Arrest["second_phone_owner"] ?? null,
                "second_phone_number" => $finalArrestData["second_phone_number"] ?? $Arrest["second_phone_number"] ?? null,
                "is_released" => (boolean) $finalArrestData["is_released"] ?? $Arrest["is_released"] ?? null,
                "email" => $finalArrestData["email"] ?? $Arrest["email"] ?? null,
            ]);
        } else {
            $Prisoner = Prisoner::query()->create([
                'identification_number' => $finalData['identification_number'],
                'first_name' => $finalData['first_name'],
                'second_name' => $finalData['second_name'],
                'third_name' => $finalData['third_name'],
                'last_name' => $finalData['last_name'],
                'mother_name' => $finalData['mother_name'],
                'nick_name' => $finalData['nick_name'],
                'date_of_birth' => $finalData['date_of_birth'],
                'gender' => $finalData['gender'],
                'city_id' => $finalData['city_id'],
                'town_id' => $finalData['town_id'],
                'notes' => $finalData['notes'],
            ]);
            $Arrest = Arrest::query()->create([
                "prisoner_id" => $Prisoner->id,
                "arrest_start_date" => $finalArrestData["arrest_start_date"] ?? $Arrest["arrest_start_date"] ?? null,
                "arrest_type" => $finalArrestData["arrest_type"] ?? $Arrest["arrest_type"] ?? null,
                "judgment_in_lifetime" => $finalArrestData["judgment_in_lifetime"] ?? $Arrest["judgment_in_lifetime"] ?? null,
                "judgment_in_years" => $finalArrestData["judgment_in_years"] ?? $Arrest["judgment_in_years"] ?? null,
                "judgment_in_months" => $finalArrestData["judgment_in_months"] ?? $Arrest["judgment_in_months"] ?? null,
                "belong_id" => $finalArrestData["belong_id"] ?? $Arrest["belong_id"] ?? null,
                "special_case" => $finalArrestData["special_case"] ?? $Arrest["special_case"] ?? null,
                "social_type" => $finalArrestData["social_type"] ?? $Arrest["social_type"] ?? null,
                "wife_type" => $finalArrestData["wife_type"] ?? $Arrest["wife_type"] ?? null,
                "number_of_children" => $finalArrestData["number_of_children"] ?? $Arrest["number_of_children"] ?? null,
                "education_level" => $finalArrestData["education_level"] ?? $Arrest["education_level"] ?? null,
                "health_note" => $finalArrestData["health_note"] ?? $Arrest["health_note"] ?? null,
                "father_arrested" => $finalArrestData["father_arrested"] ?? $Arrest["father_arrested"] ?? null,
                "mother_arrested" => $finalArrestData["mother_arrested"] ?? $Arrest["mother_arrested"] ?? null,
                "husband_arrested" => $finalArrestData["husband_arrested"] ?? $Arrest["husband_arrested"] ?? null,
                "wife_arrested" => $finalArrestData["wife_arrested"] ?? $Arrest["wife_arrested"] ?? null,
                "brother_arrested" => $finalArrestData["brother_arrested"] ?? $Arrest["brother_arrested"] ?? null,
                "sister_arrested" => $finalArrestData["sister_arrested"] ?? $Arrest["sister_arrested"] ?? null,
                "son_arrested" => $finalArrestData["son_arrested"] ?? $Arrest["son_arrested"] ?? null,
                "daughter_arrested" => $finalArrestData["daughter_arrested"] ?? $Arrest["daughter_arrested"] ?? null,
                "first_phone_owner" => $finalArrestData["first_phone_owner"] ?? $Arrest["first_phone_owner"] ?? null,
                "first_phone_number" => $finalArrestData["first_phone_number"] ?? $Arrest["first_phone_number"] ?? null,
                "second_phone_owner" => $finalArrestData["second_phone_owner"] ?? $Arrest["second_phone_owner"] ?? null,
                "second_phone_number" => $finalArrestData["second_phone_number"] ?? $Arrest["second_phone_number"] ?? null,
                "is_released" => (boolean) $finalArrestData["is_released"] ?? $Arrest["is_released"] ?? null,
                "email" => $finalArrestData["email"] ?? $Arrest["email"] ?? null,
            ]);
        }

        $Prisoner = Prisoner::query()->where('id', $ConfirmsArray['prisoner_id'])->first();
        $finalOldArrestData = [];
        if (isset($PrisonerOldArrestArray)) {
            foreach ($PrisonerOldArrestArray as $old) {
                $finalOldArrestData[] = [
                    "id" => $old['id'],
                    "prisoner_id" => $old['prisoner_id'] ?? $Prisoner->id ?? null,
                    "old_arrest_start_date" => $old['old_arrest_start_date'] ?? null,
                    "old_arrest_end_date" => $old['old_arrest_end_date'] ?? null,
                    "arrested_side" => $old['arrested_side'] ?? null,
                ];
            }
        }
        foreach ($ConfirmsOldArrestArray as $old) {
            $finalOldArrestData[] = [
                "id" => $old['id'],
                "prisoner_id" => $old['prisoner_id'] ?? $Prisoner->id ?? null,
                "old_arrest_start_date" => $old['old_arrest_start_date'] ?? null,
                "old_arrest_end_date" => $old['old_arrest_end_date'] ?? null,
                "arrested_side" => $old['arrested_side'] ?? null,
            ];
        }
        if (!empty($finalOldArrestData) && isset($Prisoner->id)) {
            $old = OldArrest::query()
                ->where('prisoner_id', $Prisoner->id)
                ->pluck('id')->toArray();

            foreach ($old as $id) {
                OldArrest::query()->find($id)->forceDelete();
            }
            foreach ($finalOldArrestData as $row) {
                OldArrest::query()->create([
                    'prisoner_id' => $row['prisoner_id'] ?? null,
                    'old_arrest_start_date' => $row['old_arrest_start_date'] ?? null,
                    'old_arrest_end_date' => $row['old_arrest_end_date'] ?? null,
                    'arrested_side' => $row['arrested_side'] ?? null,
                ]);
                OldArrestConfirm::query()
                    ->where('id', $row['id'])
                    ->update(['confirm_status' => 'تم القبول']);
            }
        }

        $finalArrestedIdn = [];
        if (isset($this->familyIDNumberColumns)) {
            foreach ($this->familyIDNumberColumns['prisoner'] as $prisoner) {
                foreach ($prisoner as $key => $row)
                    $finalArrestedIdn[] = [
                        "id" => $key,
                        "prisoner_id" => $old['prisoner_id'] ?? $Prisoner->id ?? null,
                        "id_number" => $row['idn'],
                        "relationship_name" => $row['relationship_name'],
                    ];
            }
        }
        if (isset($this->familyIDNumberColumns)) {
            foreach ($this->familyIDNumberColumns['confirm'] as $prisoner) {
                foreach ($prisoner as $key => $row)
                    $finalArrestedIdn[] = [
                        "id" => $key,
                        "prisoner_id" => $old['prisoner_id'] ?? $Prisoner->id ?? null,
                        "id_number" => $row['idn'],
                        "relationship_name" => $row['relationship_name'],
                    ];
            }
        }
        if (!empty($finalArrestedIdn) && isset($Prisoner->id)) {
            foreach ($finalArrestedIdn as $row) {
                FamilyIDNumber::query()->create([
                    'prisoner_id' => $Prisoner->id ?? null,
                    'id_number' => $row['id_number'] ?? null,
                    'relationship_name' => $row['relationship_name'] ?? null,
                ]);
                FamilyIDNumberConfirm::query()
                    ->where('id', $row['id'])
                    ->update(['confirm_status' => 'تم القبول']);
            }
        }

        $this->Confirms_->update(['confirm_status' => 'تم القبول']);
        $this->Confirms_->ArrestConfirm->update(['confirm_status' => 'تم القبول']);

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
            'ArrestedSide' => join(",", array_column(ArrestedSide::cases(), 'value')),
        ];
    }

    public function confirmDelete(): void
    {
        $this->Confirms_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(PrisonerConfirm $prisonerConfirm): void
    {
        $this->Confirms_ = $prisonerConfirm;

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

        $Confirms = $this->getConfirmsProperty()
            ->where(function ($query) use ($cityIdArray) {
                $query->whereIn('city_id', $cityIdArray)
                    ->orWhereNull('city_id');
            })
            ->when(isset($this->sortBy), function ($q) {
            if ($this->sortBy == "تم القبول")
                $q->where('confirm_status', "تم القبول");
            elseif ($this->sortBy == "يحتاج مراجعة")
                $q->where('confirm_status', 'يحتاج مراجعة');
            else   $q->whereIn('confirm_status', ['تم القبول', 'يحتاج مراجعة']);
        })
            ->orderBy('confirm_status')
            ->paginate(10);

        $ConfirmCount = [
            'all' => PrisonerConfirm::query()->count(),
            'accepted' => PrisonerConfirm::query()->where('confirm_status', 'تم القبول')->count(),
            'needReview' => PrisonerConfirm::query()->where('confirm_status', 'يحتاج مراجعة')->count(),
        ];

        return view('livewire.dashboard.main.list-prisoner-confirms', compact('Confirms', 'ConfirmCount'));
    }

    public function getConfirmsProperty()
    {
        return PrisonerConfirm::query()
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
                    ->orWhere('suggester_name', 'LIKE', '%' . $this->Search . '%')
                    ->orWhere('suggester_identification_number', 'LIKE', '%' . $this->Search . '%')
                    ->orWhere('identification_number', 'LIKE', '%' . $this->Search . '%')
                    ->orWhere('gender', 'LIKE', '%' . $this->Search . '%')
                    ->orWhereHas('City', function ($q) {
                        $q->where('city_name', 'LIKE', '%' . $this->Search . '%');
                    })
                    ->orWhereHas('Relationship', function ($q) {
                        $q->where('relationship_name', 'LIKE', '%' . $this->Search . '%');
                    });
            });
    }

    public function SortBy($sort): void
    {
        $this->resetPage();
        $this->Search = null;
        $this->sortBy = $sort;
    }

}
