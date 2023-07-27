<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DisciplinariosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class DownloadFileController extends Controller
{
    use DisciplinariosTrait;

    /**
     * Metodo encargado de generar el documento en base 64 para descargarlo
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Se captura la información
        $data = $request->route()->parameters();

        // Se captura el uuid
        $uuid = $data["uuid"];

        // Se inicializa la consulta
        $resultadoDocumento = DB::select("SELECT PDN.UUID, PDN.UUID_NOTIFICACIONES, PDN.DOCUMENTO, PDN.RUTA
                                          FROM PORTAL_DOCUMENTO_NOTIFICACIONES PDN
                                          WHERE PDN.UUID_NOTIFICACIONES = '$uuid'");

        // Se concadena la ruta del documento
        $path = storage_path() . $resultadoDocumento[0]->ruta;

        $datos = $this->buscarDocumento($uuid);

        if($datos->error){
            // Se retorna el error
            return back()->with(
                [
                    'error' => '400',
                    'msjRespuesta' => isset($datos->msjRespuesta) ? $datos->msjRespuesta : "La ruta es invalida o no existe el archivo. " . $path,
                ]
            );
        }

        $documentoBase64 = $datos->archivo;

        $fileContents = base64_decode($documentoBase64);

        // Se declara la variable
        $nombreDocumento = date("YmdHis") . "_" . $resultadoDocumento[0]->documento;

        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $nombreDocumento . '"',
        ];

        // Retornar la respuesta de descarga
        return Response::make($fileContents, 200, $headers);

        // Se valida que exista el path
        /*if (!file_exists($path)) {

            // Se retorna el error
            return back()->with(
                [
                    'error' => '400',
                    'msjRespuesta' => "La ruta es invalida o no existe el archivo. " . $path,
                ]
            );
        }

        $headers = array(
            'Content-Type: application/pdf',
        );

        // Se declara la variable
        $nombreDocumento = date("YmdHis") . "_" . $resultadoDocumento[0]->documento;

        // Se declara el path o ruta
        $url = $path;

        return response()->download($url, $nombreDocumento, $headers);*/
    }
}
