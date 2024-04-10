<?php 
  // conexão com postgree
  try {
    $host = '165.73.244.161';
    $dbname = 'iglobal_tmp';
    $username = 'temp_access';
    $password = 'G0tJ=[1DD:H:7gt{';
    // $host = '127.0.0.1';
    // $dbname = 'seal_db';
    // $username = 'postgres';
    // $password = 'admin';
      
    // Ccria uma instancia PDO
   $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password, 
      [
        // configura os atributos para o PDO
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      ]
    );

  }catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "conexão com postgres";
  }

?>