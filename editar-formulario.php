<?php
session_start();

// Trava de segurança: Se não houver sessão ativa OU o usuário não for administrador, chuta para o login
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require_once 'backend/conexao.php';

// Verifica se o ID do pet foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: editar.php");
    exit();
}

$id = intval($_GET['id']);

try {
    // Busca os dados atuais do animal selecionado
    $sql = "SELECT * FROM tb_animais WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        header("Location: editar.php");
        exit();
    }
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    echo "Erro ao carregar os dados do animal.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cadastro do Pet - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/editar-formulario.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php" class="active">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php">🦴 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
             <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Editar Dados do Pet: <?php echo htmlspecialchars($pet['nome']); ?></h1>
            <p>Altere as informações necessárias abaixo e clique em "Salvar Alterações".</p>
        </div>

        <div class="form-edit-container">
            <h2>📝 Formulário de Atualização</h2>

            <form action="backend/atualizar-pet.php" method="POST" enctype="multipart/form-data" class="edit-table-form">

                <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">

                <div class="form-row">
                    <div class="form-cell">
                        <label for="nome">Nome do Animal</label>
                        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($pet['nome']); ?>" required>
                    </div>
                    <div class="form-cell">
                        <label for="idade">Idade (Anos)</label>
                        <input type="number" name="idade" id="idade" value="<?php echo htmlspecialchars($pet['idade']); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-cell">
                        <label for="especie">Espécie</label>
                        <input type="text" name="especie" id="especie" value="<?php echo htmlspecialchars($pet['especie']); ?>" required>
                    </div>
                    <div class="form-cell">
                        <label for="raca">Raça</label>
                        <input type="text" name="raca" id="raca" value="<?php echo htmlspecialchars($pet['raca']); ?>" required>
                    </div>
                </div>

                <div class="form-row image-section">
                    <div class="form-cell current-img-box">
                        <label>Foto Atual:</label>
                        <div class="preview-container">
                            <img src="assets/img/imagens-ong/<?php echo $pet['imagem']; ?>" alt="Foto Atual" class="current-preview">
                        </div>
                    </div>
                    <div class="form-cell">
                        <label Linda-Foto for="imagem">📁 Escolher Nova Foto (Opcional)</label>
                        <input type="file" name="imagem" id="imagem" accept="image/*">
                        <small class="help-text">Deixe em branco se não quiser mudar a foto atual.</small>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="editar.php" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-save">💾 Salvar Alterações</button>
                </div>

            </form>
        </div>
    </main>

</body>

</html>