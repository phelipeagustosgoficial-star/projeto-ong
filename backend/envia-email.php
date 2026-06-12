<?php

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function enviaEmail($emailDestino,$token){
try {

$mail = new PHPMailer(true);

    // Configuração do SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'thiaguinho85@gmail.com';
    $mail->Password = 'audx hmrk wszw wzaz';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Charset
    $mail->CharSet = 'UTF-8';

    // Remetente
    $mail->setFrom('thiaguinho85@gmail.com', 'ThiagoM');

    // Destinatário
    $mail->addAddress($emailDestino);

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de Senha';

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $mail->Body = <<<HTML

    

HTML;

// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    $mail->AltBody = 'Email enviado com PHPMailer';

    // Enviar
    if ($mail->send()) {
        echo "E-mail enviado com sucesso!";
    } else {
        echo "Erro ao enviar: " . $mail->ErrorInfo;
    }

} catch (Exception $e) {
    echo "Erro: {$mail->ErrorInfo}";
}

}