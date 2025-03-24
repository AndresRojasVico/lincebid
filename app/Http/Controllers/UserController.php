<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends  Controller
{
    //

    public function config()
    {
        return view('user.config');
    }
    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $name = $request->input('name');
        $email = $request->input('email');

        var_dump($name);
        var_dump($email);
        var_dump($id);
        die();
    }
}
