var limpiar=limpiarEstados=limpiarDetalles=limpiarReferencias=printCounter=0;
var RegistroDTE = '';
var parametroAjax = {
    'token': $('input[name=_token]').val(),
    'tipo': 'POST',
    'data': {},
    'ruta': '',
    'async': false
};

var ManejoRespuestaC = function(respuesta){
    if (respuesta.code = '200'){
        cargartablaReportes(respuesta.respuesta);
    }else{
        toastr.error("No se ejecuto la consulta, contacte al personal informático", "Error!");
    };
}

// var ManejoRespuestaD = function(respuesta){
//     if (respuesta.code = '200'){
//             pintarDatos(RegistroDTE);
//             cargartablaDetalles(respuesta.respuesta.v_dte_detalles);
//             cargartablaReferencias(respuesta.respuesta.v_dte_referencias);
//             cargartablaEstados(respuesta.respuesta.v_dte_estados);
//     }else{
//         toastr.error("No se ejecuto la consulta, contacte al personal informático", "Error!");
//     };
// }

// var pintarDatos = function(data){
//     if(data.TipoDTE!=null){$("#TipoDTE").text(data.TipoDTE);}
//     if(data.FolioDTE!=null){$("#FolioDTE").text(data.FolioDTE);}
//     if(data.FechaEmision!=null){$("#FechaEmision").text(data.FechaEmision);}
//     if(data.FechaRecepcion!=null){$("#FechaRecepcion").text(data.FechaRecepcion);}
//     if(data.RutProveedor!=null){$("#RutProveedor").text(data.RutProveedor);}
//     if(data.NombreProveedor!=null){$("#NombreProveedor").text(data.NombreProveedor);}
//     if(data.RutCliente!=null){$("#RutCliente").text(data.RutCliente);}
//     if(data.NombreCliente!=null){$("#NombreCliente").text(data.NombreCliente);}
//     if(data.MontoNetoCLP!=null){$("#MontoNetoCLP").text(data.MontoNetoCLP);}
//     if(data.MontoExentoCLP!=null){$("#MontoExentoCLP").text(data.MontoExentoCLP);}
//     if(data.MontoIVACLP!=null){$("#MontoIVACLP").text(data.MontoIVACLP);}
//     if(data.MontoTotalCLP!=null){$("#MontoTotalCLP").text(data.MontoTotalCLP);}
//     // if(data.MontoNetoOM!=null){$("#MontoNetoOM").text(data.MontoNetoOM);}
//     // if(data.MontoExentoOM!=null){$("#MontoExentoOM").text(data.MontoExentoOM);}
//     // if(data.MontoIVAOM!=null){$("#MontoIVAOM").text(data.MontoIVAOM);}
//     // if(data.MontoTotalOM!=null){$("#MontoTotalOM").text(data.MontoTotalOM);}
//     if(data.EstadoActualDTE!=null){$("#EstadoActualDTE").text(data.EstadoActualDTE);}
//     if(data.FechaEstadoActualDTE!=null){$("#FechaEstadoActualDTE").text(data.FechaEstadoActualDTE);}

// }

// var cargartablaDetalles = function(data){
//     if(limpiarDetalles==1){destruirTabla('#tablaDetalles');}
//     if (data.length>0){
//         $("#tablaDetalles").dataTable({
//             'aLengthMenu': [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"]],
//             'bSort': false,
//             "language": {
//                     "url": "/plugins/DataTables-1.10.10/de_DE-all.txt"
//             },
//             "data": data,
//             "columns":[
//                 {"title": "IdDTE","data": "IdDTE",visible:0},
//                 {"title": "Código Producto","data": "CodigoProducto"},
//                 {"title": "Nombre Producto","data": "NombreProducto"},
//                 {"title": "Valor Unitario","data": "ValorUnitario"},
//                 {"title": "Cantidad","data": "Cantidad"},
//                 {"title": "Total Linea","data": "TotalLinea"}
//             ],
//         });
//         limpiarDetalles=1;
//     }else{
//         limpiarDetalles=0;
//     }
// }

