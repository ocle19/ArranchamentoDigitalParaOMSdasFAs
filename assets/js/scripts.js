function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58)) return true;
    else {
        if (tecla == 8 || tecla == 0) return true;
        else return false;
    }
}

function makeDate(id) {
    obj = document.getElementById(id);
    vl = obj.value;
    l = vl.toString().length;
    switch (l) {
        case 2:
            obj.value = vl + "/";
            break;
        case 5:
            obj.value = vl + "/";
            break;
    }
}

function makeHora(id) {
    obj = document.getElementById(id);
    vl = obj.value;
    l = vl.toString().length;
    switch (l) {
        case 2:
            obj.value = vl + ":";
            break;

    }
}

function arranchar(id) {
    Swal.showLoading()
    var data = new FormData($('#FormArrancharDias' + id)[0]);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerArranchar.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {
            Swal.showLoading()
        },

        success: function (result) {

            /// var resultado = JSON.parse(result);
            if ($.trim(result) === "ok") {
                Swal.fire({
                    title: 'Sucesso',
                    text: 'Militar arranchado!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
            } else {
                Swal.fire({
                    title: '' + result + '',
                    text: '' + result + '',
                    icon: '' + result + '',
                    showConfirmButton: false,
                    timer: 2500
                })
                Swal.hideLoading()
            }
        },

        complete: function () {
            Swal.hideLoading()
            buscarArranchamentoListaMilitaresIndividual(id)
        },

        error: function (e) {
            Swal.hideLoading()
        }

    });
}

function buscarArranchamentoListaMilitaresIndividual(militarID) {
    Swal.showLoading()
    var data = new FormData($('#FormArrancharDias' + militarID)[0]);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/buscarArranchamentoMilitar.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {
            Swal.showLoading()
        },

        success: function (result) {

            /// var resultado = JSON.parse(result);
            if ($.trim(result) === "0") {
                Swal.fire({
                    title: 'Oops',
                    text: 'Não foi encontrado nennum registro neste periodo!',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
                return false;
            } else {
                Swal.fire({
                    title: "Prévia do arranchamento.",
                    html: result,
                    customClass: 'swal-wide',
                    showConfirmButton: true
                })
                Swal.hideLoading()
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal.hideLoading()
        }

    });
}

const btn_logar = $("#btn_logar") ?? 0;
btn_logar.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_login')[0]);
    btn_logar.html('Verificando...');
    btn_logar.prop("disabled", true);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/validacao.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {

            var resultado = JSON.parse(result);
            if ($.trim(resultado.mensagem) === "ok") {
                btn_logar.html('Conectando...');
                window.location.href = resultado.irpara
                btn_logar.prop("disabled", false);

            } else {
                Swal.fire({
                    title: '' + resultado.resposta + '',
                    text: '' + resultado.mensagem + '',
                    icon: '' + resultado.status + '',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
                btn_logar.html('Entrar');
                btn_logar.prop("disabled", false);
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e.responseTex + '', 'error');
            btn_logar.prop("disabled", false);
            Swal.hideLoading()
        }

    });
});

const btn_salvar_edicao_militar = $("#btn_salvar_edicao_militar") ?? 0;
btn_salvar_edicao_militar.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_edicao_militar')[0]);
    btn_salvar_edicao_militar.html('Salvando...');
    btn_salvar_edicao_militar.prop("disabled", true);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerMilitares.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {

            var resultado = JSON.parse(result);
            if ($.trim(resultado.mensagem) === "ok") {
                btn_salvar_edicao_militar.html('Salvando...');
                btn_salvar_edicao_militar.prop("disabled", false);

            } else {
                Swal.fire({
                    title: '' + resultado.resposta + '',
                    text: '' + resultado.mensagem + '',
                    icon: '' + resultado.status + '',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
                btn_salvar_edicao_militar.html('Atualizar dados do militar');
                btn_salvar_edicao_militar.prop("disabled", false);
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e.responseTex + '', 'error');
            btn_salvar_edicao_militar.prop("disabled", false);
            Swal.hideLoading()
        }

    });
});

const btn_cadastrar_militar = $("#btn_cadastrar_militar") ?? 0;
btn_cadastrar_militar.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_cadastrar_militar')[0]);
    btn_cadastrar_militar.html('Salvando...');
    btn_cadastrar_militar.prop("disabled", true);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerMilitares.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {

            var resultado = JSON.parse(result);
            if ($.trim(resultado.mensagem) === "ok") {
                btn_cadastrar_militar.html('Salvando...');
                btn_cadastrar_militar.prop("disabled", false);

            } else {
                Swal.fire({
                    title: '' + resultado.resposta + '',
                    text: '' + resultado.mensagem + '',
                    icon: '' + resultado.status + '',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
                btn_cadastrar_militar.html('Cadastrar militar');
                btn_cadastrar_militar.prop("disabled", false);
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e.responseTex + '', 'error');
            btn_cadastrar_militar.prop("disabled", false);
            Swal.hideLoading()
        }

    });
});

const btn_trocar_senha = $("#btn_trocar_senha") ?? 0;
btn_trocar_senha.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_trocar_senha')[0]);
    btn_trocar_senha.html('Verificando...');
    btn_trocar_senha.prop("disabled", true);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerMilitares.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {

            var resultado = JSON.parse(result);
            if ($.trim(resultado.mensagem) === "ok") {
                btn_trocar_senha.html('Verificando...');
                btn_trocar_senha.prop("disabled", false);

            } else {
                Swal.fire({
                    title: '' + resultado.resposta + '',
                    text: '' + resultado.mensagem + '',
                    icon: '' + resultado.status + '',
                    showConfirmButton: false,
                    timer: 1500
                })
                Swal.hideLoading()
                btn_trocar_senha.html('Alterar senha');
                btn_trocar_senha.prop("disabled", false);
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e.responseTex + '', 'error');
            btn_trocar_senha.prop("disabled", false);
            Swal.hideLoading()
        }

    });
});

