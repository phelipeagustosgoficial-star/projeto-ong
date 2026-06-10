<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $sql = "DELETE FROM tb_parcerias WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Corrigido: Aponta de volta para a tela única de parcerias
        header("Location: ../parcerias-novo.php?status=sucesso");
        exit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../parcerias-novo.php?status=erro");
        exit();
    }
} else {
    header("Location: ../parcerias-novo.php");
    exit();
}