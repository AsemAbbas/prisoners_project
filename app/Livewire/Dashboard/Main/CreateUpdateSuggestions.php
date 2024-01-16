<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\ArrestType;
use App\Enums\DefaultEnum;
use App\Enums\EducationLevel;
use App\Enums\Gender;
use App\Enums\SocialType;
use App\Enums\SpecialCase;
use App\Enums\WifeType;
use App\Models\Belong;
use App\Models\City;
use App\Models\OldArrestSuggestion;
use App\Models\Prisoner;
use App\Models\PrisonerSuggestion;
use App\Models\PrisonerType;
use App\Models\Relationship;
use App\Models\Town;
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
    public object $Prisoners_;
    public bool $showEdit = false;

    public function mount($suggestion = null): void
    {
        if ($suggestion) {
            $loadedPrisoner = Prisoner::with('City', 'PrisonerType', 'OldArrest', 'Arrest')->find($suggestion);
            if ($loadedPrisoner) {
                $this->edit($loadedPrisoner);
            }
        }
    }

    public function edit($suggestion = null): void
    {
        if ($suggestion) {
            $this->Prisoners_ = $suggestion;
            $data = $suggestion->toArray();
            $this->state = [
                "id" => $data['id'] ?? null,
                "prisoner_id" => $data['id'] ?? null,
                "suggester_name" => $data['suggester_name'] ?? null,
                "suggester_identification_number" => $data['suggester_identification_number'] ?? null,
                "suggester_phone_number" => $data['suggester_phone_number'] ?? null,
                "relationship_id" => $data['relationship_id'] ?? null,
                "identification_number" => $data['identification_number'] ?? null,
                "first_name" => $data['first_name'] ?? null,
                "second_name" => $data['second_name'] ?? null,
                "third_name" => $data['third_name'] ?? null,
                "last_name" => $data['last_name'] ?? null,
                "mother_name" => $data['mother_name'] ?? null,
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
                "special_case" => array_fill_keys(explode(' ?? null,' ?? null, $data['arrest']['special_case']) ?? null, true) ?? null,
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
                "email" => $data['arrest']['email'] ?? null,
            ];

            $this->old_arrests = $data['old_arrest'];

            $this->showEdit = true;
        }
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Belongs = Belong::all();
        $Cities = City::all();
        $city = !empty($this->state['city_id']) ? $this->state['city_id'] : null;
        $Towns = Town::query()->where('city_id', $city)->get();
        $PrisonerTypes = PrisonerType::all();
        $Relationships = Relationship::all();
        return view('livewire.dashboard.main.create-update-suggestions', compact('Belongs', 'PrisonerTypes', 'Cities', 'Towns', 'Relationships'));

    }

    public function addOldArrest(): void
    {
        $this->old_arrests[] = [];
        $this->dispatch('scroll-to-bottom');
    }

    public function removeOldArrest($index): void
    {
        unset($this->old_arrests[$index]);
        $this->old_arrests = array_values($this->old_arrests);
    }

    /**
     * @throws ValidationException
     */
    public function ReviewMassage(): void
    {
        $this->validateData();

        $this->manipulateData();

        $this->dispatchAction();
    }

    /**
     * @throws ValidationException
     */
    private function validateData(): void
    {
        $rule = $this->showEdit
            ? "required|unique:prisoners,identification_number,{$this->state['id']},id,deleted_at,NULL"
            : "required|unique:prisoners,identification_number,NULL,id,deleted_at,NULL";

        $validation = Validator::make($this->state, [
            //Suggester
            'suggester_name' => "required",
            'suggester_identification_number' => "required",
            'suggester_phone_number' => "required",
            'relationship_id' => "required",
            //Prisoner
            'identification_number' => $rule,
            'first_name' => "required",
            'second_name' => "nullable",
            'third_name' => "nullable",
            'last_name' => "required",
            'mother_name' => "nullable",
            'date_of_birth' => "nullable",
            'gender' => "required|in:" . $this->subTables()['Gender'],
            'city_id' => "nullable|in:" . $this->subTables()['City'],
            'town_id' => "nullable|in:" . $this->subTables()['Town'],
            'prisoner_type' => "nullable",
            'notes' => "nullable",
            //Arrest
            "arrest_start_date" => 'required',
            "arrest_type" => 'nullable|in:' . $this->subTables()['ArrestType'],
            "judgment_in_lifetime" => 'nullable|integer',
            "judgment_in_years" => 'nullable|integer',
            "judgment_in_months" => 'nullable|integer',
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
            'email' => "nullable",
        ]);

        $oldArrestsValidation = Validator::make($this->old_arrests, [
            '*.old_arrest_start_date' => 'nullable|date',
            '*.old_arrest_end_date' => 'nullable|date',
        ]);

        if ($validation->fails() || $oldArrestsValidation->fails()) {
            $validation->validate();
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
        ];
    }

    public function manipulateData(): array
    {
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

        if (isset($this->state['special_case']) && !in_array('مريض', array_filter(array_keys($this->state['special_case'])))) {
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

    public function ConfirmMassage(): void
    {
        try {
            if (isset($this->state)) {
                $nameFields = ['first_name', 'second_name', 'third_name', 'last_name'];

                foreach ($nameFields as $field) {
                    if (isset($this->state[$field]) && !empty($this->state[$field])) {
                        $this->state[$field] = $this->replaceHamza($this->state[$field]);
                        $this->state[$field] = $this->replaceTaMarbuta($this->state[$field]);
                    }
                }
            }
            $this->createPrisoner();

            $this->Done();
        } catch (\Exception $e) {
            //dd($e);
            abort(403, 'هنالك مشكلة في تأكيد العملية تواصل مع الدعم الفني');
        }
    }

    private function replaceHamza($text): array|string
    {
        return str_replace('أ', 'ا', $text);
    }

    private function replaceTaMarbuta($text): array|string
    {
        return str_replace('ة', 'ه', $text);
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
                'date_of_birth' => $this->state['date_of_birth'] ?? null,
                'gender' => $this->state['gender'] ?? null,
                'city_id' => $this->state['city_id'] ?? null,
                'town_id' => $this->state['town_id'] ?? null,
                'notes' => $this->state['notes'] ?? null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            abort(403, 'مشكلة في إضافة بيانات الأسير تواصل مع الدعم الفني');
        }
        DB::beginTransaction();
        try {
            if (!empty($this->old_arrests)) {
                foreach ($this->old_arrests as $arrest) {
                    OldArrestSuggestion::query()->create([
                        'suggestion_status' => "يحتاج مراجعة",
                        'prisoner_id' => $this->Prisoners_->id ?? null,
                        'prisoner_suggestion_id' => $PrisonerSuggestion->id ?? null,
                        'old_arrest_start_date' => $arrest['old_arrest_start_date'] ?? null,
                        'old_arrest_end_date' => $arrest['old_arrest_end_date'] ?? null,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            abort(403, 'مشكلة في إضافة إعتقالات سابقة للأسير تواصل مع الدعم الفني');
        }
        DB::beginTransaction();
        try {
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

                'email' => $this->state['email'] ?? null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            abort(403, 'مشكلة في إضافة بيانات الإعتقال للأسير تواصل مع الدعم الفني');
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
