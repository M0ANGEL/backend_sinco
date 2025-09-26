<?php

namespace App\Http\Controllers\Api\Contabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PruebaConexionController extends Controller
{
    public function index()
    {
        $resultado = DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.EjecucionCliente')
            ->where('Proyecto',483)
            ->where('Numero Ejecucion Cliente',48300059)
            ->where('Item No',1.02)
            ->paginate(20); // trae de a 10 registros

        return response()->json([
            'status' => 'success',
            'data'   => $resultado
        ]);
    }
}