// var cargartablaReferencias = function(data){
//     if (limpiarReferencias>0){destruirTabla('#tablaReferencias');}
//     if (data.length>0){
//         $("#tablaReferencias").dataTable({
//             'aLengthMenu': [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"]],
//             'bSort': false,
//             "language": {
//                     "url": "/plugins/DataTables-1.10.10/de_DE-all.txt"
//             },
//             "data": data,
//             "columns":[
//                 {"title": "IdDTE","data": "IdDTE",visible:0},
//                 {"title": "IdReferencia","data": "IdReferencia",visible:0},
//                 {"title": "Tipo de Referencia","data": "TipoReferencia"},
//                 {"title": "Folio de Referencia","data": "FolioReferencia"},
//                 {"title": "Fecha de Referencia","data": "FechaReferencia"}
//             ],
//         });
//         limpiarReferencias=1;
//     }else{
//         limpiarReferencias=0;
//     }
// }

// var cargartablaEstados = function(data){
//     if (limpiarEstados>0){destruirTabla('#tablaEstados');}
//     if (data.length>0){
//         $("#tablaEstados").dataTable({
//             'aLengthMenu': [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"]],
//             'bSort': false,
//             "language": {
//                     "url": "/plugins/DataTables-1.10.10/de_DE-all.txt"
//             },
//             "data": data,
//             "columns":[
//                 {"title": "IdDTE","data": "IdDTE",visible:0},
//                 {"title": "IdEstadoDTE","data": "IdEstadoDTE",visible:0},
//                 {"title": "Fecha de Estado","data": "FechaEstado"},
//                 {"title": "Comentario de Estado","data": "ComentarioEstado"}
//             ],
//         });
//         limpiarEstados=1;
//     }else{
//         limpiarEstados=0;
//     }
// }

var cargartablaReportes = function(data){
    if (limpiar>0){destruirTabla('#tablaReportes');}
    if (data.length>0){
        $("#tablaReportes").dataTable({
            'aLengthMenu': DataTableLengthMenu,
            // 'bSort': false,
            "scrollX": true,
            "scrollY": '50vh',
            "scrollCollapse": true,
            "pagingType": "full_numbers",
            "language": LenguajeTabla,
            "data": data,
            "columns":[
                {"title": "IdOC","data": "IdOC",visible:0},
                {"title": "IdProveedor","data": "IdProveedor",visible:0},
                {"title": "IdCliente","data": "IdCliente",visible:0},
                {"title": "Folio DTE","data": "FolioOC",visible:0},
                {
                    "title": "Fecha Emisión", 
                    "data": "FechaEmision",
                    "render": function(data, type, row, meta){
                        if(type === 'display'){
                            data = moment(data, 'YYYY-MM-DD HH:mm:ss',true).format("DD-MM-YYYY");
                        }
                        return data;
                    }
                },
                {
                    "title": "Fecha Recepción Cliente", 
                    "data": "FechaRecepcion",
                    "render": function(data, type, row, meta){
                        if(type === 'display'){
                            data = moment(data, 'YYYY-MM-DD HH:mm:ss',true).format("DD-MM-YYYY");
                        }
                        return data;
                    }
                },
                {
                    "title": "RUT Proveedor", 
                    "data": "RUTEmisor",
                    "render": function(data, type, row, meta){
                        if(type === 'display'){
                            data = formateaRut(data, true)
                        }
                        return data;
                    }
                },
                {
                    "title": "RUT Cliente", 
                    "data": "RUTReceptor",
                    "render": function(data, type, row, meta){
                        if(type === 'display'){
                            data = formateaRut(data, true)
                        }
                        return data;
                    }
                },
                {"title": "Monto Neto DTE","data": "MontoNetoCLP"},
                {"title": "Monto Exento DTE","data": "MontoExentoCLP"},
                {"title": "Monto IVA DTE","data": "MontoIVACLP"},
                {"title": "Monto Total DTE","data": "MontoTotalCLP"},
                {"title": "Estado OC","data": "EstadoOC"},
                {"title": "Documento Contable","data": "DocumentoContable"},
                {"title": "Entrada de mercaderia","data": "EntradaMercaderia"}
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Imprimir',
                    className: 'btn m-btn--pill btn-accent btn-sm m-btn m-btn--custom',
                    title:'Listado DTEs',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: 'Exportar',
                    className: 'btn m-btn--pill btn-accent btn-sm m-btn m-btn--custom',
                    title:'Listado DTEs',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn m-btn--pill btn-accent btn-sm m-btn m-btn--custom',
                    orientation:'landscape',
                    pageSize:'LETTER',
                    title:'Listado DTEs',
                    exportOptions: {
                        modifier: {
                            page: 'current',
                        }
                    }
                }
            ]
        });
        // SeleccionarTablaReportes();
        limpiar=1;
    }else{
        limpiar=0;
        toastr.warning("No se encontraron resultados", "Info!");
    }
};

