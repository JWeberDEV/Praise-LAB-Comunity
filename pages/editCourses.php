<!-- Begin Page Content -->
<title>Criar/Editar</title>
<div class="container-fluid">
  <div class="row justify-content-center">

    <div class="col-md-6 pt-3">
      <div class="card mb4">
        <div class="card-header" style="color: black;">
          <strong>Criação de cursos/aulas</strong>
        </div>
        <div class="card-body">
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-basic-data" role="tabpanel" aria-labelledby="tab-pills-basic-data">
              <form class="form-user" autocomplete="off">
                  <input type="hidden" name="idUser">
                  <div class="form-group">
                    <div  class="row">
                      <div class="col-md-6">
                        <label class="label">Nome do curso</label>
                        <input type="text" class="form-control" name="name">
                      </div>
                      <div class="col-md-6">
                        <label class="label">Descrição</label>
                        <input id="phone" type="text" class="form-control" name="description">
                      </div>
                      <div class="col-md-12 pt-3">
                        <div class="light-card shadow">
                          <div class="upload-container p-2">
                            <div class="drag-area" id="drag-area">
                              <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                              <header>Drag & Drop to Upload File</header>
                              <span>OR</span>
                              <button id="browse-btn" class="btn">Browse File</button>
                              <input type="file" id="file-input" hidden>
                            </div>
                          </div>  
                        </div>
                      </div>
                    </div>
                  </div>
              </form>
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
$(document).ready(function() {
  const dragArea = $('#drag-area');
  const browseBtn = $('#browse-btn');
  const fileInput = $('#file-input');

  browseBtn.on('click', function() {
    fileInput.click();
  });

  fileInput.on('change', function(event) {
    const files = event.target.files;
    handleFiles(files);
  });

  dragArea.on('dragover', function(event) {
    event.preventDefault();
    dragArea.addClass('active');
  });

  dragArea.on('dragleave', function() {
    dragArea.removeClass('active');
  });

  dragArea.on('drop', function(event) {
    event.preventDefault();
    dragArea.removeClass('active');
    const files = event.originalEvent.dataTransfer.files;
    handleFiles(files);
  });

  // function handleFiles(files) {
  //   $.each(files, function(index, file) {
  //     console.log(file.name);
  //   });
  //   // Add your file handling logic here
  // }
});

const SaveUser = () => {
  let data = {
    action: 'save_user',
    id: $("input[name=id]").val(),
    name: $("input[name=name]").val(),
    phone: $("input[name=phone]").val(),
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


</script>