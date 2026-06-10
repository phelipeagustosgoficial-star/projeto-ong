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
    // Traz todos os pets ativos ordenados pelo ID mais recente
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
    <link class="tema-CSS" rel="stylesheet" href="assets/CSS/adotante.css">
</head>
<body>

    <?php include_once 'includes/header-publico.php'; ?>

    <section class="search-filter-section">
        <div class="search-container">
            <input type="text" id="pet-search" class="search-input" placeholder="🔍 Digite o nome, raça ou característica para buscar...">
            <button class="filter-btn active" onclick="filtrarEspecie('todos', this)">Todos</button>
            <button class="filter-btn" onclick="filtrarEspecie('Cachorro', this)">Cães 🐶</button>
            <button class="filter-btn" onclick="filtrarEspecie('Gato', this)">Gatos 🐱</button>
        </div>
    </section>

    <main class="main-wrapper">
        <div class="pets-grid" id="pets-grid">
            <?php if (empty($animais)): ?>
                <div class="no-pets">
                    <h3>Nenhum animal cadastrado no momento. 🐾</h3>
                    <p>Fique de olho, novos amiguinhos podem chegar a qualquer instante!</p>
                </div>
            <?php else: ?>
                <?php foreach ($animais as $pet): ?>
                    <div class="pet-card" data-especie="<?php echo htmlspecialchars($pet['especie']); ?>">
                        <div class="pet-img-container">
                            <img src="assets/img/imagens-ong/<?php echo htmlspecialchars($pet['imagem']); ?>" alt="Foto de <?php echo htmlspecialchars($pet['nome']); ?>" class="pet-img">
                        </div>

                        <div class="pet-info">
                            <h2 class="pet-name"><?php echo htmlspecialchars($pet['nome']); ?></h2>
                            <p class="pet-details"><?php echo htmlspecialchars($pet['especie']); ?> &bull; <?php echo htmlspecialchars($pet['raca']); ?></p>
                            
                            <p class="pet-age">
                                📅 <?php echo ($pet['idade'] == 1) ? "1 ano de idade" : htmlspecialchars($pet['idade']) . " anos de idade"; ?>
                            </p>

                            <a href="animal-info.php?id=<?php echo $pet['id']; ?>" class="btn-adopt">Quero Conhecer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // --- CONTROLE DARK / LIGHT MODE ---
        const themeToggleBtn = document.getElementById('theme-toggle');
        const bodyElement = document.body;
        const temaSalvo = localStorage.getItem('theme');
        
        if (temaSalvo === 'light') {
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

        // --- FILTRO POR DIGITAÇÃO (TEMPO REAL) ---
        const inputBusca = document.getElementById('pet-search');
        inputBusca.addEventListener('input', () => {
            const termo = inputBusca.value.toLowerCase();
            const cards = document.querySelectorAll('.pet-card');

            cards.forEach(card => {
                const textoCard = card.textContent.toLowerCase();
                if (textoCard.includes(termo)) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });

        // --- FILTRO POR BOTÃO DE ESPÉCIE ---
        function filtrarEspecie(especie, botaoClicado) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            botaoClicado.classList.add('active');

            const cards = document.querySelectorAll('.pet-card');
            cards.forEach(card => {
                const especieCard = card.getAttribute('data-especie');
                if (especie === 'todos' || especieCard.toLowerCase() === especie.toLowerCase()) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>