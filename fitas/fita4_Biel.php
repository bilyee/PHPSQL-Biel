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
        $selectedProduct = $_POST["selec"] ?? "";
        echo "<h3>Has seleccionat el producte amb codi: '$selectedProduct'</h3>";
        $stmt = $pdo->prepare("SELECT productLine FROM productlines WHERE productCode = ?");
        $stmt->execute([$selectedProduct]);
    }

    try {
        $products = $pdo->query("SELECT productLine FROM productlines ORDER BY productLine");
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
    <h2>Menu de selecció</h2>
    <form action="" method="POST">
        <label for="selec">Selecciona una producte:</label>
        <select name="selec" id="selec">
            <option value="">-- Selecciona un producte --</option>
            <?php
            while ($row = $products->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['productLine']}'>{$row['productLine']}</option>";
            }
            ?>
</body>
</html>