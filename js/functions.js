let id = "";

// Função Para efetuar o Login
function login() {
  let user = $("#user").val();
  let password = $("#password").val();

  if (user.trim() == "" || password.trim() == "") {
    default_notification({ type: "danger", message: "É necessário preencher os campos de login e senha para efetuar o Login!" });
    return;
}

  let data = {
    action: 'login',
    user,
    password
  }

  $.post("action.php", data)
  .done(function (response) {
    console.log(response);
    response = JSON.parse(response);
    if (response.return == 1) {
      if (response.keyProfile != 'KEY_STUDENT') {
        location.href = '/';
      }else{
        location.href = '/pageStudents/';
      }
    } else if (response.return == 0) {
      default_notification({ type: "danger", message: `<b>${response.message}</b>` });
    }
  });
}
// função usada para finalizazar a seção do usuário
function logout() {
  let data = {
    action: 'logout',
  }

  $.post("action.php", data)
    .done(function (response) {
      location.href = "/";
    });

}

// função que serve para mostrar uma notificação ao usuário atravez de um alert personalizado na tela
function default_notification(options) {

  let standardOption = ({ type: "success", timer: 500, from: "top", z_index: 99999, message: "" });

  let type = standardOption.type;
  let timer = standardOption.timer;
  let z_index = standardOption.z_index;
  let message = standardOption.message;
  let from = standardOption.from;

  if (options.type !== undefined) {
    type = options.type;
  }
  if (options.timer !== undefined) {
    timer = options.timer;
  }

  if (options.z_index !== undefined) {
    z_index = options.z_index;
  }

  if (options.message !== undefined) {
    message = options.message;
  }

  if (options.from !== undefined) {
    from = options.from;
  }

  $.notify({
    message: message
  }, {
    type: type,
    timer: timer,
    z_index: z_index,
    placement: {
      from: from,
      align: "center"
    },
  });

}

// Função que reseta todos os campos de um form
function reset_form(form, ignoreArray, params = {}) {
  // Caso tenha algum campo com a classe ignores, a função não irá limpar 
  let ignoreClass = [
    "ignore"
  ];

  ignoreClass = (ignoreArray) ? ignoreArray : ignoreClass;

  $.each($(form)[0].elements, function (key, element) {

  let exist = !1, classList = element.className.split(/\s+/);
  if (classList.length > 0) {
    $.each(classList, function (k) {
      if (ignoreClass.indexOf(classList[k]) !== -1) {
        exist = !0; //true           
        return !1; //false, cai fora do loop
      }
    })
  }
  if (exist) return; //proxima iteracao    

  switch (element.tagName) {
    case "INPUT":
      $(this).val('').css('border', '1px solid #cccccc');
      break;
    case "TEXTAREA":
      $(this).val('').css('border', '1px solid #cccccc');
      break;
    case "SELECT":
      if (params.refreshSelect) {
          $(this).val('').trigger('change').selectpicker('refresh');
          $(this).prev().prev().css('border', '');
      } else {
          $(this).val('').trigger('change');
          $(this).css('border', '');
      }
      break;
    }
  });

}

// Função criada para redirecionamento de rotas quando necessário
function header_url(args) {
  if (!Array.isArray(args)) {
    args = [args]; //Transforma o parâmetro da chamada da função em um array
  }

  let parameters = "";

  // Percorre os argumentos e cria a nova URL com parâmetros
  args.forEach(element => (parameters += element.parameter + "=" + element.value));
  console.log(args);

  url = "/?" + parameters;
  console.log(url);
  return;

  // Redireciona para a nova URL
  window.location.href = url;
}

async function load_profiles(args){
  let options = '';
  await $.post("../php/back_permissions.php", data = {action: 'load_profiles'})
  .done(response => {
    response = JSON.parse(response);
    $('select[name='+args.selectName+']').empty();

    response.forEach(value => {
      if (value.idperfil == 1) {
        options += '<option value="' + value.idperfil + '" selected>'+ value.nomeperfil +'</option>';  
      }else{
        options += '<option value="' + value.idperfil + '">'+ value.nomeperfil +'</option>';  
      }
    });

    $(options).appendTo('select[name='+args.selectName+']');
    
  });

  $('select[name='+args.selectName+']').selectpicker('destroy').selectpicker();
  await list_permissions();
}