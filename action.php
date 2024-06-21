<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . "/php/_db.php");
require_once(__DIR__ . "/php/_session.php");
require_once(__DIR__ . "/php/functions.php");

function register_msg($title, $msg, $class)
{
  $_SESSION['msg']['title'] = $title;
  $_SESSION['msg']['msg'] = $msg;
  $_SESSION['msg']['className'] = $class;
}


$data = json_decode(json_encode($_REQUEST));

switch ($data->action) {
  case "login":
    $response = (object) [];

    $data = [
      'user' => $data->user,
      'password' => md5($data->password),
    ];

    // busca pelo usuáruio no banco
    $stmt = $pdo->prepare("SELECT id,idProfile,name,user,status,mail FROM user WHERE user = :user AND password = :password");
    $execute = $stmt->execute($data);
    $result = $stmt->fetchAll();
    $numRows = count($result);

    if ($numRows > 0) {
      $user = $result[0];
      if ($user->status != 0) {
        // Define as variáveis de seção e inicia a seção
        $_SESSION['userAuth']['id']  = $user->id;
        $_SESSION['userAuth']['idProfile']  = $user->idProfile;
        $_SESSION['userAuth']['name'] = $user->name;

        // if ($user->namidProfile == 1) {
        //   $route = '';
        // }

        $response->message = "Ok";
        $response->return = 1;

      } else {
        $response->return = 0;
        $response->message = "Usuário inativo contate um administrador!";
      }
    } else {
      $response->return = 0;
      $response->message = "Usuário ou senha inválidos!";
    }

    echo (json_encode($response));
    break;

  case 'redefine_password':
    require ("./libs/PHPMailer/src/Exception.php");
    require ("./libs/PHPMailer/src/PHPMailer.php");
    require ("./libs/PHPMailer/src/SMTP.php");

    $data = (Object) $_REQUEST;
    $response = (object) [];

    $Data = [
      'mail' => $data->mail
    ];

    $stmt = $pdo->prepare(" SELECT idusuario,nomeusuario FROM sealusuarios WHERE email = :mail");
    $stmt->execute($Data);
    $result = $stmt->fetchAll();
    $numRows = count($result);

    if ($numRows > 0) {
      
      foreach ($result as $key => $value) {
        $id = ($value->idusuario);
        $name = ($value->nomeusuario);
      }

      $resh = random_code_generator(5);

      $ID = [
        'id' => $id
      ];

      $stmt = $pdo->prepare("UPDATE sealusuarios SET senha = '".md5($resh)."' WHERE idusuario = :id");
      $stmt->execute($ID);

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
      $mail->addAddress($data->mail, 'Usuario'); // Add a recipient
      $mail->Subject = 'Recuperação de Senha';
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
                                                      <strong>Olá, $name!</strong>
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
                                              <strong> Geramos uma nova senha para você! </strong>
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
                                              <p style='text-align:center; font-size:26px; color: #1578b4;'><strong>$resh</strong></p>
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
        $response->mensagem = "Um e-mail foi enviado para $data->mail. Acesse seu e-mail para processeguir com o processo";
      }
    }else{
      $response->retorno = 0;
      $response->mensagem = "O e-mail <strong>[$data->mail]</strong> digitado não foi encontrado em nosso sistema !";
    }

    echo json_encode($response);
    break;

  case 'logout':
    logout();
    break;
}
