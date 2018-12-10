<!-- Breadcrumbs-->
<ol class="breadcrumb">
<li class="breadcrumb-item">
    <a href="#">Dashboard</a>
</li>
<li class="breadcrumb-item active">Andamento Gestione Espositori</li>
</ol>

<div class="row">

  <div class="col-lg-6">
      <div class="card mb-3">
      <div class="card-header">
          <i class="fas fa-chart-pie"></i>
          Andamento iscrizioni per Stato</div>
      <div class="card-body">
          <canvas id="myPieChart" width="100%" height="100"></canvas>
      </div>
      <div class="card-footer small text-muted">Ci sono {{ richieste }} richieste in gestione</div>
      </div>
  </div>

  <div class="col-lg-6">
      <div class="card mb-3">
      <div class="card-header">
          <i class="fas fa-chart-pie"></i>
          Andamento iscrizioni per Area Tematica</div>
      <div class="card-body">
          <canvas id="myPieChartAT" width="100%" height="100"></canvas>
      </div>
      <div class="card-footer small text-muted">Ci sono {{ richieste }} richieste in gestione</div>
      </div>
  </div>

</div>

<div class="row">

<div class="col">
  <div class="card mb-3">
  <div class="card-header">
  <i class="fas fa-file-excel"></i>
  Download veloce dati Excel</div>
  <div class="card-body">
      <div class="row">
        <div class="col-sm-6 mb-6">
            <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
            <div class="card-body-icon">
            <i class="fas fa-fw fa-comments"></i>
            </div>
            <div class="mr-5">Estrazione domande espositori in formato csv per Excel</div>
            </div>
            {{ link_to('index/csvespositori', '<span class="float-left">Scarica!</span><span class="float-right"><i class="fas fa-angle-right"></i></span>', 'class': 'card-footer text-white clearfix small z-1') }}
            </div>
        </div>
        <div class="col-sm-6 mb-6">
            <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
            <div class="card-body-icon">
            <i class="fas fa-fw fa-list"></i>
            </div>
            <div class="mr-5">Estrazione dati per il catalogo in formato csv per Excel</div>
            </div>
            {{ link_to('index/csvcatalogo', '<span class="float-left">Scarica!</span><span class="float-right"><i class="fas fa-angle-right"></i></span>', 'class': 'card-footer text-white clearfix small z-1') }}
            </div>
        </div>
      </div>
      <div class="row"><div class="col">&nbsp;</div></div>
      <div class="row">
          <div class="col">
          <div class="card-header">
              <i class="fas fa-table"></i>
              Distribuzione delle iscrizioni per stato</div>
          <div class="table-responsive">
              <table class="table table-riepilogo" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Stato</th>
                    <th>Iscrizioni</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

              {% for index, stato in stati %}
              <tr>
                  <td>{{ stato.descrizionebreve }}</td>
                  <td>{{ tbldistribstati[index] }}</td>
                  <td>
                      {{ form('reservations/index', 'role': 'form', 'method': 'POST') }} 
                      {{ hidden_field('stato', "value" : stato.id ) }}
                    <input type="submit" class="btn btn-outline-dark btn-sm" value="Dettaglio">
                    {{ end_form() }}
                  </td>
              </tr>
              {% endfor %}

                </tbody>
                </table>
              </div>
          </div>
      </div>
  </div>
  <div class="card-footer small text-muted">&nbsp;</div>
  </div>
</div>

  <div class="col">
      <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Riepilogo distribuzione per area tematica</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-riepilogo" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Area Tematica</th>
                    <th>Iscrizioni</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

              {% for index, area in areas %}
              <tr>
                  <td>{{ area.nome }}</td>
                  <td>{{ tbldistribarea[area.nome] }}</td>
                  <td>
                      {{ form('reservations/index', 'role': 'form', 'method': 'POST') }} 
                      {{ hidden_field('areas_id', "value" : area.id ) }}
                    <input type="submit" class="btn btn-outline-dark btn-sm" value="Dettaglio">
                    {{ end_form() }}
                  </td>
              </tr>
              {% endfor %}

                </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer small text-muted">&nbsp;</div>
        </div>
  </div>

</div>

<script src="/vendor/chart.js/Chart.js"></script>

<script>
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';
  
  // Pie Chart per stato
  var ctx = document.getElementById("myPieChart");
  var myPieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['{{ labels }}'],
      datasets: [{
        data: [{{ distribution }}],
        backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745'],
      }],
    },
    options: {
        legend: {
            display: true,
            position: 'left',
        }
      }
  });

// pie chart per area tematica
  var ctx = document.getElementById("myPieChartAT");
  var myPieChartAT = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['{{ labelareas }}'],
      datasets: [{
        data: [{{ distributionareas }}],
        backgroundColor: ['{{ coloriareas }}'],
      }],
    },
        options: {
        legend: {
            display: true,
            position: 'left',
        }
      }
  });
  </script>