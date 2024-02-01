<?php

namespace App\Imports;

use App\Models\Arrest;
use App\Models\ArrestsHealths;
use App\Models\Belong;
use App\Models\City;
use App\Models\FamilyIDNumber;
use App\Models\Health;
use App\Models\Prisoner;
use App\Models\PrisonersPrisonerTypes;
use App\Models\PrisonerType;
use App\Models\Town;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PrisonerImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithStartRow
{
    use Importable, SkipsFailures;

    public function model(array $row): void
    {
        $nameFields = ['first_name', 'second_name', 'third_name', 'last_name', 'mother_name'];

        foreach ($nameFields as $field) {
            if (!empty($row[$field])) {
                $row[$field] = $this->replaceHamza($row[$field]);
                $row[$field] = $this->replaceTaMarbuta($row[$field]);
                $row[$field] = $this->removeDiacritics($row[$field]);
            }
        }

        $father_arrested = isset($row['father_arrested']) ? !empty($row['father_arrested']) : null;
        $mother_arrested = isset($row['mother_arrested']) ? !empty($row['mother_arrested']) : null;
        $husband_arrested = isset($row['husband_arrested']) ? !empty($row['husband_arrested']) : null;
        $wife_arrested = isset($row['wife_arrested']) ? !empty($row['wife_arrested']) : null;

        $brother_arrested = !empty($row['brother_arrested']) ? count(explode(',', $row['brother_arrested'])) : null;
        $sister_arrested = !empty($row['sister_arrested']) ? count(explode(',', $row['sister_arrested'])) : null;
        $son_arrested = !empty($row['son_arrested']) ? count(explode(',', $row['son_arrested'])) : null;
        $daughter_arrested = !empty($row['daughter_arrested']) ? count(explode(',', $row['daughter_arrested'])) : null;

        $City = City::query()->where('city_name', $row['city_id'])->pluck('id')->first() ?? null;
        $Town = Town::query()->where('town_name', $row['town_id'])->pluck('id')->first() ?? null;
        $Belong = Belong::query()->where('belong_name', $row['belong_id'])->pluck('id')->first() ?? null;

        $Prisoner = Prisoner::query()->create([
            'id' => $row['id'] ?? null,
            'identification_number' => $row['identification_number'] ?? null,
            'first_name' => $row['first_name'] ?? null,
            'second_name' => $row['second_name'] ?? null,
            'third_name' => $row['third_name'] ?? null,
            'last_name' => $row['last_name'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'nick_name' => $row['nick_name'] ?? null,
            'date_of_birth' => !empty($row['date_of_birth']) ? Date::excelToDateTimeObject($row['date_of_birth']) : null,
            'gender' => $row['gender'] ?? null,
            'city_id' => $City ?? null,
            'town_id' => $Town ?? null,
            'notes' => $row['notes'] ?? null,

        ]);

        $Arrest = Arrest::query()->create([
            'prisoner_id' => $Prisoner->id ?? null,
            'arrest_start_date' => !empty($row['arrest_start_date']) ? Date::excelToDateTimeObject($row['arrest_start_date']) : null,
            'arrest_end_date' => !empty($row['arrest_end_date']) ? Date::excelToDateTimeObject($row['arrest_end_date']) : null,
            'arrest_type' => $row['arrest_type'] ?? null,
            'judgment_in_lifetime' => $row['judgment_in_lifetime'] ?? null,
            'judgment_in_years' => $row['judgment_in_years'] ?? null,
            'judgment_in_months' => $row['judgment_in_months'] ?? null,
            'belong_id' => $Belong ?? null,
            'special_case' => $row['special_case'] ?? null,
            'social_type' => $row['social_type'] ?? null,
            'wife_type' => $row['wife_type'] ?? null,
            'number_of_children' => $row['number_of_children'] ?? null,
            'education_level' => $row['education_level'] ?? null,
            'health_note' => $row['health_note'] ?? null,
            'father_arrested' => $father_arrested ?? null,
            'mother_arrested' => $mother_arrested ?? null,
            'husband_arrested' => $husband_arrested ?? null,
            'wife_arrested' => $wife_arrested ?? null,
            'brother_arrested' => $brother_arrested ?? null,
            'sister_arrested' => $sister_arrested ?? null,
            'son_arrested' => $son_arrested ?? null,
            'daughter_arrested' => $daughter_arrested ?? null,
            'first_phone_owner' => $row['first_phone_owner'] ?? null,
            'first_phone_number' => $row['first_phone_number'] ?? null,
            'second_phone_owner' => $row['second_phone_owner'] ?? null,
            'second_phone_number' => $row['second_phone_number'] ?? null,
            'IsReleased' => isset($row['IsReleased']) && $row['IsReleased'] === 'نعم' ? true : null,
            'email' => $row['email'] ?? null,
        ]);

        if (!empty($row['prisoner_type'])) {
            $prisoner_types = explode(',', $row['prisoner_type']);
            foreach ($prisoner_types as $prisoner_type) {
                $prisoner_type_id = PrisonerType::query()->where('prisoner_type_name', $prisoner_type)->pluck('id')->first() ?? null;
                if (isset($prisoner_type_id)) {
                    PrisonersPrisonerTypes::query()->create([
                        'prisoner_type_id' => $prisoner_type_id,
                        'prisoner_id' => $Prisoner->id,
                    ]);
                }
            }
        }

        $fieldGroups = [
            ['father_arrested' => 'اب', 'mother_arrested' => 'ام', 'husband_arrested' => 'زوج', 'wife_arrested' => 'زوجه'],
            ['brother_arrested' => 'اخ', 'sister_arrested' => 'اخت', 'son_arrested' => 'ابن', 'daughter_arrested' => 'ابنه'],
        ];

        foreach ($fieldGroups as $fields) {
            foreach ($fields as $field => $relationshipName) {
                $idNumber = $row[$field] ?? null;

                if ($idNumber !== null) {
                    $fieldsToCreate = explode(',', $idNumber);
                    foreach ($fieldsToCreate as $IDN) {
                        FamilyIDNumber::query()->create([
                            'id_number' => $IDN,
                            'relationship_name' => $relationshipName,
                            'prisoner_id' => $Prisoner->id,
                        ]);
                    }
                }
            }
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

    function removeDiacritics($text): array|string
    {
        $diacritics = [
            'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ّ', 'ْ', 'ٓ', 'ٰ', 'ٔ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ', '۟', 'ۦ', 'ۧ', 'ۨ', '۪', '۫', '۬', 'ۭ', 'ࣧ', '࣪', 'ࣱ', 'ࣲ', 'ࣳ', 'ࣴ', 'ࣵ', 'ࣶ', 'ࣷ', 'ࣸ', 'ࣹ', 'ࣻ', 'ࣼ', 'ࣽ', 'ࣾ', 'ؐ', 'ؑ', 'ؒ', 'ؓ', 'ؔ', 'ؕ', 'ؖ', 'ٖ', 'ٗ', 'ٚ', 'ٛ', 'ٟ'
        ];

        return str_replace($diacritics, '', $text);
    }

    public function startRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|unique:prisoners,id,NULL,id,deleted_at,NULL',
            'identification_number' => 'nullable|unique:prisoners,identification_number,NULL,id,deleted_at,NULL',
            'first_name' => 'nullable',
            'second_name' => 'nullable',
            'third_name' => 'nullable',
            'last_name' => 'nullable',
            'mother_name' => 'nullable',
            'nick_name' => 'nullable',
            'date_of_birth' => 'nullable',
            'gender' => 'nullable',
            'city_id' => 'nullable',
            'town_id' => 'nullable',
            'notes' => 'nullable',
            'prisoner_type' => 'nullable',
            'arrest_start_date' => 'nullable',
            'arrest_end_date' => 'nullable',
            'arrest_type' => 'nullable',
            'judgment_in_lifetime' => 'nullable',
            'judgment_in_years' => 'nullable',
            'judgment_in_months' => 'nullable',
            'belong_id' => 'nullable',
            'special_case' => 'nullable',
            'social_type' => 'nullable',
            'wife_type' => 'nullable',
            'number_of_children' => 'nullable',
            'education_level' => 'nullable',
            'health_note' => 'nullable',
            'father_arrested' => 'nullable',
            'mother_arrested' => 'nullable',
            'husband_arrested' => 'nullable',
            'wife_arrested' => 'nullable',
            'brother_arrested' => 'nullable',
            'sister_arrested' => 'nullable',
            'son_arrested' => 'nullable',
            'daughter_arrested' => 'nullable',
            'first_phone_owner' => 'nullable',
            'first_phone_number' => 'nullable',
            'second_phone_owner' => 'nullable',
            'IsReleased' => 'nullable',
            'email' => 'nullable',
        ];
    }
}
