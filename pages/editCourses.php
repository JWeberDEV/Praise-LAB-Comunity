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
                    <div class="light-card shadow ">
                      <div class="upload-container p-2">
                        <div class="drag-area" id="drag-area">
                          <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                          <header>Drag & Drop to Upload File</header>
                          <span>OR</span>
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
            <a href="#" class="btn btn-primary" onclick="saveUser()">Salvar</a>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function() {
  let formData = new FormData(); // Initialize FormData in the outer scope
  $('#preview-container').hide();
  $('#change-image').hide();
  const dragArea = $('#drag-area');
  const browseBtn = $('#browse-btn');
  const fileInput = $('#file-input');
  const previewContainer = $('#preview-container');
  const changeImage = $('#change-image');

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

  changeImage.on('click', function(event) {
    $('#preview-container').hide();
    $('#change-image').hide();
    $('.drag-area').show();
    previewContainer.empty();
    formData = new FormData(); // Reset FormData when changing image
  });

  function handleFiles(files) {
    $.each(files, function(index, file) {
      if (file.type.startsWith('image/')) {
        formData.append('files[]', file);
        const reader = new FileReader();
        reader.onload = function(event) {
          const img = $('<img>').attr('src', event.target.result).addClass('thumbnail');
          previewContainer.append(img);
        }
        reader.readAsDataURL(file);
        $('#preview-container').show();
        $('#change-image').show();
        $('.drag-area').hide();
      }
    });
  }
});

  const saveUser = () => {

  // let data = {
  //   action: 'save_user',
  //   id: $("input[name=id]").val(),
  //   name: $("input[name=name]").val(),
  //   phone: $("input[name=phone]").val(),
  // }

  // if (!$("input[name=id]").val()) {
  //   // Verifica a obrigatoriedade dos campos
  //   if ($("input[name=user]").val() == "" || $("select[name=status]").val() == "") {
  //     default_notification({type: "danger", message:"Os Campos obrigatórios precisam ser preenchidos"});
  //     return;
  //   }
  // }

  // $.post("../php/back_users.php",data)
  // .done(response => {
  //   response = JSON.parse(response);
  //   if (response.return == 1) {
  //       default_notification({type: "success", message: response.message});
  //       header_url({subMenu: 'users'})
  //   }else{
  //       default_notification({type: "danger", message: response.message});
  //   }
  // });
}


</script>