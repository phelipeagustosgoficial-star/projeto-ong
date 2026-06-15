<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não estiver logado, redireciona para o login
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

// Captura o ID do utilizador logado na sessão para filtrar os dados
$id_usuario = $_SESSION['id_usuario'] ?? null;

$compras = [];
$adocoes = [];

if ($id_usuario) {
    try {
        // 1. QUERY: Procura as compras da Lojinha deste utilizador específico
        $sql_compras = "SELECT p.nome, p.preco, ped.data_pedido, ped.status 
                        FROM tb_pedidos ped
                        JOIN tb_produtos p ON ped.id_produto = p.id
                        WHERE ped.id_usuario = :id_usuario
                        ORDER BY ped.id DESC";
        $stmt_c = $conexao->prepare($sql_compras);
        $stmt_c->execute(['id_usuario' => $id_usuario]);
        $compras = $stmt_c->fetchAll(PDO::FETCH_ASSOC);

        // 2. QUERY: Procura os pedidos de Adoção deste utilizador específico
        $sql_adocoes = "SELECT nome_pet, raca_especie, data_solicitacao, status 
                        FROM tb_adocoes 
                        WHERE id_usuario = :id_usuario 
                        ORDER BY id DESC";
        $stmt_a = $conexao->prepare($sql_adocoes);
        $stmt_a->execute(['id_usuario' => $id_usuario]);
        $adocoes = $stmt_a->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/adotante.css">
    <link rel="stylesheet" href="assets/CSS/pedidos-loja.css">
</head>
<body>

    <?php include_once 'includes/header-publico.php'; ?>

    <main class="main-wrapper" style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
        
        <section style="margin-bottom: 50px;">
            <div class="page-header-title" style="margin-bottom: 20px; border-bottom: 2px solid #22c55e; padding-bottom: 10px;">
                <h2 style="font-size: 24px; color: #fff; display: flex; align-items: center; gap: 10px;">🛍️ Minhas Compras na Lojinha</h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php if (empty($compras)): ?>
                    <p class="carrinho-vazio" style="color: #aaaaaa; font-style: italic;">Você ainda não comprou nenhum produto... 🛒</p>
                <?php else: ?>
                    <?php foreach ($compras as $compra): ?>
                        <div class="card-pedido" style="background: #1e1e1e; padding: 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #333;">
                            <div class="pedido-info">
                                <div class="pedido-detalhes">
                                    <h3><?php echo htmlspecialchars($compra['nome']); ?></h3>
                                    <p>Data da Compra: <?php echo date('d/m/Y H:i', strtotime($compra['data_pedido'])); ?></p>
                                    <p style="font-weight: bold; color: #22c55e;">Valor: R$ <?php echo number_format($compra['preco'], 2, ',', '.'); ?></p>
                                </div>
                            </div>
                            
                            <?php 
                                $status_compra = strtolower($compra['status']);
                                $classe_c = 'pendente';
                                if (strpos($status_compra, 'aprov') !== false || strpos($status_compra, 'concl') !== false) $classe_c = 'aprovado';
                                if (strpos($status_compra, 'recus') !== false || strpos($status_compra, 'cancel') !== false) $classe_c = 'recusado';
                            ?>
                            <span class="status-badge <?php echo $classe_c; ?>">
                                <?php echo htmlspecialchars($compra['status']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="page-header-title" style="margin-bottom: 20px; border-bottom: 2px solid #22c55e; padding-bottom: 10px;">
                <h2 style="font-size: 24px; color: #fff; display: flex; align-items: center; gap: 10px;">🐾 Meus Pedidos de Adoção</h2>
            </div>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php if (empty($adocoes)): ?>
                    <p class="carrinho-vazio" style="color: #aaaaaa; font-style: italic;">Você não possui nenhuma solicitação de adoção enviada. 🐶</p>
                <?php else: ?>
                    <?php foreach ($adocoes as $adocao): ?>
                        <div class="card-pedido" style="background: #1e1e1e; padding: 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #333;">
                            <div class="pedido-info">
                                <div class="pedido-detalhes">
                                    <h3>Solicitação para: <?php echo htmlspecialchars($adocao['nome_pet']); ?></h3>
                                    <p>Raça / Espécie: <?php echo htmlspecialchars($adocao['raca_especie']); ?></p>
                                    <p>Data do Pedido: <?php echo date('d/m/Y H:i', strtotime($adocao['data_solicitacao'])); ?></p>
                                </div>
                            </div>
                            
                            <?php 
                                $status_adocao = strtolower($adocao['status']);
                                $classe_a = 'pendente';
                                if (strpos($status_adocao, 'aprov') !== false || strpos($status_adocao, 'aceit') !== false) $classe_a = 'aprovado';
                                if (strpos($status_adocao, 'recus') !== false || strpos($status_adocao, 'rejeit') !== false) $classe_a = 'recusado';
                            ?>
                            <span class="status-badge <?php echo $classe_a; ?>">
                                <?php echo htmlspecialchars($adocao['status']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <script>
        // Mantém a aplicação do tema ativo (claro/escuro) se aplicável
        document.addEventListener('DOMContentLoaded', () => {
            const temaSalvo = localStorage.getItem('tema') || 'dark';
            if (temaSalvo === 'light') {
                document.body.classList.add('light-mode');
            }
        });
    </script>
</body>
</html>