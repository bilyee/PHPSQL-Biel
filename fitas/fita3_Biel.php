<?php
    try {
        $hostname = "localhost";
        $dbname = "classicmodels";
        $username = "root";
        $pw = "Bat_3009";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Error connectant a la BD: " . $e->getMessage() . "<br>\n";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customerName = $_POST["customerName"];
        $stmt = $pdo->prepare("SELECT customerName, contactFirstName, contactLastName, city FROM customers WHERE customerName LIKE ? OR contactFirstName LIKE ? OR contactLastName LIKE ?");
        $stmt->execute(["%$customerName%", "%$customerName%", "%$customerName%"]);
        echo "<h3>Resultats per el filtre: '$customerName'</h3>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>{$row['customerName']} - {$row['contactFirstName']} {$row['contactLastName']} ({$row['city']})</p>";
        }
    }

    try {
        $customers = $pdo->query("SELECT customerName, contactFirstName, contactLastName FROM customers");
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
    <h2>Filtres per una sola taula</h2>
    <form action="" method="POST">
        <label for="customerName">Nom del client:</label>
        <input type="text" id="customerName" name="customerName">
        <br><br>
        <input type="submit" value="Filtrar">
    </form>
</body>
</html>