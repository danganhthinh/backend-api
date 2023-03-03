<?php

namespace App\Http\Controllers\Api;

use App\Consts;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\MobileLoginRequest;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    protected AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['password'] = Hash::make($request['password']);
        $user = $this->accountRepository->create($data);
        unset($user['display_password']);
        return $this->sendResponse([
            'user' => $user,
        ]);
    }

    public function login(MobileLoginRequest $request): JsonResponse
    {
        try {

            $check_account = $this->accountRepository->checkCaseInsensitiveAccount($request->account);

            if (!$check_account) {
                return $this->sendError('UserID が存在しません。');
            }

            if ($check_account->status === Consts::INACTIVE) {
                return $this->sendError('このアカウントは無効です。');
            }

            $loginData = [
                'student_code' => $request->account,
                'password' => $request->password
            ];

            if (!Auth::attempt($loginData)) {
                return $this->sendError('ログイン情報が正しくありません。再度入力してください。');
            }

            $accessToken = \auth()->user()->createToken($request->account, ['*'], Carbon::now()->addDay(2))->plainTextToken;
            $refreshToken = \auth()->user()->createToken($request->account.'_refresh', [''], Carbon::now()->addMonth(6))->plainTextToken;
            $user = \auth()->user();
            unset($user['display_password']);

            return $this->sendResponse([
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function me(): JsonResponse
    {
        return $this->sendResponse([
            'user' => Auth::user()
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        try {
            $token_refresh = $request->token;
            $token = PersonalAccessToken::findToken($token_refresh);
            if (!$token) {
                return $this->sendError('Unauthenticated.', 401);
            }
            $user = $token->tokenable;
            $token_name = $user->student_code;
//            $user->tokens()->delete();
            $accessToken = $user->createToken($token_name, ['*'], Carbon::now()->addDay(2))->plainTextToken;
            return $this->sendResponse([
                'access_token' => $accessToken,
                'token_type' => 'Bearer'
            ]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        if (Auth::check()) {
            $user = Auth::user()->tokens();
            return $this->sendResponse($user->delete(), 'Success logout.');
        }
        return $this->sendError('Error');
    }

    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->accountRepository->update([
                'password' => $request['password'],
                'display_password' => Str::random(15) . Crypt::encrypt($request['password']),
                'check_first_login' => 1
            ], $user->id);
            return $this->sendResponse('Success.');
        }
        catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function numberOpenApp(): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->accountRepository->update([
                'number_open_app' => intval($user->number_open_app) + 1
            ], $user->id);
            return $this->sendResponse('Success.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function updateUsageTime(Request $request): JsonResponse
    {
        try {
            $user =Auth::user();
            $this->accountRepository->update([
                'usage_time' => $user->usage_time + $request->usage_time
            ], $user->id);
            return $this->sendResponse('Success.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }
}
