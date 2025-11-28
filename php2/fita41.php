<html>
 <head>
 	<title>Exemple de lectura de dades a MySQL</title>
 	<style>
 		body{
 		}
 		table,td {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}
 	</style>
 </head>
 
 <body>
 	<h1>Filtre de dades PHP</h1>
	<!-- El formulario se genera más abajo después de conectar a la BBDD -->
 	<?php
 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)
 		$conn = mysqli_connect('localhost','root','Bat_3009');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');

		// Obtenir la llista de continents per al desplegable
		$continents = [];
		$res_cont = mysqli_query($conn, "SELECT DISTINCT Continent FROM country ORDER BY Continent");
		if ($res_cont) {
			while ($r = mysqli_fetch_assoc($res_cont)) {
				$continents[] = $r['Continent'];
			}
			mysqli_free_result($res_cont);
		}
 
		# (2.1) creem el string de la consulta (query)
		// Mostrem un formulari amb desplegable de continents
		echo "<form method=\"get\">\n";
		echo "<label for=\"continent\">Continente: </label>";
		echo "<select name=\"continent\" id=\"continent\" onchange=\"this.form.submit()\">\n";
		echo "<option value=\"\">-- Todos --</option>\n";
		foreach ($continents as $c) {
			$sel = (isset($_GET['continent']) && $_GET['continent'] === $c) ? ' selected' : '';
			echo "<option value=\"" . htmlspecialchars($c) . "\"$sel>" . htmlspecialchars($c) . "</option>\n";
		}
		echo "</select>\n";
		echo "<noscript><button type=\"submit\">Filtrar</button></noscript>\n";
		echo "</form>\n";

		$continent = trim($_GET['continent'] ?? '');
		if ($continent !== '') {
			$continent_esc = mysqli_real_escape_string($conn, $continent);
			$consulta = "SELECT Name, Code, Region, Continent, Population FROM country WHERE Continent = '" . $continent_esc . "' ORDER BY Name;";
		} else {
			$consulta = "SELECT Name, Code, Region, Continent, Population FROM country ORDER BY Name;";
		}
 		# (2.2) enviem la query al SGBD per obtenir el resultat
 		$resultat = mysqli_query($conn, $consulta);
 
 		# (2.3) si no hi ha resultat (0 files o bé hi ha algun error a la sintaxi)
 		#     posem un missatge d'error i acabem (die) l'execució de la pàgina web
 		if (!$resultat) {
     			$message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
     			$message .= 'Consulta realitzada: ' . $consulta;
     			die($message);
 		}
 	?>
 
	<!-- (3.1) Aquí mostrem la llista de països com a <ul> -->
	<?php
		echo "<h2>Llistat de països</h2>\n";
		echo "<ul>\n";
		# Bucle while: només mostrar el nom del país dins d'un <li>
		while ($registre = mysqli_fetch_assoc($resultat)) {
			echo "\t<li>" . htmlspecialchars($registre['Name']) . "</li>\n";
		}
		echo "</ul>\n";
	?>
 </body>
</html>
