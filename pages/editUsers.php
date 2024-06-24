<!-- Begin Page Content -->
<title>Criar/Editar</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card border border-dark shadow mb4">
        <input type="hidden" name="id">
        <div class="card-header" style="color: black; background-color: #454c53;">
          <strong>Cadastro de Usuários</strong>
        </div>
        <div class="card-body" style="background-color: #656c74;">
          <div class="row">
            <div class="col-md-3">
              <label class="label">Nome</label>
              <input type="text" class="form-control" name="name">
            </div>
            <div class="col-md-3">
              <label class="label">Telefone</label>
              <input id="phone" type="text" class="form-control" name="phone">
            </div>
            <div class="col-md-3">
              <label class="label">Email</label>
              <input type="text" class="form-control" name="mail">
            </div>
            <div class="col-md-3">
              <label class="label">Usuário</label>
              <input type="text" class="form-control" name="user">
            </div>
            <div class="col-md-3">
              <label class="label">Senha</label>
              <input type="text" class="form-control" name="password">
            </div>
            <div class="col-md-3">
              <div class="control-group">
                <label for="profile" class="label">Perfil</label>
                <select id="profile" class="profile" placeholder="Selecione..."></select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="control-group">
                <label for="status" class="label">Status</label>
                <select id="status" class="status" placeholder="Selecione..."></select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer" style="background-color: #454c53;">
          <div class="row justify-content-end">
            <a href="#" class="btn btn-primary" onclick="SaveUser()">Salvar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#phone').mask('(00) 00000-0000');
  });

  $(function(){
    $.post("../php/back_users.php", {action:'load_profile'})
    .done(function (response) {
      options = JSON.parse(response);
      $('#profile').selectize({
        options: options,
        valueField: 'id',
        labelField: 'profileName',
        searchField: ['profileName'],
        create: false
      });
    }).fail(() => {
      default_notification({ type: "danger", message: 'Ocorreu um problema ao carregar os perfis, entre em contato com o administrador!' });
    });
  });

  $('#status').selectize({
    options: [
      {value: 1, title: 'Ativo'},
      {value: 0, title: 'Inativo'}
    ],
    valueField: 'value',
    labelField: 'title',
    searchField: ['title'],
    create: false
  });

  const SaveUser = () => {
    let data = {
            action: 'save_user',
            id: $("input[name=id]").val(),
            name: $("input[name=name]").val(),
            phone: $("input[name=phone]").val(),
            mail: $("input[name=mail]").val(),
            user: $("input[name=user]").val(),
            password: $("input[name=password]").val(),
            profile: $("#profile").val(),
            status: $("#status").val(),
        }

    if (!$("input[name=id]").val()) {
      // Verifica a obrigatoriedade dos campos
      if ($("input[name=user]").val() == "" || $("select[name=status]").val() == "") {
        default_notification({type: "danger", message:"Os Campos obrigatórios precisam ser preenchidos"});
        return;
      }
    }

    $.post("../php/back_users.php",data)
    .done(response => {
      response = JSON.parse(response);
      if (response.return == 1) {
          default_notification({type: "success", message: response.message});
          header_url({subMenu: 'users'})
      }else{
          default_notification({type: "danger", message: response.message});
      }
    });
  }

  const editUser = (args) =>{
    $("select[name=profile]").removeAttr( "title" );
    $("select[name=statusUser]").removeAttr( "title" );

    reset_form('.form-user');
    let data = {
      action: "list_user_id",
      idUser: args
    }

    let response = $.post("../php/back_users.php", data)
      .done(function (response) {
        response = JSON.parse(response);
        $('#modal-new-user').modal('show');
        $("input[name=idUser]").val(response.idusuario);
        $("input[name=fullName]").val(response.nomeusuario);
        $("input[name=mail]").val(response.email);
        $("input[name=user]").val(response.usuario);
        $("select[name=profile]").val(response.idperfil+"").selectpicker('destroy').selectpicker('render');
        $("select[name=statusUser]").val(response.estado+"").selectpicker('destroy').selectpicker('render');
      }).fail(() => {
        default_notification({ type: "danger", message: error });
      });
      list_process();
  }


</script>