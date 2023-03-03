<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ErrorValidateQuestion implements FromView
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
