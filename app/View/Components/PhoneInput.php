<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PhoneInput extends Component
{
    public $divClass;
    public $label;
    public $name;
    public $placeholder;
    public $class;
    public $value;
    public $id;
    public $required;
    public $defaultCountry;

    public function __construct(
        $divClass = 'col-12',
        $name = "mobile_no",  // Cambiado para coincidir con tu campo existente
        $class = 'form-control',
        $label = null,
        $placeholder = null,
        $value = null,
        $id = null,
        $required = false,
        $defaultCountry = 'ec' // Ecuador por defecto
    ) {
        $this->divClass = $divClass;
        $this->label = $label ?? __('Mobile No');  // Mismo texto que en Mobile
        $this->name = $name;
        $this->placeholder = $placeholder ?? __('Enter Mobile No');  // Mismo placeholder
        $this->class = $class;
        $this->value = $value;
        $this->id = $id ?? 'phone_' . uniqid();
        $this->required = $required;
        $this->defaultCountry = $defaultCountry;
    }

    public function render(): View|Closure|string
    {
        return view('components.phone-input');
    }
}