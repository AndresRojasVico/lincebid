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
        echo "el tipo de dato es :" . $proyectos[0]->id  . gettype($proyectos[0]->id);
        die();
        return view('proyectos.index', ['proyectos' => $proyectos]);
    }

    public function show($id)
    {


        $proyecto = Proyecto::findOrFail("001/2024/4881_V3");
        return view('proyectos.detalle', compact('proyecto'));
    }
}
