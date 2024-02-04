<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\ArrestedSide;
use App\Enums\ArrestType;
use App\Enums\DefaultEnum;
use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\SocialType;
use App\Enums\SpecialCase;
use App\Enums\WifeType;
use App\Models\Belong;
use App\Models\City;
use App\Models\FamilyIDNumberSuggestion;
use App\Models\OldArrestSuggestion;
use App\Models\Prisoner;
use App\Models\PrisonerSuggestion;
use App\Models\PrisonerType;
use App\Models\Relationship;
use App\Models\Town;
use App\Rules\PalestineIdValidationRule;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateUpdateSuggestions extends Component
{
    public array $state = [];
    public array $old_arrests = [
        []
    ];

    public $old_errors = null;
    public object $Prisoners_;
    public bool $showEdit = false;
    public $googleUrl;

    public function mount($suggestion = null): void
    {
        if ($suggestion) {
            $loadedPrisoner = Prisoner::with('City', 'PrisonerType', 'OldArrest', 'Arrest', 'FamilyIDNumber')->find($suggestion);
            if ($loadedPrisoner) {
                $this->edit($loadedPrisoner);
            }
        }
    }

    public function edit($suggestion = null): void
    {
        if ($suggestion) {
            $this->Prisoners_ = $suggestion;

            $father_arrested_id = $suggestion->FamilyIDNumber->where('relationship_name', 'اب')->pluck('id_number')->first() ?? null;
            $mother_arrested_id = $suggestion->FamilyIDNumber->where('relationship_name', 'ام')->pluck('id_number')->first() ?? null;
            $husband_arrested_id = $suggestion->FamilyIDNumber->where('relationship_name', 'زوج')->pluck('id_number')->first() ?? null;
            $wife_arrested_id = $suggestion->FamilyIDNumber->where('relationship_name', 'زوجة')->pluck('id_number')->first() ?? null;

            $brother_arrested_values = $suggestion->FamilyIDNumber->where('relationship_name', 'اخ')->pluck('id_number')->toArray();
            $brother_arrested_ids = [];
            foreach ($brother_arrested_values as $key => $value) {
                $brother_arrested_ids[$key + 1] = $value;
            }

            $sister_arrested_values = $suggestion->FamilyIDNumber->where('relationship_name', 'اخت')->pluck('id_number')->toArray();
            $sister_arrested_ids = [];
            foreach ($sister_arrested_values as $key => $value) {
                $sister_arrested_ids[$key + 1] = $value;
            }

            $son_arrested_values = $suggestion->FamilyIDNumber->where('relationship_name', 'ابن')->pluck('id_number')->toArray();
            $son_arrested_ids = [];
            foreach ($son_arrested_values as $key => $value) {
                $son_arrested_ids[$key + 1] = $value;
            }

            $daughter_arrested_values = $suggestion->FamilyIDNumber->where('relationship_name', 'ابنه')->pluck('id_number')->toArray();
            $daughter_arrested_ids = [];
            foreach ($daughter_arrested_values as $key => $value) {
                $daughter_arrested_ids[$key + 1] = $value;
            }


            $data = $suggestion->toArray();
            $this->state = [
                "id" => $data['id'] ?? null,
                "prisoner_id" => $data['id'] ?? null,
                "suggester_name" => $data['suggester_name'] ?? null,
                "suggester_identification_number" => $data['suggester_identification_number'] ?? null,
                "suggester_phone_number" => $data['suggester_phone_number'] ?? null,
                "relationship_id" => $data['relationship_id'] ?? null,
                "identification_number" => null,
                "first_name" => $data['first_name'] ?? null,
                "second_name" => $data['second_name'] ?? null,
                "third_name" => $data['third_name'] ?? null,
                "last_name" => $data['last_name'] ?? null,
                "mother_name" => $data['mother_name'] ?? null,
                "nick_name" => $data['nick_name'] ?? null,
                "date_of_birth" => $data['date_of_birth'] ?? null,
                "gender" => $data['gender'] ?? null,
                "city_id" => $data['city_id'] ?? null,
                "town_id" => $data['town_id'] ?? null,
                "notes" => $data['notes'] ?? null,

                "arrest_start_date" => $data['arrest']['arrest_start_date'] ?? null,
                "arrest_type" => $data['arrest']['arrest_type'] ?? null,
                "judgment_in_lifetime" => $data['arrest']['judgment_in_lifetime'] ?? null,
                "judgment_in_years" => $data['arrest']['judgment_in_years'] ?? null,
                "judgment_in_months" => $data['arrest']['judgment_in_months'] ?? null,
                "belong_id" => $data['arrest']['belong_id'] ?? null,
                "special_case" => array_fill_keys(explode(',' ?? null, $data['arrest']['special_case']) ?? null, true) ?? null,
                "health_note" => $data['arrest']['health_note'] ?? null,
                "social_type" => $data['arrest']['social_type'] ?? null,
                "wife_type" => $data['arrest']['wife_type'] ?? null,
                "number_of_children" => $data['arrest']['number_of_children'] ?? null,
                "education_level" => $data['arrest']['education_level'] ?? null,
                "father_arrested" => (bool)$data['arrest']['father_arrested'] ?? null,
                "mother_arrested" => (bool)$data['arrest']['mother_arrested'] ?? null,
                "husband_arrested" => (bool)$data['arrest']['husband_arrested'] ?? null,
                "wife_arrested" => (bool)$data['arrest']['wife_arrested'] ?? null,
                "brother_arrested" => $data['arrest']['brother_arrested'] ?? null,
                "sister_arrested" => $data['arrest']['sister_arrested'] ?? null,
                "son_arrested" => $data['arrest']['son_arrested'] ?? null,
                "daughter_arrested" => $data['arrest']['daughter_arrested'] ?? null,
                "first_phone_owner" => $data['arrest']['first_phone_owner'] ?? null,
                "first_phone_number" => $data['arrest']['first_phone_number'] ?? null,
                "second_phone_owner" => $data['arrest']['second_phone_owner'] ?? null,
                "second_phone_number" => $data['arrest']['second_phone_number'] ?? null,
                "is_released" => isset($data['arrest']['is_released']) ? (boolean)$data['arrest']['is_released'] : null,
                "email" => $data['arrest']['email'] ?? null,

                "father_arrested_id" => $father_arrested_id,
                "mother_arrested_id" => $mother_arrested_id,
                "husband_arrested_id" => $husband_arrested_id,
                "wife_arrested_id" => $wife_arrested_id,
                "brother_arrested_id" => $brother_arrested_ids,
                "sister_arrested_id" => $sister_arrested_ids,
                "son_arrested_id" => $son_arrested_ids,
                "daughter_arrested_id" => $daughter_arrested_ids,

            ];
            $this->old_arrests = $data['old_arrest'];

            $this->showEdit = true;
        }
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Belongs = Belong::all()->sortBy('belong_name');
        $Cities = City::all()->sortBy('city_name');
        $city = !empty($this->state['city_id']) ? $this->state['city_id'] : null;
        $Towns = Town::query()->where('city_id', $city)->orderBy('town_name')->get();
        $Relationships = Relationship::all()->sortBy('id');
        return view('livewire.dashboard.main.create-update-suggestions', compact('Belongs', 'Cities', 'Towns', 'Relationships'));

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

    /**
     * @throws ValidationException
     */
    public function ReviewMassage(): void
    {
        $this->oldArrestManipulateData();

        $this->validateData();

        $this->manipulateData();

        $this->dispatchAction();
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
        $rule = $this->showEdit
            ? ["required", "min:9", "max:9", new PalestineIdValidationRule, "unique:prisoners,identification_number,{$this->state['id']},id,deleted_at,NULL"]
            : ["required", "min:9", "max:9", new PalestineIdValidationRule, "unique:prisoners,identification_number,NULL,id,deleted_at,NULL"];
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
            //Suggester
            'suggester_name' => "required",
            'suggester_identification_number' => ["required", "min:9", "max:9", new PalestineIdValidationRule],
            'suggester_phone_number' => "required",
            'relationship_id' => "required",
            //Prisoner
            'identification_number' => $rule,
            'first_name' => 'required',
            'second_name' => 'nullable',
            'third_name' => 'nullable',
            'last_name' => 'required',
            'mother_name' => "nullable",
            'nick_name' => "nullable",
            'date_of_birth' => "nullable",
            'gender' => "required|in:" . $this->subTables()['Gender'],
            'city_id' => "nullable|in:" . $this->subTables()['City'],
            'town_id' => "nullable|in:" . $this->subTables()['Town'],
            'prisoner_type' => "nullable",
            'notes' => "nullable",
            //Arrest
            "arrest_start_date" => 'required',
            "arrest_type" => 'nullable|in:' . $this->subTables()['ArrestType'],
            "judgment_in_lifetime" => $judgment_in_lifetime_rule,
            "judgment_in_years" => $judgment_in_years_rule,
            "judgment_in_months" => $judgment_in_months_rule,
            "education_level" => 'nullable|in:' . $this->subTables()['EducationLevel'],
            "health_note" => 'nullable',
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
        ], [
            'last_name.unique' => 'يوجد اسم كامل مشابه في قاعدة البيانات',
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

    public function subTables(): array
    {
        return [
            'Relationship' => Relationship::query()->pluck('id')->implode(','),
            'ArrestType' => join(",", array_column(ArrestType::cases(), 'value')),
            'Belongs' => Belong::query()->pluck('id')->implode(','),
            'SocialType' => join(",", array_column(SocialType::cases(), 'value')),
            'WifeType' => join(",", array_column(WifeType::cases(), 'value')),
            'City' => City::query()->pluck('id')->implode(','),
            'Town' => Town::query()->pluck('id')->implode(','),
            'PrisonerType' => PrisonerType::query()->pluck('id')->implode(','),
            'Gender' => join(",", array_column(Gender::cases(), 'value')),
            'DefaultEnum' => join(",", array_column(DefaultEnum::cases(), 'value')),
            'EducationLevel' => join(",", array_column(EducationLevel::cases(), 'value')),
            'SpecialCase' => join(",", array_column(SpecialCase::cases(), 'value')),
            'ArrestedSide' => join(",", array_column(ArrestedSide::cases(), 'value')),
        ];
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

    private function dispatchAction(): void
    {
        $this->dispatch('ReviewMassage');
    }

    public function openGoogleModal($full_name, $identification_number): void
    {
        $url = "https://docs.google.com/forms/d/e/1FAIpQLSfmpVKLKaav-jRfpER4ntQjM1bd2hNhclDFwnuy1fRy0WiwNQ/viewform";
        $url .= "?entry.1999411339=" . urlencode($full_name);
        $url .= "&entry.1095886086=" . urlencode($identification_number);

        $this->googleUrl = $url;
        $this->dispatch('open_google_modal');
    }

    public function goToIndex(): void
    {
        $this->dispatch('open_go_to_index_modal');
    }

    public function ConfirmMassage(): void
    {
        try {
            $this->createPrisoner();

            $this->Done();
        } catch (\Exception $e) {
            abort(403, 'هنالك مشكلة في تأكيد العملية تواصل مع الدعم الفني');
        }
    }

    private function createPrisoner(): void
    {
        DB::beginTransaction();
        try {
            $PrisonerSuggestion = PrisonerSuggestion::query()->create([
                'suggestion_status' => "يحتاج مراجعة",
                'suggester_name' => $this->state['suggester_name'] ?? null,
                'suggester_identification_number' => $this->state['suggester_identification_number'] ?? null,
                'suggester_phone_number' => $this->state['suggester_phone_number'] ?? null,
                'relationship_id' => $this->state['relationship_id'] ?? null,
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
            if (!empty(array_filter($this->old_arrests))) {
                foreach (array_filter($this->old_arrests) as $arrest) {
                    if (!empty($arrest['old_arrest_start_date']) && !empty($arrest['old_arrest_end_date']))
                        OldArrestSuggestion::query()->create([
                            'suggestion_status' => "يحتاج مراجعة",
                            'prisoner_id' => $this->Prisoners_->id ?? null,
                            'prisoner_suggestion_id' => $PrisonerSuggestion->id ?? null,
                            'old_arrest_start_date' => $arrest['old_arrest_start_date'] ?? null,
                            'old_arrest_end_date' => $arrest['old_arrest_end_date'] ?? null,
                            'arrested_side' => $arrest['arrested_side'] ?? null,
                        ]);
                }
            }
            $PrisonerSuggestion->ArrestSuggestion()->create([
                'suggestion_status' => "يحتاج مراجعة",
                'prisoner_suggestion_id' => $PrisonerSuggestion->id ?? null,
                'prisoner_id' => $this->Prisoners_->id ?? null,
                'arrest_start_date' => $this->state['arrest_start_date'] ?? null,
                'arrest_type' => $this->state['arrest_type'] ?? null,
                'judgment_in_lifetime' => $this->state['judgment_in_lifetime'] ?? null,
                'judgment_in_years' => $this->state['judgment_in_years'] ?? null,
                'judgment_in_months' => $this->state['judgment_in_months'] ?? null,

                'belong_id' => $this->state['belong_id'] ?? null,
                'special_case' => !empty($this->state['special_case']) ? implode(',', array_keys(array_filter($this->state['special_case']))) : null,

                'social_type' => $this->state['social_type'] ?? null,
                'wife_type' => $this->state['wife_type'] ?? null,
                'number_of_children' => $this->state['number_of_children'] ?? null,

                'education_level' => $this->state['education_level'] ?? null,
                'health_note' => $this->state['health_note'] ?? null,

                'father_arrested' => $this->state['father_arrested'] ?? null,
                'mother_arrested' => $this->state['mother_arrested'] ?? null,
                'husband_arrested' => $this->state['husband_arrested'] ?? null,
                'wife_arrested' => $this->state['wife_arrested'] ?? null,

                'brother_arrested' => $this->state['brother_arrested'] ?? null,
                'sister_arrested' => $this->state['sister_arrested'] ?? null,
                'son_arrested' => $this->state['son_arrested'] ?? null,
                'daughter_arrested' => $this->state['daughter_arrested'] ?? null,

                'first_phone_owner' => $this->state['first_phone_owner'] ?? null,
                'first_phone_number' => $this->state['first_phone_number'] ?? null,
                'second_phone_owner' => $this->state['second_phone_owner'] ?? null,
                'second_phone_number' => $this->state['second_phone_number'] ?? null,

                'is_released' => isset($this->state['is_released']) ? (boolean)$this->state['is_released'] : null,

                'email' => $this->state['email'] ?? null,
            ]);
            if (isset($this->state) && isset($this->state['father_arrested_id'])) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $this->state['father_arrested_id'],
                    'relationship_name' => "اب",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

            if (isset($this->state) && isset($this->state['mother_arrested_id'])) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $this->state['mother_arrested_id'],
                    'relationship_name' => "ام",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

            if (isset($this->state) && isset($this->state['husband_arrested_id'])) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $this->state['husband_arrested_id'],
                    'relationship_name' => "زوج",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }
            if (isset($this->state) && isset($this->state['wife_arrested_id'])) {
                FamilyIDNumberSuggestion::query()->create([
                    'id_number' => $this->state['wife_arrested_id'],
                    'relationship_name' => "زوجة",
                    'prisoner_id' => $this->state['prisoner_id'] ?? null,
                    'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                    'suggestion_status' => "يحتاج مراجعة",
                ]);
            }

            if (isset($this->state) && isset($this->state['brother_arrested_id'])) {
                foreach ($this->state['brother_arrested_id'] as $row) {
                    FamilyIDNumberSuggestion::query()->create([
                        'id_number' => $row,
                        'relationship_name' => "اخ",
                        'prisoner_id' => $this->state['prisoner_id'] ?? null,
                        'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                        'suggestion_status' => "يحتاج مراجعة",
                    ]);
                }
            }

            if (isset($this->state) && isset($this->state['sister_arrested_id'])) {
                foreach ($this->state['sister_arrested_id'] as $row) {
                    FamilyIDNumberSuggestion::query()->create([
                        'id_number' => $row,
                        'relationship_name' => "اخت",
                        'prisoner_id' => $this->state['prisoner_id'] ?? null,
                        'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                        'suggestion_status' => "يحتاج مراجعة",
                    ]);
                }
            }

            if (isset($this->state) && isset($this->state['son_arrested_id'])) {
                foreach ($this->state['son_arrested_id'] as $row) {
                    FamilyIDNumberSuggestion::query()->create([
                        'id_number' => $row,
                        'relationship_name' => "ابن",
                        'prisoner_id' => $this->state['prisoner_id'] ?? null,
                        'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                        'suggestion_status' => "يحتاج مراجعة",
                    ]);
                }
            }

            if (isset($this->state) && isset($this->state['daughter_arrested_id'])) {
                foreach ($this->state['daughter_arrested_id'] as $row) {
                    FamilyIDNumberSuggestion::query()->create([
                        'id_number' => $row,
                        'relationship_name' => "ابنه",
                        'prisoner_id' => $this->state['prisoner_id'] ?? null,
                        'prisoner_suggestion_id' => $PrisonerSuggestion->id,
                        'suggestion_status' => "يحتاج مراجعة",
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(403, "مشكلة في اقتراح تعديل أو إضافة أسير تواصل مع الدعم الفني");
//            $massage = $e->getMessage();
//            abort(403, "مشكلة في اقتراح تعديل أو إضافة أسير تواصل مع الدعم الفني \n$massage");
        }
    }

    public function Done(): void
    {
        $this->dispatch('hideReviewMassage');
        $this->dispatch('scroll-to-top');

        if (!$this->showEdit) {
            $this->state = [];
            $this->old_arrests = [
                []
            ];
            $this->dispatch('create_massage');
        } else {
            $this->dispatch('update_massage');
        }
    }
}
