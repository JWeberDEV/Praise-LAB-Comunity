<?php 

function pass_encriptor($text, $method = 'encrypt'){ 
      
  if($method == 'encrypt'){ 
      $text = base64_encode(openssl_encrypt($text, 'AES-256-CBC', '457jk9@','0', '1234567891011121')); 
  }elseif($method == 'decrypt'){ 
      $text = openssl_decrypt(base64_decode($text), 'AES-256-CBC', '457jk9@','0', '1234567891011121'); 
  }                 

  return $text; 
}

/**
 * Função que gera um número aleatório
 * 
 * @param $length Tamanho do hash a ser gerado
 * @return $code Hash de tamanho definido
 */
function random_code_generator($lenght) {
  $char = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $code = '';
  
  for ($i = 0; $i < $lenght; $i++) {
      $position = mt_rand(0, strlen($char) - 1);
      $code .= $char[$position];
  }
  
  return $code;
}

function permissions_check($args) {

  include ("../includes/_db.php");
  $query = $pdo->prepare("SELECT alterar FROM sealpermissoesperfil m
        JOIN sealrotinas r ON r.idrotina = m.idpagina
        WHERE m.idperfil = {$_SESSION['userAuth']['idPerfil']}
        AND r.nomerotina = '$args'
    ");
  $query->execute();
  $queryResult = $query->fetchAll(PDO::FETCH_OBJ);
  foreach ($queryResult as $key => $value) {
    $permission = $value->alterar;
  }

  return $permission;
}

?>