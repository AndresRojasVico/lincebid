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
            $fechaPublucacion = $entry
                    ->children($namespaces['cac-place-ext'])?->ContractFolderStatus
                    ?->children($namespaces['cac-place-ext'])?->ValidNoticeInfo
                    ?->children($namespaces['cac-place-ext'])?->AdditionalPublicationStatus
                    ?->children($namespaces['cac-place-ext'])?->AdditionalPublicationDocumentReference
                    ?->children($namespaces['cbc'])?->IssueDate;

            //filtrar por codigos 
            $codigos = [30000000, 30100000, 30120000, 30121100, 30123000, 30123100, 30140000, 30141000, 30141100, 30141200, 30141300, 30150000, 30151000, 30170000, 30172000, 30190000, 30191000, 30191400, 30192000, 30192100, 30192200, 30192300, 30192400, 30192500, 30192600, 30192700, 30192800, 30192900, 30193000, 30193100, 30193200, 30197000, 30197100, 30197200, 30197300, 30197400, 30197500, 30197600, 30199000, 30199100, 30199200, 30199300, 30199400, 30199500, 30199600, 30199700, 30200000, 30210000, 30211000, 30211100, 30211200, 30211300, 30211400, 30211500, 30212000, 30212100, 30213000, 30213100, 30213200, 30213300, 30213400, 30213500, 30214000, 30215000, 30215100, 30216000, 30216100, 30230000, 30231000, 30231100, 30231200, 30231300, 30232000, 30232100, 30233000, 30233100, 30233300, 30234000, 30234100, 30234200, 30234300, 30234400, 30234500, 30234600, 30234700, 30236000, 30236100, 30236200, 30237000, 30237100, 30237200, 30237300, 30237400, 32000000, 32200000, 32250000, 32251000, 32251100, 32252000, 32252100, 32300000, 32320000, 32340000, 32341000, 32342000, 32342100, 32342200, 32342300, 32400000, 32410000, 32420000, 32421000, 32422000, 32423000, 32424000, 48000000, 51000000, 51600000, 51610000, 51611000, 51611100, 51612000, 72000000, 72100000, 72200000, 72400000, 72500000, 72600000, 72700000, 72900000];
            foreach ($codes as $code) {

                if (in_array($code, $codigos)) {
                    $data[] = [
                        'fecha_update' => (string) $xml->updated,
                        'fecha_publicacion' =>  $fechaPublucacion,
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
        //return view('user.userAdmin');
        return redirect()->route('loadConten');
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
                $objetoContrato = $entry->children($namespaces['cac-place-ext'])->ContractFolderStatus->children($namespaces['cac'])->ProcurementProject->children($namespaces['cbc'])->Name;
                //Valor estimado del contrato	
                //Presupuesto base sin impuestos	
                //Presupuesto base con impuestos	
                //CPV	
                //Tipo de contrato

                //Lugar de ejecución (ojo en agregadas no existe este nodo si ejecuto esto de error )

               /* $lugarEjecucion = $entry
                    ->children($namespaces['cac-place-ext'])->ContractFolderStatus
                    ->children($namespaces['cac-place-ext'])->LocatedContractingParty
                    ->children($namespaces['cac'])->Party
                    ->children($namespaces['cac'])->PostalAddress
                    ->children($namespaces['cbc'])->CityName;
                */


                 
                // Lugar de ejecución (Implementación Segura con XPath)
                $lugarEjecucion = "dato no disponible"; // Valor por defecto

                // 1. Registrar los namespaces necesarios para que XPath funcione.
                // Asumo que el array $namespaces ya contiene los URIs correctos.
                // Es crucial que estos prefijos (cac-place-ext, cac, cbc) se registren con sus URIs.
                $entry->registerXPathNamespace('cac-place-ext', $namespaces['cac-place-ext']);
                $entry->registerXPathNamespace('cac', $namespaces['cac']);
                $entry->registerXPathNamespace('cbc', $namespaces['cbc']);

                 // 2. Definir la consulta XPath para la ruta completa.
                 // El punto inicial (./) indica que la búsqueda comienza desde el nodo actual ($entry).
                $xpath_query = './cac-place-ext:ContractFolderStatus/cac-place-ext:LocatedContractingParty/cac:Party/cac:PostalAddress/cbc:CityName';

                 // 3. Ejecutar la consulta. Devuelve un array de objetos SimpleXMLElement (estará vacío si el nodo no existe).
                $nodos_encontrados = $entry->xpath($xpath_query);

                 // 4. Verificar el resultado.
                 if (!empty($nodos_encontrados)) {
                  // Si el array no está vacío, el nodo existe. Obtenemos el valor del primer resultado.
                  $lugarEjecucion = (string) $nodos_encontrados[0];
                  }

                // Al final, $lugarEjecucion contiene el valor o "dato no disponible"
                 
                    
                   
                //Órgano de Contratación	
                $organoContratacion = $entry
                    ->children($namespaces['cac-place-ext'])->ContractFolderStatus
                    ->children($namespaces['cac-place-ext'])->LocatedContractingParty
                    ->children($namespaces['cac'])->Party
                    ->children($namespaces['cac'])->PartyName
                    ->children($namespaces['cbc'])->Name;

                //ID OC en PLACSP	
                //NIF OC	
                //DIR3	
                //Enlace al Perfil de Contratante del OC	
                //Tipo de Administración	
                //Código Postal	
                //Tipo de procedimiento	
                //Sistema de contratación	
                //Tramitación	
                //Fecha de publicacion
                $fechaPuplicacion = $entry
                    ->children($namespaces['cac-place-ext'])?->ContractFolderStatus
                    ?->children($namespaces['cac-place-ext'])?->ValidNoticeInfo
                    ?->children($namespaces['cac-place-ext'])?->AdditionalPublicationStatus
                    ?->children($namespaces['cac-place-ext'])?->AdditionalPublicationDocumentReference
                    ?->children($namespaces['cbc'])?->IssueDate;

                //Forma de presentación de la oferta
                // $fechapresentacion =  ;


                //Fecha de presentación de ofertas
                $fechaMaximaPresentacion = $entry
                    ->children($namespaces['cac-place-ext'])?->ContractFolderStatus
                    ?->children($namespaces['cac'])?->TenderingProcess
                    ?->children($namespaces['cac'])?->TenderSubmissionDeadlinePeriod
                    ?->children($namespaces['cbc'])?->EndDate;
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

                $codigos = [30000000, 30100000, 30120000, 30121100, 30123000, 30123100, 30140000, 30141000, 30141100, 30141200, 30141300, 30150000, 30151000, 30170000, 30172000, 30190000, 30191000, 30191400, 30192000, 30192100, 30192200, 30192300, 30192400, 30192500, 30192600, 30192700, 30192800, 30192900, 30193000, 30193100, 30193200, 30197000, 30197100, 30197200, 30197300, 30197400, 30197500, 30197600, 30199000, 30199100, 30199200, 30199300, 30199400, 30199500, 30199600, 30199700, 30200000, 30210000, 30211000, 30211100, 30211200, 30211300, 30211400, 30211500, 30212000, 30212100, 30213000, 30213100, 30213200, 30213300, 30213400, 30213500, 30214000, 30215000, 30215100, 30216000, 30216100, 30230000, 30231000, 30231100, 30231200, 30231300, 30232000, 30232100, 30233000, 30233100, 30233300, 30234000, 30234100, 30234200, 30234300, 30234400, 30234500, 30234600, 30234700, 30236000, 30236100, 30236200, 30237000, 30237100, 30237200, 30237300, 30237400, 32000000, 32200000, 32250000, 32251000, 32251100, 32252000, 32252100, 32300000, 32320000, 32340000, 32341000, 32342000, 32342100, 32342200, 32342300, 32400000, 32410000, 32420000, 32421000, 32422000, 32423000, 32424000, 48000000, 51000000, 51600000, 51610000, 51611000, 51611100, 51612000, 72000000, 72100000, 72200000, 72400000, 72500000, 72600000, 72700000, 72900000];


                foreach ($codes as $code) {
                    

                    //filtrado  de codigos con el array codigos
                    if (in_array($code, $codigos)) {
                        $data[] = [
                            'id' => (string) $contractFolderID,
                            'link' => (string) $link,
                            'summary' => (string) $summary,
                            'fecha_actualizacion' => $fecha_update,
                            'fecha_puplicacion' => $fechaPuplicacion,
                            'fecha_maxima_presentacion' => $fechaMaximaPresentacion,
                            'vigente_anulada_archivada' => "desconocido",
                            'estado' => (string) $estado,
                            'importe' => (string) $importe,
                            'organo_contratacion' => (string) $organoContratacion,
                            'objeto_contrato' => (string) $objetoContrato,
                            'lugar_ejecucion' => (string) $lugarEjecucion,
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
                    $proyecto->fecha_publicacion  = $proyec['fecha_puplicacion'];
                    $proyecto->fecha_presentacion = $proyec['fecha_maxima_presentacion'];
                    $proyecto->vigente_anulada_archivada = $proyec['vigente_anulada_archivada'];
                    $proyecto->estado = $proyec['estado'];
                    $proyecto->presupuesto_sin_impuestos = $proyec['importe'];
                    $proyecto->organo_contratacion = $proyec['organo_contratacion'];
                    $proyecto->objeto_contrato = $proyec['objeto_contrato'];
                    $proyecto->lugar_ejecucion = $proyec['lugar_ejecucion'];

                    // Guardar el nuevo registro


                    $proyecto->save();
                    //esto es una marca que quiero que se vea en el equipo a 
                } else {
                    // Si el registro ya existe, actualízalo
                    echo "el proyecto con id : " . $proyectoExistente->id . "se ha actualizado  <br>";
                    $proyectoExistente->link_licitacion = $proyec['link'];
                    $proyectoExistente->summary = $proyec['summary'];
                    $proyectoExistente->fecha_actualizacion = $proyec['fecha_actualizacion'];
                    $proyectoExistente->fecha_publicacion  = $proyec['fecha_puplicacion'];
                    $proyectoExistente->fecha_presentacion = $proyec['fecha_maxima_presentacion'];
                    $proyectoExistente->vigente_anulada_archivada = $proyec['vigente_anulada_archivada'];
                    $proyectoExistente->estado = $proyec['estado'];
                    $proyectoExistente->presupuesto_sin_impuestos = $proyec['importe'];
                    $proyectoExistente->organo_contratacion = $proyec['organo_contratacion'];
                    $proyectoExistente->objeto_contrato = $proyec['objeto_contrato'];
                    $proyectoExistente->lugar_ejecucion = $proyec['lugar_ejecucion'];

                    // Guardar los cambios
                    $proyectoExistente->save();
                }
            }
            return view('dashboard', ['message' => 'Datos guardados correctamente']);
        } else { // end comprobar si existe el archivo
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    }
}
