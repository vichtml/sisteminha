<?php
    require_once "conexao.php";
    require_once "crud.php";

    $blog  = crudBlog::getInstance(Conexao::getInstance());

    quebrar_sessao();

    if(!isset($_POST['usuario']) || !isset($_POST['senha'])) {
        header("Location: index.php?erro=1");
    } else {
        $usuario = $blog->login($_POST['usuario'], $_POST['senha']);

        if(!$usuario){
            header("Location: index.php?erro=2");
        } else {
            quebrar_sessao();
            session_start();
            $_SESSION['usuario'] = $usuario->usuarios_codigo;
            $_SESSION['email'] = $usuario->usuarios_email;
            header("Location: inicio/");
        }        
    }
?>