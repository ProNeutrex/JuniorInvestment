<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RedeController extends Controller
{
    public function index()
    {
        $data = DB::table('level_referrals')
        ->whereIn('type', ['deposit', 'investment'])
        ->get();
        return view('frontend.rede', ['data' => $data]);
    }
}

