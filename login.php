<?php
session_start();
// Se o administrador já estiver logado, joga direto pro painel
if (isset($_SESSION['logado'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/login.css">
</head>

<body>

    <div class="login-container">
        <div class="login-logo">🐾 Cuida Animal</div>
        <div class="login-subtitle">Painel de Controle Administrativo</div>

        <?php if (isset($_GET['erro'])): ?>
            <div class="error-box">
                ⚠️ <strong>Acesso Negado:</strong> E-mail ou senha incorretos.
            </div>
        <?php endif; ?>

        <form action="backend/validar_login.php" method="POST">
            <div class="form-group">
                <label for="email">E-mail Administrativo</label>
                <input type="email" name="email" id="email" placeholder="Ex: admin@ong.com" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha de Acesso</label>
                <input type="password" name="senha" id="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-submit">Entrar no Sistema</button>
        </form>

        <div class="login-links">
            <a href="recuperar-senha.php" class="link-item">Esqueceu a senha?</a>
            <span class="divider">|</span>
            <p class="signup-text">Não tem cadastro? <a href="cadastro-usuario.php">Cadastre-se</a></p>
        </div>
    </div>

</body>

</html>