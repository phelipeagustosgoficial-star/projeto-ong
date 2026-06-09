<?php
session_start();

// Trava de segurança: Se não houver sessão ativa OU o usuário não for administrador, chuta para o login
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
require_once 'backend/conexao.php';

try {
    // Busca todos os animais cadastrados na ONG (trazendo a coluna status também)
    $sql = "SELECT id, nome, especie, raca, idade, imagem, status FROM tb_animais ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $animais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    echo "Erro ao carregar a lista de animais.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pets - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/editar-lista.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php" class="active">✏️ Editar Pets</a></li>
            <li><a href="#">🦴 Gerenciar Estoque</a></li>
            <li><a href="#">🤝 Parcerias</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Gerenciar e Editar Pets</h1>
            <p>Visualização de todos os animais protegidos. Aqui você pode atualizar as informações ou mudar a situação cadastral deles.</p>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'sucesso'): ?>
                <div class="alert-box success">✅ Alteração realizada com sucesso!</div>
            <?php elseif ($_GET['status'] == 'deletado'): ?>
                <div class="alert-box success">🗑️ Pet removido do sistema com sucesso!</div>
            <?php elseif ($_GET['status'] == 'erro'): ?>
                <div class="alert-box error">❌ Ops! Ocorreu um erro ao processar a ação. Tente novamente.</div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="section-card">
            <h2><span>🐾 Animais Cadastrados</span></h2>
            
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome do Pet</th>
                        <th>Espécie / Raça</th>
                        <th>Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($animais) > 0): ?>
                        <?php foreach ($animais as $pet): ?>
                            <tr class="<?php echo ($pet['status'] == 0) ? 'row-inactive' : ''; ?>">
                                <td>
                                    <div class="pet-thumb-container">
                                        <img src="assets/img/imagens-ong/<?php echo $pet['imagem']; ?>" alt="Foto de <?php echo $pet['nome']; ?>" class="pet-thumb">
                                    </div>
                                </td>
                                <td><strong><?php echo $pet['nome']; ?></strong></td>
                                <td><?php echo $pet['especie']; ?> / <?php echo $pet['raca']; ?></td>
                                <td>
                                    <?php if ($pet['status'] == 0): ?>
                                        <span class="status-badge badge-inativo">● Inativo</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-ativo">● Ativo</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div class="actions-wrapper">
                                        <a href="editar-formulario.php?id=<?php echo $pet['id']; ?>" class="btn-action-table btn-edit">✏️ Editar</a>
                                        
                                        <?php if ($pet['status'] == 0): ?>
                                            <a href="backend/status-pet.php?id=<?php echo $pet['id']; ?>&acao=ativar" class="btn-action-table btn-activate">🟢 Ativar</a>
                                        <?php else: ?>
                                            <a href="backend/status-pet.php?id=<?php echo $pet['id']; ?>&acao=desativar" class="btn-action-table btn-deactivate">🟡 Desativar</a>
                                        <?php endif; ?>

                                        <a href="backend/status-pet.php?id=<?php echo $pet['id']; ?>&acao=deletar" class="btn-action-table btn-delete" onclick="return confirm('Tem certeza absoluta que deseja deletar este pet permanentemente?');">🗑️ Deletar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #71717a; padding: 30px;">Nenhum animal cadastrado no sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>