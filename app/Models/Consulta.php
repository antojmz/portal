<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\QueryException;
use App\Exceptions\Handler;

use DB;
use Crypt;
use Hash;
use Log;
use DateTime;
use Session;
use Mail;
use Storage;
use Exception;

//Modelos
use App\Models\User;

class Consulta extends Authenticatable
{
    //use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    // Carga inicial de dte y buqueda en pantalla de consultas
    public function listDtes(){
        $p = Session::get('perfiles');

        switch ($p['idPerfil']) {
            // Perfil ad[ministrador]
            case 1:
                $result = DB::table('v_dtes')->whereRaw('YEAR(FechaEmision) = YEAR(NOW()) ')->get();
                break;
                
            // Perfil Cliente
            case 2:
                $result = DB::table('v_dtes')->whereRaw('IdCliente = '. $p['v_detalle'][0]->IdCliente . ' AND YEAR(FechaEmision) = YEAR(NOW()) ')->get();
                break;

            // Perfil Proveedor
            case 3:
                $result = DB::table('v_dtes')->whereRaw('IdProveedor = ' . $p['v_detalle'][0]->IdProveedor . '')->get();
                break;

            default:
                log::info("Se requieren permisos");
                $result = "Se requieren permisos";
            break;
        }
        return $result;
    }

    // Busqueda segun los graficos
    public function BusDtesGraf($IdDTE){
        $p = Session::get('perfiles');

        if($p['idPerfil']==3){
            $sql= "select * from v_dtes where IdDTE in (".$IdDTE.") and IdProveedor=".$p['v_detalle'][0]->IdProveedor;
            return DB::select($sql);
        }  
    }


    public function listBusquedaDte(){
        return DB::table('v_busq_consulta')->get();
    }
    
    public function listTipoDTE(){
        return DB::table('v_tipo_dte')->get();
    }

    public function BuscarDtes($d){
        $user= new User();
        $caso = 0;

        $d['RutCliente'] =  $user->LimpiarRut($d['RutCliente']);
        $d['RutProveedor'] =  $user->LimpiarRut($d['RutProveedor']);
        
        $p = Session::get('perfiles');
        $sql = "Select * from v_dtes where ";

        switch ($p['idPerfil']){
            // Perfil Cliente
            case 2:
                $sql .= "IdCliente=".$p['v_detalle'][0]->IdCliente;
                break;
            // Perfil Proveedor
            case 3:
                $sql .= "IdProveedor=".$p['v_detalle'][0]->IdProveedor;
                break;
        }

        foreach ($d as $key => $value) {
            if ($key<>'_token'){
                if ($value <>null){
                    $caso += 1;
                    switch ($key) {
                        case 'f_desde':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaEmision AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hasta':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaEmision AS DATE) <= '".$f_hasta."' ";
                        break;
                        case 'f_desdeR':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaRecepcion AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hastaR':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaRecepcion AS DATE) <= '".$f_hasta."' ";
                        break;                        
                        case 'f_desdeA':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaAutorizacionSII AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hastaA':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaAutorizacionSII AS DATE) <= '".$f_hasta."' ";
                        break;
                        case 'f_desdeO':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaOC AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hastaO':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaOC AS DATE) <= '".$f_hasta."' ";
                        break;
                        case 'f_desdeP':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaPago AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hastaP':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaPago AS DATE) <= '".$f_hasta."' ";
                        break;
                        case 'f_desdeV':
                            $f_desde = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaVencimiento AS DATE) >= '".$f_desde."'";
                        break;
                        case 'f_hastaV':
                            $f_hasta = $this->formatearFecha($value);
                            $sql .= " and CAST(FechaVencimiento AS DATE) <= '".$f_hasta."' ";
                        break;
                        case 'existencia':
                            if ($value==1){
                                $sql .= " and ExistenciaSII=1 ";
                            }
                            if ($value==2){
                                $sql .= " and ExistenciaSII=1 and ExistenciaPaperles=1 ";
                            }
                        break;
                        case 'TipoAcuse':
                                $sql .= " and TipoAcuse=".$value."";
                        break;
                        case 'SelectDTE':
                                $sql .= " and TipoDTE=".$value."";
                        break;
                        case 'selectEstado':
                                $sql .= " and IdEstadoDTE=".$value."";
                        break;
                        default:
                            $sql .= " and upper(".$key.") like '%".$value."%'";
                        break;
                    }
                }  
            }   
        }

        if ($caso==0){
            $result['status']='{"code":"-1","des_code":"Debe seleccionar al menos un item."}';
            return $result;
        }

