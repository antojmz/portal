@extends('menu.index')
@section('content')

@php
    $data = Session::get('perfiles');
    $widget = Session::get('widget');
@endphp

<!-- BEGIN: Subheader -->
<div id="divBienvenida" class="m-subheader" style="padding-top: 10px;">
    <div class="d-flex align-items-center">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="mr-auto">
                <div class="m-subheader__title">
                    <h3 class="m--font-danger">
                    @if( $data['idPerfil'] == 1 )

                    @elseif( $data['idPerfil'] == 2 )
                        Bienvenido al Dashboard de Cliente [Todos los Proveedores]

                    @elseif( $data['idPerfil'] == 3 )
                        Bienvenido a tu Portal de Proveedores

                    @endif
                    </h1>
                </div>
                </div>
            </div>

            @if( $data['idPerfil'] == 2 )
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="m-portlet__head-tools">
                    {!! Field::select('IdProveedor', null, null, ['label' => ' ', 'placeholder' => 'Todos los Proveedores', 'style' => 'width:100%;height:35px;', 'class' =>'form-control comboclear']) !!}
                </div>
            </div>
            @endif
            @if( $data['idPerfil'] == 3 )
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                <div class="m-portlet__head-tools">
                    {!! Field::select('IdCliente', null, null, ['label' => ' ', 'placeholder' => 'Todos los Clientes', 'style' => 'width:100%;height:35px;', 'class' =>'form-control comboclear']) !!}
                </div>
            </div>
            @endif
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                <div class="m-portlet__head-tools">
                    {!! Field::select('IdTipoDTE', null, null, ['label' => ' ', 'placeholder' => 'Todos los Tipos DTE', 'style' => 'width:100%;height:35px;', 'class' =>'form-control comboclear']) !!}
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                <div class="m-portlet__head-tools">
                    {!! Field::select('IdPeriodo', null, null, ['label' => ' ', 'placeholder' => '', 'style' => 'width:100%;height:35px;', 'class' =>'form-control comboclear']) !!}
                    <div hidden>
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                            <a href="#" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
                                Filtrar x Periodo
                            </a>
                            <div class="m-dropdown__wrapper">
                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36.5px;"></span>
                                <div class="m-dropdown__inner">
                                    <div class="m-dropdown__body">
                                        <div class="m-dropdown__content">
                                            <ul class="m-nav">
                                                <li class="m-nav__item">
                                                    <a href="javascript:void(0);" id="FiltroAnio" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                        <span class="m-nav__link-text">
                                                            Este Año
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="javascript:void(0);" id="FiltroMes" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                        <span class="m-nav__link-text">
                                                            Este Mes
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="javascript:void(0);" id="FiltroTryMes" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                        <span class="m-nav__link-text">
                                                            Últumos 3 meses
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="javascript:void(0);" id="FiltroSixMes" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                        <span class="m-nav__link-text">
                                                            Últumos 6 meses
                                                        </span>
                                                    </a>
                                                </li>
                                                <li class="m-nav__item">
                                                    <a href="javascript:void(0);" id="FiltrotweMes" class="m-nav__link">
                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                        <span class="m-nav__link-text">
                                                            Últumos 12 meses
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="m-content"  style="padding-top: 10px;">
    @switch($data['idPerfil'])
        @case(1)
        <!-- caso administrador -->
        @break

    @case(2) 
    @case(3)
        <form id="formIdDtes" method="POST" action='{!! URL::route("busquedaDTE") !!}' style="display: none;">
            {{ csrf_field() }}
            <input type="hidden" id="idSubmitDtes" name="idSubmitDtes">
        </form>
        
        <div class="divTablaFacP">
            <!-- Panel Superior -->
            <div class="m-portlet">
                <div class="m-portlet__body  m-portlet__body--no-padding">
                    <div class="row m-row--no-padding m-row--col-separator-xl">
                        <!-- Grafico de DTEs x MES -->
                        <div class="col-xl-6">
                            <!--begin:: Widgets/Daily Sales-->
                            <div class="m-widget14">
                                <div class="m-widget14__header m--margin-bottom-30">
                                    <h3 class="m-widget14__title" id="widget14__title">
                                        Facturación útimos 12 meses
                                    </h3>
                                    <span class="m-widget14__desc" id="widget14__desc">
                                        Facturas emitidas al cliente los últimos 12 meses.
                                    </span>
                                </div>
                                <div id="divFacturacion_por_mes" class="m-widget14__chart" style="height:120px;"></div>
                            </div>
                            <!--end:: Widgets/Daily Sales-->
                        </div>
                        <!-- Grafico % DTEs x Estado -->
                        <div class="col-xl-6">
                            <!--begin:: Widgets/Profit Share-->
                            <div class="m-widget14">
                                <div class="m-widget14__header">
                                    <h3 class="m-widget14__title">
                                        DTE recibidos por estado
                                    </h3>
                                    <span class="m-widget14__desc">
                                        Resumen se DTE recibidos por estado.
                                    </span>
                                </div>
                                <div class="row  align-items-center">
                                    <div class="col">
                                        <div id="facturacion_por_estado" class="m-widget14__chart" style="height: 160px;">
                                            <div class="m-widget14__stat">
                                                <span id="spanPorcentaje"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="m-widget14__legends">
                                            <div id="div1" style="display:none;" class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-brand"></span>
                                                <span id="span1" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div2" style="display:none;" class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-success"></span>
                                                <span id="span2" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div3" style="display:none;" class="m-widget14__legend">
                                                <span style="background-color:#FA58F4;" class="m-widget14__legend-bullet"></span>
                                                <span id="span3" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div4" style="display:none;" class="m-widget14__legend">
                                                <span style="background-color:#F515C7;" class="m-widget14__legend-bullet"></span>
                                                <span id="span4" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div5" style="display:none;" class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-danger"></span>
                                                <span id="span5" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div6" style="display:none;" class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-warning"></span>
                                                <span id="span6" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div7" style="display:none;" class="m-widget14__legend">
                                                <span style="background-color:#66FEF1;" class="m-widget14__legend-bullet"></span>
                                                <span id="span7" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div8" style="display:none;" class="m-widget14__legend">
                                                <span style="background-color:#2DF130;" class="m-widget14__legend-bullet"></span>
                                                <span id="span8" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div9" style="display:none;" class="m-widget14__legend">
                                                <span class="m-widget14__legend-bullet m--bg-info"></span>
                                                <span id="span9" class="m-widget14__legend-text"></span>
                                            </div>
                                            <div id="div99" style="display:none;" class="m-widget14__legend">
                                                <span style="background-color:#F514C7;" class="m-widget14__legend-bullet"></span>
                                                <span id="span99" class="m-widget14__legend-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end:: Widgets/Profit Share-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8">
                    <!-- DTEs x Estado -->
                    <div class="m-portlet m-portlet--space m-portlet--full-height ">
                        <div class="m-portlet__head" style="padding-top: 10px; padding-bottom: 0px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        DTEs Recibidos
                                        <span class="m-portlet__head-desc">
                                            Total de Facturas (afextas y exentas), Notas de Crédito, Notas de Débito Recibidas
                                        </span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding-top: 0px;">
                            <div class="m-widget25">
                                <span id="spanMontoTotal" class="m-widget25__price m--font-brand"></span>
                                <br>
                                <span class="m-widget25__desc">
                                    Total recibido el periodo seleccionado
                                </span>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">
                                    <div class="m-widget25__progress">
                                        <span id="spanMonto1" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress1" class="progress-bar m--bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes1" class="m-widget25__progress-sub"></span>
                                        <span><a id="href1" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                    <div class="m-widget25__progress">
                                        <span id="spanMonto2" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress2" class="progress-bar m--bg-accent" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes2" class="m-widget25__progress-sub"></span>
                                        <span><a id="href2" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                </div>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">
                                    <div class="m-widget25__progress" >
                                        <span id="spanMonto3" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress3" class="progress-bar m--bg-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes3" class="m-widget25__progress-sub"></span>
                                        <span><a id="href3" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                    <div class="m-widget25__progress" >
                                        <span id="spanMonto4" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress4" class="progress-bar m--bg-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes4" class="m-widget25__progress-sub"></span>
                                        <span><a id="href4" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                </div>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">                                    
                                    <div class="m-widget25__progress" >
                                        <span id="spanMonto6" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress6" class="progress-bar m--bg-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes6" class="m-widget25__progress-sub"></span>
                                        <span><a id="href6" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                    <div class="m-widget25__progress" >
                                        <span id="spanMonto7" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress7" class="progress-bar m--bg-warning" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes7" class="m-widget25__progress-sub"></span>
                                        <span><a id="href7" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin DTEs x Estado -->
                </div>
                <div class="col-xl-4">
                    <div class="m-portlet m-portlet--full-height m-portlet--fit ">
                        <div class="m-portlet__head" style="padding-top: 10px; padding-bottom: 0px;">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        DTEs en Pronto Pago
                                        <span class="m-portlet__head-desc">
                                            Total de Facturas en Proceso de Pronto Pago
                                        </span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding-top: 0px;">
                            <div class="m-widget25">
                                <span id="spanMontoTotalPP" class="m-widget25__price m--font-brand"></span>
                                <br>
                                <span class="m-widget25__desc">
                                    Total Pronto Pago solicitado el periodo seleccionado
                                </span>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">
                                    <div class="m-widget25__progress">
                                        <span id="spanMonto11" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress11" class="progress-bar-pp m--bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes11" class="m-widget25__progress-sub"></span>
                                        <span><a id="href11" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                </div>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">
                                    <div class="m-widget25__progress">
                                        <span id="spanMonto12" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress12" class="progress-bar-pp m--bg-accent" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes12" class="m-widget25__progress-sub"></span>
                                        <span><a id="href12" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                </div>
                                <div class="m-widget25--progress" style="margin-top: 10px;padding-top: 0px;">
                                    <div class="m-widget25__progress">
                                        <span id="spanMonto13" class="m-widget25__progress-number"></span>
                                        <div class="m--space-10"></div>
                                        <div class="progress m-progress--sm">
                                            <div id="progress13" class="progress-bar-pp m--bg-accent" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="spanDes13" class="m-widget25__progress-sub"></span>
                                        <span><a id="href13" href="#" class="m-menu__link btn btn-link">Ver DTE´s </a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @break

    @default
        {{"Perfíl no encontrado"}}
        <script Language="Javascript">
            Salir();
        </script>
    @endswitch
</div>
<script Language="Javascript">
    var rutaF = "{{ URL::route('filtrarwidget') }}";
    var IdPerfil = "{{ $data['idPerfil'] }}";
    var v_tipodte = JSON.parse(rhtmlspecialchars('{{ json_encode($v_tipo_dte) }}'));
</script>

@if( $data['idPerfil'] == 2 )
<script Language="Javascript">
    var nombreCliente = "{{$data['v_detalle'][0]->NombreEmpresa}}";
    var v_proveedores = JSON.parse(rhtmlspecialchars('{{ json_encode($v_proveedores) }}'));
</script>
@endif

@if( $data['idPerfil'] == 3 )
<script Language="Javascript">
    var nombreCliente = "";
    var v_proveedores = "";
    var v_clientes = JSON.parse(rhtmlspecialchars('{{ json_encode($v_clientes) }}'));
</script>
@endif
<script src="js/menu/dasboard.js?v=<?php echo date('Ymd_His'); ?>"></script>
@endsection