<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Test extends Component
{
    use WithFileUploads;

    public $document;

    public function save()
    {
        dd($this->document);
    }

    public function render()
    {
        return view('livewire.test')
            ->layout('layouts.app');

    }
}
