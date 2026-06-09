<?php
session_start();
session_destroy(); // Destrói todas as informações de login
header("Location: login.php"); // Redireciona para a tela de login externa
exit();
?>