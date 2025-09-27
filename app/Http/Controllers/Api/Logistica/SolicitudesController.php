<?php

namespace App\Http\Controllers\Api\Logistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class SolicitudesController extends Controller
{
    public function SolicitudAgrupada(Request $request)
    {
        $resultado = DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas')
            ->where('Solicitud Salidas No', $request->numeroTraslado)
            ->where('Proyecto Codigo', $request->proyecto)
            ->select(
                'Solicitud Salidas Cantidad',
                'Insumo Descripcion',
                'Solicitud Salidas No',
                'Insumo Codigo',
                'Proyecto Nombre'
            )
            ->get();

        // Agrupar por "Insumo Descripcion" y sumar "Solicitud Salidas Cantidad" sin decimales
        $agrupado = $resultado->groupBy('Insumo Descripcion')->map(function ($items, $key) {
            $primerItem = $items->first();
            return [
                'Insumo Descripcion' => $key,
                'CantidadTotal' => intval($items->sum(function ($i) {
                    return floatval($i->{"Solicitud Salidas Cantidad"});
                })),
                'Solicitud Salidas No' => $primerItem->{"Solicitud Salidas No"},
                'Proyecto Nombre' => $primerItem->{"Proyecto Nombre"},
                'Insumo Codigo' => $primerItem->{"Insumo Codigo"},
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => $agrupado
        ]);



        $resultado = DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas')
            ->select('Insumo Descripcion', 'Solicitud Salidas Cantidad')
            ->where('Solicitud Salidas No', $request->numeroTraslado)
            ->get();



        return response()->json([
            'status' => 'success',
            'data'   => $resultado
        ]);
    }

    public function SolicitudProyectos()
    {
        $resultado = DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas')
            ->select(
                'Proyecto Nombre as nombre',
                'Proyecto Codigo as codigo'
            )
            ->distinct()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $resultado
        ]);
    }

    //generar pdf arp
    public function generarPDF(Request $request)
    {
        // Obtener resultados filtrados
        $resultado = DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas')
            ->where('Solicitud Salidas No', $request->numeroTraslado)
            ->where('Proyecto Codigo', $request->proyecto)
            ->select(
                'Solicitud Salidas Cantidad',
                'Insumo Descripcion',
                'Solicitud Salidas No',
                'Insumo Codigo',
                'Usuario Nombre',
                'Fecha',
                'Proyecto Nombre'
            )
            ->get();

        // Agrupar por Insumo y sumar cantidades
        $agrupado = $resultado->groupBy('Insumo Descripcion')->map(function ($items, $key) {
            $primerItem = $items->first();
            return [
                'Insumo Descripcion' => $key,
                'CantidadTotal' => intval($items->sum(function ($i) {
                    return floatval($i->{"Solicitud Salidas Cantidad"});
                })),
                'Solicitud Salidas No' => $primerItem->{"Solicitud Salidas No"},
                'Proyecto Nombre' => $primerItem->{"Proyecto Nombre"},
                'Insumo Codigo' => $primerItem->{"Insumo Codigo"},
                'Usuario Nombre' => $primerItem->{"Usuario Nombre"},
                'Fecha' => $primerItem->{"Fecha"},

            ];
        })
            ->sortBy('Insumo Descripcion') // Orden alfabético
            ->values();

        // Obtener nombre del proyecto aunque no haya datos
        $proyectoNombre = $resultado->first()?->{"Proyecto Nombre"}
            ?? DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas') // Ajusta tabla si es necesario
            ->where('Proyecto Codigo', $request->proyecto)
            ->value('Proyecto Nombre')
            ?? '';

        //nombre de quien hace el traslado
        $usuarioNombre = $resultado->first()?->{"Usuario Nombre"}
            ?? DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas') // Ajusta tabla si es necesario
            ->where('Proyecto Codigo', $request->proyecto)
            ->value('Usuario Nombre')
            ?? '';

        //fecha del traslado
        $fechaTraslado = $resultado->first()?->{"Fecha"}
            ?? DB::connection('sqlsrv')
            ->table('ADP_DTM_VFACT.SolicitudSalidas') // Ajusta tabla si es necesario
            ->where('Proyecto Codigo', $request->proyecto)
            ->value('Fecha')
            ?? '';

        // Generar PDF
        $pdf = Pdf::loadView('pdf.solicitudes', [
            'data' => $agrupado,
            'numeroTraslado' => $request->numeroTraslado,
            'proyecto' => $proyectoNombre,
            'usuarioNombre' => $usuarioNombre,
            'fechaTraslado' => $fechaTraslado,
        ])->setPaper('a4', 'portrait');

        // Retornar PDF como descarga
        return $pdf->download("Solicitud_{$request->numeroTraslado}.pdf");
    }

    public function Prueba(){
        $resultado = "prueba de controlador y respues local";

        return response()->json([
            'status' => 'success',
            'data'   => $resultado
        ]);
    }



}
