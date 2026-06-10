<?php
session_start();

// Verifica se o usuário está logado e é administrador
if (
    !isset($_SESSION['logado']) ||
    !isset($_SESSION['nivel_acesso']) ||
    $_SESSION['nivel_acesso'] !== 'admin'
) {
    header("Location: ../login.php");
    exit();
}

// Conexão com o banco
require_once 'conexao.php';

// Verifica se o ID foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../gerenciar-estoque.php?status=erro");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false) {
    header("Location: ../gerenciar-estoque.php?status=erro");
    exit();
}

try {

    $sql = "DELETE FROM tb_estoque WHERE id = :id";

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    header("Location: ../gerenciar-estoque.php?status=deletado");
    exit();

} catch (PDOException $e) {

    die("Erro ao deletar item: " . $e->getMessage());

}
?>