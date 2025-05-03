<?php
header('Content-Type: application/json'); 
include_once('conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = mysqli_real_escape_string($connection, $data['correo']);
    $nueva_password = mysqli_real_escape_string($connection, $data['nueva_password']);

    $sql = "UPDATE usuarios SET contraseña = ? WHERE correo = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $nueva_password, $correo);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Contraseña actualizada correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al actualizar la contraseña'
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Método no permitido'
    ]);
}

mysqli_close($connection);
?>