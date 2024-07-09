<!-- Begin Page Content -->
<title>Criar/Editar Cursos</title>
<input type="hidden" name="id">
<div class="container-fluid">
  <div class="row justify-content-center">

    <div class="col-md-6 pt-3">
      <div class="card mb4">
        <div class="card-header" style="color: black;">
          <strong>Criação de cursos/módulos</strong>
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
                          <header>Arraste ou clique para adicionar uma imagem</header>
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
const formData = new FormData();
let uploadedFiles = []; // Global variable to hold the uploaded files
let path = $('input[name="path"]').val();
$(document).ready(function() {
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const id = urlParams.get('id');

  if (id) {
    editCourses(id);
  }

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
    uploadedFiles = []; // Reset uploaded files
  });

  function handleFiles(files) {
    $.each(files, function(index, file) {
      if (file.type.startsWith('image/')) {
        formData.append('files[]', file);
        uploadedFiles.push(file); // Store the file in the global array
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

// Example of how to call the log function
const saveCourse = () => {
  let thumb;
  uploadedFiles.forEach(file => {
    thumb = file.name;
  });

  let data = {
    action: 'save_course',
    id: $("input[name=id]").val(),
    course: $("input[name=course]").val(),
    description: $("input[name=description]").val(),
    thumb
  }

  $.post("../php/back_courses.php",data)
  .done(response => {
    response = JSON.parse(response);
    if (response.return == 1) {
      default_notification({type: "success", message: response.message});
      uploadFiles();
    }else{
      default_notification({type: "danger", message: response.message});
    }
  });
}

// Function to upload files to the server
const uploadFiles = () => {
  $.ajax({
    url: '../php/uploadFiles.php', // Change to your PHP script path
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      console.log('Upload successful');
      console.log(response);
    },
    error: function(jqXHR, textStatus, errorMessage) {
      console.log('Upload failed');
      console.log(errorMessage);
    }
  });
}

const editCourses = (args) =>{
  $("input[name=id]").val(args);
  let data = {
    action: "list_course_id",
    id: args
  }

  let response = $.post("../php/back_courses.php", data)
  .done(function (response) {
    response = JSON.parse(response);
    console.log(response);
    $("input[name=course]").val(response.name);
    $("input[name=description]").val(response.description);
    console.log(path)
    const img = $('<img>').attr('src', `uploads/${response.fileName}`).addClass('thumbnail');
    $('#preview-container').empty().append(img).show();
    $('#change-image').show();
    $('.drag-area').hide();

  }).fail(() => {
    default_notification({ type: "danger", message: error });
  });
}

</script>