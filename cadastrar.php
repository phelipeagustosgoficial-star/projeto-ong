<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Animal</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
</head>
<body>
    <?php require_once 'includes/header.php'; ?>
    <main>
        <h1>Cadastro - Animal</h1>
        <form action="backend/cadastrar-animal.php" method="post" enctype="multipart/form-data">
            <div id="grid">
                <div>
                    <label for="nome">Nome do Animal</label>
                    <input type="text" name="nome" id="nome" required>
                </div>
                <div>
                    <label for="especie">Espécie</label>
                    <select name="especie" id="especie" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Cachorro">Cachorro</option>
                        <option value="Gato">Gato</option>
                        <option value="Coelho">Coelho</option>
                        <option value="Ave">Ave</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
                <div>
                    <label for="raca">Raça</label>
                    <input type="text" name="raca" id="raca" required>
                </div>
                <div>
                    <label for="idade">Idade (anos)</label>
                    <input type="number" name="idade" id="idade" required>
                </div>
                <div>
                    <label for="cor">Cor</label>
                    <input type="text" name="cor" id="cor" required>
                </div>
                <div>
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="Macho">Macho</option>
                        <option value="Fêmea">Fêmea</option>
                    </select>
                </div>
                <div>
                    <label for="imagem">Imagem</label>
                    <input type="file" name="imagem" id="imagem" required>
                </div>
                <div>
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao"></textarea>
                </div>
            </div>
            <input class="btn-cadastrar" type="submit" value="Cadastrar">
        </form>
    </main>
    <footer>
        &copy; 2026 - Todos os direitos reservados
    </footer>
</body>
</html>