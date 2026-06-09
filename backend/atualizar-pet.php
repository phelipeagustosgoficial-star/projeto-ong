<?php
session_start();

// Trava de segurança: Se não for administrador, chuta
if (!isset($_SESSION['logado']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $idade = intval($_POST['idade']);
    $especie = trim($_POST['especie']);
    $raca = trim($_POST['raca']);

    try {
        // 1. Busca os dados atuais do pet para sabermos qual é a imagem atual cadastrada
        $sql_busca = "SELECT imagem FROM tb_animais WHERE id = :id";
        $stmt_busca = $conexao->prepare($sql_busca);
        $stmt_busca->bindParam(':id', $id);
        $stmt_busca->execute();
        $pet_atual = $stmt_busca->fetch(PDO::FETCH_ASSOC);

        $nome_imagem = $pet_atual['imagem']; // Mantém a imagem atual por padrão

        // 2. Verifica se o usuário enviou uma NOVA imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            // Gera um nome único e criptografado para evitar arquivos duplicados
            $novo_nome_imagem = md5(uniqid(rand(), true)) . "." . $extensao;
            $diretorio_destino = "../assets/img/imagens-ong/" . $novo_nome_imagem;

            // Faz o upload do novo arquivo
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio_destino)) {
                
                // Remove a foto ANTIGA da pasta se ela existir, limpando o servidor
                $foto_antiga = "../assets/img/imagens-ong/" . $pet_atual['imagem'];
                if (file_exists($foto_antiga) && !empty($pet_atual['imagem'])) {
                    unlink($foto_antiga);
                }

                // Atualiza a variável para salvar o novo nome no banco
                $nome_imagem = $novo_nome_imagem;
            }
        }

        // 3. Executa o UPDATE no banco de dados com as informações atualizadas
        $sql_update = "UPDATE tb_animais 
                       SET nome = :nome, idade = :idade, especie = :especie, raca = :raca, imagem = :imagem 
                       WHERE id = :id";
        
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':idade', $idade);
        $stmt_update->bindParam(':especie', $especie);
        $stmt_update->bindParam(':raca', $raca);
        $stmt_update->bindParam(':imagem', $nome_imagem);
        $stmt_update->bindParam(':id', $id);

        if ($stmt_update->execute()) {
            // Sucesso! Retorna para a lista com a mensagem verde
            header("Location: ../editar.php?status=sucesso");
            exit();
        } else {
            header("Location: ../editar.php?status=erro");
            exit();
        }

    } catch (PDOException $erro) {
        error_log($erro->getMessage());
        header("Location: ../editar.php?status=erro");
        exit();
    }
} else {
    header("Location: ../editar.php");
    exit();
}