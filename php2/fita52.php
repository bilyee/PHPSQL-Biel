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
    <h1>Buscador de ciudades V2 - PHP</h1>
    <form method="GET">
        <input name="pais" />
        <input type="submit" value="Buscar"/>
    </form>
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
      
      try {
        //preparem i executem la consulta
        $pais = trim($_GET['pais'] ?? '');
        if ($pais !== '') {
          $query = $pdo->prepare("SELECT co.Name as Country, col.Language as Language, col.isOfficial as Official, col.Percentage as Porcentaje
                                  FROM country co
                                  JOIN countrylanguage col ON co.Code = col.CountryCode
                                  WHERE co.Name LIKE '%$pais%'");
          $query->execute();
        }
      } catch (PDOException $e) {
        // alternativa: obtenim missatge d'error de $query
        $err = $query->errorInfo();
        if ($err[0]!='00000') {
          echo "\nPDO::errorInfo():\n";
          die("Error accedint a dades: " . $err[2]);
        }  
      }
      
      //anem agafant les fileres una a una
      $row = $query->fetch();
      while ( $row ) {
        echo $row['Country']."<br/>" . $row['Language']. "<br/>" . $row['Official'] . "<br/>" . $row['Porcentaje'] . "<br/>----------------------------------------------------<br/>";
        $row = $query->fetch();
      }

      //versió alternativa amb foreach
      /*foreach ($query as $row) {
        echo $row['i']." - " . $row['a']. "<br/>";
      }*/

      //eliminem els objectes per alliberar memòria 
      unset($pdo); 
      unset($query) 
    ?>
  </body>
</html>