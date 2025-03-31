<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use Illuminate\Routing\Controller;

class UserController extends Controller
{
    //restringir acceso a usuarios no autenticados
    public function __construct()
    {
        $this->middleware('auth');
    }


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

        $image_path = $request->file('image_path');
        //si existe la imagen
        if ($image_path) {
            //Poner nombre unico
            $image_path_name = time() . $image_path->getClientOriginalName();
            //Guardar imagen en la carpeta storage (storage/app/users)
            Storage::disk('users')->put($image_path_name, File::get($image_path));
            //Seteo el nombre de la imagen en el objeto
            $user->image = $image_path_name;

            //pendiente de trabajar con la imagen como optimizarla en tamaña y peso y eliminar la imagen anterior
        }

        //Ejecutar consulta y cambios en la base de datos
        $user->update();
        return redirect()->route('user.config')->with(['message' => 'Usuario actualizado correctamente']);
    }

    public function getImage($filename)
    {
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }
}
