<title>Usuários</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card border border-dark shadow mb4">
        <a href='?route=route2' class="btn btn-primary">Novo</a>
      </div>
    </div>
    <div class="col-md-10">
      <div class="card border border-dark shadow mb4">
        <div class="card-header" style="color: black; background-color: #454c53;">
          <strong>Usuários</strong>
        </div>
        <div class="card-body" style="background-color: #656c74;">
          <div class="list"></div>
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
      list_users();
    });

    function list_users() {
      $.post("../php/back_users.php", {action: "list_user"})
      .done(function(response) {
          $(".list").html(response);
      });
    }

</script>