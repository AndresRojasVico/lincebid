<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AtonController extends Controller
{
    //


    public function upload(Request $request)
    {
        $request->validate([
            'atom_file' => 'required|file|mimes:xml',
        ]);

        // Obtener el archivo del request
        $file = $request->file('atom_file');

        // Generar un nombre único para el archivo
        //$fileName = time() . '_' . $file->getClientOriginalName();
        $fileName = 'atom';
        // Guardar el archivo en el disco 'atoms'
        Storage::disk('atoms')->put('atoms/' . $fileName, file_get_contents($file));


        // Opcional: Obtener la ruta del archivo guardado
        $filePath = Storage::disk('atoms')->path('atoms/' . $fileName);

        // Opcional: Obtener la URL del archivo guardado (si es un disco público)
        $fileUrl = Storage::disk('public')->url('atoms/' . $fileName);




        //etiqueta de los codigos ContractFolderStatus -> ProcurementProject  -> RequiredCommodityClassification
        return view('dashboard');
    }
}
