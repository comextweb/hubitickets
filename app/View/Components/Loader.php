<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loader extends Component
{
    public $description;

    /**
     * Create a new component instance.
     */
    public function __construct($description = null)
    {
        $this->description = $description;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.global_loader');
    }
}
