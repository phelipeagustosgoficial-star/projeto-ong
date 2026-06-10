<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Conexão com o banco para listar as parcerias na tabela abaixo
require_once 'backend/conexao.php';

try {
    $sql = "SELECT * FROM tb_parcerias ORDER BY id DESC";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    $parcerias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    $parcerias = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Parcerias - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/estoque-lista.css">
    <link rel="stylesheet" href="assets/CSS/parcerias.css">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php">📦 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php" class="active">🤝 Parcerias</a></li>
             <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">

        <div class="header-dash">
            <h1>🤝 Gestão de Parcerias</h1>
            <p>Cadastre e gerencie as empresas ou apoiadores parceiros da ONG.</p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert-box alert-success">
                ✅ Operação realizada com sucesso!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert-box alert-error">
                ❌ Erro ao processar a requisição! Verifique os dados.
            </div>
        <?php endif; ?>

        <div class="parcerias-container">
            <h2>Nova Parceria</h2>

            <form action="backend/salvar-parcerias.php" method="POST">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Nome da Empresa</label>
                        <input type="text" name="nome_empresa" required>
                    </div>

                    <div class="form-group">
                        <label>Responsável</label>
                        <input type="text" name="responsavel" required>
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone">
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email">
                    </div>

                    <div class="form-group">
                        <label>Tipo de Parceria</label>
                        <select name="tipo_parceria" required>
                            <option value="Veterinária">Veterinária</option>
                            <option value="Pet Shop">Pet Shop</option>
                            <option value="Patrocinador">Patrocinador</option>
                            <option value="Voluntariado">Voluntariado</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status_parceria" required>
                            <option value="Ativa">Ativa</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Encerrada">Encerrada</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Observações</label>
                        <textarea name="observacoes"></textarea>
                    </div>

                </div>

                <div style="margin-top: 20px; display: flex; gap: 15px;">
                    <button type="submit" class="btn-salvar">
                        💾 Salvar Parceria
                    </button>
                </div>
            </form>
        </div>

        <div class="section-card" style="margin-top: 35px;">
            <div class="card-header-estoque" style="margin-bottom: 20px;">
                <h2>🤝 Parceiros Registrados</h2>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Empresa / Parceiro</th>
                        <th>Responsável</th>
                        <th>Contato</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($parcerias) > 0): ?>
                        <?php foreach ($parcerias as $p): ?>
                            <?php 
                                // Ajusta as cores das bolinhas com base no arquivo estoque-lista.css do seu sistema
                                $classe_status = 'status-ok'; 
                                if ($p['status_parceria'] === 'Pendente') $classe_status = 'status-atencao';
                                if ($p['status_parceria'] === 'Encerrada') $classe_status = 'status-critico';
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($p['nome_empresa']); ?></strong></td>
                                <td><?php echo htmlspecialchars($p['responsavel']); ?></td>
                                <td>
                                    📱 <?php echo htmlspecialchars($p['telefone']); ?><br>
                                    📧 <small style="color: #a1a1aa;"><?php echo htmlspecialchars($p['email']); ?></small>
                                </td>
                                <td>
                                    <span style="background: #18181b; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; border: 1px solid #27272a;">
                                        <?php echo htmlspecialchars($p['tipo_parceria']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $classe_status; ?>">
                                        <?php echo htmlspecialchars($p['status_parceria']); ?>
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <a href="backend/status-parceria.php?id=<?php echo $p['id']; ?>&atual=<?php echo $p['status_parceria']; ?>" class="btn-status-toggle" title="Mudar Status">🔄 Status</a>
                                        
                                        <a href="backend/deletar-parceria.php?id=<?php echo $p['id']; ?>" class="btn-delete" style="padding: 6px 12px; font-size: 0.85rem; text-decoration: none;" onclick="return confirm('Deseja realmente excluir esta parceria permanentemente?');">🗑️ Deletar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #71717a; padding: 40px;">Nenhum parceiro cadastrado até o momento.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>
</body>

</html>