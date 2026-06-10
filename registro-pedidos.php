<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança: Se não for administrador, volta pro login
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

try {
    // Busca os pedidos cruzando com a tabela tb_animais usando a coluna 'nome'
    $sql = "SELECT p.*, a.nome as pet_nome 
            FROM tb_pedidos_adocao p
            INNER JOIN tb_animais a ON p.id_animal = a.id
            ORDER BY p.id DESC";
    
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    $pedidos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Adoção - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/estoque-lista.css"> <link rel="stylesheet" href="assets/CSS/pedidos.css"> 
    <link rel="stylesheet" href="assets/CSS/pedidos.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php">📦 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
            <li><a href="registro-pedidos.php" class="active">📩 Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Solicitações de Adoção</h1>
            <p>Analise os perfis dos candidatos e aprove ou recuse novos lares para os pets.</p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'aprovado'): ?>
            <div class="alert-box alert-success">
                🎉 Adoção aprovada com sucesso! O status do pet foi atualizado.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'recusado'): ?>
            <div class="alert-box alert-error">
                ❌ Pedido de adoção arquivado/recusado.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert-box alert-error">
                ⚠️ Ocorreu um erro ao processar a solicitação.
            </div>
        <?php endif; ?>

        <div class="section-card">
            <div class="card-header-estoque">
                <h2>📋 Candidatos Interessados</h2>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Candidato (Adotante)</th>
                        <th>Contato (Telefone)</th>
                        <th>Pet Escolhido</th>
                        <th>Mensagem / Perfil</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pedidos) > 0): ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($pedido['nome_adotante']); ?></strong></td>
                                <td><?php echo htmlspecialchars($pedido['telefone_adotante']); ?></td>
                                <td><span class="category-tag">🐾 <?php echo htmlspecialchars($pedido['pet_nome']); ?></span></td>
                                <td class="mensagem-coluna"><?php echo htmlspecialchars($pedido['mensagem']); ?></td>
                                <td style="text-align: center;">
                                    <div class="actions-wrapper">
                                        <a href="backend/status-pedido.php?id=<?php echo $pedido['id']; ?>&acao=aprovar" class="btn-action-table btn-edit" style="background-color: #22c55e;">✔️ Aprovar</a>
                                        <a href="backend/status-pedido.php?id=<?php echo $pedido['id']; ?>&acao=recusar" class="btn-action-table btn-delete" onclick="return confirm('Deseja realmente recusar esta solicitação?');">❌ Recusar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #71717a; padding: 30px;">Nenhuma solicitação de adoção recebida até o momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>