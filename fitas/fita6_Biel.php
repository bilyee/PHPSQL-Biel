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
        $productLine = $_POST["productLine"] ?? "";
        $description = $_POST["description"] ?? "";
        $htmlDescription = $_POST["htmlDescription"] ?? "";
        $image = null;
        $stmt = $pdo->prepare("INSERT INTO productlines (productLine, textDescription, htmlDescription, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$productLine, $description, $htmlDescription, $image]);
        echo "<h3>Product Line '$productLine' afegida correctament!</h3>";
    }

    try {
        $productLines = $pdo->query("SELECT productLine FROM productlines ORDER BY productLine");
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
    <title>Fita 6 - Biel</title>
</head>
<body>
    <h1>Afegir una nova Product Line</h1>
    <form action="" method="POST">
        <label for="productLine">Product Line:</label><br>
        <input type="text" id="productLine" name="productLine" required><br><br>
        <label for="description">Text Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
        <label for="htmlDescription">HTML Description:</label><br>
        <textarea id="htmlDescription" name="htmlDescription" rows="4" cols="50"></textarea><br><br>
        <input type="submit" value="Afegir Product Line">
    </form>
</body>
</html>