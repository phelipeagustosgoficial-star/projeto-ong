<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$status_atual = filter_input(INPUT_GET, 'atual', FILTER_DEFAULT);

if ($id && $status_atual) {
    // Se estiver ativa, desativa (Encerrada). Se não, reativa (Ativa).
    $novo_status = ($status_atual === 'Ativa') ? 'Encerrada' : 'Ativa';

    try {
        $sql = "UPDATE tb_parcerias SET status_parceria = :novo_status WHERE id = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':novo_status', $novo_status);
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