<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ErrorValidateUser implements FromView
{
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function view(): View
    {
        return view('exports.error_validate_user', [
            'errors' => $this->errors
        ]);
    }
}
