<?php

echo "<h2>=== INICIO DE INIT.PHP ===</h2>";

$databaseUrl = "postgresql://fly-user:IqtnnAWAtmKx8L89mR4NPHj0@pgbouncer.d1zj5omzqjvryqkv.flympg.net/fly-db";
// Más adelante podrás sustituir la línea anterior por:
// $databaseUrl = getenv('DATABASE_URL');

if (!$databaseUrl) {
    die("<h3>ERROR: DATABASE_URL no definida</h3>");
}

echo "✔ DATABASE_URL encontrada.<br>";

$dbConfig = parse_url($databaseUrl);

$host   = $dbConfig['host'];
$user   = $dbConfig['user'];
$pass   = $dbConfig['pass'];
$port   = $dbConfig['port'] ?? 5432;
$dbname = ltrim($dbConfig['path'], '/');

echo "✔ Host: $host<br>";
echo "✔ Base de datos: $dbname<br>";
echo "✔ Usuario: $user<br><br>";

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {

    echo "<b>Conectando a PostgreSQL...</b><br>";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✔ Conexión correcta.<br><br>";

    // Información de la conexión
    $info = $pdo->query("
        SELECT
            current_user,
            current_database(),
            current_schema()
    ")->fetch();

    echo "<b>Información de la conexión:</b><br>";
    echo "Usuario: " . $info['current_user'] . "<br>";
    echo "Base de datos: " . $info['current_database'] . "<br>";
    echo "Esquema: " . $info['current_schema'] . "<br><br>";

    echo "<b>Creando tabla users...</b><br>";

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        );
    ");

    echo "✔ Tabla creada o ya existía.<br><br>";

    echo "<b>Contando usuarios...</b><br>";

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM users");
    $fila = $stmt->fetch();

    $total = $fila['total'];

    echo "Número de usuarios: <b>$total</b><br><br>";

    if ($total == 0) {

        echo "<b>Insertando usuarios...</b><br>";

        $pdo->exec("
            INSERT INTO users(name)
            VALUES
            ('Admin'),
            ('User');
        ");

        echo "✔ Usuarios insertados correctamente.<br><br>";

    } else {

        echo "✔ No se insertan usuarios porque la tabla ya contiene datos.<br><br>";

    }

    echo "<b>Contenido de la tabla:</b><br>";

    $stmt = $pdo->query("SELECT id, name FROM users");

    while ($fila = $stmt->fetch()) {

        echo "ID: {$fila['id']} - {$fila['name']}<br>";

    }

    echo "<br><h2>=== FIN DE INIT.PHP ===</h2>";

} catch (PDOException $e) {

    echo "<h2>ERROR PDO</h2>";

    echo "<pre>";
    echo $e->getMessage();
    echo "</pre>";

}
