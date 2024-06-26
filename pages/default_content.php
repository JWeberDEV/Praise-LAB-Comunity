<!-- Begin Page Content -->
<title>Home</title>
<div class="container-fluid">
  <div class="conainer">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow mb4 p-1">
          <div id="chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(function pupilChart(){

  let chart, options = {
    chart: {
      type: 'column',
      renderTo: 'chart',
    },
    title: {
      text: 'Alunos Cursando'
    },
    subtitle: {
      text: 'Semestral'
    },
    credits: {
      enabled: false
    },
    xAxis: {
      categories: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho'],
      crosshair: true
    },
    series: [
      {
        name: ['Matriculados'],
        data: [ 14500, 27500, 68300, 68000, 107000, 260000, 406292],
        color:'#157B7F'
      },
      {
        name: ['Interessados'],
        data: [ 27500, 68300, 68000, 107000, 260000, 406292, 506292],
        color:'#E26E71'
      },
      {
        name: ['Desistências'],
        data: [ 4500, 6300, 6000, 7000, 7600, 6020, 5200],
        color:'#C2AA50'
      },
    ]
  };

  chart = new Highcharts.Chart(options);

});
</script>