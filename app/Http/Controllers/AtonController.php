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
                //Lugar de ejecución	
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
                $fechaPuplicacion = $entry->children($namespaces['cac-place-ext'])
                    ?->ContractFolderStatus
                    ?->children($namespaces['cac-place-ext'])
                    ?->ValidNoticeInfo
                    ?->children($namespaces['cac-place-ext'])
                    ?->AdditionalPublicationStatus
                    ?->children($namespaces['cac-place-ext'])
                    ?->AdditionalPublicationDocumentReference
                    ?->children($namespaces['cbc'])
                    ?->IssueDate;

                //Forma de presentación de la oferta
                // $fechapresentacion =  ;


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
                // 30000000 MAQUINAS, EQUIPOS Y ARTICULOS DE OFICINA Y DE INFORMATICA,EXCEPTO MUEBLES Y PAQUETES DE SOFWARE 
                // 30100000-Máquinas, equipo y artículos de oficina, excepto ordenadores, impresoras y mobiliario.
                // 30110000-Máquinas de tratamiento de textos.
                // 30111000-Procesadores de texto.
                // 30120000-Fotocopiadoras, máquinas offset e impresoras.
                //30121000-Fotocopiadoras y aparatos termocopiadores.
                // 30121100-Fotocopiadoras.
                // 30121200-Equipo de fotocopiado.
                // 30121300-Equipo de reproducción.
                // 30121400-Máquinas de sacar copias.
                // 30121410-Conmutadores teléfono-fax.
                // 30121420-Escáneres digitales con sistema de envío.
                // 30121430-Duplicadores digitales.
                // 30130000-Equipo para oficinas de correos.
                // 30140000-Máquinas de calcular y máquinas de contabilidad.
                // 30150000-Máquinas de escribir.
                // 30160000-Tarjetas magnéticas.
                // 30170000-Etiquetadoras.
                // 30180000-Máquinas de escribir y endosar cheques.
                // 30190000-Equipo y artículos de oficina diversos.
                // 30120000-Fotocopiadoras, máquinas offset e impresoras.  

                // 30200000-Equipo y material informático.++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // 30210000-Máquinas procesadoras de datos (hardware).

                //30211000-Ordenador «mainframe».
                // 30211100-Superordenador.
                // 30211200-Equipo informático de unidad central.
                // 30211300-Plataformas informáticas.
                // 30211400-Configuraciones informáticas.
                // 30211500-Unidad central de procesamiento (CPU) o procesadores.

                //30212000-Equipo informático para miniordenadores.
                //30212100-Unidades centrales de proceso para miniordenadores.



                // 30220000-Equipo de cartografía digital.

                // 30230000-Equipo relacionado con la informática.

                $equipos = [30000000, 30100000, 30110000, 30111000, 30120000, 30121000, 30121100, 30121200, 30121300, 30121400, 30121410, 30121420, 30121430, 30122000, 30122100, 3012220030123000, 30123100, 30123200, 30123300, 30123400, 30123500, 30123600, 30123610, 30123620, 30123630, 30124000, 30124100, 30124110, 30124120, 30124130, 30124140, 30124150, 30124200, 30124300, 30124300];
                //************REVISADO*/
                $equipoInformatica =  [30200000, 30210000, 30211000, 30211100, 30211200, 30211300, 30211400, 30211500, 30212000, 30212100, 30213000, 30213100, 30213200, 30213300, 30213400, 30213500, 30214000, 30215000, 30215100];
                // LECTORES MAGNETICOS Y OPTICOS
                $lectoresMagneticos = [30216000, 30216100, 30216110, 30216120, 30216130, 30216200, 30216200];

                $equiposRelacionadosInformatica = [30230000];
                $pantallasOrdenadorConsolas = [30231000, 30231100, 30231200, 30231300, 30231310, 30231320];
                $perifericos = [30232000, 30232100, 30232600, 30232700, 30232110, 30232120, 30232130, 30232140, 30232150, 30232600, 30232700];

                $dispositivosMultimediAlmacenamientoLectura = [30233000, 30233100, 30233110, 30233120, 30233130, 30233131, 30233132, 30233140, 30233141, 30233150, 30233151, 30233152, 30233153, 30233160, 30233161, 30233170, 30233180, 30233190, 30233300, 30233310, 30233320, 30234000, 30234100, 30234200, 30234300, 30234400, 30234500, 30234600, 30234700, 30236000, 30236100, 30236110, 30236111, 30236112, 30236113, 30236114, 30236115, 30236120, 30236121, 30236122, 30236123, 30236200];
                $partesAccesorios = [30237000, 30237100, 30237110, 30237120, 30237121, 30237130, 30237131, 30237132, 30237133, 30237134, 30237135, 30237136, 302371400];

                //accesorios para escaner y impresoras
                //30000000-Máquinas, equipo y artículos de oficina y de informática, excepto mobiliario y paquetes de software.
                //30100000-Máquinas, equipo y artículos de oficina, excepto ordenadores, impresoras y mobiliario.
                //	30111000-Procesadores de texto.
                $impresoras = [30000000, 30100000, 30111000, 30100000, 30120000, 30121000, 30121100, 30121200, 30121300, 30121400, 30121410, 30121420, 30121430, 3012000, 30122100, 30122200, 30123000, 30123100, 30123200, 30123300, 30123400, 30123500, 30123600, 30123610, 30123620, 30123630, 30124000, 30124000, 30124100, 30124110, 30124120, 30124130, 30124140, 30124150, 30124200, 30124300, 30124400, 30124500, 30124510, 30124520, 30124530, 30125000, 30125100, 30125110, 30125120, 30125130, 30124500, 30124510, 30124520, 30124530, 30125000, 30125100, 30125110, 30125120, 30125130, 30130000];
                $desarrollo = [72000000, 72100000];
                $diseñoWeb = [72413000, 72413000, 72414000, 72415000, 72416000, 72417000, 72420000, 72421000, 72422000];
                // uno todos los arrays que me interesan en uno solo llamado codigos usando la funcion array_merge
                $codigos = array_merge($impresoras, $equipos, $desarrollo, $diseñoWeb);




                foreach ($codes as $code) {

                    //filtrado  de codigos con el array codigos
                    if (in_array($code, $codigos)) {
                        $data[] = [
                            'id' => (string) $contractFolderID,
                            'link' => (string) $link,
                            'summary' => (string) $summary,
                            'fecha_actualizacion' => $fecha_update,
                            'fecha_puplicacion' => $fechaPuplicacion,
                            'vigente_anulada_archivada' => "desconocido",
                            'estado' => (string) $estado,
                            'importe' => (string) $importe,
                            'organo_contratacion' => (string) $organoContratacion,
                            'objeto_contrato' => (string) $objetoContrato,
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
                    $proyecto->vigente_anulada_archivada = $proyec['vigente_anulada_archivada'];
                    $proyecto->estado = $proyec['estado'];
                    $proyecto->presupuesto_sin_impuestos = $proyec['importe'];
                    $proyecto->organo_contratacion = $proyec['organo_contratacion'];
                    $proyecto->objeto_contrato = $proyec['objeto_contrato'];

                    // Guardar el nuevo registro
                    $proyecto->save();
                }
                echo "el organo de contratacion :" . $proyecto->organo_contratacion . "La fecha de publicacion es:" . $proyecto->fecha_publicacion . "<br>";
            }

            return view('dashboard', ['message' => 'Datos guardados correctamente']);
        } else { // end comprobar si existe el archivo
            return response()->json(['message' => 'Archivo no encontrado'], 404);
        }
    }
}
