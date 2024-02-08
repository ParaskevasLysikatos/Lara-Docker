<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function welcome(){

       // $users= User::paginate(5);

        $users=Cache::remember('users-page-'.request('page',1), 60 * 60, function(){
            return User::paginate(5);
        });

        return view('welcome', [
            'users' => $users
        ]);

    }

}
