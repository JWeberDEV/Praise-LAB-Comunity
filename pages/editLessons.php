<!-- Begin Page Content -->
<title>Criar/Editar</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow mb4 p-1">
        <div class="row justify-content-start">
          <div class="col-1">
            <a href='?route=route2' class="btn btn-primary">Curso</a>
          </div>
          <div class="col-1">
            <a href='?route=route2' class="btn btn-primary">Aula</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-10 pt-3">
      <div class="card mb4">
        <input type="hidden" name="id">
        <div class="card-header" style="color: black;">
          <strong>Criação de cursos/aulas</strong>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <label class="label">Nome do curso</label>
              <input type="text" class="form-control" name="name">
            </div>
            <div class="col-md-3">
              <label class="label">Descrição</label>
              <input id="phone" type="text" class="form-control" name="description">
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

</script>