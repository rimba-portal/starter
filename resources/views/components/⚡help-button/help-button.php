<?php

namespace App\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Livewire\Component;

class HelpButton extends Component implements HasActions
{
    use InteractsWithActions;

    public function render()
    {
        return view('components.help-button');
    }
}