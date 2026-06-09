<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e limpa os dados enviados
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    
    // DEFINIÇÃO AUTOMÁTICA E SEGURA: Todo cadastro pelo site vira 'user' (Adotante Comum)
    $nivel_acesso = 'user'; 

    try {
        // 1. Verifica se o e-mail digitado já existe para evitar duplicidade
        $sql_busca = "SELECT id FROM tb_usuarios WHERE email = :email";
        $comando_busca = $conexao->prepare($sql_busca);
        $comando_busca->bindParam(':email', $email);
        $comando_busca->execute();

        if ($comando_busca->rowCount() > 0) {
            header("Location: ../cadastro-usuario.php?erro=email_existente");
            exit();
        }

        // 2. Insere o novo registro com o nível 'user' travado pelo backend
        $sql_insere = "INSERT INTO tb_usuarios (nome, email, senha, nivel_acesso) 
                       VALUES (:nome, :email, :senha, :nivel_acesso)";
        
        $comando_insere = $conexao->prepare($sql_insere);
        $comando_insere->bindParam(':nome', $nome);
        $comando_insere->bindParam(':email', $email);
        $comando_insere->bindParam(':senha', $senha);
        $comando_insere->bindParam(':nivel_acesso', $nivel_acesso);

        if ($comando_insere->execute()) {
            header("Location: ../cadastro-usuario.php?sucesso=1");
            exit();
        } else {
            header("Location: ../cadastro-usuario.php?erro=1");
            exit();
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        header("Location: ../cadastro-usuario.php?erro=1");
        exit();
    }
} else {
    header("Location: ../cadastro-usuario.php");
    exit();
}