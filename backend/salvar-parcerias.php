<?php
session_start();

// Verifica se o usuário está logado e é administrador
if (
    !isset($_SESSION['logado']) ||
    !isset($_SESSION['nivel_acesso']) ||
    $_SESSION['nivel_acesso'] !== 'admin'
) {
    header("Location: ../login.php");
    exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome_empresa = trim(filter_input(INPUT_POST, 'nome_empresa'));
    $responsavel = trim(filter_input(INPUT_POST, 'responsavel'));
    $telefone = trim(filter_input(INPUT_POST, 'telefone'));
    $email = trim(filter_input(INPUT_POST, 'email'));
    $tipo_parceria = trim(filter_input(INPUT_POST, 'tipo_parceria'));
    $observacoes = trim(filter_input(INPUT_POST, 'observacoes'));
    $status_parceria = trim(filter_input(INPUT_POST, 'status_parceria'));

    // Validação de campos obrigatórios
    if (
        empty($nome_empresa) ||
        empty($responsavel) ||
        empty($tipo_parceria) ||
        empty($status_parceria)
    ) {
        // Se der erro, volta para o formulário exibindo mensagem
        header("Location: ../parcerias-novo.php?status=erro");
        exit();
    }

    try {
        // Query ajustada perfeitamente com as colunas da sua tabela do banco de dados
        $sql = "INSERT INTO tb_parcerias
        (
            nome_empresa,
            responsavel,
            telefone,
            email,
            tipo_parceria,
            observacoes,
            status_parceria
        )
        VALUES
        (
            :nome_empresa,
            :responsavel,
            :telefone,
            :email,
            :tipo_parceria,
            :observacoes,
            :status_parceria
        )";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':nome_empresa', $nome_empresa);
        $stmt->bindParam(':responsavel', $responsavel);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tipo_parceria', $tipo_parceria);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':status_parceria', $status_parceria);

        $stmt->execute();

        // Sucesso! Redireciona para a listagem principal de parcerias
        header("Location: ../parcerias-novo.php?status=sucesso");
        exit();

    } catch (PDOException $e) {
        error_log($e->getMessage());
        header("Location: ../parcerias-novo.php?status=erro");
        exit();
    }

} else {
    header("Location: ../parcerias-novo.php");
    exit();
}