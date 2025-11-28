<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exemple de lectura de dades a MySQL - Checkboxes</title>
    <style>
        table, td { border: 1px solid black; border-spacing: 0; }
        .checkbox-list { display: flex; flex-wrap: wrap; gap: 8px; }
        .checkbox-item { margin-right: 12px; }
    </style>
</head>
<body>
    <h1>Filtre de dades PHP (Checkboxes per continents)</h1>
<?php
    // (1) Connexió
    $conn = mysqli_connect('localhost','root','Bat_3009');
    mysqli_select_db($conn, 'world');

    // (2) Obtenir llista de continents per al formulari
    $continents = [];
    $res_cont = mysqli_query($conn, "SELECT DISTINCT Continent FROM country ORDER BY Continent");
    if ($res_cont) {
        while ($r = mysqli_fetch_assoc($res_cont)) {
            $continents[] = $r['Continent'];
        }
        mysqli_free_result($res_cont);
    }

    // (3) Mostrem el formulari amb checkboxes
    echo '<form method="get">';
    echo '<div class="checkbox-list">';
    foreach ($continents as $c) {
        $isChecked = false;
        if (isset($_GET['continent'])) {
            // assegurem-nos que ho tractem com array
            $selected = $_GET['continent'];
            if (!is_array($selected)) {
                $selected = [$selected];
            }
            // comparació estricta de valor
            $isChecked = in_array($c, $selected, true);
        }
        $val = htmlspecialchars($c, ENT_QUOTES, 'UTF-8');
        $checkedAttr = $isChecked ? ' checked' : '';
        echo "<label class=\"checkbox-item\"><input type=\"checkbox\" name=\"continent[]\" value=\"{$val}\"{$checkedAttr}> {$val}</label>";
    }
    echo '</div>';
    echo '<p><button type="submit">Filtrar</button></p>';
    echo '</form>';

    // (4) Processar la selecció i construir la consulta
    $selectedContinents = $_GET['continent'] ?? []; // pot ser array o string
    if (!is_array($selectedContinents)) {
        $selectedContinents = [$selectedContinents];
    }

    $clean = [];
    foreach ($selectedContinents as $s) {
        $s_trim = trim((string)$s);
        if ($s_trim === '') continue;
        // Usar mysqli_real_escape_string per cada valor
        $clean[] = "'" . mysqli_real_escape_string($conn, $s_trim) . "'";
    }

    if (count($clean) > 0) {
        $in_list = implode(',', $clean);
        $consulta = "SELECT Name, Code, Region, Continent, Population FROM country WHERE Continent IN ($in_list) ORDER BY Name;";
    } else {
        // si no hi ha cap selecció seleccionem tots els països (com en l'original)
        $consulta = "";
    }

    // (5) Executem la consulta
    $resultat = mysqli_query($conn, $consulta);
    if (!$resultat) {
        $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
        $message .= 'Consulta realitzada: ' . $consulta;
        die($message);
    }
?>

<?php
    // (6) Mostrem resultats (llista simple)
    echo "<h2>Llistat de països</h2>\n";
    echo "<ul>\n";
    while ($registre = mysqli_fetch_assoc($resultat)) {
        echo "\t<li>" . htmlspecialchars($registre['Name'], ENT_QUOTES, 'UTF-8') . " — " 
                . htmlspecialchars($registre['Continent'], ENT_QUOTES, 'UTF-8') . "</li>\n";
    }
    echo "</ul>\n";

    mysqli_free_result($resultat);
    mysqli_close($conn);
?>
</body>
</html>
