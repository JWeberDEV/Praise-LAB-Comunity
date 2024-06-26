<title>Usuários</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow mb4 p-1">
        <div class="col">
          <a href='?route=route2' class="btn btn-primary">Novo Usuário</a>
        </div>
      </div>
    </div>
    <div class="col-md-10 pt-3">
      <div class="card border border-dark shadow mb4">
        <div class="card-header" style="color: black;">
          <strong>Usuários</strong>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead class="thead-light">
              <th>Nome</th>
              <th>Email</th>
              <th>Perfil</th>
              <th>Status</th>
              <th colspan="3" class="text-right">Ações</th>
            </thead>
            <tbody class="list"></tbody>
          </table>
        </div>
        
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
      listUsers();
    });

    function listUsers() {
      $.post("../php/back_users.php", {action: "list_user"})
      .done(function(response) {
        $(".list").html(response);
      });
    }

  const deleteUser = (args) => {
    let data = {
      action: "delete_server",           
      idUser: args
    }

    let html = 
    `<i style="font-size: 130px; color: #edb72c;" class="fas fa-exclamation-triangle"></i>
    </br></br>
    <div class="alert alert-danger" role="alert">
      Tem Certeza de que deseja excluir este usuario?
    </div>
    `;

    Swal.fire({
      html: html,
      customClass: 'swal-height',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Confirmar',
      showCancelButton: true,
      allowEnterKey: true,
      confirmButtonColor: "#4e73df",
      width: 500,
      preConfirm:() => {
        $.post("../php/back_users.php",data)
        .done(response => {
          response = JSON.parse(response);
          if (response.return == 1) {
            default_notification({type: "success", message: response.message});
            listUsers();
          }else{
            default_notification({type: "danger", message: response.message});
          }
        });
      },
    });
  }

</script>