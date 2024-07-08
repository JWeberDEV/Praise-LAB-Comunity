<?php
// Inclusão da conexão com o Banco e com as funções gerais
include_once(__DIR__ . "../_protect.php");
include_once(__DIR__ . "../_db.php");
include_once(__DIR__ . "../functions.php");

// Variáveis
$data = (Object) $_REQUEST;
$response = (object) [];
switch ($data->action){
  case 'save_course':
    if (isset($data->id) != '') {

      // Decalara os Valores para usar o prepare
      $arrayData = [
        'course' => "$data->course",
        'description' => "$data->description",
        'thumb' => "$data->thumb",
        'sessionUser' => $_SESSION['userAuth']['id']
      ];

      // Preapara a query de fato
      $stmt = $pdo->prepare(
        "UPDATE course SET
          idProfile = :profile,
          name = :name,
          description = :description,
          thumb = :thumb,
          editedBy = :sessionUser,
          editionDate = NOW()
        WHERE id = :id"
      );

      // executa a query
      $execute = $stmt->execute($arrayData);

      if ($execute) {
        $response->return = 1;
        $response->message = "Registro editado com sucesso!";
      } else {
        $response->return = 0;
        $response->message = "Erro ao editar o registro!";
      }
    } else {

      $id = random_code_generator(32);

      $arrayData = [
        'id' => "$id",
        'course' => "$data->course",
        'description' => "$data->description",
        'thumb' => "$data->thumb",
        'sessionUser' => $_SESSION['userAuth']['id']
      ];

      $stmt = $pdo->prepare("INSERT INTO course (id,name,description,fileName,creationDate,createdBy)
      VALUES (:id, :course, :description, :thumb, NOW(), :sessionUser)");

      $execute = $stmt->execute($arrayData);

      if ($execute) {
        $response->return = 1;
        $response->message = "Registro criado com sucesso!";
      } else {
        $response->return = 0;
        $response->message = "Erro ao criar o registro!";
      }
    }

    echo (json_encode($response));
    
    break;
  case 'list_courses':
    $stmt = $pdo->prepare("SELECT 
        id,
        name,
        description
      FROM course
      WHERE deletedDate IS NULL
      ORDER BY name
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $list = '';
    foreach ($results as $key => $value) {
      print_r($value);
      if ($value) {
        $list .= "<tr>
            <td>" . $value['name'] . "</td>
            <td>" . $value['description'] . "</td>
            <td class='actions text-right'>
              <a type='button' class='btn btn-warning btn-sm btn-just-ico' data-toggle='tooltip' title='Editar' href=\"?route=route4&id='".$value['id']."'\">
                <i class='fas fa-pencil-alt'></i>
              </a>
              <button type='button' class='btn btn-danger btn-sm btn-just-ico' data-toggle='tooltip' data-placement='top' title='Excluir' onclick=\"deleteCourse('".$value['id']."')\">
                <i class='fas fa-trash'></i>
              </button>
            </td>
          </tr>
        ";
      }else {
        $list =
          "<tr>
            <td style='padding:10px;' colspan='8'>
              <a href='#' style='color:#ED6663;font-style:italic;'><i class='fas fa-info-circle'></i> Nenhum registro encontrado!</a>
            </td>
          </tr>
        ";
      }
    }

    echo $list;
    break;
    case 'list_course_id':
      $arrayData = [
        'idUser' => "$data->idUser"
      ];
  
      $id = "{$arrayData['idUser']}";
  
      $stmt = $pdo->prepare("SELECT * FROM course WHERE id = $id");
      $stmt->execute();
      $results = $stmt->fetch();
  
      print_r(json_encode($results));
      break;
  

}

?>