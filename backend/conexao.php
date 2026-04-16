<?php

// arquivo de conexão do PHP com MYSQL usando PDO
define('SERVIDOR', 'localhost');
define('USUARIO', 'root');
define('SENHA', '');
define('BANCO', 'db_ong_animais');

try {
    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO . ";charset=utf8", USUARIO, SENHA);

    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //  echo "Conectado com sucesso!";
} catch (PDOException $erro) {
    echo "Erro ao conectar no banco de dados" . $erro->getMessage();
}
