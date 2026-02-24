<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AuthLayout extends Component
{
    public $title;

    /**
     * @param string|null $title
     */
    public function __construct($title = null)
    {
        $this->title = config('app.name') . " | " . $title;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.auth');
    }
}
