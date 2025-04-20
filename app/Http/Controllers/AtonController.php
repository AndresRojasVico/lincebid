<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Proyecto;
use DateTime;

class AtonController extends Controller
{
    //
    public function loadContenUrl()
    {


        $url = "https://contrataciondelsectorpublico.gob.es/sindicacion/sindicacion_643/licitacionesPerfilesContratanteCompleto3.atom"; // URL del XML
        $xml = simplexml_load_file($url);

        if ($xml === false) {
            die("Error cargando XML");
        } else {
            echo "cargado";
        }

        $data = [];
        $contador = 0;



        foreach ($xml->entry as $entry) {
            $contador++;
            // Obtener los espacios de nombres
            $namespaces = $entry->getNamespaces(true);

            $contractFolderID = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cbc'])->ContractFolderID;
            //ver linea 107 del xml en esa linea esta el dato del impote

            $importe = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->BudgetAmount->children($namespaces['cbc'])->TaxExclusiveAmount;
            //recorro los codigos de la entrada y los meto en un array
            $codes = [];
            $co = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->RequiredCommodityClassification;
            foreach ($co as $code) {
                $codes[] = (string) $code->children($namespaces['cbc'])->ItemClassificationCode;
            }

            //filtrar por codigos 
            $impresoras = [];
            $portatiles = [];
            $desarrollo = [72000000, 72100000];
            $diseñoWeb = [72413000, 72413000, 72414000, 72415000, 72416000, 72417000, 72420000, 72421000, 72422000];
            // uno todos los arrays que me interesan en uno solo llamado codigos usando la funcion array_merge
            $codigos = array_merge($impresoras, $portatiles, $desarrollo, $diseñoWeb);



            foreach ($codes as $code) {

                if (in_array($code, $codigos)) {
                    $data[] = [
                        'fecha_update' => (string) $xml->updated,
                        'id' => (string) $entry->id,
                        'link' => (string) $entry->link['href'],
                        'summary' => (string) $entry->summary,
                        'title' => (string) $entry->title,
                        'updated' => (string) $entry->updated,
                        'ContractFolderID' => $contractFolderID,
                        'importe' => $importe,
                        'codigos' => $codes,
                        'contador' => $contador,
                    ];
                }
            }
        }
        // Retornar la vista con los datos procesados
        return view('proyectos.proyectosUrl', compact('data'));
    }




