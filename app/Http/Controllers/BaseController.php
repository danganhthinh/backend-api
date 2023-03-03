<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected $urlGameService;

    public function __construct()
    {
        $this->urlGameService = config('env.urlGameService');
    }

    /**
     * @param $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message = 'success', $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $result,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($message = 'error', $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ], $code);
    }

    public function unsetTimeStamp($array = []): array
    {
         array_walk($array, function (&$a, $k){
             unset($a['created_at'], $a['updated_at'], $a['status'], $a['deleted_at'], $a['account_id']);
         });
         return $array;
    }

    public function sortUpFuncID($x , $y): int
    {
        if ($x['id'] == $y['id'])
            return 0;
        if ($x['id'] > $y['id'])
            return 1;
        else
            return -1;
    }

    public function sortDownFunc($x , $y, $key): int
    {
        if ($x[$key] == $y[$key])
            return 0;
        if ($x[$key] < $y[$key])
            return 1;
        else
            return -1;
    }


}
