<?php
session_start();

// Trava de segurança: Se não estiver logado, chuta de volta para a tela de login
if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit();
}

// Importa a sua conexão com o banco de dados
require_once 'backend/conexao.php';

try {
    // Busca todos os animais cadastrados na tabela tb_animais para listar na tela
    $sql = "SELECT * FROM tb_animais ORDER BY id DESC";
    $comando = $conexao->prepare($sql);
    $comando->execute();
    $animais = $comando->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    $animais = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quero Adotar - CUIDA ANIMAL</title>
    
    <link rel="stylesheet" href="assets/CSS/styale.css">
    <link rel="stylesheet" href="assets/CSS/adotante.css">
</head>
<body>

    <nav class="navbar-adotante">
        <a href="painel-adotante.php" class="navbar-brand">🐾 CUIDA ANIMAL</a>
        <div class="navbar-user">
            <span>Bem-vindo(a), <strong><?php echo htmlspecialchars($_SESSION['user_nome']); ?></strong>!</span>
            <a href="sair.php" class="btn-logout">Sair 🚪</a>
        </div>
    </nav>

    <main class="container-adotante">
        
        <div class="welcome-section">
            <h1>Encontre o seu novo melhor amigo</h1>
            <p>Estes são os animaizinhos sob a nossa proteção. Escolha um deles para conhecer a sua história e iniciar o processo de adoção!</p>
        </div>

        <div class="pets-grid">
            <?php if (empty($animais)): ?>
                <div class="no-pets">
                    <p>Não há animais disponíveis para adoção no momento. Volte mais tarde!</p>
                </div>
            <?php else: ?>
                <?php foreach ($animais as $pet): ?>
                    <div class="pet-card">
                        
                        <div class="pet-img-container">
                            <img src="assets/img/imagens-ong/<?php echo htmlspecialchars($pet['imagem']); ?>" alt="Foto de <?php echo htmlspecialchars($pet['nome']); ?>" class="pet-img">
                        </div>

                        <div class="pet-info">
                            <h2 class="pet-name"><?php echo htmlspecialchars($pet['nome']); ?></h2>
                            <p class="pet-details"><?php echo htmlspecialchars($pet['especie']); ?> &bull; <?php echo htmlspecialchars($pet['raca']); ?></p>
                            
                            <p class="pet-age">
                                <?php 
                                    if ($pet['idade'] == 1) {
                                        echo "1 ano de idade";
                                    } else {
                                        echo htmlspecialchars($pet['idade']) . " anos de idade";
                                    }
                                ?>
                            </p>

                            <a href="animal-info.php?id=<?php echo $pet['id']; ?>" class="btn-adopt">Quero Conhecer</a>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>

</body>
</html>