<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RegisterSummonerController extends Controller
{
    public function register(Request $request){
      $summonerName = $request->input('registerSummonerName');

      // display accept message if successed.

      // display ng message if not done.
    }
}
