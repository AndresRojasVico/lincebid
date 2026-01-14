<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\User;
use App\Models\Proyecto_usuario;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    //
    public function index()
    {


        $proyectos = Proyecto::where('estado', 'EN PLAZO')
            ->orderBy('fecha_presentacion', 'asc')
            ->get();

        $ultimaActualizacion = Proyecto::orderBy('updated_at', 'desc')
            ->first();


        return view('proyectos.index', ['proyectos' => $proyectos], ['ultimaActualizacion' => $ultimaActualizacion]);
    }

    public function show(Request $request)
    {
        $id = urldecode($request->query('id'));
        $proyecto = Proyecto::findOrFail($id);
        $todos_proyectos = Proyecto_usuario::all();
        foreach ($todos_proyectos as $proyecto_usuario) {
            echo "Proyecto Usuario ID: " . $proyecto_usuario->id . "<br>";
            echo "Proyecto iniciado por : " . $proyecto_usuario->usuario->name . "<br>";
            echo "<hr>";
        }
        echo "<hr>";
        echo "<br>";
        $proyectos_usuario = auth()->user()->proyectos_usuarios;
        foreach ($proyectos_usuario as $proyecto_usuario) {
            echo "el usuario actual que es :" . auth()->user()->name . " tiene este proyecto iniciado " . $proyecto_usuario->id . "<br>";
            echo "<hr>";
        }

        return view('proyectos.detalle', compact('proyecto'));
    }

    public function delete(Request $request)
    {

        $id = urldecode($request->query('id'));


        DB::table('proyectos')->where('id', '=', $id)->delete();

        return redirect()->route('proyectos.index')->with('status', 'Proyecto eliminado correctamente.');
    }
}
