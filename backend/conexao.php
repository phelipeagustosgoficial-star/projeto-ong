<?php

// arquivo de conexão do PHP com MYSQL usando PDO
// define('SERVIDOR', 'localhost');
// define('USUARIO', 'root');
// define('SENHA', '');
// define('BANCO', 'db_ong_animais');

// Banco remoto
define('SERVIDOR', '193.203.175.121');
define('USUARIO', 'u297790137_cuidaanimal');
define('SENHA', '1i71F*n3');
define('BANCO', 'u297790137_cuidaanimal');

try {
    $conexao = new PDO("mysql:host=" . SERVIDOR . ";dbname=" . BANCO . ";charset=utf8", USUARIO, SENHA);

    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //  echo "Conectado com sucesso!";
} catch (PDOException $erro) {
    echo "Erro ao conectar no banco de dados" . $erro->getMessage();
}
