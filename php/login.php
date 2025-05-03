<?php
session_start();
include_once('conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = mysqli_real_escape_string($connection, $data["usuario"]); 
    $pwd = mysqli_real_escape_string($connection, $data["password"]);

    $sql = "SELECT * FROM usuarios WHERE correo='$mail'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        $colum = mysqli_fetch_array($result);
        $mail2 = $colum['correo'];
        $pwd2 = $colum['contraseña'];

        if ($mail2 == $mail && $pwd == $pwd2) {
            $_SESSION['id_usuario'] = $colum['id_usuario'];
            $_SESSION['correo'] = $mail;
            $_SESSION['nombre'] = $colum['username'];
            
            echo json_encode([
                'success' => true,
                'respuesta' => $colum
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'respuesta' => 'La contraseña es incorrecta'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'respuesta' => 'Correo no encontrado'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'respuesta' => 'Método no permitido'
    ]);
}

mysqli_close($connection);
?>