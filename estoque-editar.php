<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança administrativa
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

// Verifica se o ID do item foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerenciar-estoque.php");
    exit();
}

$id = intval($_GET['id']);

try {
    // Busca os dados do item de consumo selecionado
    $sql = "SELECT * FROM tb_estoque WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se o item não existir no banco, retorna para a listagem
    if (!$item) {
        header("Location: gerenciar-estoque.php");
        exit();
    }
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    echo "Erro ao carregar os dados do insumo.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Item do Estoque - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/estoque-form.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php" class="active">Bone Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
             <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Editar Dados do Insumo: <?php echo htmlspecialchars($item['item_nome']); ?></h1>
            <p>Altere as informações necessárias abaixo e clique em "Salvar Alterações".</p>
        </div>

        <div class="form-estoque-container">
            <h2>📝 Formulário de Atualização</h2>
            
            <form action="backend/atualizar-estoque.php" method="POST" class="estoque-form-body">
                
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

                <div class="form-row">
                    <div class="form-cell">
                        <label for="item_nome">Nome do Insumo</label>
                        <input type="text" name="item_nome" id="item_nome" value="<?php echo htmlspecialchars($item['item_nome']); ?>" required>
                    </div>
                    
                    <div class="form-cell">
                        <label for="categoria">Categoria</label>
                        <select name="categoria" id="categoria" required>
                            <option value="Ração" <?php echo ($item['categoria'] == 'Ração') ? 'selected' : ''; ?>>Ração / Alimentação</option>
                            <option value="Medicamento" <?php echo ($item['categoria'] == 'Medicamento') ? 'selected' : ''; ?>>Medicamento / Veterinário</option>
                            <option value="Higiene" <?php echo ($item['categoria'] == 'Higiene') ? 'selected' : ''; ?>>Higiene / Limpeza</option>
                            <option value="Brinquedo" <?php echo ($item['categoria'] == 'Brinquedo') ? 'selected' : ''; ?>>Brinquedos / Acessórios</option>
                            <option value="Outros" <?php echo ($item['categoria'] == 'Outros') ? 'selected' : ''; ?>>Outros Insumos</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-cell">
                        <label for="quantidade">Quantidade Atual</label>
                        <input type="text" name="quantidade" id="quantidade" value="<?php echo htmlspecialchars($item['quantidade']); ?>" required>
                    </div>
                    
                    <div class="form-cell">
                        <label for="status_necessidade">Status de Urgência</label>
                        <select name="status_necessidade" id="status_necessidade" required>
                            <option value="OK" <?php echo ($item['status_necessidade'] == 'OK') ? 'selected' : ''; ?>>● OK (Estoque Controlado)</option>
                            <option value="Atenção" <?php echo ($item['status_necessidade'] == 'Atenção') ? 'selected' : ''; ?>>● Atenção (Estoque Baixo)</option>
                            <option value="Crítico" <?php echo ($item['status_necessidade'] == 'Crítico') ? 'selected' : ''; ?>>● Crítico (Falta Urgente)</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">💾 Salvar Alterações</button>
                    <a href="gerenciar-estoque.php" class="btn-cancel">Cancelar</a>
                </div>

            </form>
        </div>
    </main>

</body>
</html>