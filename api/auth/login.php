<?php
/**
 * Archivo: login.php
 * Descripción: Inicia sesión verificando email y contraseña.
 * Proyecto: FacturApp
 * Desarrollador: Mauricio Chara
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../db.php";

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(["success" => false, "message" => "Credenciales incompletas."]);
    exit;
}

$email = filter_var($data->email, FILTER_SANITIZE_EMAIL);
$password = $data->password;

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user["password"])) {
            echo json_encode([
                "success" => true,
                "message" => "Inicio de sesión exitoso.",
                "user" => [
                    "id" => $user["id"],
                    "full_name" => $user["full_name"],
                    "email" => $user["email"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>