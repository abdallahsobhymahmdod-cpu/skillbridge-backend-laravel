<?php

namespace App\Http\Controllers;
use App\models\article;
use Illuminate\Http\Request;

class articlecontroller extends Controller
{
    //

    function createA(Request $req){
        $a = article::create([
            "title" => "first article",
            "body" => "this is the body of the first article",
        ]);
        return $a;
    }

    function getA(){
        $a = article::all();
        return $a;
    }


    function geetA($id){
        $a = article::find($id);
        return $a;
    }
}
