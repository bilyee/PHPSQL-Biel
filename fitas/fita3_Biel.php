<?php
    //connexió dins block try-catch:
    //  prova d'executar el contingut del try
    //  si falla executa el catch
    try {
        $hostname = "localhost";
        $dbname = "classicmodels";
        $username = "root";
        $pw = "Bat_3009";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        // obtenim missatge d'error de l'excepció llançada
        echo "Error connectant a la BD: " . $e->getMessage() . "<br>\n";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Aqui debemos listar los clientes que coincidan o tengan una coincidencia parcial con el filtro
        $customerName = $_POST["customerName"];
        $stmt = $pdo->prepare("SELECT customerName, contactFirstName, contactLastName FROM customers WHERE customerName LIKE ?");
        $stmt->execute(["%$customerName%"]);
        echo "<h3>Resultats per el filtre: '$customerName'</h3>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>{$row['customerName']} - {$row['contactFirstName']} {$row['contactLastName']}</p>";
        }
    }

    try {
        $countries = $pdo->query("SELECT Code, Name FROM country ORDER BY Name");
    } catch (PDOException $e) {
        echo "Error " . $e->getMessage();
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Hay que hacer un formulario que filtre por clientes (customers) a partir del nombre del cliente, nombre o apellido del contacto (mirar los 3 campos) -->
    <h2>Filtres per una sola taula</h2>
    <form action="" method="POST">
        <label for="customerName">Nom del client:</label>
        <input type="text" id="customerName" name="customerName">
        <br><br>
        <input type="submit" value="Filtrar">
    </form>
</body>
</html>