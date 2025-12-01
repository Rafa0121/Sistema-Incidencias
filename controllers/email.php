<?php
// controllers/email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php"; // composer autoload

function enviarCorreo($para, $asunto, $mensajeHTML)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rafaelsore22@gmail.com';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('rafaelsore22@gmail.com', 'Sistema Incidencias');
        $mail->addAddress($para);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensajeHTML;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer error: " . $mail->ErrorInfo);
        return false;
    }
}
