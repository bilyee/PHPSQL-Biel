<?php
    //connexió dins block try-catch:
    //  prova d'executar el contingut del try
    //  si falla executa el catch
    try {
        $hostname = "localhost";
        $dbname = "world";
        $username = "root";
        $pw = "Bat_3009";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        // obtenim missatge d'error de l'excepció llançada
        echo "Error connectant a la BD: " . $e->getMessage() . "<br>\n";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $countryCode = $_POST["country"] ?? "";
        $language = trim($_POST["language"] ?? "");
        $official = $_POST["official"] ?? "";
        $porcentaje = floatval($_POST["porcentaje"] ?? "");

        try {
            $sql = "INSERT INTO countrylanguage (CountryCode, Language, isOfficial, Percentage) VALUES (:country, :language, :official, :percentage)";

            $query = $pdo->prepare($sql);

            $query->execute([
                ":country" => $countryCode,
                ":language" => $language,
                ":official" => $official,
                "percentage" => $porcentaje
            ]);

            echo "<p style='color:green;'>La llengua s'ha afegit correctament!</p>";
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error afegint la llengua: " . $e->getMessage() . "</p>";
        }
    }

    try {
        $countries = $pdo->query("SELECT Code, Name FROM country ORDER BY Name");
    } catch (PDOException $e) {
        echo "Error carregant països: " . $e->getMessage();
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
    <h2>Afegir una nova llengua</h2>

    <form action="" method="POST">

        <label for="country">País:</label><br>
        <select name="country" id="country" required>
            <option value="">-- Selecciona un país --</option>

            <?php
            while ($row = $countries->fetch()) {
                echo "<option value='{$row['Code']}'>{$row['Name']}</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="language">Llengua:</label><br>
        <input type="text" name="language" id="language" required>
        <br><br>

        <label>És oficial?</label><br>
        <input type="radio" name="official" value="T" required> Sí<br>
        <input type="radio" name="official" value="F" required> No<br><br>

        <label for="porcentaje">Percentatge:</label><br>
        <input type="number" step="0.1" name="porcentaje" id="porcentaje" required>
        <br><br>

        <button type="submit">Afegir llengua</button>
    </form>
</body>
</html>