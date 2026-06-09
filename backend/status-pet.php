<?php
session_start();

// Trava de segurança: Se não for admin, chuta daqui
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

if (isset($_GET['id']) && isset($_GET['acao'])) {
    $id = intval($_GET['id']);
    $acao = $_GET['acao'];

    try {
        if ($acao === 'ativar') {
            // Grava 1 para representar Ativo
            $sql = "UPDATE tb_animais SET status = 1 WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: ../editar.php?status=sucesso");
            exit();

        } elseif ($acao === 'desativar') {
            // Grava 0 para representar Inativo
            $sql = "UPDATE tb_animais SET status = 0 WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: ../editar.php?status=sucesso");
            exit();

        } elseif ($acao === 'deletar') {
            // Busca o nome da imagem para deletar o arquivo físico
            $sql_img = "SELECT imagem FROM tb_animais WHERE id = :id";
            $stmt_img = $conexao->prepare($sql_img);
            $stmt_img->bindParam(':id', $id);
            $stmt_img->execute();
            $pet = $stmt_img->fetch(PDO::FETCH_ASSOC);

            if ($pet) {
                $caminho_imagem = "../assets/img/imagens-ong/" . $pet['imagem'];
                if (file_exists($caminho_imagem) && !empty($pet['imagem'])) {
                    unlink($caminho_imagem);
                }
            }

            // Deleta do banco
            $sql = "DELETE FROM tb_animais WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header("Location: ../editar.php?status=deletado");
            exit();
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        header("Location: ../editar.php?status=erro");
        exit();
    }
} else {
    header("Location: ../editar.php");
    exit();
}