<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Conexão com o banco de dados
require_once 'backend/conexao.php';

try {
    // Busca contagem total de animais
    $sql_animais = "SELECT COUNT(*) as total FROM tb_animais";
    $stmt = $conexao->prepare($sql_animais);
    $stmt->execute();
    $total_animais = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Busca contagem de itens críticos no estoque
    $sql_criticos = "SELECT COUNT(*) as total FROM tb_estoque WHERE status_necessidade = 'Crítico'";
    $stmt = $conexao->prepare($sql_criticos);
    $stmt->execute();
    $itens_criticos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // CORRIGIDO: Busca contagem real e dinâmica de parcerias ATIVAS direto do banco
    $sql_parcerias = "SELECT COUNT(*) as total FROM tb_parcerias WHERE status_parceria = 'Ativa'";
    $stmt = $conexao->prepare($sql_parcerias);
    $stmt->execute();
    $parcerias_ativas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Busca todos os itens do estoque
    $sql_estoque = "SELECT * FROM tb_estoque ORDER BY status_necessidade DESC";
    $stmt = $conexao->prepare($sql_estoque);
    $stmt->execute();
    $itens_estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $erro) {
    error_log($erro->getMessage());
    echo "Erro ao carregar o painel administrativo.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="active">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php">🦴 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
            <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Painel de Administração</h1>
            <p>Bem-vindo de volta! Aqui está o resumo operacional da sua ONG hoje.</p>
        </div>

        <div class="metrics-grid">
            <div class="metric-card">
                <span class="metric-title">Animais Hospedados</span>
                <span class="metric-value"><?php echo $total_animais; ?></span>
            </div>
            <div class="metric-card alert">
                <span class="metric-title">Itens Críticos</span>
                <span class="metric-value"><?php echo $itens_criticos; ?></span>
            </div>
            <div class="metric-card info">
                <span class="metric-title">Parcerias Ativas</span>
                <span class="metric-value"><?php echo $parcerias_ativas; ?></span>
            </div>
        </div>

        <div class="section-card">
            <h2>
                <span>📋 Controle de Insumos e Necessidades</span>
                <a href="#" class="btn-action">+ Atualizar Estoque</a>
            </h2>
            
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Item de Consumo</th>
                        <th>Categoria</th>
                        <th>Qtd. Atual</th>
                        <th>Status de Urgência</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($itens_estoque) > 0): ?>
                        <?php foreach ($itens_estoque as $item): ?>
                            <tr>
                                <td><strong><?php echo $item['item_nome']; ?></strong></td>
                                <td><?php echo $item['categoria']; ?></td>
                                <td><?php echo $item['quantidade']; ?> un</td>
                                <td>
                                    <?php 
                                        $classe_status = 'ok';
                                        if ($item['status_necessidade'] == 'Atenção') $classe_status = 'atencao';
                                        if ($item['status_necessidade'] == 'Crítico') $classe_status = 'critico';
                                    ?>
                                    <span class="badge <?php echo $classe_status; ?>">
                                        ● <?php echo $item['status_necessidade']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #71717a;">Nenhum item adicionado ao estoque.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>