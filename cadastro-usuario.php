<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/login.css">
</head>
<body>

    <div class="login-container">
        <div class="login-logo">🐾 Cuida Animal</div>
        <div class="login-subtitle">Criar Nova Conta no Sistema</div>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="success-box" style="background-color: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.2); color: #4caf50; padding: 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; text-align: left;">
                ✅ <strong>Sucesso:</strong> Usuário cadastrado com sucesso! <a href="login.php" style="color: #ffffff; font-weight: bold;">Fazer Login</a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <div class="error-box">
                ⚠️ <strong>Erro:</strong> <?php 
                    if($_GET['erro'] == 'email_existente') echo "Este e-mail já está cadastrado.";
                    else echo "Não foi possível realizar o cadastro. Tente novamente.";
                ?>
            </div>
        <?php endif; ?>

        <form action="backend/salvar-usuario.php" method="POST">
            
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" name="nome" id="nome" placeholder="Ex: João Silva" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Ex: usuario@email.com" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="Crie uma senha segura" required>
            </div>

            <button type="submit" class="btn-submit">Finalizar Cadastro</button>
        </form>

        <div class="login-links">
            <p class="signup-text">Já tem uma conta? <a href="login.php">Voltar para o Login</a></p>
        </div>
    </div>

</body>
</html>