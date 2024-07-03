<!-- Begin Page Content -->
<title>Criar/Editar</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card border border-dark shadow mb4">
        <input type="hidden" name="id">
        <div class="card-header" style="color: black;">
          <strong>Cadastro de Usuários</strong>
        </div>
        <div class="card-body">
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
        <div class="card-footer">
          <div class="row justify-content-end">
            <a href="#" class="btn btn-primary" onclick="SaveUser()">Salvar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const fetchProfiles = async () => {
    const response = await $.post("../php/back_users.php", {action:'load_profile'})
    return JSON.parse(response);
  }

  let profile;

  $(document).ready(function() {
    $('#phone').mask('(00) 00000-0000');
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('id');
    fetchProfiles().then(response => {
      let profileSelectize = $('#profile').selectize({
        options: response,
        valueField: 'id',
        labelField: 'profileName',
        searchField: ['profileName'],
        create: false
      })
      profile = profileSelectize[0].selectize;
      editUser(id);
    })
  });

  let status = $('#status').selectize({
    options: [
      {value: 1, title: 'Ativo'},
      {value: 0, title: 'Inativo'}
    ],
    valueField: 'value',
    labelField: 'title',
    searchField: ['title'],
    create: false
  });

  status = status[0].selectize;
  console.log('status', status)

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
    let data = {
      action: "list_user_id",
      idUser: args
    }

    let response = $.post("../php/back_users.php", data)
    .done(function (response) {
      response = JSON.parse(response);
      console.log('Profile:', profile);
      console.log('response:', response);
      $("input[name=id]").val(response.id);
      $("input[name=name]").val(response.name);
      $("input[name=phone]").val(response.phone);
      $("input[name=mail]").val(response.mail);
      $("input[name=user]").val(response.user);
      profile.setValue([response.idProfile]);
      status.setValue([response.status]);
    }).fail(() => {
      default_notification({ type: "danger", message: error });
    });
  }
  
</script>