const btn_gerar_relatorio_entre_periodos = $("#btn_gerar_relatorio_entre_periodos") ?? 0;
btn_gerar_relatorio_entre_periodos.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_gerar_relatorio_entre_periodos')[0]);
    btn_gerar_relatorio_entre_periodos.html('Gerando...');
    btn_gerar_relatorio_entre_periodos.prop("disabled", true);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerRelatorios.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {
            try {
                var resultado = JSON.parse(result);
                if ($.trim(resultado.mensagem) === "ok") {
                    btn_gerar_relatorio_entre_periodos.html('Gerar relatório');
                    btn_gerar_relatorio_entre_periodos.prop("disabled", false);
                    window.open(resultado.pdf);
                } else {
                    Swal.fire({
                        title: '' + resultado.resposta + '',
                        text: '' + resultado.mensagem + '',
                        icon: '' + resultado.status + '',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    Swal.hideLoading()
                    btn_gerar_relatorio_entre_periodos.html('Gerar relatório');
                    btn_gerar_relatorio_entre_periodos.prop("disabled", false);
                }
            } catch {
                Swal.fire({
                    title: 'Oops',
                    text: 'Ocorreu um erro!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                })
                btn_gerar_relatorio_entre_periodos.html('Gerar relatório');
                btn_gerar_relatorio_entre_periodos.prop("disabled", false);
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e + '', 'error');
            btn_gerar_relatorio_entre_periodos.prop("disabled", false);
            Swal.hideLoading()
        }

    });
});

//// Exibe erro caso a nova senha não combine com a repetição
function relatorioArranchados(relatorio, dataArranchamento) {
    Swal.showLoading();
    let data = {
        'acao': relatorio,
        'dataArranchamento': dataArranchamento
    };
    $.ajax({
        type: "POST",
        url: "acoes/controllerRelatorios.php",
        data: data,
        cache: false,
        timeout: 600000,

        beforeSend: function () {

        },

        success: function (result) {
            try {
                var resultado = JSON.parse(result);
                if ($.trim(resultado.mensagem) === "ok") {
                    Swal.fire({
                        title: '' + resultado.resposta + '',
                        text: '' + resultado.mensagem + '',
                        icon: '' + resultado.status + '',
                        showConfirmButton: true,
                        timer: 500
                    })
                    window.open(resultado.pdf);
                } else {
                    Swal.fire({
                        title: '' + resultado.resposta + '',
                        text: '' + resultado.mensagem + '',
                        icon: '' + resultado.status + '',
                        showConfirmButton: true,
                        timer: 1500
                    })
                    Swal.hideLoading()
                }
            } catch (e) {
                Swal('Oops!', 'Ocorreu algum erro!', 'error');
            }
        },

        complete: function () {
            Swal.hideLoading()
        },

        error: function (e) {
            Swal('Oops!', '' + e.responseTex + '', 'error');
            btn.prop("disabled", false);
            Swal.hideLoading()
        }

    });
}
const btn_enviar_cardapio = $("#btn_enviar_cardapio") ?? 0;
btn_enviar_cardapio.click(function (event) {
    event.preventDefault();
    var data = new FormData($('#form_enviar_cardapio')[0]);
    data.append('pdf', $('input[type=file]')[0].files[0]);

    if ((($('input[type=file]')[0].files[0]))) {
    } else {
        Swal.fire({
            title: 'Oops',
            text: 'Selecione um arquivo PDF',
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
        })
        return;
    }

    btn_enviar_cardapio.prop("disabled", true);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: "acoes/controllerCardapio.php",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,

        beforeSend: function () {
            
        },

        success: function (result) {
            try {
                var resultado = JSON.parse(result);
                if ($.trim(resultado.mensagem) === "ok") {
                    Swal.fire({
                        title: '' + resultado.resposta + '',
                        text: 'Cardápio enviado!',
                        icon: '' + resultado.status + '',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $("#form_enviar_cardapio").get(0).reset();
                    btn_enviar_cardapio.prop("disabled", false);
                } else {
                    Swal.fire({
                        title: '' + resultado.resposta + '',
                        text: '' + resultado.mensagem + '',
                        icon: '' + resultado.status + '',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            } catch {
                Swal.fire({
                    title: 'Oops',
                    text: 'Ocorreu um erro!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                })
                btn_enviar_cardapio.html('Enviar cardápio');
                btn_enviar_cardapio.prop("disabled", false);
            }
        },

        error: function (e) {
            Swal.fire({
                title: 'Oops',
                text: 'Ocorreu um erro!',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500
            })
            btn_enviar_cardapio.prop("disabled", false);
        }

    });
});


$("input[type=password]").keyup(function () {
    if (document.getElementById('nova_senha').value.length > 0) {
        if (document.getElementById('nova_senha').value === document.getElementById('nova_senha_r').value) {
            $("#erro_repeticao_senha").css("display", "block");
            $("#pwmatch").removeClass("glyphicon-remove");
            $("#pwmatch").addClass("glyphicon-ok");
            $("#pwmatch").css("color", "#00A41E");
            $("#txtpwmatch").html("As senhas conferem!")
        } else {
            $("#erro_repeticao_senha").css("display", "block");
            $("#pwmatch").removeClass("glyphicon-ok");
            $("#pwmatch").addClass("glyphicon-remove");
            $("#pwmatch").css("color", "#FF0004");
            $("#txtpwmatch").html("As senhas <b>NÃO</b> conferem!")
        }
    }
});