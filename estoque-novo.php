<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança: Se não for administrador, volta pro login
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Item - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/cadastrar.css"> </head>

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
            <h1>Novo Item de Estoque</h1>
            <p>Adicione rações, medicamentos ou insumos ao inventário da ONG.</p>
        </div>

        <div class="section-card">
            <form action="backend/cadastrar-estoque.php" method="POST" class="admin-form">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="item_nome">Item de Consumo / Nome do Insumo</label>
                        <input type="text" id="item_nome" name="item_nome" placeholder="Ex: Ração Cão Adulto 15kg, Dipirona Gotas..." required>
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <select id="categoria" name="categoria" required>
                            <option value="" disabled selected>Selecione a categoria...</option>
                            <option value="Ração">Ração</option>
                            <option value="Medicamento">Medicamento</option>
                            <option value="Brinquedo">Brinquedo</option>
                            <option value="Higiene">Higiene / Limpeza</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantidade">Quantidade Atual</label>
                        <input type="text" id="quantidade" name="quantidade" placeholder="Ex: 2 un, 500 ml, 10 kg..." required>
                    </div>

                    <div class="form-group full-width">
                        <label for="status_necessidade">Status de Urgência / Necessidade</label>
                        <select id="status_necessidade" name="status_necessidade" required>
                            <option value="" disabled selected>Selecione a urgência...</option>
                            <option value="OK">OK (Estoque Abastecido)</option>
                            <option value="Atenção">Atenção (Estoque Baixo)</option>
                            <option value="Crítico">Crítico (Item em Falta)</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action">Salvar Cadastro</button>
                    <a href="gerenciar-estoque.php" class="btn-cancel">Cancelar</a>
                </div>

            </form>
        </div>
    </main>

</body>

</html>