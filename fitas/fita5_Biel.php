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

    $productName = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productName = trim($_POST["productName"] ?? '');

        if ($productName !== '') {
            $stmt = $pdo->prepare("SELECT p.productName, p.productVendor, o.orderNumber, o.customerNumber, o.orderDate, od.quantityOrdered, od.priceEach
        FROM products p
        JOIN orderdetails od ON p.productCode = od.productCode
        JOIN orders o ON od.orderNumber = o.orderNumber
        WHERE p.productName LIKE ?");
            $stmt->execute(["%$productName%"]);

            echo "<h3>Resultats per el filtre: '$productName'</h3>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>
                    <thead>
                        <tr>
                            <th>Producte</th>
                            <th>Vendor</th>
                            <th>Order Number</th>
                            <th>Customer Number</th>
                            <th>Order Date</th>
                            <th>Quantity Ordered</th>
                            <th>Price Each</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['productName']}</td>
                    <td>{$row['productVendor']}</td>
                    <td>{$row['orderNumber']}</td>
                    <td>{$row['customerNumber']}</td>
                    <td>{$row['orderDate']}</td>
                    <td>{$row['quantityOrdered']}</td>
                    <td>{$row['priceEach']}</td>
                </tr>";
            }

            echo "</tbody></table>";
        }
    }

    try {
        if ($productName !== '') {
            $products = $pdo->query("SELECT productName FROM products WHERE productName LIKE '%$productName%'");
        } else {
            echo "<p>Introdueix un nom de producte per mostrar les opcions.</p>";
        }
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
    <h1>Filtres per més d'una taula</h1>
    <form action="" method="POST">
        <label for="productName">Nom del producte:</label>
        <input type="text" id="productName" name="productName">
        <br><br>
        <input type="submit" value="Filtrar">
    </form>
</body>
</html>