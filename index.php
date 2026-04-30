<?php
// include do arquivo de conexao
require_once 'backend/conexao.php';

try {
    $sql = "SELECT * FROM tb_animais";
    $comando = $conexao->prepare($sql);
    $comando->execute();
    //armazena os dados do select em um array para que seja exibido na tabela
    $animais = $comando->fetchAll(PDO::FETCH_ASSOC);

    //    echo "<pre>";
    //    var_dump($produtos);

} catch (PDOException $erro) {
    // guarda o erro gerado no log do servidor
    error_log($erro->getMessage());
    // exibe a mensagem de erro
    echo "Não foi possível buscar os dados";
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROJETO CUIDA ANIMAL</title>
    <link rel="stylesheet" href="assets/CSS/styale.css">
</head>
<body>
    <?php 
        // include do header(menu)
        require_once 'includes/header.php';
    ?>    
    <main>
    <h1>Animais Para Adoção</h1>
    <div id="grid-card">
        <?php foreach ($animais as $animal): ?>
            <div class="card">
                <img src="assets/img/imagens-ong/<?php echo $animal['imagem']; ?>" alt="">
                <div class="info">
                    <div class="titulo"><?php echo $animal['nome']; ?></div>
                    <div class="categoria"><?php echo $animal['especie']; ?> — <?php echo $animal['raca']; ?></div>
                    <div class="valor"><?php echo $animal['idade']; ?> anos</div>
                    <a href="animal-info.php?id=<?php echo $animal['id']; ?>">
                        <button type="button">Visualizar</button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>