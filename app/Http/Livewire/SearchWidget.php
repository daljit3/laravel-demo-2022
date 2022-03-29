<?php

namespace App\Http\Livewire;

use App\Models\CalorificValue;
use Livewire\Component;
use Livewire\WithPagination;

class SearchWidget extends Component
{
    use WithPagination;

    public $searchWord;

    public function updatingSearchWord()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchWord = '%'.$this->searchWord.'%';

        if (str_contains($this->searchWord, '/')) {
            $tmp = explode('/', $this->searchWord);
            $day = $tmp[0] ?? '__';
            $month = $tmp[1] ?? '__';
            $year = $tmp[2] ?? '____';
            $searchWordDate = "{$year}%-{$month}%-{$day}%";
        } else {
            $searchWordDate = $searchWord;
        }

        return view('livewire.search-widget', [
            'calorificvalues' => CalorificValue::where('area','like', $searchWord)
                                                ->orWhere('clf_value','like', $searchWord)
                                                ->orWhere('applicable_for','like', $searchWordDate)
                                                ->paginate(10)
        ]);
    }
}
