function gerenciar_erro(erro){
    if(erro.responseJSON.redirecionar != undefined && erro.responseJSON.redirecionar){
        window.location.href = "../?erro=" + erro.responseJSON.codigo.toString();
    } else {
        mostrar_alerta(erro.responseJSON.mensagem);
    }                
}

function mostrar_carregando_no_botao(id){
    $("#" + id).html("<div class='spinner-border spinner-border-sm text-light'></div>");
    $("#" + id).attr("disabled","");
}

function esconder_carregando_no_botao(id, origem){
    $("#" + id).html(origem);
    $("#" + id).removeAttr("disabled");
}

var alerta_atual = 0;

function atualizar_alerta(){
    alerta_atual++;
}

function mostrar_alerta(mensagem, tipo = "danger"){
    $("#alertas").html("");
    var alerta = alerta_atual.toString();
    var elemento = "<div class='alert alert-" + tipo + " alert-dismissible fade show d-flex align-items-center' role='alert'>" +
                        "<strong>" + (tipo == "danger" ? "<i class='bi bi-exclamation-triangle-fill icone-alerta'></i>" : "<i class='bi bi-check-circle-fill icone-alerta'></i>") + "</strong> <span>" + mensagem + "</span>" +
                        "<button id='fecharAlerta_" + alerta + "' type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Fechar' onclick='atualizar_alerta()'></button>" +
                    "</div>";
    $("#alertas").html(elemento);
    setTimeout(function() {fechar_alerta(alerta)}, 4000);
}

function fechar_alerta(id){
    $("#fecharAlerta_" + id).click();
}

function obter_qtd_colunas_tabela(id){
    return document.getElementById(id).getElementsByTagName("thead")[0].getElementsByTagName("tr")[0].getElementsByTagName("th").length;
}

function mostrar_carregando_elemento(id){
    $("#" + id + " tbody").html("<tr><td colspan='" + obter_qtd_colunas_tabela(id) + "'><div class='d-flex justify-content-center align-items-center text-center' style='height: 5rem'><div class='spinner-border'></div></div></td></tr>");
}