    public function upload(Request $request)
    {
        //esta funcion se encarga de subir el archivo aton al disco virtual
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



    public function loadConten()
    // Esta funcion se encarga de cargar el contenido xml del fichero atons 
    {
        // Especifica el nombre del archivo que deseas cargar
        $fileName = '/atoms/atom';

        // Verifica si el archivo existe en el disco 'atoms'
        if (Storage::disk('atoms')->exists($fileName)) {
            // Obtener el contenido del archivo
            $xmlContent = Storage::disk('atoms')->get($fileName);

            // Procesar el contenido del archivo XML
            $xml = new \SimpleXMLElement($xmlContent);

            $data = [];
            $contador = 0;
            foreach ($xml->entry as $entry) {

                // Obtener los espacios de nombres
                $namespaces = $entry->getNamespaces(true);

                // Obtener los datos de la entrada
                //sumario
                $summary = $entry->summary;
                //identificador
                $identificador = $entry->id;
                preg_match('/(\d+)$/', $identificador, $matches);
                $identificador = $matches[1];
                //link
                $link = $entry->link['href'];
                // fecha de actualizacion
                //$fecha_update =  $entry->updated;// Fecha original
                $fecha_update = (new DateTime($entry->updated))->format('Y-m-d H:i:s'); // Convertir al formato 'YYYY-MM-DD HH:MM:SS'

                //vigente/anulada/archivada   
                //Primera publicación
                //Estado	  --PRE=ANUNCIO PREVIO -- PUB=EN PLAZO -- EV=PENDIENTE DE ADJUDICACION -- ADJ=ADJUDICADA -- RES=RESUELTA -- ANU=ANULADA -- ARC=ARCHIVADA
                $estado = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cbc-place-ext'])->ContractFolderStatusCode;
                switch ($estado) {
                    case 'PRE':
                        $estado = 'ANUNCIO PREVIO';
                        break;
                    case 'PUB':
                        $estado = 'EN PLAZO';
                        break;
                    case 'EV':
                        $estado = 'PENDIENTE DE ADJUDICACION';
                        break;
                    case 'ADJ':
                        $estado = 'ADJUDICADA';
                        break;
                    case 'RES':
                        $estado = 'RESUELTA';
                        break;
                    case 'ANU':
                        $estado = 'ANULADA';
                        break;
                    case 'ARC':
                        $estado = 'ARCHIVADA';
                        break;
                    default:
                        $estado = 'DESCONOCIDO';
                        break;
                }
                //Número de expediente
                $contractFolderID = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cbc'])->ContractFolderID;
                //Objeto del Contrato
                //Valor estimado del contrato	
                //Presupuesto base sin impuestos	
                //Presupuesto base con impuestos	
                //CPV	
                //Tipo de contrato	
                //Lugar de ejecución	
                //Órgano de Contratación	
                //ID OC en PLACSP	
                //NIF OC	
                //DIR3	
                //Enlace al Perfil de Contratante del OC	
                //Tipo de Administración	
                //Código Postal	
                //Tipo de procedimiento	
                //Sistema de contratación	
                //Tramitación	
                //Forma de presentación de la oferta	
                //Fecha de presentación de ofertas	
                //Fecha de presentación de solicitudes de participacion	
                //Directiva de aplicación	
                //Financiación Europea y fuente	
                //Descripción de la financiación europea	
                //Subcontratación permitida	
                //Subcontratación permitida porcentaje
                //importe
                $importe = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->BudgetAmount->children($namespaces['cbc'])->TaxExclusiveAmount;
                //recorro los codigos de la entrada y los meto en un array
                $codes = [];
                $co = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cac'])->RequiredCommodityClassification;
                foreach ($co as $code) {
                    $codes[] = (string) $code->children($namespaces['cbc'])->ItemClassificationCode;
                }

                //filtrar por codigos 
                $impresoras = [];
                $portatiles = [];
                $desarrollo = [72000000, 72100000];
                $diseñoWeb = [72413000, 72413000, 72414000, 72415000, 72416000, 72417000, 72420000, 72421000, 72422000];
                // uno todos los arrays que me interesan en uno solo llamado codigos usando la funcion array_merge
                $codigos = array_merge($impresoras, $portatiles, $desarrollo, $diseñoWeb);



                foreach ($codes as $code) {

                    //filtrado  de codigos con el array codigos
                    if (in_array($code, $codigos)) {
                        $data[] = [
                            'id' => (string) $contractFolderID,
                            'link' => (string) $link,
                            'summary' => (string) $summary,
                            'fecha_actualizacion' => $fecha_update,
                            'vigente_anulada_archivada' => "desconocido",
                            'estado' => (string) $estado,
                            'importe' => (string) $importe,
                        ];
                    }
                }
            } // Close the foreach loop que recorre todas las entradas 
            //GUARDO EN LA BASE DE DATS 
            $proyecto = new Proyecto();
            foreach ($data as $proyec) {
                // Verificar si el registro ya existe en la base de datos
                $proyectoExistente = Proyecto::find($proyec['id']); // Busca por la clave primaria (id)

                if (!$proyectoExistente) {
                    // Si no existe, crea un nuevo registro
                    $proyecto = new Proyecto();
                    $proyecto->id = $proyec['id'];
                    $proyecto->link_licitacion = $proyec['link'];
                    $proyecto->summary = $proyec['summary'];
                    $proyecto->fecha_actualizacion = $proyec['fecha_actualizacion'];
                    $proyecto->vigente_anulada_archivada = $proyec['vigente_anulada_archivada'];
                    $proyecto->estado = $proyec['estado'];
                    $proyecto->presupuesto_sin_impuestos = $proyec['importe'];

                    // Guardar el nuevo registro

                    ($proyecto);
                    $proyecto->save();

                    // Imprimir en pantalla para probar
                    echo "Nuevo registro guardado:<br>";
                    echo "id: " . $proyecto->id . '<br>';
                    echo "link: " . $proyecto->link_licitacion . '<br>';
                    echo "summary: " . $proyecto->summary . '<br>';
                    echo "fecha de actualizacion: " . $proyecto->fecha_actualizacion . '<br>';
                    echo "vigente/anulada/archivada: " . $proyecto->vigente_anulada_archivada . '<br>';
                    echo "estado: " . $proyecto->estado . '<br>';
                    echo '<hr>';
                }
            }

            return view('dashboard', ['message' => 'Datos guardados correctamente']);
        } else { // end comprobar si existe el archivo
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    }
}
