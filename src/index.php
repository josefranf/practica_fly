<?php

require_once "init.php";

try {

    echo "<h1>Conexión exitosa a PostgreSQL</h1>";

    $stmt = $pdo->query("SELECT id, name FROM users");
    $users = $stmt->fetchAll();

    if ($users) {

        echo "<h3>Cuentas de usuarios:</h3><ul>";

        foreach ($users as $user) {

            echo "<li>ID: {$user['id']} - Nombre: "
                . htmlspecialchars($user['name'])
                . "</li>";

        }

        echo "</ul>";

    } else {

        echo "<p>No hay usuarios.</p>";

    }

} catch (PDOException $e) {

    echo "<h1>Error</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";

}
?>
