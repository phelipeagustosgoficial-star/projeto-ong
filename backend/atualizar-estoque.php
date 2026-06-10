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

// Verifica se os dados vieram via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura e sanitiza os dados
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $item_nome = trim(filter_input(INPUT_POST, 'item_nome'));
    $categoria = trim(filter_input(INPUT_POST, 'categoria'));
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $status_necessidade = trim(filter_input(INPUT_POST, 'status_necessidade'));

    // Validação dos dados
    if (
        $id === false ||
        empty($item_nome) ||
        empty($categoria) ||
        $quantidade === false ||
        empty($status_necessidade)
    ) {
        header("Location: ../gerenciar-estoque.php?status=erro");
        exit();
    }

    try {

        $sql = "UPDATE tb_estoque
                SET
                    item_nome = :item_nome,
                    categoria = :categoria,
                    quantidade = :quantidade,
                    status_necessidade = :status_necessidade
                WHERE id = :id";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':item_nome', $item_nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmt->bindParam(':status_necessidade', $status_necessidade);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        header("Location: ../gerenciar-estoque.php?status=sucesso");
        exit();

    } catch (PDOException $e) {

        // Durante o desenvolvimento
        die("Erro ao atualizar item do estoque: " . $e->getMessage());

        // Em produção use:
        // header("Location: ../gerenciar-estoque.php?status=erro");
        // exit();
    }

} else {

    header("Location: ../gerenciar-estoque.php");
    exit();

}
?>