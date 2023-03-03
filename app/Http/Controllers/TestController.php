<?php

namespace App\Http\Controllers;

use App\Repositories\FuriganaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    protected FuriganaRepository $FuriganaRepository;

    public function __construct()
    {
        $this->FuriganaRepository = new FuriganaRepository();
    }

    public function index(Request $request)
    {
        try {
            $text = $request->text;
            dd(Redis::get($text));
        }
        catch (\Exception $e) {
            dd($e);
        }
    }

    public function testFurigana() {
        $limelight = new \Limelight\Limelight();
        $results = $limelight->parse('庭でライムを育てています。');
        dd( $results->string('furigana'));
    }
}
