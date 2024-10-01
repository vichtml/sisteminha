<?php
    require_once "../conexao.php";
    require_once "../crud.php";

    $blog  = crudBlog::getInstance(Conexao::getInstance());

    session_start();
    
    if(!isset($_POST["funcao"])) {
        http_response_code(400);
        echo json_encode(gerenciar_erro(4, $erros, true));
		quebrar_sessao();
    }else if(!isset($_SESSION['usuario']) || !intval($_SESSION['usuario']) || intval($_SESSION['usuario']) < 1){
        http_response_code(401);
        echo json_encode(gerenciar_erro(3, $erros, true));
		quebrar_sessao();
    } else if($_POST["funcao"] == "adicionar_aluno"){
        $turmas = $blog->obter_turmas_por_usuario($_SESSION['usuario']);
        $codigos_turmas = array();        

        foreach($turmas as $turma):
            array_push($codigos_turmas, $turma->turmas_codigo);
        endforeach;
        
        if(!isset($_POST["nome"]) || !isset($_POST["sobrenome"]) || !isset($_POST["nascimento"]) || !isset($_POST["turma"]) ||
            trim($_POST["nome"]) == "" || trim($_POST["sobrenome"]) == "" || !in_array($_POST["turma"], $codigos_turmas)
        ){
            http_response_code(400);
            echo json_encode(gerenciar_erro(1, $erros));
        } else {
            $adicionar_aluno = $blog->adicionar_aluno(trim($_POST["nome"]),trim($_POST["sobrenome"]),$_POST["nascimento"],$_POST["turma"]);

            if($adicionar_aluno){
                http_response_code(200);
                echo json_encode("Aluno adicionado com sucesso!");
            } else {
                http_response_code(500);
                echo json_encode(gerenciar_erro(5, $erros));
            }
        }
    } else if($_POST["funcao"] == "obter_lista_alunos"){        
        $consulta = "SELECT alunos_codigo, alunos_nome, alunos_nota_1, alunos_nota_2, alunos_nota_3, alunos_sobrenome, TIMESTAMPDIFF(YEAR, alunos_nascimento, CURDATE()) AS idade, turmas_nome AS turma, " .
                    "CASE WHEN alunos_nota_1 IS NULL THEN '-' " .
                        " WHEN alunos_nota_2 IS NULL THEN '-' " .
                        " ELSE (CASE WHEN alunos_nota_3 IS NULL THEN TRUNCATE((alunos_nota_1 + alunos_nota_2)/2, 2) " .
                                " ELSE TRUNCATE((((alunos_nota_1 + alunos_nota_2)/2) + alunos_nota_3)/2, 2) " .
                                " END) " .
                        " END as media, " .
                    "CASE WHEN alunos_nota_1 IS NULL THEN 'N' " .
                        " WHEN alunos_nota_2 IS NULL THEN 'N' " .
                        " ELSE (CASE WHEN alunos_nota_3 IS NULL THEN IF((alunos_nota_1 + alunos_nota_2)/2 >= 7,'A','N') " .
                                " ELSE IF((((alunos_nota_1 + alunos_nota_2)/2) + alunos_nota_3)/2 >= 5,'A','R') " .
                                " END) " .
                        " END as situacao " .
                    " FROM alunos, turmas WHERE turmas_codigo=alunos_turma AND alunos_ativo=1 ";
        if(isset($_POST["pesquisa"]) && trim($_POST["pesquisa"]) != ""){
            $consulta .= " AND (alunos_nome LIKE '%" . trim($_POST["pesquisa"]) . "%' OR alunos_sobrenome LIKE '%" . trim($_POST["pesquisa"]) . "%') ";
        }

        $turmas = $blog->obter_turmas_por_usuario($_SESSION['usuario']);
        $codigos_turmas = array();        

        foreach($turmas as $turma):
            array_push($codigos_turmas, $turma->turmas_codigo);
        endforeach;

        if(isset($_POST["turma"]) && in_array($_POST["turma"], $codigos_turmas)){
            $consulta .= " AND turmas_codigo=" . $_POST["turma"];
        }

        if(isset($_POST["situacao"]) && ($_POST["situacao"] == "A" || $_POST["situacao"] == "R" || $_POST["situacao"] == "N")){
            $consulta .= " HAVING situacao='" . $_POST["situacao"] . "'";
        }

        $consulta .= " ORDER BY alunos_nome";

        $alunos = $blog->obter_resultado_consulta($consulta);

        if($alunos || $alunos == []){
            http_response_code(200);
            echo json_encode($alunos);
        } else {
            http_response_code(500);
            echo json_encode(gerenciar_erro(10, $erros));
        }
    } else if($_POST["funcao"] == "atualizar_nota"){        
        if(!isset($_POST["aluno"]) || !isset($_POST["prova"]) || !isset($_POST["nota"]) ||
           !intval($_POST["aluno"]) || intval($_POST["aluno"]) < 1 ||
           !intval($_POST["prova"]) || intval($_POST["prova"]) < 1 || intval($_POST["prova"]) > 3
        ){
            http_response_code(400);
            echo json_encode(gerenciar_erro(1, $erros));
        } else {
            $nota = trim($_POST["nota"]) == "" || floatval($_POST["nota"]) < 0 ? null : $_POST["nota"];
            $atualizar_nota = $blog->atualizar_nota($_POST["aluno"],$_POST["prova"],$nota,$_SESSION['usuario']);

            if($atualizar_nota){
                http_response_code(200);
                echo json_encode("Nota atualizada com sucesso!");
            } else {
                http_response_code(500);
                echo json_encode(gerenciar_erro(11, $erros));
            }
        }
    } else if($_POST["funcao"] == "excluir_aluno"){        
        if(!isset($_POST["codigo"]) || !intval($_POST["codigo"]) || intval($_POST["codigo"]) < 1){
            http_response_code(400);
            echo json_encode(gerenciar_erro(1, $erros));
        } else {
            $excluir_aluno = $blog->excluir_aluno($_POST["codigo"],$_SESSION['usuario']);

            if($excluir_aluno){
                http_response_code(200);
                echo json_encode("Aluno excluído com sucesso!");
            } else {
                http_response_code(500);
                echo json_encode(gerenciar_erro(12, $erros));
            }
        }        
    } else {
        http_response_code(405);
        echo json_encode(gerenciar_erro(4, $erros, true));
		quebrar_sessao();
    }
?>