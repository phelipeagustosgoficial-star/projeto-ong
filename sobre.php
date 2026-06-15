<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Se quiser proteger a página para apenas usuários logados verem, deixe as linhas abaixo.
// Se quiser que qualquer visitante veja, pode apagar este bloco do 'if'.
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
    <title>Sobre a ONG - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/adotante.css">
    <link rel="stylesheet" href="assets/CSS/sobre-ong.css">
</head>
<body>

    <?php include_once 'includes/header-publico.php'; ?>

    <main class="sobre-container">
        
        <div class="banner-sobre">
            <h1>Quem Somos</h1>
            <p>Conheça a história e o propósito por trás do Cuida Animal</p>
        </div>

        <section class="secao-historia">
            <h2>Nossa História</h2>
            <p>
                O projeto <strong>Cuida Animal</strong> nasceu do desejo de transformar a realidade de animais abandonados e em situação de risco. Atuamos como uma ponte entre pessoas que desejam dar um lar de amor e pets que precisam de uma segunda chance.
            </p>
            <p>
                Através da nossa plataforma digital, facilitamos o processo de adoção responsável, conectando protetores, adotantes e apoiadores. Além disso, nossa lojinha solidária reverte 100% dos lucros para a compra de ração, medicamentos e tratamentos veterinários para os animais resgatados.
            </p>
        </section>

        <section class="grid-pilares">
            
            <div class="card-pilar">
                <span class="icone-pilar">🎯</span>
                <h3>Missão</h3>
                <p>Promover a adoção responsável e garantir o bem-estar animal, conscientizando a sociedade e oferecendo suporte digno aos pets resgatados.</p>
            </div>

            <div class="card-pilar">
                <span class="icone-pilar">👁️</span>
                <h3>Visão</h3>
                <p>Ser uma plataforma de referência no ecossistema de proteção animal, reduzindo drasticamente o índice de abandonment através da tecnologia.</p>
            </div>

            <div class="card-pilar">
                <span class="icone-pilar">❤️</span>
                <h3>Valores</h3>
                <p>Amor incondicional aos animais, transparência nas ações, responsabilidade social, empatia e compromisso com a causa pet.</p>
            </div>

        </section>

    </main>

    <script>
        // Mantém a sincronização do tema Sol e Lua caso o usuário mude de página
        document.addEventListener('DOMContentLoaded', () => {
            const temaSalvo = localStorage.getItem('tema') || 'dark';
            if (temaSalvo === 'light') {
                document.body.classList.add('light-mode');
            }
        });
    </script>
</body>
</html>