<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerSingleton {
    private static $instancia;
    private $mail;

    private function __construct() {
        $this->mail = new PHPMailer(true); 

        // Configura las opciones de SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.*******.***'; //Nombre del SMTP
        $this->mail->SMTPAuth = true;
        $this->mail->Username = '******'; // Nombre de usuario del correo
        $this->mail->Password = '********'; // contraseña
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
    }

    public static function obtenerInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function enviarCorreo($destinatario, $tarea, $prioridad, $descripcion) {
        try {
            $this->mail->setFrom('lfg436@educa.madrid.org', 'ToDo');
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = 'Recordatorio Tarea';
            $this->mail->isHTML(true);  
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Body = "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Correo Enviado</title>
            </head>
            <body style='font-family: Arial, sans-serif;'>
            
                <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; border-radius: 10px;'>
                    <h2 style='color: #333;'>Recordatorio de Tarea</h2>
                    <p>¡Hola!</p>
                    <p>Este es un recordatorio amistoso de que tienes pendiente la tarea:</p>
            
                    <strong>Tarea:</strong> $tarea<br>
                    <strong>Prioridad:</strong> $prioridad<br>
                    <strong>Descripción:</strong> $descripcion<br>
            
                    <p>¡Gracias!</p>
                </div>
            
            </body>
            </html>";
            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}

?>
