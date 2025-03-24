<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UserController extends Controller
{
    use ValidatesRequests;
    //

    public function config()
    {
        return view('user.config');
    }
    public function update(Request $request)
    {
        // Conseguir usuario identificado
        $user = Auth::user();

        // Validación del formulario
        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::user()->id],
        ]);
        //recoger datos del formulario
        $id = Auth::user()->id;
        $name = $request->input('name');
        $surname = $request->input('surname');
        $email = $request->input('email');
        //asignar nuevos valores al objeto del usuario
        $user->name = $name;
        $user->surname = $surname;
        $user->email = $email;
        //Ejecutar consulta y cambios en la base de datos
        $user->save();
        return redirect()->route('config')->with(['message' => 'Usuario actualizado correctamente']);
    }
}
