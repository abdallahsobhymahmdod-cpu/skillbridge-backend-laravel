<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    function mybookings(Request $req){
        return "myBookinings";
        // return "my Bookings";
    }

    function sayhello( $name){



    return "welcome , you are authenticated";
        
        // $isAuthenticated = true;

        // if($name =="Abdallah"){
        //     $isAuthenticated = true;
        // }
        // else {
        //     $isAuthenticated = false;s
        // }


        // if($isAuthenticated){
        //  return response()->json(['message' => "hello"." " . $name]);

        // }
        // else {
        //     return response()->json(["error"=>"Not Authenticated"],401) ;
        //     // return redirect('/login');
        //  }

        

        // return view('welcomeName')-> with ('requestedName',$name);



        // return "hello"." " . $name ;
        // return "abdallah";
    }

    function saywelcome( $name){

        $isAuthenticated = true;

        if($name =="Abdallah"){
            $isAuthenticated = true;
        }
        else {
            $isAuthenticated = false;
        }
        if($isAuthenticated ){
         return response()->json(['message' => "hello"." " . $name]);

        }
        else {
            return back ();
            // return redirect('/login');
         }

        

        // return view('welcomeName')-> with ('requestedName',$name);



        // return "hello"." " . $name ;
        // return "abdallah";
    }





    
}

