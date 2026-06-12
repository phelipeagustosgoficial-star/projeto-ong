<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

try {
    $sql = "SELECT * FROM tb_produtos WHERE estoque > 0 ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $produtos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brinquedos & Acessórios - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/adotante.css">
    <link rel="stylesheet" href="assets/CSS/brinquedos.css">
    <link rel="stylesheet" href="assets/CSS/pedidos-loja.css">
</head>
<body>

    <?php include_once 'includes/header-publico.php'; ?>

    <main class="main-wrapper">
        <div class="page-header-title">
            <h2>🧸 Lojinha Cuida Animal</h2>
            <p>Todo o lucro das vendas é revertido diretamente para o tratamento, ração e cuidados dos nossos resgatados!</p>
        </div>

        <div class="grid-container">
            <?php if (empty($produtos)): ?>
                <div class="empty-state">
                    <p>Nenhum produto disponível no estoque neste momento. Volte mais tarde! 🛒</p>
                </div>
            <?php else: ?>
                <?php foreach ($produtos as $prod): ?>
                    <div class="card-item">
                        <div class="card-img-box">
                            <img src="assets/img/imagens-ong/<?php echo htmlspecialchars($prod['imagem']); ?>" 
                                 onerror="this.src='https://placehold.co/400x300/222/fff?text=<?php echo urlencode($prod['nome']); ?>'" 
                                 alt="<?php echo htmlspecialchars($prod['nome']); ?>">
                        </div>

                        <div class="card-info">
                            <span class="badge-categoria"><?php echo htmlspecialchars($prod['categoria']); ?></span>
                            <h3><?php echo htmlspecialchars($prod['nome']); ?></h3>
                            <p class="txt-descricao"><?php echo htmlspecialchars($prod['descricao']); ?></p>

                            <div class="card-footer-price">
                                <span class="txt-preco">R$ <?php echo number_format($prod['preco'], 2, ',', '.'); ?></span>
                                <span class="txt-estoque"><?php echo $prod['estoque']; ?> un.</span>
                            </div>

                            <button class="btn-quero-conhecer" onclick="adicionarAoCarrinho(<?php echo $prod['id']; ?>, '<?php echo htmlspecialchars($prod['nome'], ENT_QUOTES); ?>', <?php echo $prod['preco']; ?>)">
                                🛒 Comprar e Ajudar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <div class="carrinho-sidebar" id="carrinhoSide">
        <div class="carrinho-header">
            <h3>🛒 Meu Carrinho</h3>
            <button class="btn-fechar-carrinho" onclick="fecharCarrinho()">✕</button>
        </div>
        
        <form action="meus-pedidos.php" method="POST" id="form-checkout-carrinho" style="height: 100%; display: flex; flex-direction: column; margin: 0;">
            <input type="hidden" name="id_produto" id="checkout-id-produto" value="">
            <input type="hidden" name="finalizar_compra" value="1">

            <div id="checkout-passo-1" class="checkout-passo ativo">
                <div class="carrinho-itens" id="carrinhoItens">
                    <p class="carrinho-vazio">Seu carrinho está vazio... 🐾</p>
                </div>

                <div class="carrinho-footer" style="margin-top: auto;">
                    <div class="total-box">
                        <span>Total:</span>
                        <span id="carrinhoTotal">R$ 0,00</span>
                    </div>
                    <button type="button" class="btn-finalizar-compra" onclick="irParaPasso(2)">🔒 Confirmar e Comprar</button>
                </div>
            </div>

            <div id="checkout-passo-2" class="checkout-passo">
                <h4 style="margin-top: 0; color: #22c55e; border-bottom: 1px solid #333; padding-bottom: 8px;">📍 Dados de Entrega</h4>
                
                <div class="form-grupo">
                    <label>Nome Completo do Recebedor:</label>
                    <input type="text" name="nome_titular" id="input-nome" placeholder="Nome de quem vai receber">
                </div>

                <div class="form-grup-linha">
                    <div class="form-grupo">
                        <label>CEP:</label>
                        <input type="text" id="cep-input" placeholder="00000-000" onblur="buscarCEP()">
                    </div>
                    <div class="form-grupo">
                        <label>Número:</label>
                        <input type="text" placeholder="Nº">
                    </div>
                </div>

                <div class="form-grupo">
                    <label>Rua / Logradouro:</label>
                    <input type="text" id="rua-input" placeholder="Nome da rua ou avenida">
                </div>

                <div class="form-grupo">
                    <label>Bairro:</label>
                    <input type="text" id="bairro-input" placeholder="Bairro">
                </div>

                <div class="carrinho-footer-botoes">
                    <button type="button" class="btn-voltar" onclick="irParaPasso(1)">Voltar</button>
                    <button type="button" class="btn-finalizar-compra" style="flex: 2;" onclick="irParaPasso(3)">Avançar ➡️</button>
                </div>
            </div>

            <div id="checkout-passo-3" class="checkout-passo">
                <h4 style="margin-top: 0; color: #22c55e; border-bottom: 1px solid #333; padding-bottom: 8px;">💳 Método de Pagamento</h4>
                
                <div class="opcoes-pagamento">
                    <label class="opcao-card">
                        <input type="radio" name="metodo_pagamento" value="Cartão de Crédito" checked onclick="atualizarCamposPagamento('cartao')">
                        <span>💳 Cartão de Crédito</span>
                    </label>
                    <label class="opcao-card">
                        <input type="radio" name="metodo_pagamento" value="PIX" onclick="atualizarCamposPagamento('pix')">
                        <span>⚡ PIX</span>
                    </label>
                </div>

                <div id="checkout-campos-cartao">
                    <div class="form-grupo">
                        <label>Número do Cartão:</label>
                        <input type="text" placeholder="0000 0000 0000 0000">
                    </div>
                    <div class="form-grup-linha">
                        <div class="form-grupo">
                            <label>Validade:</label>
                            <input type="text" placeholder="MM/AA">
                        </div>
                        <div class="form-grupo">
                            <label>CVC:</label>
                            <input type="text" placeholder="123">
                        </div>
                    </div>
                </div>

                <div id="checkout-campos-pix" style="display: none; background: rgba(34, 197, 94, 0.1); padding: 15px; border-radius: 6px; border: 1px dashed #22c55e; margin-bottom: 15px; text-align: center;">
                    <span style="font-size: 13px; color: #22c55e; font-weight: bold;">O QR Code Copia e Cola será gerado ao concluir!</span>
                </div>

                <div class="carrinho-footer-botoes">
                    <button type="button" class="btn-voltar" onclick="irParaPasso(2)">Voltar</button>
                    <button type="submit" class="btn-finalizar-compra" style="flex: 2; background: #22c55e;">Concluir e Pagar ❤️</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let carrinho = [];

        function adicionarAoCarrinho(id, nome, preco) {
            const itemExistente = carrinho.find(item => item.id === id);
            if (itemExistente) {
                itemExistente.qtd++;
            } else {
                carrinho.push({ id, nome, preco, qtd: 1 });
            }
            atualizarInterfaceCarrinho();
            abrirCarrinho();
        }

        function removerDoCarrinho(id) {
            carrinho = carrinho.filter(item => item.id !== id);
            atualizarInterfaceCarrinho();
        }

        function abrirCarrinho() {
            irParaPasso(1);
            document.getElementById('carrinhoSide').classList.add('open');
        }

        function fecharCarrinho() {
            document.getElementById('carrinhoSide').classList.remove('open');
        }

        function atualizarInterfaceCarrinho() {
            const container = document.getElementById('carrinhoItens');
            const txtTotal = document.getElementById('carrinhoTotal');
            container.innerHTML = '';

            if (carrinho.length === 0) {
                container.innerHTML = '<p class="carrinho-vazio">Seu carrinho está vazio... 🐾</p>';
                txtTotal.innerText = 'R$ 0,00';
                document.getElementById('checkout-id-produto').value = '';
                return;
            }

            let totalGeral = 0;
            carrinho.forEach(item => {
                const subtotal = item.preco * item.qtd;
                totalGeral += subtotal;

                const div = document.createElement('div');
                div.className = 'item-carrinho-linha';
                div.innerHTML = `
                    <div class="item-detalhes">
                        <h4>${item.nome}</h4>
                        <span>${item.qtd}x - R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                    </div>
                    <button class="btn-remover-item" onclick="removerDoCarrinho(${item.id})">🗑️</button>
                `;
                container.appendChild(div);
            });

            txtTotal.innerText = `R$ ${totalGeral.toFixed(2).replace('.', ',')}`;
            document.getElementById('checkout-id-produto').value = carrinho[0].id;
        }

        function irParaPasso(passo) {
            if (passo === 2 && carrinho.length === 0) {
                alert("Adicione um produto antes de avançar!");
                return;
            }
            if (passo === 3 && document.getElementById('input-nome').value.trim() === "") {
                alert("Por favor, digite o nome do recebedor.");
                return;
            }

            document.querySelectorAll('.checkout-passo').forEach(el => el.classList.remove('ativo'));
            document.getElementById(`checkout-passo-${passo}`).classList.add('ativo');
        }

        function buscarCEP() {
            const cep = document.getElementById('cep-input').value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(dados => {
                        if (!dados.erro) {
                            document.getElementById('rua-input').value = dados.logradouro;
                            document.getElementById('bairro-input').value = dados.bairro;
                        }
                    });
            }
        }

        function atualizarCamposPagamento(tipo) {
            const cartao = document.getElementById('checkout-campos-cartao');
            const pix = document.getElementById('checkout-campos-pix');
            if (tipo === 'pix') {
                cartao.style.display = 'none';
                pix.style.display = 'block';
            } else {
                cartao.style.display = 'block';
                pix.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const btnTheme = document.getElementById('theme-toggle');
            function aplicarTema(tema) {
                if (tema === 'light') {
                    document.body.classList.add('light-mode');
                    if (btnTheme) btnTheme.innerText = '🌙';
                } else {
                    document.body.classList.remove('light-mode');
                    if (btnTheme) btnTheme.innerText = '☀️';
                }
            }
            aplicarTema(localStorage.getItem('tema') || 'dark');
            if (btnTheme) {
                btnTheme.addEventListener('click', () => {
                    const novoTema = document.body.classList.contains('light-mode') ? 'dark' : 'light';
                    localStorage.setItem('tema', novoTema);
                    aplicarTema(novoTema);
                });
            }
        });
    </script>
</body>
</html>