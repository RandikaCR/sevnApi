<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function getUser(Request $request){

        $out = ['name' => 'Randika De Alwis'];

        return response()->json($out);
    }
}
