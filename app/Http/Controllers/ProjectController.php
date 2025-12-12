<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    //
    public function index()
    {


        $proyectos = Proyecto::where('estado', 'EN PLAZO')
            ->orderBy('fecha_presentacion', 'desc')
            ->get();

        $ultimaActualizacion = Proyecto::orderBy('updated_at', 'desc')
            ->first();


        return view('proyectos.index', ['proyectos' => $proyectos], ['ultimaActualizacion' => $ultimaActualizacion]);
    }

    public function show(Request $request)
    {
        $id = urldecode($request->query('id'));
        $proyecto = Proyecto::findOrFail($id);
        return view('proyectos.detalle', compact('proyecto'));
    }

    public function delete(Request $request)
    {
        
        $id = urldecode($request->query('id'));
       
      
        DB::table('proyectos')->where('id', '=', $id)->delete();
       
        return redirect()->route('proyectos.index')->with('status', 'Proyecto eliminado correctamente.');
    }
}