var SeleccionarTablaReportes = function(){
    var tableB = $('#tablaReportes').dataTable();
    $('#tablaReportes tbody').on('click', 'tr', function (e) {
        tableB.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    });
    $('#tablaReportes tbody').on('dblclick', 'tr', function () {
        RegistroDTE = TablaTraerCampo('tablaReportes',this);
        cargarFormularioVisualizacion(RegistroDTE);
    });
    tableB.on('dblclick', 'tr', function () {
        $('#close').trigger('click');
    });
}

var ProcesarConsulta = function(){
    var desde = $('#f_desde').val();
    var Selectcampo = $('#Selectcampo').val();
    var SelectDTE = $('#SelectDTE').val();
    if (desde.length<1 && Selectcampo.length<1 && SelectDTE.length<1){
        toastr.error("Debe seleccionar al menos un item", "Error!");
        return;
    }
    parametroAjax.ruta=ruta;
    parametroAjax.data = $("#FormConsultas").serialize();
    respuesta=procesarajax(parametroAjax);
    ManejoRespuestaC(respuesta);
};

// var cargarFormularioVisualizacion = function(data){
//     $(".divForm").toggle();
//     parametroAjax.ruta=rutaD;
//     parametroAjax.data = {"IdDTE":data.IdDTE};
//     respuesta=procesarajax(parametroAjax);
//     ManejoRespuestaD(respuesta);
// };

// var BotonVolver = function(){
//     $(".divForm").toggle();
//     $(".span").text("Desconocido");
// }

var crearAllcombos = function(d){
    var busqueda= [{"id":"RUTEmisor","text":"RUT Emisor"},{"id":"RUTReceptor","text":"RUT Receptor"},{"id":"EstadoOC","text":"EstadoOC"}]
    crearcombo('#Selectcampo',busqueda);
    crearcombo('#SelectDTE',d.v_tipo_dte);
}

var cal1 = function (){$("#fecha").click();};
$(document).ready(function(){
    ClassActive("LiOc");
    cargartablaReportes(d.v_ocs)
    $(".span").text("Desconocido");
    $("#spanTitulo").text("Consultas OC");
    crearAllcombos(d)
    $('#fecha').daterangepicker({}, function(start, end, label) {
        $('#fecha').text(moment(start._d, 'MM-DD-YYYY HH:mm:ss',true).format("DD-MM-YYYY")+" al "+moment(end._d, 'MM-DD-YYYY HH:mm:ss',true).format("DD-MM-YYYY"));
        $('#f_desde').val(moment(start._d, 'MM-DD-YYYY HH:mm:ss',true).format("DD-MM-YYYY"));
        $('#f_hasta').val(moment(end._d, 'MM-DD-YYYY HH:mm:ss',true).format("DD-MM-YYYY"));
    });
    $(document).on('click','#consultar',ProcesarConsulta);
    $(document).on('click','#btnCal',cal1);
    // $(document).on('click','#volver',BotonVolver);
});