        $result['status']='{"code":"204","des_code":"No cotent"}';
        $result['data']= DB::select($sql);
        return $result;
    }

    public function BuscarDetalle($id){
        $result['v_dte_detalles'] = DB::table('v_dte_detalles')->where('IdDTE',$id)->get();
        $result['v_dte_estados'] = DB::table('v_dte_estados')->where('IdDTE',$id)->get();
        $result['v_dte_referencias'] = DB::table('v_dte_referencias')->where('IdDTE',$id)->get();
        return $result;
    }

    public function BuscarTraza($id){
        $result['v_dte_estados'] = DB::table('v_dte_estados')->where('IdDTE',$id)->get();
        return $result;
    }

    
    public function formatearFecha($d){
        $formato = explode("-", $d);
        $fecha = $formato[2]."-".$formato[1]."-".$formato[0];
        return $fecha;
    }

    public function filtrarFecha($datos){
        $perfil = "0";
        $p = Session::get('perfiles');

        $perfil = $p['idPerfil'];
        log::info("Perfil: " . $p['idPerfil']);

        $caso = $datos['caso'];
        log::info("Caso: " . $caso);


        switch ($perfil) {
            case "1":
                break;

            case "2":
                $result['v_info'] = '{"code":"204", "des_code":"No content."}';
                
                $IdProveedor = $datos['IdProveedor'] ? " AND IdProveedor = " . $datos['IdProveedor'] . "" : ""; 
                $IdCliente = $p['v_detalle'][0]->IdCliente;

                switch ($caso) {
                    case 1: 
                    case 3: 
                    case 6: 
                    case 12:
                        $sql1 = "SELECT DATE_FORMAT(FechaEmision, '%m') MesGrupo, DATE_FORMAT(FechaEmision, '%m') as IdMesGrupo, DATE_FORMAT(FechaEmision, '%M') NombreMesGrupo, SUM(montoTotalCLP) MontoTotalMesGrupo, COUNT(1) NroDTEGrupo, (SELECT SUM(montoTotalCLP) FROM v_dtes where IdCliente = " . $IdCliente . ") AS MontoVentaTotal, (SELECT COUNT(1) FROM v_dtes where IdCliente = " . $IdCliente . ") AS NroTotalDTE FROM v_dtes WHERE IdCliente = " . $IdCliente . " AND FechaEmision BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW() " . $IdProveedor . " GROUP BY MesGrupo, IdMesGrupo, NombreMesGrupo";

                        // log::info($sql1);
                        $result['v_widget1']=DB::select($sql1);

                        $sql2 = "SELECT group_concat(IdDTE) as id_dtes, IdEstadoDTE, EstadoActualDTE, idProveedor, SUM(montoTotalCLP) as MontoTotal, COUNT(1) as cantidad, ROUND( SUM(montoTotalCLP) / (SELECT SUM(d.montoTotalCLP) FROM v_dtes d where d.IdCliente = " . $IdCliente . ") * 100) AS Porcentaje FROM v_dtes WHERE IdCliente = " . $IdCliente . " AND FechaEmision BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW() " . $IdProveedor . " GROUP BY IdEstadoDTE, EstadoActualDTE, idProveedor";

                        //log::info($sql2);
                        $result['v_widget2']=DB::select($sql2);

                        $sql3 = "SELECT count(1) as Cantidad, t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor FROM (SELECT * FROM v_dte_estados ORDER BY FechaEstado DESC) t1 WHERE IdCliente= ". $IdCliente . " " . $IdProveedor . " GROUP BY t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor LIMIT 50";

                        //log::info($sql3);
                        $result['v_widget4']=DB::select($sql3);

                        break;

                    case 13: 
                        $sql1= "SELECT DATE_FORMAT(FechaEmision, '%m') MesGrupo, DATE_FORMAT(FechaEmision, '%m') as IdMesGrupo, DATE_FORMAT(FechaEmision, '%M') NombreMesGrupo, SUM(montoTotalCLP) MontoTotalMesGrupo, COUNT(1) NroDTEGrupo, (SELECT SUM(montoTotalCLP) FROM v_dtes where IdCliente = " . $IdCliente . ") AS MontoVentaTotal, (SELECT COUNT(1) FROM v_dtes where IdCliente = " . $IdCliente . ") AS NroTotalDTE FROM v_dtes WHERE IdCliente = " . $IdCliente . " AND YEAR(FechaEmision) = YEAR(NOW()) " . $IdProveedor . " GROUP BY MesGrupo, IdMesGrupo, NombreMesGrupo";

                        //log::info($sql1);
                        $result['v_widget1']=DB::select($sql1);

                        $sql2= "SELECT group_concat(IdDTE) as id_dtes, IdEstadoDTE, EstadoActualDTE, idProveedor, SUM(montoTotalCLP) as MontoTotal, COUNT(1) as cantidad, ROUND( SUM(montoTotalCLP) / (SELECT SUM(d.montoTotalCLP) FROM v_dtes d where d.IdCliente = " . $IdCliente . ") * 100) AS Porcentaje FROM v_dtes WHERE IdCliente = " . $IdCliente . " AND YEAR(FechaEmision) = YEAR(NOW()) " . $IdProveedor . " GROUP BY IdEstadoDTE, EstadoActualDTE, idProveedor";

                        //log::info($sql2);
                        $result['v_widget2']=DB::select($sql2);


                        $sql3= "SELECT count(1) as Cantidad, t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor FROM (SELECT * FROM v_dte_estados ORDER BY FechaEstado DESC) t1 WHERE IdCliente= ". $IdCliente . " " . $IdProveedor . " GROUP BY t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor LIMIT 50";

                        //log::info($sql3);
                        $result['v_widget4']=DB::select($sql3);
                    break;
                }

                break;

            case "3":
                $IdProveedor = $p['v_detalle'][0]->IdProveedor;

                switch ($caso) {
                    case 1: 
                    case 3: 
                    case 6: 
                    case 12:
                        $result['v_info'] = '{"code":"204", "des_code":"No content."}';
                        $fecha = "";

                        $sql1 = "SELECT date_FORMAT(FechaEmision, '%m') MesGrupo, date_FORMAT(FechaEmision, '%m') as IdMesGrupo, date_FORMAT(FechaEmision, '%M') NombreMesGrupo, SUM(montoTotalCLP) MontoTotalMesGrupo, COUNT(1) NroDTEGrupo, (SELECT SUM(montoTotalCLP) FROM v_dtes where idProveedor = ".$IdProveedor.") AS MontoVentaTotal ,(SELECT COUNT(1) FROM v_dtes where idProveedor = ".$IdProveedor.") AS NroTotalDTE FROM v_dtes where idProveedor = ".$IdProveedor." and FechaEmision BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW() GROUP BY MesGrupo, IdMesGrupo, NombreMesGrupo";

                        //log::info($sql1);
                        $result['v_widget1']=DB::select($sql1);

                        $sql2 = "SELECT GROUP_CONCAT(IdDTE) as id_dtes, IdEstadoDTE,EstadoActualDTE, idProveedor, SUM(montoTotalCLP) as MontoTotal, COUNT(1) as cantidad, ROUND( SUM(montoTotalCLP) / (SELECT SUM(d.montoTotalCLP) FROM v_dtes d where d.IdProveedor = ".$IdProveedor." AND FechaEmision BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW()) * 100) AS Porcentaje FROM v_dtes WHERE IdProveedor =".$IdProveedor." AND FechaEmision BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW() GROUP BY IdEstadoDTE, EstadoActualDTE, IdProveedor";
                        log::info($sql2);
                        $result['v_widget2']=DB::select($sql2);

                        $sql4 = "select count(1) as Cantidad, t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor from (select * from v_dte_estados where FechaEstado BETWEEN DATE_SUB(NOW(), INTERVAL ".$caso." MONTH) AND NOW() ORDER BY FechaEstado DESC) t1 where IdProveedor=".$IdProveedor." group by t1.IdEstadoDTE,t1.NombreEstado,t1.IdProveedor limit 50";

                        //log::info($sql4);
                        $result['v_widget4']=DB::select($sql4); 

                        break;

                    case 13:
                        $result['v_info'] = '{"code":"204", "des_code":"No content."}';

                        $sql1="SELECT date_FORMAT(FechaEmision, '%m') MesGrupo, date_FORMAT(FechaEmision, '%m') AS IdMesGrupo, date_FORMAT(FechaEmision, '%M') NombreMesGrupo, SUM(montoTotalCLP) MontoTotalMesGrupo, COUNT(1) NroDTEGrupo, (SELECT SUM(montoTotalCLP) FROM v_dtes WHERE idProveedor = ".$IdProveedor.") AS MontoVentaTotal ,(SELECT COUNT(1) FROM v_dtes where idProveedor = ".$IdProveedor.") AS NroTotalDTE FROM v_dtes where idProveedor = ".$IdProveedor." and YEAR(FechaEmision) = YEAR(NOW()) and DATE_FORMAT(FechaEmision, '%Y') = DATE_FORMAT(NOW(), '%Y') GROUP BY MesGrupo, IdMesGrupo, NombreMesGrupo";

                        //log::info($sql1);
                        $result['v_widget1']=DB::select($sql1);

                        $sql2 = "SELECT group_concat(IdDTE) as id_dtes, IdEstadoDTE,EstadoActualDTE, idProveedor, SUM(montoTotalCLP) AS  MontoTotal, COUNT(1) AS cantidad, ROUND( SUM(montoTotalCLP) / (SELECT SUM(d.montoTotalCLP) FROM v_dtes d WHERE d.idProveedor = " . $IdProveedor . " and YEAR(FechaEmision) = YEAR(NOW())) * 100) AS Porcentaje FROM v_dtes where idProveedor =" . $IdProveedor . " and YEAR(FechaEmision) = YEAR(NOW()) GROUP BY IdEstadoDTE, EstadoActualDTE, idProveedor";

                        //log::info($sql2);
                        $result['v_widget2']=DB::select($sql2);

                        $sql4="select count(1) as Cantidad, t1.IdEstadoDTE, t1.NombreEstado, t1.IdProveedor from (select * from v_dte_estados where YEAR(FechaEstado) = YEAR(NOW()) ORDER BY FechaEstado DESC) t1 where IdProveedor=".$IdProveedor." GROUP BY t1.IdEstadoDTE,t1.NombreEstado,t1.IdProveedor LIMIT 50";

                        //log::info($sql4);
                        $result['v_widget4']=DB::select($sql4);

                        break;

                    default:
                        $result['v_info']='{"code":"-2", "des_code":"Se esperaban resultados"}'; 
                        break;

                }

                break;

            default:
                break;

        }
        

        return $result;
    }
}