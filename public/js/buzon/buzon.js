var errorLoad = errorLoadAll = refreshMessage = VarIdChat = VarReload = 0;

var parametroAjaxB = {
    'token': $('input[name=_token]').val(),
    'tipo': 'POST',
    'data': {},
    'ruta': '',
    'async': false
};

var parametroAjaxGETB = {
    'token': $('input[name=_token]').val(),
    'tipo': 'GET',
    'data': {},
    'ruta': '',
    'async': false
};

var ManejoRespuestaC = function(respuesta,idChat){
    if (respuesta.code = '200'){
        var res = respuesta.respuesta
        if (refreshMessage == 0){$(".divForm").toggle();}
        refreshMessage = 1;
        var arrayC = [];
        var valuechat = 0;
        if(res!=null){
            for (i = 0; i < res.length; i++) {
                if (valuechat == 0){
                    $("#idChat").val(res[i].idChat);valuechat = 1
                    $("#NombreUsuario").text(res[i].Usuario+" - "+res[i].Proveedor);
                    var image = '/img/default.png'
                    var foto = res[i].imageUsuario
                    if ( foto != null){if (foto.length > 13){ image = res[i].imageUsuario;}}
                    $('#imgUserChat').attr('src',image)+ '?' + Math.random();
                }

                if (res[i].IdPerfil==3){
                    arrayC[i]='<div class="row"><div class="col-md-12"><div class="m-messenger__message m-messenger__message--in"><div class="m-messenger__message-body"><div class="m-messenger__message-arrow"></div><div class="m-messenger__message-content"><div class="m-messenger__message-username">'+res[i].Usuario+'</div><div class="m-messenger__message-text">'+res[i].message+'</div><div class="m-messenger__message-username" style="text-align:right;">'+moment(res[i].FechaMessage, 'YYYY-MM-DD HH:mm:ss',true).format("HH:mm")+'</div></div></div></div></div></div>';
                }else{
                    var UserResponse = '';
                    res[i].id_creador==d['idUser'] ? UserResponse='Yo' : UserResponse=res[i].creador;
                    arrayC[i]='<div class="row"><div class="col-md-12"><div class="m-messenger__message m-messenger__message--out"><div class="m-messenger__message-body"><div class="m-messenger__message-arrow"></div><div class="m-messenger__message-content"><div class="m-messenger__message-username" style="color:#FFF">'+UserResponse+'</div><div class="m-messenger__message-text">'+res[i].message+'</div><div class="m-messenger__message-username" style="color:#FFF;text-align:right;">'+moment(res[i].FechaMessage, 'YYYY-MM-DD HH:mm:ss',true).format("HH:mm")+'</div></div></div></div></div></div></div>';
                }
                $("#ChatBodyC").html(arrayC);
            }
            var top = $("#styleScroll").prop("scrollHeight");
            $("#styleScroll").scrollTop(top);
            VarIdChat = idChat;
        }
    }else{
        toastr.error("Error al cargar la conversación, contacte al personal informático", "Error!");
    };
}

var ManejoRespuestaProcesarBChat = function (respuesta){ 
    if(respuesta.code==200){
        var res = JSON.parse(respuesta.respuesta.f_registro_chat);
        if(res.code==200){
            $("#idChat").val(res.idChat);
            $("#message").val("");
        }else{
            toastr.warning(respuesta.respuesta.des_code, "Info!");
        }
    }else{
        toastr.error("Disculpe, No conseguimos enviar su mensaje", "Error!");
    }
}

var enviarMessage = function(){
    var message = $("#message").val();
    var id_chat = $("#idChat").val();
    if (message.length > 1){    
        if (id_chat == 0){
            toastr.warning("No fue posible identificar el origen del chat. Por favor, intente nuevamente", "Error!");
            return;
        }else{
            parametroAjaxB.ruta = rutaGetChat;
            parametroAjaxB.data = $("#FormChatC").serialize();
            respuesta=procesarajaxChat(parametroAjaxB);
            ManejoRespuestaProcesarBChat(respuesta);
        }
    }    
};

