<?php
header('Content-Type: application/json'); 
include_once('conexion.php');
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$data = json_decode(file_get_contents('php://input'), true);
$codigo = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

$mail = new PHPMailer(true);

try {
    $sql = "SELECT * FROM usuarios WHERE correo=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $data['correo_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows <= 0){
        echo json_encode([
            'success' => false,
            'mensaje' => 'Correo no encontrado'
        ]);
        return;
    }

    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'sanchezvera490@gmail.com';
    $mail->Password = 'vtkj ekyt dnqz xnxl'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->setFrom('sanchezvera490@gmail.com', 'Dual');
    $mail->addReplyTo('sanchezvera490@gmail.com', 'Dual');
    $mail->addAddress($data['correo_usuario'], 'Usuario');
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);
    $mail->Subject = 'Código de recuperación de contraseña';
    $mail->Body = '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #2ecc71;">Recuperación de Contraseña</h2>
        </div>
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 5px;">
            <p style="margin-bottom: 15px;">Hola,</p>
            <p style="margin-bottom: 15px;">Recibimos una solicitud para restablecer la contraseña de tu cuenta. Tu código de verificación es:</p>
            <div style="text-align: center; padding: 15px; background-color: #eee; border-radius: 5px; font-size: 24px; font-weight: bold; letter-spacing: 2px;">
                '.$codigo.'
            </div>
            <p style="margin-top: 15px;">Si no solicitaste este código, puedes ignorar este correo.</p>
        </div>
        <div style="text-align: center; font-size: 12px; color: #666;">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>';
    $mail->AltBody = "Tu código de recuperación es: $codigo";

    $mail->send();

    echo json_encode([
        'success' => true,
        'mensaje' => 'Correo enviado correctamente',
        'codigo' => $codigo
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => "Error al enviar el correo: {$mail->ErrorInfo}"
    ]);
}

$stmt->close();
$connection->close();
?>
