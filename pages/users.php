<!-- Begin Page Content -->
<title>Usuários</title>
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="light-card shadow mb4">
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
              <label class="label">Email</label>
              <input type="text" class="form-control" name="mail">
            </div>
            <div class="col-md-3">
              <label class="label">Usuário</label>
              <input type="text" class="form-control" name="user">
            </div>
            <div class="col-md-3">
              <div class="control-group">
                <label for="profile" class="label">Perfil</label>
                <select id="profile" class="demo-consoles teste" placeholder="Select console..."></select>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row justify-content-end">
            <a href="#" class="btn btn-primary">Salvar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

  $(function(){
    $('#profile').selectize({
      options: [
        { manufacturer: 'nintendo', value: "nes", name: "Nintendo Entertainment System" },
        { manufacturer: 'nintendo', value: "snes", name: "Super Nintendo Entertainment System" },
        { manufacturer: 'nintendo', value: "n64", name: "Nintendo 64" },
        { manufacturer: 'nintendo', value: "gamecube", name: "GameCube" },
        { manufacturer: 'nintendo', value: "wii", name: "Wii" },
        { manufacturer: 'microsoft', value: 'xss', name: 'Xbox Series S' },
        { manufacturer: 'nintendo', value: "wiiu", name: "Wii U" },
        { manufacturer: 'nintendo', value: "switch", name: "Switch" },
        { manufacturer: 'sony', value: 'ps1', name: 'PlayStation' },
        { manufacturer: 'sony', value: 'ps2', name: 'PlayStation 2' },
        { manufacturer: 'sony', value: 'ps3', name: 'PlayStation 3' },
        { manufacturer: 'sony', value: 'ps4', name: 'PlayStation 4' },
        { manufacturer: 'sony', value: 'ps5', name: 'PlayStation 5' },
        { manufacturer: 'microsoft', value: 'xbox', name: 'Xbox' },
        { manufacturer: 'microsoft', value: '360', name: 'Xbox 360' },
        { manufacturer: 'microsoft', value: 'xbone', name: 'Xbox One' },
        { manufacturer: 'microsoft', value: 'xsx', name: 'Xbox Series X' }
      ],
      optionGroupRegister: function (optgroup) {
        var capitalised = optgroup.charAt(0).toUpperCase() + optgroup.substring(1);
        var group = {
          label: 'Manufacturer: ' + capitalised
        };

        group[this.settings.optgroupValueField] = optgroup;

        return group;
      },
        optgroupField: 'manufacturer',
        labelField: 'name',
        searchField: ['name'],
        sortField: 'name'
    });
  })
</script>