<?php
// Inclusão da conexão com o Banco e com as funções gerais
include_once(__DIR__ . "../_protect.php");
include_once(__DIR__ . "../_db.php");
include_once(__DIR__ . "../functions.php");

// Variáveis
$data = (Object) $_REQUEST;
$response = (object) [];
switch ($data->action){
  case 'save_class':
    if ($data->idClass != '') {

      // Decalara os Valores para usar o prepare
      $arrayData = [
        'id' => $data->idClass,
        'class' => "$data->class",
        'description' => "$data->description",
        'file' => "$data->file",
        'sessionUser' => $_SESSION['userAuth']['id']
      ];

      // Preapara a query de fato
      $stmt = $pdo->prepare(
        "UPDATE class SET
          name = :class,
          description = :description,
          fileName = :file,
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

      $data->idCourse = trim($data->idCourse, "'");

      $arrayData = [
        'id' => $id,
        'idCourse' => "$data->idCourse",
        'class' => "$data->class",
        'description' => "$data->description",
        'file' => "$data->video",
        'sessionUser' => $_SESSION['userAuth']['id']
      ];

      $stmt = $pdo->prepare("INSERT INTO class (id,idCourse,name,description,fileName,creationDate,createdBy)
      VALUES (:id, :idCourse, :class, :description, :file, NOW(), :sessionUser)");

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
  case 'list_classes':

    $data->idCourse = trim($data->idCourse, "'");

    $arrayData = [
      'id' => $data->idCourse,
    ];

    $stmt = $pdo->prepare("SELECT 
        s.id,
        s.idCourse,
        s.name,
        s.description
      FROM class s
      JOIN course c ON c.id = s.idCourse
      WHERE s.deletedDate IS NULL
      AND s.idCourse = :id
      ORDER BY NAME
    ");
    $stmt->execute($arrayData);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $list = '';
    foreach ($results as $key => $value) {
      if ($value) {
        $list .= "<tr>
            <td>" . $value['name'] . "</td>
            <td>" . $value['description'] . "</td>
            <td class='actions text-right'>
              <button type='button' class='btn btn-warning btn-sm btn-just-ico' data-toggle='tooltip' data-placement='top' title='Editar' onclick=\"editClass('".$value['id']."')\">
                <i class='fas fa-pencil-alt'></i>
              </button>
              <button type='button' class='btn btn-danger btn-sm btn-just-ico' data-toggle='tooltip' data-placement='top' title='Excluir' onclick=\"deleteClass('".$value['id']."')\">
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
  case 'show_class_id':

    $id = "$data->idCourse";

    $stmt = $pdo->prepare("SELECT 
        c.name,
        c.description,
        c.fileName
      FROM class c
      WHERE c.id = '$id'
      ");
    $stmt->execute() or die ("Error executing" . $stmt->error);
    $results = $stmt->fetch();

    print_r(json_encode($results));
    break;
  case 'show_course_name':

    $id = "$data->idCourse";

    $stmt = $pdo->prepare("SELECT 
        c.name
      FROM course c
      WHERE c.id = $id
      ");
    $stmt->execute() or die ("Error executing" . $stmt->error);
    $results = $stmt->fetch();

    print_r(json_encode($results));
    break;
  case 'delete_class':

    $arrayData = [
      'id' => "$data->id"
    ];

    $stmt = $pdo->prepare("UPDATE class SET deletedBy = '{$_SESSION['userAuth']['id']}', deletedDate = NOW() WHERE id = :id");
    $execute = $stmt->execute($arrayData);

    if ($execute) {
      $response->return = 1;
      $response->message = "Registro Deletado com sucesso!";
    } else {
      $response->return = 0;
      $response->message = "Erro ao deletar o registro!";
    }

    echo json_encode($response);
    break;
}
?>