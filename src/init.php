<?php
$databaseUrl = "postgresql://fly-user:IqtnnAWAtmKx8L89mR4NPHj0@pgbouncer.d1zj5omzqjvryqkv.flympg.net/fly-db";

if (!$databaseUrl) {
    die("DATABASE_URL no definida");
}

$dbConfig = parse_url($databaseUrl);

$host = $dbConfig['host'];
$user = $dbConfig['user'];
$pass = $dbConfig['pass'];
$port = $dbConfig['port'] ?? 5432;
$dbname = ltrim($dbConfig['path'], '/');

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Crear la tabla si no existe
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users(
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        );
    ");

    // Insertar datos solo si la tabla está vacía
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");

    if ($stmt->fetchColumn() == 0) {

        $pdo->exec("
            INSERT INTO users(name) VALUES
            ('Admin'),
            ('User');
        ");

        echo "Base de datos inicializada.<br>";

    } else {

        echo "La base de datos ya estaba inicializada.<br>";

    }

} catch(PDOException $e){

    die($e->getMessage());

}