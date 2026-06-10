<?php
// backend/status-pedido.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Segurança: Apenas admin
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

$id   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_SPECIAL_CHARS);

if ($id && $acao) {
    try {
        if ($acao === 'aprovar') {
            // 1. Primeiro, buscamos o id_animal associado a este pedido
            $sql_busca = "SELECT id_animal FROM tb_pedidos_adocao WHERE id = :id";
            $stmt_busca = $conexao->prepare($sql_busca);
            $stmt_busca->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt_busca->execute();
            $pedido = $stmt_busca->fetch(PDO::FETCH_ASSOC);

            if ($pedido) {
                $id_animal = $pedido['id_animal'];

                // 2. Atualiza o status do pedido para Aprovado
                $sql1 = "UPDATE tb_pedidos_adocao SET status_pedido = 'Aprovado' WHERE id = :id";
                $stmt1 = $conexao->prepare($sql1);
                $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt1->execute();

                // 3. Atualiza o status do animal para 'Adotado' (ou desativado) para sumir do site público
                // Nota: Verifique se sua coluna se chama 'status' na tb_animais
                $sql_animal = "UPDATE tb_animais SET status = 'Adotado' WHERE id = :id_animal";
                $stmt_animal = $conexao->prepare($sql_animal);
                $stmt_animal->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
                $stmt_animal->execute();
            }
            
            header("Location: ../registro-pedidos.php?status=aprovado");
            exit();

        } elseif ($acao === 'recusar') {
            // Atualiza o status do pedido para Recusado
            $sql2 = "UPDATE tb_pedidos_adocao SET status_pedido = 'Recusado' WHERE id = :id";
            $stmt2 = $conexao->prepare($sql2);
            $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            header("Location: ../registro-pedidos.php?status=recusado");
            exit();
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../registro-pedidos.php?status=erro");
        exit();
    }
}

header("Location: ../registro-pedidos.php");
exit();
?>