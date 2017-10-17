var limpiar=0;
var parametroAjax = {
    'token': $('input[name=_token]').val(),
    'tipo': 'POST',
    'data': {},
    'ruta': '',
    'async': false
};

var cargartablaClientes = function(data){
    if (data.length>0){
        $("#tablaClientes").dataTable({
            'aLengthMenu': [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"]],
            "language": {
                "url": "/plugins/DataTables-1.10.10/de_DE-all.txt"
            },
            "data": data,
            "columns":[
                {"title": "Id","data": "idUser",visible:0 },
                {"title": "RUT","data": "usrUserName"},
                {"title": "Nombres","data": "usrNombreFull"},
                {"title": "Email","data": "usrEmail"},
                {"title": "Empresa","data": "NombreCliente"},
                {"title": "Última visita","data": "usrUltimaVisita"}
            ],
        });
        limpiar=1;
    }else{
        limpiar=0;
        mensajesAlerta('Info','No se encontraron resultados', 'info');
    }
};

$(document).ready(function(){
    $("#spanTitulo").text("Listado de clientes");
    cargartablaClientes(d.v_clientes);
});