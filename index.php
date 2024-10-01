<!--
    PARA A PRIMEIRA PARTE DO CÓDIGO, OS CONCEITOS EXPLORADOS SERÃO SOBRE ELEMENTOS HTML, CLASSES, FORMATAÇÃO, FORMULÁRIOS E MÉTODOS
    MONTAREMOS UMA PÁGINA DE LOGIN EM UMA PLATAFORMA PARA PROFESSORES LANÇAREM AS NOTAS DOS ALUNOS, UTILIZANDO O FRAMEWORK DE FRONTEND BOOTSTRAP 5
-->

<?php
    require_once "crud.php";            // INCLUSÃO DO ARQUIVO CRUD, QUE VAI LIDAR COM AS TRANSAÇÕES COM O BANCO DE DADOS E CONTÉM FUNÇÕES UTILIZADAS POR TODO O SISTEMA
    quebrar_sessao();
?>
<!DOCTYPE html>
    <html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="stylesheet" href="bibliotecas/css/bootstrap.css">
        <link rel="stylesheet" href="bibliotecas/css/bootstrap-icons.css">
        <link rel="stylesheet" href="bibliotecas/css/principal.css">
        <script type="text/javascript" src="bibliotecas/javascript/bootstrap.js"></script>        
    </head>
    <title>LOGIN - Notas</title>
    <body>
        <div class="container mt-5">
            <div class="row mt-5">
                <div class="col-6 mt-5 mx-auto">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>LOGIN</h4><h5>Gerenciamento de Notas</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="login.php">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                            <input name="usuario" type="text" class="form-control" placeholder="Usuário" aria-label="Usuário" aria-describedby="basic-addon1" required>
                                        </div>

                                        <div class="input-group mt-1">
                                            <input name="senha" type="password" class="form-control" placeholder="Senha" aria-label="Senha" required>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button type="subimt" class="btn btn-primary" style="width: 100%; height: 100%;">Entrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php
                            if(isset($_GET['erro'])){
                        ?>
                        <div class="py-2 px-3">
                            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                                <strong><i class="bi bi-exclamation-triangle-fill icone-alerta"></i> </strong> <span><?= intval($_GET['erro']) && intval($_GET['erro']) < sizeof($erros) ? $erros[$_GET['erro']] : $erros["0"]; ?></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>