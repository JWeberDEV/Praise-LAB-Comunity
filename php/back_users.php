<?php
// Inclusão da conexão com o Banco e com as funções gerais
include_once(__DIR__ . "/../php/_protect.php");
include_once(__DIR__ . "/../php/_db.php");
include_once(__DIR__ . "/../php/functions.php");

// Variáveis
$data = (Object) $_REQUEST;
$response = (object) [];
switch ($data->action) {

  case 'logout':
    session_start();
    session_destroy();
    exit;
    break;

  case 'save_user':
    if ($data->idUser != '') {

      // Decalara os Valores para usar o prepare
      $arrayData = [
        'idUser' => "$data->idUser",
        'idPerfil' => "$data->perfil",
        'statusUser' => "$data->statusUser",
        'email' => "$data->email",
        'fullName' => "$data->fullName",
        'user' => $data->user,
      ];

      // Preapara a query de fato
      $stmt = $pdo->prepare(
        "UPDATE sealusuarios SET
          idperfil = :idPerfil,
          estado = :statusUser,
          nomeusuario = :fullName,
          email = :email,
          usuario = :user,
          alteradopor = {$_SESSION['userAuth']['idUsuario']},
          alteradoem = NOW()
        WHERE idusuario = :idUser"
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
      // Cria uma senha temporária
      $resh = random_code_generator(5);

      // Decalara os Valores para usar o prepare
      $arrayData = [
        'idPerfil' => "$data->perfil",
        'statusUser' => "$data->statusUser",
        'email' => "$data->email",
        'fullName' => "$data->fullName",
        'user' => $data->user,
        'password' => md5("$resh"),
        'idUsuarioSessao' => $_SESSION['userAuth']['idUsuario']
      ];
      // Preapara a query de fato
      $stmt = $pdo->prepare("INSERT INTO sealusuarios (idperfil,estado,email,nomeusuario,usuario,senha,criadopor)
      VALUES (:idPerfil, :statusUser, :email, :fullName, :user, :password,:idUsuarioSessao)");

      // executa a query
      $execute = $stmt->execute($arrayData);

      if ($execute) {
        $response->return = 1;
        $response->message = "Registro criado com sucesso! Um e-mail será enviado para: <b> $data->email </b>";

        send_password([$resh, $data->email, $data->user, $data->fullName]);
      } else {
        $response->return = 0;
        $response->message = "Erro ao criar o registro!";
      }
    }

    echo (json_encode($response));
    break;

  case 'list_user':
    $stmt = $pdo->prepare("SELECT u.idusuario,p.nomeperfil,u.nomeusuario,u.usuario,u.email,u.estado 
      FROM sealusuarios u 
      JOIN sealperfil p ON p.idperfil = u.idperfil
      WHERE u.deletadoem IS NULL
      ORDER BY u.nomeusuario
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $list = '';
    foreach ($results as $key => $value) {
      if ($value) {
        $list .= "<tr>
            <td>" . $value['nomeusuario'] . "</td>
            <td>" . $value['email'] . "</td>
            <td>" . $value['nomeperfil'] . "</td>
            <td>" . ($value['estado'] == 1 ? 'Ativo' : 'Inativo') . "</td>
            <td class='actions text-right'>
              <button type='button' class='btn btn-warning btn-sm btn-just-ico' data-toggle='tooltip' title='Editar' onclick=\"header_url([{parameter: 'subMenu', value: 'edit'},{parameter: 'id', value: '".$value['idusuario']."'}])\">
                <i class='fas fa-pencil-alt'></i>
              </button>
              <button type='button' class='btn btn-danger btn-sm btn-just-ico' data-toggle='tooltip' data-placement='top' title='Excluir' onclick='delete_user(".$value['idusuario'].")'>
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
  
  case 'list_user_id':
    $arrayData = [
      'idUser' => $data->idUser
    ];

    $stmt = $pdo->prepare("SELECT * FROM sealusuarios WHERE idusuario = :idUser");
    $stmt->execute($arrayData);
    $results = $stmt->fetch();

    print_r(json_encode($results));
    break;

  case 'delete_server':

    $arrayData = [
      'idUser' => $data->idUser
    ];

    $stmt = $pdo->prepare("UPDATE sealusuarios SET deletadopor = {$_SESSION['userAuth']['idUsuario']}, deletadoem = NOW(), estado = 0 WHERE idusuario = :idUser");
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

  case 'change_password':

      $arrayData = [
        'idUser' => $data->id,
        'password' => md5("$data->pass2")
      ];
  
      $stmt = $pdo->prepare("UPDATE sealusuarios SET senha = :password WHERE idusuario = :idUser");
      $execute = $stmt->execute($arrayData);
  
      if ($execute) {
        $response->return = 1;
        $response->message = "Senha Alterada com sucesso!";
      } else {
        $response->return = 0;
        $response->message = "Erro ao alterar senha!";
      }
  
      echo json_encode($response);
      break;
    
  case 'load_processes':
    // Decalara os Valores para usar o prepare
    $idUser = isset($data->idUser) ?: "";
    if ($idUser != "") {
      $arrayData = [
        'idUser' => $idUser,
      ];
    }
    // Prepara a query
    $query = "SELECT idpessoa,
      cdpessoa,
      nmpessoa
      FROM pessoa
    ";

    if ($idUser != "") {
      $query .= "WHERE idpessoa NOT IN (SELECT idpessoa FROM sealusuariospessoa WHERE idusuario = :idUser)";
    }

    $stmt = $pdo->prepare($query);

    if ($idUser != "") {$stmt->execute($arrayData);}else{$stmt->execute();}

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arrayResult = [];
    foreach ($results as $key => $value) {
      $arrayResult[] = [
        'idpessoa' => $value['idpessoa'],
        'cdpessoa' => $value['cdpessoa'],
        'nmpessoa' => $value['nmpessoa'],
      ];
    }

    echo(json_encode($arrayResult));
    break;

  case 'save_process':
    // Decalara os Valores para usar o prepare
    $arrayData = [
      'idUser' => "$data->idUser",
      'idProcess' => $data->idProcess
    ];

    // Prepara a query
    $stmt = $pdo->prepare(
      "INSERT INTO sealusuariospessoa(idusuario, idpessoa, criadoem) VALUES (:idUser, :idProcess, NOW())"
    );
  
    // executa a query
    $execute = $stmt->execute($arrayData);

    if ($execute) {
      $response->return = 1;
      $response->message = "Processo vinculado com sucesso!";
    } else {
      $response->return = 0;
      $response->message = "Erro ao vincular o processo!";
    }
    
    echo json_encode($response);
    break;

  case 'list_process':
    $arrayData = [
      'idUser' => $data->idUser
    ];

    $stmt = $pdo->prepare("SELECT u.idpessoa,u.idusuario,p.cdpessoa,p.nmpessoa FROM sealusuariospessoa u
      JOIN pessoa p ON u.idpessoa = p.idpessoa
      WHERE u.idusuario = :idUser
      ORDER BY u.criadoem
    ");
    $stmt->execute($arrayData);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $list = '';
    foreach ($results as $key => $value) {
      if ($value) {
        $list .= "<tr>
            <td>" . $value['cdpessoa'] . "</td>
            <td>" . $value['nmpessoa'] . "</td>
            <td class='actions text-right'>
              <button type='button' class='btn btn-primary btn-sm btn-just-ico' data-toggle='tooltip' data-placement='top' title='Excluir' onclick='remove_process({idPessoa:".$value['idpessoa']." , idUsuario:".$value['idusuario']."})'>
                <i class='fas fa-minus'></i>
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

  case 'remove_process':
    $arrayData = [
      'idUser' => $data->idUser,
      'idPeople' => $data->idPeople
    ];

    $stmt = $pdo->prepare("DELETE FROM sealusuariospessoa WHERE idusuario = :idUser AND idpessoa = :idPeople");
    $execute = $stmt->execute($arrayData);

    if ($execute) {
      $response->return = 1;
      $response->message = "Processo removido com sucesso!";
    } else {
      $response->return = 0;
      $response->message = "Erro ao remover o processo!";
    }

    echo json_encode($response);
    break;
}

function send_password($args) {
  require ("./libs/PHPMailer/src/Exception.php");
  require ("./libs/PHPMailer/src/PHPMailer.php");
  require ("./libs/PHPMailer/src/SMTP.php");

  $response = new stdClass();

  try {
    // [$resh, $data->email, $data->user, $data->fullName]
    $mail = new PHPMailer(true);
    // $mail->SMTPDebug = 4;
    $mail->isHTML(true); // Set email format to HTML
    $mail->isSMTP(true); // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
    $mail->Port = 587; // TCP port to connect to
    $mail->CharSet = 'utf-8';
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
    $mail->Username = 'portoalegre@sealservice.com.br'; // SMTP username
    $mail->Password = 'seal2019'; // SMTP password
    $mail->setFrom('portoalegre@sealservice.com.br', '');
    $mail->FromName = 'Seal Service';
    $mail->addAddress($args[1], 'Usuario'); // Add a recipient
    $mail->Subject = 'Novo Usuário';
    $mail->Body    = "
      <!DOCTYPE html>
      <html lang='en'>
      <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Document</title>
      </head>
      <body>
      <div style='background-color:#f7f7f7;margin:0;padding:0'>
        <div style='display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden'>@ Recuperação de Senha</div>
        <div style='background-color:#f7f7f7'>
          <div style='margin:0px auto;max-width:600px'>
            <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
              <tbody>
                <tr>
                  <td style='direction:ltr;font-size:0px;padding:10px;padding-left:40px;padding-right:40px;text-align:center;vertical-align:top;border-collapse:collapse'></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div style='border:1px solid #d9d9d9;background:white;background-color:white;margin:0px auto;max-width:600px'>
            <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:white;background-color:white;width:100%;border-collapse:collapse'>
              <tbody>
                <tr>
                  <td style='direction:ltr;font-size:0px;padding:20px 0;padding-bottom:30px;text-align:center;vertical-align:top;border-collapse:collapse'>
                    <div style='margin:0px auto;max-width:600px'>
                      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
                        <tbody>
                          <tr>
                            <td style='direction:ltr;font-size:0px;padding:20px 0;padding-left:40px;padding-right:40px;padding-top:0;text-align:center;vertical-align:top;border-collapse:collapse'>
                              <div style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
                                <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;border-collapse:collapse' width='100%'>
                                  <tbody>
                                    <tr>
                                      <img height='auto' style='padding-left:20%;display:block;outline:none;text-decoration:none;width:60%;line-height:100%' src='../img/logo.svg'>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div style='margin:0px auto;max-width:600px'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
                          <tbody>
                            <tr>
                              <td style='direction:ltr;font-size:0px;padding:0;padding-left:0;padding-right:0;text-align:center;vertical-align:top;border-collapse:collapse'>
                                <div style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
                                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;border-collapse:collapse' width='100%'>
                                    <tbody>
                                      <tr>
                                        <td align='center' style='font-size:0px;padding:0 0;word-break:break-word;border-collapse:collapse'>
                                          <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px'>
                                            <tbody>
                                              <tr>
                                                <td align='center' style='font-size:0px;padding:0px;word-break:break-word;width:600px;border-collapse:collapse;background-color: #1578b4;'>
                                                  <div style='font-family:sans-serif;font-size:28px;line-height:250%;text-align:center;color:white;'>
                                                    <strong>Olá, $args[3]!</strong>
                                                  </div>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div style='margin:0px auto;max-width:600px'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
                          <tbody>
                            <tr>
                              <td style='direction:ltr;font-size:0px;padding:20px 0;padding-left:40px;padding-right:40px;padding-top:60px;text-align:center;vertical-align:top;border-collapse:collapse'>
                                <div style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
                                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;border-collapse:collapse' width='100%'>
                                    <tbody>
                                      <tr>
                                        <td align='center' style='font-size:0px;padding:0px;word-break:break-word;border-collapse:collapse'>
                                          <div style='font-family:sans-serif;font-size:28px;line-height:250%;text-align:center;color:#1578b4'>
                                            <strong> Um usuário foi criado para Você! </strong>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div style='margin:0px auto;max-width:600px'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
                          <tbody>
                            <tr>
                              <td style='direction:ltr;font-size:0px;padding:20px 0;padding-left:40px;padding-right:40px;text-align:center;vertical-align:top;border-collapse:collapse'>
                                <div style='font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
                                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;border-collapse:collapse' width='100%'>
                                    <tbody>
                                      <tr>
                                        <td align='center' style='font-size:0px;padding:0px;word-break:break-word;border-collapse:collapse'>
                                          <div style='background: #f8f9fc; margin-left:20px; Height: 20%; width:25%; margin:auto;'>
                                            <p style='font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:26px; color: #1578b4;'>Usuário: <strong>$args[2]</strong></p>
                                            <p style='font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:26px; color: #1578b4;'>Senha: <strong>$args[0]</strong></p>
                                          </div>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>                                 
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div style='margin:0px auto;max-width:600px'>
                  <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;border-collapse:collapse'>
                    <tbody>
                      <tr>
                        <td style='direction:ltr;font-size:0px;padding:20px 0;padding-left:40px;padding-right:40px;padding-top:30px;text-align:center;vertical-align:top;border-collapse:collapse'></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>    
      </body>
      </html>
    ";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
      $response->retorno = 0;
      $response->mensagem = "O E-mail não pode ser enviado!";
      $response->erro = "$mail->ErrorInfo";
    } else {
      $response->retorno = 1;
      $response->mensagem = "Um e-mail foi enviado para $args[1]. Acesse seu e-mail para processeguir com o processo";
    }

  } catch (Exception $th) {
    throw $th;
  }    

}