<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class ProjectController extends Controller
{
    //
    public function index()
    {


        $proyectos = Proyecto::orderBy('fecha_actualizacion', 'desc')->get();

        return view('proyectos.index', ['proyectos' => $proyectos]);
    }

    public function show(Request $request)
    {
        $id = urldecode($request->query('id'));
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.detalle', compact('proyecto'));
    }
}
