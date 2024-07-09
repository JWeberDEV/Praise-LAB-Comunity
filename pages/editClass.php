<!-- Begin Page Content -->
<title>Criar/Editar Aula</title>
<input type="hidden" name="path" value="<?php echo __DIR__ ?>">
<div class="container-fluid">
  <div class="row justify-content-center">

    <div class="col-md-6 pt-3">
      <div class="card mb4">
        <div class="card-header" style="color: black;">
          <strong>Criação de aulas</strong>
        </div>
        <div class="card-body">
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-basic-data" role="tabpanel" aria-labelledby="tab-pills-basic-data">
              <div class="form-group">
                <div  class="row">
                  <div class="col-md-6">
                    <label class="label">Nome</label>
                    <input type="text" class="form-control" name="course">
                  </div>
                  <div class="col-md-6">
                    <label class="label">Descrição</label>
                    <input id="phone" type="text" class="form-control" name="description">
                  </div>
                  <div class="col-md-12 pt-3">
                    <div class="light-card shadow ">
                      <div class="upload-container p-2">
                        <div class="drag-area" id="drag-area">
                          <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                          <header>Arraste ou clique para adicionar um vídeo</header>
                          <span>OU</span>
                          <button id="browse-btn">Selecione o Arquivo</button>
                          <input type="file" id="file-input" hidden>
                        </div>
                        <button type="button" id="change-image" class="btn btn-primary">Alterar</button>
                        <div id="preview-container"></div>
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row justify-content-end">
            <a href="#" class="btn btn-primary" onclick="saveCourse()">Salvar</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>

</script>