var cargarFormulario = function(idChat){
    parametroAjaxB.ruta=rutaC;
    parametroAjaxB.data = {"idChat":idChat};
    respuesta=procesarajaxChat(parametroAjaxB);
    ManejoRespuestaC(respuesta,idChat);
}

var volverChat = function(){
    $("#FormChatC")[0].reset();
    refreshMessage = 0; 
    $('#imgUserChat').attr('src','/img/default.png')+ '?' + Math.random();    
    if (VarReload == 1){
        VarReload = 0;
        window.location.href = "/buzon";
    }else{
        $(".divForm").toggle();
    }
}

var cargarBuzon = function(res){
    var array = [];
    if(res!=null){
        if (res.length > 0){
            for (i = 0; i < res.length; i++) {    
                var image = '/img/default.png'
                var foto = res[i].imageUsuario; 
                var operador = "No asignado";
                if (foto != null){if (foto.length > 13){ image = res[i].imageUsuario;}}
                if (res[i].Operador != null){ operador=res[i].Operador}
                var message = res[i].message;
                var cadena =60;
                if (message.length > cadena ){message = message.substr(0,cadena)+"...";} 
                if (res[i].statusMessage==1){
                    array[i] = '<div onclick="cargarFormulario('+res[i].idChat+');" class="m-widget3__item" style="background-color:#FDF2A0"><div class="m-widget3__header"><div class="m-widget3__user-img"><img class="m-widget3__img" src="'+image+'" alt=""></div><div class="m-widget3__info"><div class="row"><div class="col-md-3"><span class="m-widget3__username">'+res[i].Usuario+'</span><br><span class="m-widget3__time">'+moment(res[i].FechaMessage).fromNow()+'</span></div><div class="col-md-9"><div class="row"><div style="padding-top: 10px;" class="col-md-9"><span class="m-widget3__username">'+message+'</span></div><div class="col-md-3"><span style="float:right;padding-right:20px;" class="m-widget3__time">'+operador+'</span></div></div></div></div></div></div></div>';
                }else{
                    array[i] = '<div onclick="cargarFormulario('+res[i].idChat+');" class="m-widget3__item" style="background-color:#FFFFFF"><div class="m-widget3__header"><div class="m-widget3__user-img"><img class="m-widget3__img" src="'+image+'" alt=""></div><div class="m-widget3__info"><div class="row"><div class="col-md-3"><span class="m-widget3__username">'+res[i].Usuario+'</span><br><span class="m-widget3__time">'+moment(res[i].FechaMessage).fromNow()+'</span></div><div class="col-md-9"><div class="row"><div style="padding-top: 10px;" class="col-md-9"><span class="m-widget3__username">'+message+'</span></div><div class="col-md-3"><span style="float:right;padding-right:20px;" class="m-widget3__time">'+operador+'</span></div></div></div></div></div></div></div>';
                }
            } 
        }else{
            array ='<div style="font-size:12px;color:#898b96"><br><center>No hay mensajes pendientes...</center></div>';
        }
        $("#divBandejaMensaje").html(array);
    }
}

var LoadBuzon = function(){
    parametroAjaxGETB.ruta = RutabR;
    respuesta=procesarajaxChat(parametroAjaxGETB);
    $.get(RutabR, function(data) {cargarBuzon(data);});
    // if (respuesta.code == 200){
    //     cargarBuzon(respuesta.respuesta);
    // }else{
    //     if(errorLoadAll==0){
    //         toastr.warning("Ocurrio un error al refrescar el buzon de mensajes", "Error!");
    //         errorLoadAll=1;
    //     }
    // }
}

var selected = function(){
    if (stopload==0){
        if (refreshMessage == 0){
            LoadBuzon();           
        }else{
            if (VarIdChat != 0){cargarFormulario(VarIdChat);}
        }
    }    
}

var Cargarconversacion = function(idChat){
    if (idChat > 0){cargarFormulario(idChat);VarReload=1;}
}

$(document).ready(function(){
    ClassActive("LiChatP");
    cargarBuzon(d.v_chat);
    Cargarconversacion(d['idChat']);  
    // selected();
    setInterval("selected()", 2200);
    $(document).on('click','#ChatSubmitC',enviarMessage);
    $(document).on('click','#volverChat',volverChat);
});