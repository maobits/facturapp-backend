<?php
/**
 * Archivo: db.php
 * Descripción: Establece la conexión a la base de datos MySQL en Hostinger.
 * Proyecto: FacturApp
 * Desarrollador: Mauricio Chara
 * Versión: 1.0.0
 */

// Configura tus credenciales reales aquí
$host = "195.35.61.14"; // o IP externa
$dbname = "u429495711_facturapp"; // ⚠️ Reemplazar por tu nombre real de base de datos
$username = "u429495711_facturapp";   // ⚠️ Reemplazar por tu usuario real
$password = "FacturApp2025@@"; // ⚠️ Reemplazar por tu contraseña real

// Crear conexión PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configura los errores para que lance excepciones
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si falla, muestra error y termina
    die("❌ Error de conexión: " . $e->getMessage());
}
?>