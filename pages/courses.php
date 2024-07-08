<title>Usuários</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow mb4 p-1">
        <div class="col">
          <a href='?route=route4' class="btn btn-primary">Novo Conteúdo</a>
        </div>
      </div>
    </div>
    <div class="col-md-10 pt-3">
      <div class="card border border-dark shadow mb4">
        <div class="card-header" style="color: black;">
          <strong>Cursos</strong>
        </div>
        <div class="card-body">
          <table class="table table-hover">
            <thead class="thead-light">
              <th>Curso</th>
              <th>Descrição</th>
              <th colspan="3" class="text-right">Ações</th>
            </thead>
            <tbody class="list">

            </tbody>
          </table>
        </div>
        
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  listCourses();
});

function listCourses() {
  $.post("../php/back_courses.php", {action: "list_courses"})
  .done(function(response) {
    $(".list").html(response);
  });
}

</script>