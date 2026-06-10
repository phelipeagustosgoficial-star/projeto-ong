<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Trava de segurança
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

require_once 'backend/conexao.php';

// 1. Verifica se o ID do animal foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: painel-adotante.php");
    exit();
}

$id_animal = (int)$_GET['id'];

// 2. Busca os dados específicos deste pet
try {
    $sql = "SELECT * FROM tb_animais WHERE id = :id";
    $comando = $conexao->prepare($sql);
    $comando->bindValue(':id', $id_animal, PDO::PARAM_INT);
    $comando->execute();
    $pet = $comando->fetch(PDO::FETCH_ASSOC);

    // Se o animal não existir no banco, volta para a listagem
    if (!$pet) {
        header("Location: painel-adotante.php");
        exit();
    }
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    header("Location: painel-adotante.php");
    exit();
}

// 3. Processa o envio do formulário de adoção
$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_adotante = trim($_POST['nome_adotante']);
    $telefone_adotante = trim($_POST['telefone_adotante']);
    $mensagem = trim($_POST['mensagem']);

    if (!empty($nome_adotante) && !empty($telefone_adotante) && !empty($mensagem)) {
        try {
            $sql_pedido = "INSERT INTO tb_pedidos_adocao (id_animal, nome_adotante, telefone_adotante, mensagem, status_pedido) 
                           VALUES (:id_animal, :nome, :telefone, :mensagem, 'Pendente')";
            
            $comando_pedido = $conexao->prepare($sql_pedido);
            $comando_pedido->bindValue(':id_animal', $id_animal, PDO::PARAM_INT);
            $comando_pedido->bindValue(':nome', $nome_adotante, PDO::PARAM_STR);
            $comando_pedido->bindValue(':telefone', $telefone_adotante, PDO::PARAM_STR);
            $comando_pedido->bindValue(':mensagem', $mensagem, PDO::PARAM_STR);
            
            if ($comando_pedido->execute()) {
                $mensagem_sucesso = "🎉 Solicitação enviada com sucesso! A ONG analisará seu perfil.";
            } else {
                $mensagem_erro = "Não foi possível processar seu pedido. Tente novamente.";
            }
        } catch (PDOException $erro) {
            error_log($erro->getMessage());
            $mensagem_erro = "Erro interno no servidor ao salvar a solicitação.";
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos do formulário.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conhecendo <?php echo htmlspecialchars($pet['nome']); ?> - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/adotante.css">
</head>
<body>

    <?php include_once 'includes/header-publico.php'; ?>

    <main class="main-wrapper">
        
        <a href="painel-adotante.php" style="color: var(--primary); text-decoration: none; font-weight: 600; display: inline-block; margin-bottom: 20px;">⬅ Voltar para a listagem</a>

        <?php if (!empty($mensagem_sucesso)): ?>
            <div style="background-color: rgba(34, 197, 94, 0.15); border: 1px solid var(--primary); color: var(--primary); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <?php echo $mensagem_sucesso; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($mensagem_erro)): ?>
            <div style="background-color: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; color: #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                <?php echo $mensagem_erro; ?>
            </div>
        <?php endif; ?>

        <div class="info-container">
            <div class="info-img-box">
                <img src="assets/img/imagens-ong/<?php echo htmlspecialchars($pet['imagem']); ?>" alt="Foto de <?php echo htmlspecialchars($pet['nome']); ?>">
            </div>

            <div class="info-content-box">
                <div class="pet-header-details">
                    <h1><?php echo htmlspecialchars($pet['nome']); ?></h1>
                    <span class="badge-especie"><?php echo htmlspecialchars($pet['especie']); ?></span>
                </div>
                
                <table class="pet-specs-table">
                    <tr>
                        <td><strong>Raça:</strong></td>
                        <td><?php echo htmlspecialchars($pet['raca']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Idade aproximada:</strong></td>
                        <td><?php echo ($pet['idade'] == 1) ? "1 ano" : htmlspecialchars($pet['idade']) . " anos"; ?></td>
                    </tr>
                </table>

                <hr class="divisor-linha">

                <form action="" method="POST" class="form-adocao">
                    <h3>Ficou interessado(a)? Solicite a adoção!</h3>
                    <p style="color: var(--texto-suave); font-size: 0.9rem; margin-bottom: 15px;">Deixe seus dados de contato e conte um pouco sobre sua rotina para a avaliação da ONG.</p>

                    <div class="input-group-form">
                        <label for="nome_adotante">Seu Nome Completo:</label>
                        <input type="text" id="nome_adotante" name="nome_adotante" value="<?php echo htmlspecialchars($_SESSION['user_nome'] ?? ''); ?>" required>
                    </div>

                    <div class="input-group-form">
                        <label for="telefone_adotante">Telefone de Contato (WhatsApp):</label>
                        <input type="text" id="telefone_adotante" name="telefone_adotante" placeholder="(00) 99999-9999" required>
                    </div>

                    <div class="input-group-form">
                        <label for="mensagem">Por que você quer adotar o <?php echo htmlspecialchars($pet['nome']); ?>?</label>
                        <textarea id="mensagem" name="mensagem" rows="4" placeholder="Conte sobre sua casa, se tem outros animais, espaço livre..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit-adocao">Enviar Intenção de Adoção ❤️</button>
                </form>
            </div>
        </div>

    </main>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const bodyElement = document.body;
        
        if (localStorage.getItem('theme') === 'light') {
            bodyElement.classList.remove('dark-theme');
            themeToggleBtn.innerText = '☀️';
        } else {
            bodyElement.classList.add('dark-theme');
            themeToggleBtn.innerText = '🌙';
        }

        themeToggleBtn.addEventListener('click', () => {
            bodyElement.classList.toggle('dark-theme');
            if (bodyElement.classList.contains('dark-theme')) {
                themeToggleBtn.innerText = '🌙';
                localStorage.setItem('theme', 'dark');
            } else {
                themeToggleBtn.innerText = '☀️';
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>