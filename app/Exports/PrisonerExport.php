<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PrisonerExport implements FromCollection, WithHeadings, WithStyles
{
    public object $data;
    public array $column;

    public function __construct($data, $column)
    {
        $this->data = $data;
        $this->column = $column;
    }

    /**
     * @return object
     */
    public function collection(): object
    {
        return $this->data;
    }

    public function headings(): array
    {
        $selected = $this->column;
        $ar_name = [
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

            'arrest_start_date' => 'بداية الاعتقال',
            'arrest_end_date' => 'نهاية الاعتقال',
            'arrest_type' => 'نوع الاعتقال',
            'judgment_in_lifetime' => 'الحكم مؤبدات',
            'judgment_in_years' => 'الحكم سنوات',
            'judgment_in_months' => 'الحكم شهور',
            'belong_id' => 'الانتماء',
            'health_id' => 'الحالة الصحية',
            'social_type' => 'الحالة الإجتماعية',
            'wife_type' => 'عدد الزوجات',
            'number_of_children' => 'عدد الأبناء',
            'first_phone_number' => 'رقم التواصل (واتس/تلجرام)',
            'second_phone_number' => 'رقم التواصل الإضافي',
            'isReleased' => 'الإفراج',
        ];
        $export = [];
        foreach ($selected as $column) {
            foreach ($ar_name as $key => $name) {
                if ($column === $key) {
                    $export[] = $name;
                }
            }
        }
        return $export;
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        $columns = range('A', $sheet->getHighestColumn());

        foreach ($columns as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // تطبيق تصميم الخلايا بما في ذلك تكبير حجم الخط
        $sheet->getStyle('A1:Z' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'size' => 14,
            ],
        ]);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 17,
                    'color' => ['rgb' => 'f59b42'], // لون الذهبي
                ],
            ],
        ];
    }
}
