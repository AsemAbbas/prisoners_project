<?php

namespace App\Livewire\Main;

use App\Models\News;
use App\Models\Prisoner;
use App\Models\SocialMedia;
use App\Models\Statistic;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public array $search = [
        'identification_number' => '',
        'first_name' => '',
        'second_name' => '',
        'last_name' => '',
    ];
    public ?object $Prisoners = null;


    public ?string $error_ms = null;

    public bool $show = false;


    protected string $paginationTheme = 'bootstrap';

    public function SearchPrisoners(): void
    {
        // Replace 'أ' with 'ا' in Arabic names
        $this->search['first_name'] = $this->replaceHamza($this->search['first_name']);
        $this->search['second_name'] = $this->replaceHamza($this->search['second_name']);
        $this->search['last_name'] = $this->replaceHamza($this->search['last_name']);


        // Replace 'ة' with 'ه' in Arabic names
        $this->search['first_name'] = $this->replaceTaMarbuta($this->search['first_name']);
        $this->search['second_name'] = $this->replaceTaMarbuta($this->search['second_name']);
        $this->search['last_name'] = $this->replaceTaMarbuta($this->search['last_name']);

        // Replace 'بدون الحركات' with 'الحركات' in Arabic names
        $this->search['first_name'] = $this->removeDiacritics($this->search['first_name']);
        $this->search['second_name'] = $this->removeDiacritics($this->search['second_name']);
        $this->search['last_name'] = $this->removeDiacritics($this->search['last_name']);


        if (!empty(array_filter($this->search))) {
            $this->Prisoners = Prisoner::query()
                ->with('City', 'Arrest', 'RelativesPrisoner')
                ->where('identification_number', $this->search['identification_number'])
                ->orWhere(function ($q) {
                    $q->where('first_name', $this->search['first_name'])
                        ->where('second_name', $this->search['second_name'])
                        ->where('last_name', $this->search['last_name']);
                })
                ->get();


            if ($this->Prisoners->isEmpty()) {
                $this->Prisoners = null;
                $this->error_ms = 'لا يوجد بيانات مشابهة';
            } else {
                $this->dispatch('show_prisoners_modal');
            }
        } else {
            $this->Prisoners = null;
            $this->error_ms = 'عليك تعبئة الاسم أو رقم الهوية';
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

    public function showDetails(): void
    {
        $this->show = true;
    }

    public function hideDetails(): void
    {
        $this->show = false;
    }

    public function render()
    {
        $News = News::query()
            ->orderBy('order_by')
            ->get();
        $Statistics = Statistic::query()
            ->orderBy('order_by')
            ->get();
        $SocialMedia = SocialMedia::all();
        return view('livewire.main.index-page', compact('News', 'Statistics', 'SocialMedia'))
            ->layout('components.layouts.main');
    }

    private function replaceSpace($text): array|string
    {
        return str_replace(' ', '', $text);
    }

    private function replaceAlefMaksura($text): array|string
    {
        return str_replace('ى', 'ي', $text);
    }
}
