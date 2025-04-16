<?php
/**
 * Archivo: register.php
 * Descripción: Registra un nuevo usuario en la tabla users.
 * Proyecto: FacturApp
 * Desarrollador: Mauricio Chara
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Incluir conexión
require_once "../db.php";

// Obtener datos desde JSON
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->full_name) || !isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Campos incompletos."]);
    exit;
}

// Sanitizar
$name = htmlspecialchars(strip_tags($data->full_name));
$email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
$password = password_hash($data->password, PASSWORD_DEFAULT); // Encriptar contraseña

try {
    // Verificar si ya existe el correo
    $check = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $check->bindParam(":email", $email);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "El correo ya está registrado."]);
        exit;
    }

    // Insertar nuevo usuario
    $sql = "INSERT INTO users (full_name, email, password) VALUES (:name, :email, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro exitoso."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar usuario."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>