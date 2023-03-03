<?php

namespace App\Http\Controllers;

use App\Exports\ErrorValidateUser;
use App\Imports\AccountsImport;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends BaseController
{
    protected AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function importData(Request $request)
    {
        $request = $request->all();
        $fileExcel = $request['file_excel'];
        try {
            $school_id = 1;
            $accountImport = new AccountsImport($school_id);
            Excel::import($accountImport, $fileExcel);
            return $this->sendResponse('Success');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e)
        {
            $failures = $e->failures();
            return Excel::download(new ErrorValidateUser($failures), 'Error_'.$fileExcel->getClientOriginalName());
        }
    }


}
