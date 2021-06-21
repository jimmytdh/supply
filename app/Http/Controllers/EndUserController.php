<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    static function getEndUserInfo($id)
    {
        $info = User::select('users.fname','users.lname','section.description')
                    ->where('users.id',$id)
                    ->leftJoin('section','section.id','=','users.section')
                    ->first();
        if($info)
            return $info;
        return 0;
    }
}
