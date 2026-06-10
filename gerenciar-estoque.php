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
    // Busca todos os itens do estoque salvos no banco
    $sql = "SELECT * FROM tb_estoque ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    $itens = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estoque - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/estoque-lista.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php" class="active">📦 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
             <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Gerenciamento de Estoque</h1>
            <p>Controle a quantidade de rações, medicamentos e insumos da ONG.</p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert-box alert-success">
                ✅ Alteração realizada com sucesso!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'deletado'): ?>
            <div class="alert-box alert-success">
                🗑️ Item removido do estoque com sucesso!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert-box alert-error">
                ❌ Ocorreu um erro ao processar a solicitação.
            </div>
        <?php endif; ?>

        <div class="section-card">
            <div class="card-header-estoque">
                <h2>📦 Itens Cadastrados</h2>
                <a href="estoque-novo.php" class="btn-add-item">➕ Novo Item</a>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Item de Consumo</th>
                        <th>Categoria</th>
                        <th>Qtd. Atual</th>
                        <th>Status de Urgência</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($itens) > 0): ?>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($item['item_nome']); ?></strong></td>
                                <td><span class="category-tag"><?php echo htmlspecialchars($item['categoria']); ?></span></td>
                                <td><?php echo htmlspecialchars($item['quantidade']); ?></td>
                                <td>
                                    <?php
                                    $status = mb_strtolower($item['status_necessidade']);
                                    if ($status === 'crítico' || $status === 'critico'):
                                    ?>
                                        <span class="status-urgencia urgencia-critico">● Crítico</span>
                                    <?php elseif ($status === 'atenção' || $status === 'atencao'): ?>
                                        <span class="status-urgencia urgencia-atencao">● Atenção</span>
                                    <?php else: ?>
                                        <span class="status-urgencia urgencia-ok">● OK</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div class="actions-wrapper">
                                        <a href="estoque-editar.php?id=<?php echo $item['id']; ?>" class="btn-action-table btn-edit">✏️ Editar</a>
                                        <a href="backend/deletar-estoque.php?id=<?php echo $item['id']; ?>" class="btn-action-table btn-delete" onclick="return confirm('Deseja realmente remover este item do estoque?');">🗑️ Deletar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #71717a; padding: 30px;">Nenhum insumo cadastrado no estoque.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>