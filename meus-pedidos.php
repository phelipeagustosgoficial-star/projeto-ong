<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

// Pega o ID do usuário logado na sessão
$id_usuario = $_SESSION['id'] ?? 1; 

// --- PROCESSAR NOVA COMPRA VINDA DA MODAL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
    $metodo_pagamento = filter_input(INPUT_POST, 'metodo_pagamento', FILTER_DEFAULT);
    $nome_titular = filter_input(INPUT_POST, 'nome_titular', FILTER_DEFAULT);

    if ($id_produto) {
        try {
            // Insere o pedido na tabela de compras da lojinha
            $sql_inserir = "INSERT INTO tb_pedidos_loja (id_usuario, id_produto, metodo_pagamento, nome_titular, status_pedido, data_pedido) 
                            VALUES (:id_usuario, :id_produto, :metodo_pagamento, :nome_titular, 'pendente', NOW())";
            
            $stmt_ins = $conexao->prepare($sql_inserir);
            $stmt_ins->execute([
                ':id_usuario' => $id_usuario,
                ':id_produto' => $id_produto,
                ':metodo_pagamento' => $metodo_pagamento,
                ':nome_titular' => $nome_titular
            ]);

            // Diminui 1 unidade do estoque do produto comprado
            $sql_estoque = "UPDATE tb_produtos SET estoque = estoque - 1 WHERE id = :id_produto AND estoque > 0";
            $stmt_est = $conexao->prepare($sql_estoque);
            $stmt_est->execute([':id_produto' => $id_produto]);

            // Redireciona para limpar o POST e atualizar a lista
            header("Location: meus-pedidos.php");
            exit();

        } catch (PDOException $e) {
            error_log("Erro ao salvar pedido: " . $e->getMessage());
        }
    }
}

// --- BUSCAR HISTÓRICO DE COMPRAS DA LOJINHA ---
try {
    $sql_compras = "SELECT p.*, prod.nome AS nome_produto, prod.preco, prod.imagem 
                    FROM tb_pedidos_loja p 
                    JOIN tb_produtos prod ON p.id_produto = prod.id 
                    WHERE p.id_usuario = :id_usuario 
                    ORDER BY p.id DESC";
    $stmt_compras = $conexao->prepare($sql_compras);
    $stmt_compras->execute([':id_usuario' => $id_usuario]);
    $compras_loja = $stmt_compras->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $compras_loja = [];
}

// --- BUSCAR HISTÓRICO DE ADOÇÕES ---
try {
    $sql_adocoes = "SELECT pa.*, a.nome AS nome_animal, a.especie 
                    FROM tb_pedidos_adocao pa
                    JOIN tb_animais a ON pa.id_animal = a.id
                    WHERE pa.id_usuario = :id_usuario
                    ORDER BY pa.id DESC";
    $stmt_adocoes = $conexao->prepare($sql_adocoes);
    $stmt_adocoes->execute([':id_usuario' => $id_usuario]); 
    $pedidos_adocao = $stmt_adocoes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $pedidos_adocao = [];
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

    <div class="pedidos-container">
        
        <section class="secao-pedidos">
            <h2 class="pedidos-title">🛍️ Minhas Compras na Lojinha</h2>
            <div class="grid-pedidos">
                <?php if (empty($compras_loja)): ?>
                    <p class="txt-vazio">Você ainda não comprou nenhum produto... 🛒</p>
                <?php else: ?>
                    <?php foreach ($compras_loja as $compra): ?>
                        <div class="card-pedido">
                            <div class="pedido-info">
                                <div class="pedido-detalhes">
                                    <h3><?php echo htmlspecialchars($compra['nome_produto']); ?></h3>
                                    <p><strong>Valor:</strong> R$ <?php echo number_format($compra['preco'], 2, ',', '.'); ?></p>
                                    <p><strong>Forma de Pagamento:</strong> <?php echo htmlspecialchars($compra['metodo_pagamento']); ?></p>
                                    <p class="txt-data">Comprado em: <?php echo date('d/m/Y H:i', strtotime($compra['data_pedido'])); ?></p>
                                </div>
                            </div>
                            <div class="pedido-status">
                                <span class="status-badge <?php echo strtolower($compra['status_pedido']); ?>">
                                    <?php echo htmlspecialchars($compra['status_pedido']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <hr class="divisor-pedidos">

        <section class="secao-pedidos">
            <h2 class="pedidos-title">🐾 Meus Pedidos de Adoção</h2>
            <div class="grid-pedidos">
                <?php if (empty($pedidos_adocao)): ?>
                    <p class="txt-vazio">Você não possui nenhuma solicitação de adoção enviada.</p>
                <?php else: ?>
                    <?php foreach ($pedidos_adocao as $adocao): ?>
                        <div class="card-pedido">
                            <div class="pedido-info">
                                <div class="pedido-detalhes">
                                    <h3>Solicitação para: <?php echo htmlspecialchars($adocao['nome_animal']); ?></h3>
                                    <p><strong>Raça/Espécie:</strong> <?php echo htmlspecialchars($adocao['especie']); ?></p>
                                    <p class="txt-data">Solicitado em: <?php echo date('d/m/Y H:i', strtotime($adocao['data_pedido'])); ?></p>
                                </div>
                            </div>
                            <div class="pedido-status">
                                <span class="status-badge <?php echo strtolower($adocao['status_pedido']); ?>">
                                    <?php echo htmlspecialchars($adocao['status_pedido']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnTheme = document.getElementById('theme-toggle');
            
            function aplicarTema(tema) {
                if (tema === 'light') {
                    document.body.classList.add('light-mode');
                    document.body.classList.remove('dark-mode');
                    if (btnTheme) btnTheme.innerText = '🌙'; 
                } else {
                    document.body.classList.remove('light-mode');
                    document.body.classList.add('dark-mode');
                    if (btnTheme) btnTheme.innerText = '☀️'; 
                }
            }

            // Pega a escolha anterior do usuário no navegador
            const temaSalvo = localStorage.getItem('tema') || 'dark';
            aplicarTema(temaSalvo);

            // Torna o botão funcional caso ele exista no header carregado
            if (btnTheme) {
                btnTheme.onclick = function(e) {
                    e.preventDefault();
                    const novoTema = document.body.classList.contains('light-mode') ? 'dark' : 'light';
                    localStorage.setItem('tema', novoTema);
                    aplicarTema(novoTema);
                };
            }
        });
    </script>
</body>
</html>