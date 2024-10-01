<?php
    session_start();
    if(!isset($_SESSION['usuario']) || !intval($_SESSION['usuario']) || intval($_SESSION['usuario']) < 1){
        header("Location: ../index.php?erro=3");
    }

    require_once "../conexao.php";
    require_once "../crud.php";

    $blog = crudBlog::getInstance(Conexao::getInstance());
?>
<!DOCTYPE html>
    <html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="stylesheet" href="../bibliotecas/css/bootstrap.css">
        <link rel="stylesheet" href="../bibliotecas/css/bootstrap-icons.css">
        <link rel="stylesheet" href="../bibliotecas/css/principal.css">
        <script type="text/javascript" src="../bibliotecas/javascript/bootstrap.js"></script>
        <script type="text/javascript" src="../bibliotecas/javascript/echarts.js"></script>
        <script type="text/javascript" src="../bibliotecas/javascript/jquery.js"></script>
        <script type="text/javascript" src="../bibliotecas/javascript/inicio.js"></script>
    </head>
    <title>Inicio</title>
    <body>
        <div class="bg-primary p-3 shadow-lg text-white">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h5><?= $_SESSION['email']; ?></h5>
                    <button type="button" class="btn btn-outline-light" onclick="window.location.href = '../'"><i class="bi bi-box-arrow-right"></i> Sair</button>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div id="alertas"></div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 my-1">
                    <button id="botaoAdicionarAluno" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdicionarAluno" aria-expanded="false" aria-controls="collapseAdicionarAluno" onclick="alternar_botao_adicionar()"><i class="bi bi-plus-circle"></i> Adicionar</button>
                </div>
                <div class="col-12 col-sm-12 col-md-9 col-lg-10 col-xl-10">
                    <div class="collapse" id="collapseAdicionarAluno">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 my-1">
                                <div class="input-group">
                                    <input id="nome" name="nome" type="text" class="form-control" placeholder="Nome" aria-label="Nome" maxlength="255">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 my-1">
                                <div class="input-group">
                                    <input id="sobrenome" name="sobrenome" type="text" class="form-control" placeholder="Sobrenome" aria-label="Sobrenome" maxlength="255">
                                </div>
                            </div>
                            <div class="col-5 col-sm-5 col-md-5 col-lg-2 col-xl-2 my-1">
                                <div class="input-group">
                                    <span class="input-group-text" id="nascimentoLabel">Nascimento</span>
                                    <input id="nascimento" name="nascimento" type="date" class="form-control" placeholder="Nascimento" aria-label="nascimentoLabel" aria-describedby="nascimentoLabel">
                                </div>
                            </div>
                            <div class="col-5 col-sm-5 col-md-5 col-lg-3 col-xl-3 my-1">
                                <div class="input-group">
                                    <select class="form-control" id="turma">
                                        <option value="0">Turma</option>
                                        <?php
                                            $turmas = $blog->obter_turmas_por_usuario($_SESSION['usuario']);

                                            foreach ($turmas as $turma):
                                        ?>
                                            <option value="<?= $turma->turmas_codigo; ?>"><?= $turma->turmas_nome; ?></option>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 col-lg-1 col-xl-1 my-1" align="right">
                                <button type="button" id="botaoSalvarAluno" class="btn btn-success" onclick="adicionar_aluno(this.id)"><i class="bi bi-check2-square"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 my-1">
                    <div class="input-group">
                        <input id="pesquisaNome" name="pesquisaNome" type="text" class="form-control" placeholder="Nome ou sobrenome">
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-3 col-xl-3 my-1">
                    <div class="input-group">
                        <select class="form-control" id="pesquisaTurma" onchange="atualizar_lista_alunos()">
                            <option value="0">Turma</option>
                            <?php
                                $turmas = $blog->obter_turmas_por_usuario($_SESSION['usuario']);

                                foreach ($turmas as $turma):
                            ?>
                                <option value="<?= $turma->turmas_codigo; ?>"><?= $turma->turmas_nome; ?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-10 col-sm-10 col-md-5 col-lg-2 col-xl-2 my-1">
                    <div class="input-group">
                        <select class="form-control" id="pesquisaSituacao" onchange="atualizar_lista_alunos()">
                            <option value="0">Situação</option>
                            <?php
                                $situacao = array(
                                    array("valor" => "A", "descricao" => "Aprovado"),
                                    array("valor" => "R", "descricao" => "Reprovado"),
                                    array("valor" => "N", "descricao" => "Nota faltante"),
                                );

                                for($index = 0; $index < sizeof($situacao); $index++){
                            ?>
                                <option value="<?= $situacao[$index]["valor"]; ?>"><?= $situacao[$index]["valor"] . " - " . $situacao[$index]["descricao"]; ?></option>
                            <?php
                                };
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-2 col-sm-2 col-md-2 col-lg-1 col-xl-1 my-1" align="right">
                    <button type="button" id="botaoPesquisar" class="btn btn-dark" onclick="atualizar_lista_alunos()"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="table-responsive mt-2">
                <table id="tabelaAlunos" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>Idade</th>
                            <th>Turma</th>
                            <?php for($index = 1; $index <= 3; $index++) { echo "<th>Nota Prova " . $index . "</th>";} ?>
                            <th>Média</th>
                            <th>Situacao</th>
                            <th style="text-align: center; width: 1%"><button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalAnaliseGrafica" onclick="redimensionar_graficos_espera()"><i class="bi bi-graph-up"></i> Análise</button></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot></tfoot>
                </table>
            </div>
            <div class="card shadow mt-3">
                <div class="card-header">CRITÉRIO DE APROVAÇÃO</div>
                <div class="card-body">
                    <strong>SE [MÉDIA] (N1 + N2)/2 >= 7, <span class="text-success">APROVADO</span>. CASO CONTRÁRIO, FAZER P3. SE (MÉDIA + N3)/2 >= 5, <span class="text-success">APROVADO</span>. CASO CONTRÁRIO, <span class="text-danger">REPROVADO</span>.</strong>
                </div>
            </div>

            <div class="modal fade" id="modalConfirmacaoExclusao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Confirmação de Exclusão</h4>
                        </div>
                        <div class="modal-body">
                            <h5>Deseja mesmo excluir o aluno <b id="nomeAlunoExclusao"></b>?</h5>
                            <h6>Esta ação não poderá ser desfeita por você.</h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class='bi bi-x-lg'></i> Não</button>
                            <button id="botaoConfirmarExclusaoAluno" type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class='bi bi-trash3'></i> Sim</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalAnaliseGrafica" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Análise Gráfica</h4>
                        </div>
                        <div class="modal-body" id="modalAnaliseGraficaBody">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class='bi bi-x-lg'></i> Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function alternar_botao_adicionar(){
                if($("#botaoAdicionarAluno").hasClass("btn-primary")){
                    $("#botaoAdicionarAluno").removeClass("btn-primary");
                    $("#botaoAdicionarAluno").addClass("btn-secondary");
                } else {
                    $("#botaoAdicionarAluno").removeClass("btn-secondary");
                    $("#botaoAdicionarAluno").addClass("btn-primary");
                }
            }

            function limpar_campos_aluno(){
                $("#botaoAdicionarAluno").click();
                $("#nome").val("");
                $("#sobrenome").val("");
                $("#nascimento").val("");
                $("#turma").val("0");
            }

            function adicionar_aluno(id){
                if($("#nome").val().trim() == ""){
                    mostrar_alerta("<?= $erros[6]; ?>","danger");
                } else if($("#sobrenome").val().trim() == ""){
                    mostrar_alerta("<?= $erros[7]; ?>","danger");
                } else if($("#nascimento").val() == ""){
                    mostrar_alerta("<?= $erros[8]; ?>","danger");
                } else if($("#turma").val() == "0"){
                    mostrar_alerta("<?= $erros[9]; ?>","danger");
                } else {
                    var origem = $("#" + id).html();
                    mostrar_carregando_no_botao(id);
                    $.ajax({
                        type: "POST",
                        url: "api.php",
                        dataType: 'json',
                        data: {
                            funcao: "adicionar_aluno",
                            nome: $("#nome").val(),
                            sobrenome: $("#sobrenome").val(),
                            nascimento: $("#nascimento").val(),
                            turma: $("#turma").val(),
                        },
                        success: function(resultado){
                            mostrar_alerta(resultado,"success");
                            limpar_campos_aluno();
                            atualizar_lista_alunos();
                        },
                        error: function(erro) {
                            gerenciar_erro(erro);
                        },
                        complete: function(){
                            esconder_carregando_no_botao(id,origem);
                        }
                    });
                }
            }

            function obter_resultado_situacao(situacao){
                var elemento;
                switch (situacao){
                    case "A":
                        elemento = "<i class='bi bi-check-circle-fill text-success icone-alerta' title='Aprovado'></i>";
                        break;
                    case "R":
                        elemento = "<i class='bi bi-x-circle-fill text-danger icone-alerta' title='Reprovado'></i>";
                        break;
                    case "N":
                        elemento = "<i class='bi bi-exclamation-circle-fill text-warning icone-alerta' title='Nota faltante'></i>";
                        break;
                    default:
                        elemento = "<i class='bi bi-exclamation-circle-fill text-warning icone-alerta' title='Nota faltante'></i>";
                }

                return elemento;
            }

            function atualizar_lista_alunos(){
                mostrar_carregando_elemento("tabelaAlunos");
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    dataType: 'json',
                    data: {
                        funcao: "obter_lista_alunos",
                        pesquisa: $("#pesquisaNome").val(),
                        turma: $("#pesquisaTurma").val(),
                        situacao: $("#pesquisaSituacao").val()
                    },
                    success: function(resultado){
                        var dados_tabela = "";
                        var dados_footer = "";
                        var medias = [0,0,0,0];
                        var qtd_notas = [0,0,0,0];

                        if(resultado.length > 0){
                            for(var i = 0; i < resultado.length; i++){
                                var dados_notas = "";
                                for(var j = 1; j <= 3; j++){
                                    dados_notas += "<td>" +
                                                        "<input id='aluno_" + resultado[i].alunos_codigo + "prova_" + j + "' type='text' class='form-control' value='" + (resultado[i]["alunos_nota_" + j] != null ? resultado[i]["alunos_nota_" + j] : "") + "' style='width: 5rem; display: inline'>" +
                                                        "<button id='botao_aluno_" + resultado[i].alunos_codigo + "prova_" + j + "' class='btn btn-success btn-sm ms-2 mb-1' style='display: inline;' onclick='atualizar_nota(" + resultado[i].alunos_codigo + "," + j + ")'><i class='bi bi-check2-square'></i></button>" +
                                                    "</td>";

                                    if(resultado[i]["alunos_nota_" + j] != null){
                                        medias[j - 1] = medias[j - 1] + Number(resultado[i]["alunos_nota_" + j]);
                                        qtd_notas[j - 1] = qtd_notas[j - 1] + 1;
                                    }
                                }
                                dados_tabela += "<tr style='vertical-align: middle;'>" +
                                                    "<td>" + resultado[i].alunos_nome + "</td>" +
                                                    "<td>" + resultado[i].alunos_sobrenome + "</td>" +
                                                    "<td>" + resultado[i].idade + "</td>" +
                                                    "<td>" + resultado[i].turma + "</td>" +
                                                    dados_notas +
                                                    "<td style='text-align: center;'>" + resultado[i].media + "</td>" +
                                                    "<td style='text-align: center;'>" + obter_resultado_situacao(resultado[i].situacao) + "</td>" +
                                                    "<td style='text-align: right; width: 1%'><button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#modalConfirmacaoExclusao' onclick='abrirModalConfirmacao(\"" + resultado[i].alunos_nome + " " + resultado[i].alunos_sobrenome + "\"," + resultado[i].alunos_codigo + ")'><i class='bi bi-trash3'></i> Excluir</button></td>"
                                                "</tr>";
                                if(resultado[i].media != "-"){
                                    medias[3] = medias[3] + Number(resultado[i].media);
                                    qtd_notas[3] = qtd_notas[3] + 1;
                                }
                            }
                            dados_footer = "<tr>" +
                                                "<th align='right' colspan='4'>Médias</th>";
                            for(var i = 0; i < medias.length; i++){
                                dados_footer += "<th>" + (medias[i] != 0 && qtd_notas[i] != 0 ? (medias[i]/qtd_notas[i]).toFixed(2) : "") + "</th>";
                            }
                            dados_footer += "<th colspan='2'></th></tr>"
                        } else {
                            dados_tabela += "<tr><td align='center' colspan='" + obter_qtd_colunas_tabela("tabelaAlunos") + "'>Nenhum resultado encontrado</td></tr>";
                            dados_footer = "";1
                        }
                        $("#modalAnaliseGraficaBody").html("<div id='graficos' class='row'>" +
                                                                "<div class='col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9'>" +
                                                                    "<div id='grafico_notas' class='echart-graphic'></div>" +
                                                                "</div>" +
                                                                "<div class='col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3'>" +
                                                                    "<div id='grafico_situacao' class='echart-graphic'></div>" +
                                                                "</div>" +
                                                            "</div>");
                        gerar_graficos(resultado);

                        $("#tabelaAlunos tbody").html(dados_tabela);
                        $("#tabelaAlunos tfoot").html(dados_footer);
                    },
                    error: function(erro) {
                        gerenciar_erro(erro);
                        $("#tabelaAlunos tbody").html("<?= $erros[10]; ?>");
                    }
                });
            }

            function atualizar_nota(aluno, prova){
                var id = "aluno_" + aluno + "prova_" + prova;
                var origem = $("#botao_" + id).html();
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    dataType: 'json',
                    data: {
                        funcao: "atualizar_nota",
                        aluno: aluno,
                        prova: prova,
                        nota: $("#" + id).val()
                    },
                    success: function(resultado){
                        mostrar_alerta(resultado,"success");
                        atualizar_lista_alunos();
                    },
                    error: function(erro) {
                        gerenciar_erro(erro);
                    },
                    complete: function(){
                        esconder_carregando_no_botao("botao_" + id,origem);
                    }
                });
            }

            function abrirModalConfirmacao(nome, codigo){
                $("#nomeAlunoExclusao").html(nome);
                $("#botaoConfirmarExclusaoAluno").attr("onclick","confirmarExclusaoAluno(" + codigo + ")");
            }

            function confirmarExclusaoAluno(codigo){
                var origem = $("#botaoConfirmarExclusaoAluno").html();
                $.ajax({
                    type: "POST",
                    url: "api.php",
                    dataType: 'json',
                    data: {
                        funcao: "excluir_aluno",
                        codigo: codigo
                    },
                    success: function(resultado){
                        mostrar_alerta(resultado,"success");
                        atualizar_lista_alunos();
                    },
                    error: function(erro) {
                        gerenciar_erro(erro);
                    },
                    complete: function(){
                        esconder_carregando_no_botao("botaoConfirmarExclusaoAluno", origem);
                        $("#modalConfirmacaoExclusao").modal("hide");
                    }
                });
            }

            var pesquisaNome = document.getElementById("pesquisaNome");
            pesquisaNome.addEventListener("keydown", function (e) {
                if (e.code === "Enter") {
                    atualizar_lista_alunos();
                }
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            $(document).ready(function(){
                atualizar_lista_alunos();
            });

            var grafico_notas_definicao;
            var grafico_notas;

            var grafico_situacao_definicao;
            var grafico_situacao;

            function gerar_graficos(dados){
                $("#grafico_notas").html("");

                var fonte = [];
                var somaA = 0;
                var somaR = 0;
                var somaN = 0;
                var somaTotal = 0;

                for(var i = 0; i < dados.length; i++){
                    fonte.push({aluno: dados[i].alunos_nome + " " + dados[i].alunos_sobrenome,'Nota 1': (dados[i].alunos_nota_1 != null ? dados[i].alunos_nota_1 : 0),'Nota 2': (dados[i].alunos_nota_2 != null ? dados[i].alunos_nota_2 : 0),'Nota 3': (dados[i].alunos_nota_3 != null ? dados[i].alunos_nota_3 : 0)});                    
                    if(dados[i].situacao == "A"){
                        somaA++;
                        somaTotal++;
                    } else if(dados[i].situacao == "R"){
                        somaR++;
                        somaTotal++;
                    } else if(dados[i].situacao == "N"){
                        somaN++;
                        somaTotal++;
                    } else {
                        somaN++;
                        somaTotal++;
                    }
                }

                var grafico_notas_definicao = {
                    title: {
                        text: 'Notas'
                    },
                    legend: {},
                    tooltip: {},
                    dataset: {
                        dimensions: ['aluno', 'Nota 1', 'Nota 2', 'Nota 3'],
                        source: fonte
                    },
                    xAxis: { type: 'category' },
                    yAxis: {},
                    series: [{ type: 'bar', color: "#5470C6" }, { type: 'bar', color: "#91CC75" }, { type: 'bar', color: "#FAC858" }]
                };

                grafico_notas = echarts.init(document.getElementById('grafico_notas'));
                grafico_notas.setOption(grafico_notas_definicao);

                $("#grafico_situacao").html("");

                var grafico_situacao_definicao = {
                    title: {
                        text: 'Situações'
                    },
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        top: '5%',
                        left: 'center'
                    },
                    series: [
                        {
                        name: 'Situacao em %',
                        type: 'pie',
                        radius: ['40%', '75%'],
                        avoidLabelOverlap: false,
                        padAngle: 5,
                        itemStyle: {
                            borderRadius: 8
                        },
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                            show: true,
                            fontSize: 20,
                            fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            { value: (somaA == 0 || somaTotal == 0 ? 0 : ((somaA/somaTotal)*100).toFixed(2)), name: 'Aprovados', itemStyle: { color: "#91CC75"} },
                            { value: (somaR == 0 || somaTotal == 0 ? 0 : ((somaR/somaTotal)*100).toFixed(2)), name: 'Reprovados', itemStyle: { color: "#EE6666"} },
                            { value: (somaN == 0 || somaTotal == 0 ? 0 : ((somaN/somaTotal)*100).toFixed(2)), name: 'Nota Faltando', itemStyle: { color: "#FAC858"} },
                        ]
                        }
                    ]
                }

                grafico_situacao = echarts.init(document.getElementById('grafico_situacao'));
                grafico_situacao.setOption(grafico_situacao_definicao);

                redimensionar_graficos_espera();
            }

            function redimensionar_graficos(){
                grafico_notas.resize();
                grafico_situacao.resize();
            }

            function redimensionar_graficos_espera(){
                setTimeout(redimensionar_graficos, 300);
            }

            window.addEventListener('resize',function(){
                redimensionar_graficos();
            });
        </script>
    </body>
</html>