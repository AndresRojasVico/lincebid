<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

// Ensure the ProyectoUsuario model exists in this namespace
use App\Models\Proyecto_usuario;


class ProyectoUsuarioController extends Controller
{
    //


    public function index()
    {
        $misProyectos = Proyecto_usuario::where('usuario_id', auth()->user()->id)->get();

        return view('proyectosUsuarios.index', ['misProyectos' => $misProyectos]);
    }
    public function newProyecto(Request $request)
    {
        $id = urldecode($request->query('id'));
        $proyecto = Proyecto::find($id);
        if (!$proyecto) {
            return redirect()->route('proyectos.index')->with('error', 'Proyecto no encontrado');
        }

        $usuario_id = $id;
        $nombre = $proyecto->organo_contratacion;
        $usuario_id = auth()->user()->id;
        $proyecto_id = $proyecto->id;
        $role = "admin";
        $estado = 1;
        $proyecto_usuario = new Proyecto_usuario();
        $proyecto_usuario->nombre = $nombre;
        $proyecto_usuario->usuario_id = $usuario_id;
        $proyecto_usuario->proyecto_id = $proyecto_id;
        $proyecto_usuario->estado_id = $estado;
        $proyecto_usuario->rol = $role;


        // Verificar si ya existe un registro para este usuario y proyecto
        $existe = Proyecto_usuario::where('usuario_id', $usuario_id)
            ->where('proyecto_id', $proyecto_id)
            ->exists();

        if ($existe) {
            return redirect()->route('proyectos.index')->with('status', 'Este proyecto ya está asignado a este usuario.');
        }
        $proyecto_usuario->save();
        return redirect()->route('proyectos.index')->with('status', 'Proyecto asignado al usuario correctamente.');
    }
}
