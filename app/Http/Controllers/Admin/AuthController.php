<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AuthRequest;
use App\Repositories\AccountRepository;
use App\Repositories\PasswordResetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AuthController extends BaseController
{
    protected AccountRepository $accountRepository;
    protected PasswordResetRepository $passwordResetRepository;

    public function __construct(AccountRepository $accountRepository, PasswordResetRepository $passwordResetRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }
    public function index()
    {
        Session::put('locale', 'ja');
        if (Auth::user()) {
            return redirect('/admin/learning');
        }
        return view('admin.auth.login');
    }
    public function logout()
    {
        Session::flush();
        Auth::logout();

        return Redirect('admin/login');
    }
    public function doLogin(AuthRequest $request)
    {
        $check_account = $this->accountRepository->checkCaseInsensitiveAccount($request->account);
        if ($check_account) {
            $credentials = ['student_code' => $request['account'], 'password' => $request['password']];
            if (Auth::attempt($credentials)) {
                Session::put('locale', 'ja');
                if (Auth::user()->role_id == Consts::STUDENT) {
                    auth()->logout();
                    return redirect()->back()->with('error', __('response.Unauthorized'));
                }
                if(Auth::user()->status == Consts::INACTIVE){
                    auth()->logout();
                    return redirect()->back()->with('error', __('response.Unauthorized'));
                }
                if (Auth::user()->check_first_login == 0) {
                    return Redirect('/admin/change-password');
                }
            }
        }
        return redirect()->back()->with('error', __('response.Invalid'));
    }
    public function switchLanguage($lang)
    {
        Session::put('locale', $lang);
        return redirect::back();
    }
}
