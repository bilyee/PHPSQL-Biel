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
    <h1>Buscador de ciudades PHP</h1>
    <form method="GET">
        <input name="pais" />
        <input type="submit" />
    </form>
 	<?php
 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)
 		$conn = mysqli_connect('localhost','root','Bat_3009');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');
 
 		# (2.1) creem el string de la consulta (query)
        $pais = trim($_GET['pais'] ?? '');
        if ($pais !== '') {
            $pais_esc = mysqli_real_escape_string($conn, $pais);
            $consulta = "
                SELECT city.Name AS CityName, country.Name AS CountryName
                FROM city
                JOIN country ON city.CountryCode = country.Code
                WHERE country.Name LIKE '%$pais_esc%'
                ORDER BY CountryName, CityName;
            ";

            # (2.2) enviem la query al SGBD per obtenir el resultat
            $resultat = mysqli_query($conn, $consulta);
    
            # (2.3) si no hi ha resultat (0 files o bé hi ha algun error a la sintaxi)
            #     posem un missatge d'error i acabem (die) l'execució de la pàgina web
            if (!$resultat) {
                    $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
                    $message .= 'Consulta realitzada: ' . $consulta;
                    die($message);
            }

            echo "<h2>Llistat de països</h2>\n";
            echo "<ul>\n";
            while ($registre = mysqli_fetch_assoc($resultat)) {
                echo "<li>" . htmlspecialchars($registre['CityName']) . " — <strong>" . htmlspecialchars($registre['CountryName']) . "</strong></li>";
            }
            echo "</ul>\n";

            mysqli_free_result($resultat);
        }
        mysqli_close($conn);
 	?>
</body>
</html>
