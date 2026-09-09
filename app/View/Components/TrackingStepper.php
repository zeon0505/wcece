<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TrackingStepper extends Component
{
    public $history;
    public $currentStatus;

    public function __construct($history = [], $currentStatus = 'waiting_arrival')
    {
        $this->history = $history;
        $this->currentStatus = $currentStatus;
    }

    public function render(): View|Closure|string
    {
        return view('components.tracking-stepper');
    }
}
