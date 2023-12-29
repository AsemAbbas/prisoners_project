<?php

namespace App\Livewire;

use Livewire\Component;

class FailuresTable extends Component
{
    public $failures;

    public $perPage = 10; // Set the number of items per page
    public $currentPage = 1;

    public function mount($failures)
    {
        $this->failures = $failures->map(function ($failure) {
            return [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        })->toArray();
    }

    public function render()
    {
        $failures = collect($this->failures);

        $totalFailures = $failures->count();
        $startIndex = ($this->currentPage - 1) * $this->perPage;
        $paginatedFailures = $failures->slice($startIndex, $this->perPage);

        $ImportErrors = $paginatedFailures;

        return view('livewire.failures-table',compact('totalFailures', 'ImportErrors'));
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage()
    {
        $totalFailures = count($this->failures);
        $maxPages = ceil($totalFailures / $this->perPage);

        if ($this->currentPage < $maxPages) {
            $this->currentPage++;
        }
    }
}
