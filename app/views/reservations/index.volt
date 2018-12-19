
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

        <div class="container-fluid">
                <div class="row">
                    <div class="col-1"><strong>Filtra per:</strong> </div>
                    <div class="col-11">
                      {{ form('reservations/index', 'id' : 'filtriricerca', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class': 'd-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0') }} 
                      <div class="input-group">
                              <label for="FiltroArea" class="control-label">Area Tematica:&nbsp;</label>
                            {{ select('areas_id', areas, 'using': ['id', 'nome'], 'class' : 'form-control', 'useEmpty' : true, 'emptyText'  : 'Tutte le aree', 'id' : 'FiltroArea') }}
                           &nbsp;&nbsp; 
                           <label for="FiltroStato" class="control-label">Stato:&nbsp; </label>
                           {{ select('stato', stati, 'using': ['id', 'descrizionebreve'], 'class' : 'form-control', 'useEmpty' : true, 'emptyText'  : 'Tutti gli stati', 'id' : 'FiltroStato') }}
                           &nbsp;&nbsp; 
                           <label for="OrderBy" class="control-label">Ordina i risultati per:&nbsp; </label>
                           {{ select_static('orderby', ['' : 'Più recenti', 'Exhibitors.ragionesociale' :'Ragione Sociale', 'stato' : 'Stato', 'areas_id' : 'Area Tematica'], 'class' : 'form-control', 'id' : 'OrderBy') }}                         
                           &nbsp;&nbsp; 
                           <label for="Filtroprogcult" class="control-label">Prog. Culturale:&nbsp; </label>
                           {{ check_field('interventoprogrammaculturale', 'value' : '1', 'class' : 'form-control', 'id' : 'Filtroprogcult') }}                                                  
                           &nbsp;<button type="submit" class="btn btn-primary">&nbsp;<i class="fas fa-search"></i>&nbsp;Cerca</button>
                           {{ end_form() }}
                           {{ form('reservations/excelgen', 'id' : 'fexcelgen', 'role': 'form', 'method': 'POST', 'autocomplete': 'off') }} 
                           {{ hidden_field('areas_id', 'id' : 'FiltroAreaexcel') }}
                           {{ hidden_field('stato', 'id' : 'FiltroStatoexcel') }}
                           {{ hidden_field('orderby', 'id' : 'FiltroOrderbyexcel') }}
                           {{ hidden_field('interventoprogrammaculturale', 'id' : 'Filtroprogcultexcel') }}
                           &nbsp;<button type="button" id="excelgen" class="btn btn-primary" data-toggle="tooltip" title="Scarica in formato csv i dati con i criteri di ricerca impostati">&nbsp;<i class="fas fa-file-excel"></i>&nbsp;Scarica</button>
                           {{ end_form() }}
                           {{ form('reservations/index', 'id' : 'freset', 'role': 'form', 'method': 'POST', 'autocomplete': 'off') }} 
                           &nbsp;<input type="submit" id="ResetFiltri" value="Reset" class="btn btn-primary">
                           {{ end_form() }}
                        </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col">&nbsp;</div>
              </div>
        </div>

  <div class="container-fluid">

    {{ content() }}
    
          <!-- DataTables Example -->

          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"> Elenco richieste</i>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th>Ragione sociale</th>
                        <th>Contatti Azienda</th>
                        <th>Referente Fiera</th>
                        <th>Area Tematica</th>
                        <th>Stato</th>
                        <th>Info</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tbody>
                        {% for index, reservation in page.items %}
                        {% if reservation.exhibitors.fasciadiprezzo == 'a' %}
                        {% set colorefascia = 'secondary' %}
                        {% else %}
                        {% set colorefascia = 'warning' %}
                        {% endif %}
                        <tr>
                        <td>{{ reservation.exhibitors.ragionesociale }}</td>
                        <td><a href='tel:{{ reservation.exhibitors.telefono }}'><i class="fas fa-phone-square"> {{ reservation.exhibitors.telefono }}</i></a><br>
                            <a href='mailto:{{ reservation.exhibitors.emailaziendale }}'><i class="far fa-envelope"> {{ reservation.exhibitors.emailaziendale }}</i></a></td>
                        <td>
                            {{ reservation.exhibitors.referentenome }} 
                            <a href='tel:{{ reservation.exhibitors.referentetelefono }}'><i class="fas fa-phone-square"> {{ reservation.exhibitors.referentetelefono }}</i></a> 
                            <a href='mailto:{{ reservation.exhibitors.referenteemail }}'><i class="far fa-envelope"> {{ reservation.exhibitors.referenteemail }}</i></a></td>
                        <td>
                            <span class="badge" style="background-color:{{ reservation.areas.colore }};">{{ reservation.areas.nome }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ reservation.stati.colore }}">{{ reservation.stati.descrizionebreve }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ colorefascia }}">Fascia {{ reservation.exhibitors.fasciadiprezzo }}</span>
                            {% if reservation.exhibitors.nomecoespositore != '' %}
                            <a href="#" class="badge badge-info" id="coespositore-{{ reservation.id }}" data-toggle="tooltip" title="Co-espositore: {{ reservation.exhibitors.nomecoespositore }}" data-content="{{ reservation.exhibitors.numerocoespositore }}">C</a>
                            {% endif %}
                            {% if reservation.interventoprogrammaculturale == 1 %}
                            <a href="#" class="badge badge-info" id="ipc-{{ reservation.id }}" data-toggle="tooltip" title="Parteciperà al programma culturale"><i class="fas fa-graduation-cap"></i></a>
                            {% endif %}
                        </td>
                        <td class="text-nowrap">
                            {{ link_to('exhibitors/edit/' ~ reservation.exhibitors.id, "<i class='fas fa-pencil-alt'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Modifica i dati anagrafici di fatturazione espositore", 'data-toggle' : 'tooltip') }}
                            {{ link_to('exhibitors/delete/' ~ reservation.exhibitors.id, "<i class='fas fa-trash-alt'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Elimina espositore e tutte le sue richieste!", 'data-toggle' : 'tooltip') }}
                            {{ link_to('reservations/edit/' ~ reservation.id, "<i class='fas fa-euro-sign'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Dettaglio di Stand, Servizi e pagamenti", 'data-toggle' : 'tooltip') }}
                        </td>
                        </tr>
                    {% endfor %} 
                  </tbody>
                </table>
              </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous <?php echo ($page->current == 1) ? 'disabled' :''; ?>" id="dataTable_previous">
                                    <?php echo $this->tag->linkTo(['reservations/index', 'First','aria-controls'=> "dataTable", 'data-dt-idx'=> "0", 'tabindex'=> "0", 'class'=> "page-link"]) ?>
                                </li>
                                <li class="paginate_button page-item <?php echo ($page->current== 1) ? 'disabled' : ''; ?>">
                                    <?php echo $this->tag->linkTo(['reservations/index?page=' . $page->before, 'Previous', 'aria-controls' => "dataTable", 'data-dt-idx' => "0", 'tabindex' => "0", 'class' => "page-link"]) ?>
                                </li>
                                <?php for($numpages = 1; $numpages <= $page->total_pages; $numpages++){ ?>
                                    <li class="paginate_button page-item <?php echo ($this->request->get('page') == $numpages) ? 'active' : ''; ?>">
                                        <?php echo $this->tag->linkTo(['reservations/index?page=' . $numpages, $numpages,'aria-controls'=> "dataTable", 'data-dt-idx'=> "7", 'tabindex'=> "0", 'class'=> 'page-link']) ?>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button page-item next <?php echo ($page->current== $page->total_pages) ? 'disabled' : ''; ?>" id="dataTable_next">
                                    <?php echo $this->tag->linkTo(['reservations/index?page=' . $page->next, 'Next', 'aria-controls' => "dataTable", 'data-dt-idx' => "7", 'tabindex' => "0", 'class' => "page-link"]) ?>
                                </li>
                                <li class="paginate_button page-item next <?php echo ($page->current== $page->total_pages) ? 'disabled' : ''; ?>" id="dataTable_next">
                                    <?php echo $this->tag->linkTo(['reservations/index?page=' . $page->last, 'Last', 'aria-controls' => "dataTable", 'data-dt-idx' => "7", 'tabindex' => "0", 'class' => "page-link"]) ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
            <div class="card-footer small text-muted">Ci sono {{ page.total_items }} richieste in gestione - Pagina {{ page.current}} di {{page.total_pages}}</div>
          </div>



  </div>
  <!-- /.container-fluid -->

  <!-- Sticky Footer -->
  <footer class="sticky-footer">
    <div class="container my-auto">
      <div class="copyright text-center my-auto">
        <span>Copyright © Fairlab 2018</span>
      </div>
    </div>
  </footer>

</div>
<!-- /.content-wrapper -->


</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>