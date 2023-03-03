<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function updateToken(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->user()->update(['fcm_token' => $request->token]);
            return $this->sendResponse([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return $this->sendError([
                'success' => false
            ], 500);
        }
    }
}
