<?php
// backend/cadastrar-estoque.php
require_once 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $item_nome          = filter_input(INPUT_POST, 'item_nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $categoria          = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_SPECIAL_CHARS);
    $quantidade         = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_SPECIAL_CHARS);
    $status_necessidade = filter_input(INPUT_POST, 'status_necessidade', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($item_nome && $categoria && $quantidade && $status_necessidade) {
        try {
            // Nota: Altere '$conexao' para '$pdo' caso seu arquivo de conexão use a variável $pdo
            $sql = "INSERT INTO tb_estoque (item_nome, categoria, quantidade, status_necessidade) 
                    VALUES (:item_nome, :categoria, :quantidade, :status_necessidade)";
            
            $stmt = $conexao->prepare($sql);
            
            $stmt->bindValue(':item_nome', $item_nome);
            $stmt->bindValue(':categoria', $categoria);
            $stmt->bindValue(':quantidade', $quantidade);
            $stmt->bindValue(':status_necessidade', $status_necessidade);
            
            if ($stmt->execute()) {
                header("Location: ../gerenciar-estoque.php?status=sucesso");
                exit();
            } else {
                header("Location: ../gerenciar-estoque.php?status=erro");
                exit();
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header("Location: ../gerenciar-estoque.php?status=erro");
            exit();
        }
    } else {
        header("Location: ../gerenciar-estoque.php?status=erro");
        exit();
    }
} else {
    header("Location: ../gerenciar-estoque.php");
    exit();
}