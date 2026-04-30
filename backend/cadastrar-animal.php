<?php
require_once 'conexao.php';

// Pega os dados do formulário
$nome      = $_POST['nome'];
$especie   = $_POST['especie'];
$raca      = $_POST['raca'];
$idade     = $_POST['idade'];
$cor       = $_POST['cor'];
$sexo      = $_POST['sexo'];
$descricao = $_POST['descricao'];

// Upload da imagem
$imagem = $_FILES['imagem']['name'];
$destino = '../assets/img/imagens-ong/' . $imagem;
move_uploaded_file($_FILES['imagem']['tmp_name'], $destino);

try {
    $sql = "INSERT INTO tb_animais 
            (nome, especie, raca, idade, cor, sexo, descricao, imagem) 
            VALUES 
            (:nome, :especie, :raca, :idade, :cor, :sexo, :descricao, :imagem)";
    
    $comando = $conexao->prepare($sql);
    $comando->execute([
        ':nome'      => $nome,
        ':especie'   => $especie,
        ':raca'      => $raca,
        ':idade'     => $idade,
        ':cor'       => $cor,
        ':sexo'      => $sexo,
        ':descricao' => $descricao,
        ':imagem'    => $imagem
    ]);

    // Volta para a página inicial após cadastrar
    header('Location: ../index.php');

} catch (PDOException $erro) {
    error_log($erro->getMessage());
    echo "Erro ao cadastrar animal.";
}
?>