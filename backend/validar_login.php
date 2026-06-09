<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    try {
        $sql = "SELECT * FROM tb_usuarios WHERE email = :email";
        $comando = $conexao->prepare($sql);
        $comando->bindParam(':email', $email);
        $comando->execute();
        
        $userObj = $comando->fetch(PDO::FETCH_ASSOC);

        if ($userObj && $userObj['senha'] === $senha) {
            
            // Inicia a sessão gravando os dados do Usuário
            $_SESSION['logado'] = true;
            $_SESSION['user_id'] = $userObj['id'];
            $_SESSION['user_nome'] = $userObj['nome'];
            $_SESSION['nivel_acesso'] = $userObj['nivel_acesso']; // Salva se é 'admin' ou 'user'

            // DIRECCIONAMENTO BASEADO NO NÍVEL DE ACESSO
            if ($userObj['nivel_acesso'] === 'admin') {
                // Se for Administrador, vai para a Dashboard da ONG
                header("Location: ../index.php");
                exit();
            } else {
                // Se for Adotante Comum (user), vai para a nova página de adoção
                header("Location: ../painel-adotante.php");
                exit();
            }

        } else {
            header("Location: ../login.php?erro=1");
            exit();
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        echo "Erro crítico de sistema ao processar o login.";
    }
} else {
    header("Location: ../login.php");
    exit();
}