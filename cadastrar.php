<?php
session_start();
// Se não existir a sessão 'logado', chuta o usuário de volta para a tela de login
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Animal - CUIDA ANIMAL</title>
    
    <link rel="stylesheet" href="assets/CSS/styale.css">
    
    <link rel="stylesheet" href="assets/CSS/cadastrar.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">🐾 Cuida Animal</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Visão Geral</a></li>
            <li><a href="cadastrar.php" class="active">➕ Cadastrar Pet</a></li>
            <li><a href="editar.php">✏️ Editar Pets</a></li>
            <li><a href="gerenciar-estoque.php">🦴 Gerenciar Estoque</a></li>
            <li><a href="parcerias-novo.php">🤝 Parcerias</a></li>
             <li><a href="registro-pedidos.php">📖 Registro de Pedidos de Adoção</a></li>
            <li><a href="sair.php" class="logout">🚪 Sair do Painel</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="header-dash">
            <h1>Cadastro de Novo Pet</h1>
            <p>Preencha com atenção todas as informações do animal para disponibilizá-lo para adoção.</p>
        </div>

        <div class="section-card">
            <form action="backend/cadastrar-animal.php" method="post" enctype="multipart/form-data" class="admin-form">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nome">Nome do Animal</label>
                        <input type="text" name="nome" id="nome" placeholder="Ex: Max, Lili..." required>
                    </div>

                    <div class="form-group">
                        <label for="especie">Espécie</label>
                        <select name="especie" id="especie" required>
                            <option value="" disabled selected>Selecione a espécie...</option>
                            <option value="Cachorro">Cachorro</option>
                            <option value="Gato">Gato</option>
                            <option value="Coelho">Coelho</option>
                            <option value="Ave">Ave</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="raca">Raça</label>
                        <input type="text" name="raca" id="raca" placeholder="Ex: Pastor Alemão, Vira-lata..." required>
                    </div>

                    <div class="form-group">
                        <label for="idade">Idade (Anos)</label>
                        <input type="number" name="idade" id="idade" min="0" placeholder="Ex: 2" required>
                    </div>

                    <div class="form-group">
                        <label for="cor">Cor da Pelagem</label>
                        <input type="text" name="cor" id="cor" placeholder="Ex: Preto/Caramelo..." required>
                    </div>

                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select name="sexo" id="sexo" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="Macho">Macho</option>
                            <option value="Fêmea">Fêmea</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="imagem">Foto do Pet</label>
                        <input type="file" name="imagem" id="imagem" accept="image/*" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="descricao">Descrição / História do Pet</label>
                        <textarea name="descricao" id="descricao" rows="4" placeholder="Conte um pouco sobre o temperamento ou histórico dele..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-action">Salvar Cadastro</button>
                    <a href="index.php" class="btn-cancel">Cancelar</a>
                </div>

            </form>
        </div>
    </main>

</body>
</html>