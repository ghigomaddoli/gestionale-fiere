
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

        <div class="container-fluid">
                <div class="row">
                    <div class="col-1"><strong>Filtra per:</strong> </div>
                    <div class="col-11">
                      {{ form('reservations/index', 'id' : 'filtriricerca', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class' : 'form-inline') }} 
                      <div class="form-row align-items-center">
                            {{ select('areas_id', areas, 'using': ['id', 'nome'], 'class' : 'form-control form-control-sm', 'useEmpty' : true, 'emptyText'  : 'Tutte le aree', 'id' : 'FiltroArea') }}
                           &nbsp;&nbsp; 
                           {{ select('stato', stati, 'using': ['id', 'descrizionebreve'], 'class' : 'form-control form-control-sm', 'useEmpty' : true, 'emptyText'  : 'Tutti gli stati', 'id' : 'FiltroStato') }}
                           &nbsp;&nbsp; 
                           <label for="OrderBy" class="control-label">Ordina per:&nbsp;</label>
                           {{ select_static('orderby', ['' : 'Più recenti', 'Exhibitors.ragionesociale' :'Ragione Sociale', 'stato' : 'Stato', 'areas_id' : 'Area Tematica'], 'class' : 'form-control form-control-sm', 'id' : 'OrderBy') }}                         
                           &nbsp;&nbsp; 
                           <label for="Filtroprogcult" class="control-label">Prog. Cult.:&nbsp; </label>
                           {{ check_field('interventoprogrammaculturale', 'value' : '1', 'data-size' : 'mini', 'id' : 'Filtroprogcult', 'class' : 'form-control form-control-sm' ) }}                                                 
                           &nbsp;&nbsp;<button type="submit" class="btn btn-primary">&nbsp;<i class="fas fa-search"></i>&nbsp;Cerca</button>
                           {{ end_form() }}

                           {{ form('reservations/index', 'id' : 'freset', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class' : 'form-inline') }} 
                           &nbsp;<input type="submit" id="ResetFiltri" value="Reset" class="btn btn-primary">
                           {{ end_form() }}
                           &nbsp;&nbsp;
                           {{ form('reservations/excelgen', 'id' : 'fexcelgen', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class' : 'form-inline') }} 
                           {{ hidden_field('areas_id', 'id' : 'FiltroAreaexcel') }}
                           {{ hidden_field('stato', 'id' : 'FiltroStatoexcel') }}
                           {{ hidden_field('orderby', 'id' : 'FiltroOrderbyexcel') }}
                           {{ hidden_field('interventoprogrammaculturale', 'id' : 'Filtroprogcultexcel') }}
                           &nbsp;<button type="button" id="excelgen" class="btn btn-primary" data-toggle="tooltip" title="Scarica in formato Excel i dati con i criteri di ricerca impostati">&nbsp;<i class="fas fa-file-excel"></i>&nbsp;Scarica</button>
                           &nbsp;
                           {{ check_field('separasheets', 'value' : '1', 'data-toggle' : 'toggle','data-on':'più fogli','data-off':'un foglio', 'id' : 'separasheets', 'data-size' : 'mini', 'class' : 'form-control form-control-sm') }}
                           {{ end_form() }}
                      </div>
                </div>
                <div class="row">&nbsp;</div>
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
                        <th>Contatti</th>
                        <th>Area Tematica</th>
                        <th>Stato</th>
                        <th>Info</th>
                        <th>Commerciale</th>
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
                        <td>
                          {% if reservation.padre_id is not null %} <i class='fas fa-user-friends' data-toggle='tooltip' title='Coespositore di {{ reservation.getPadri().exhibitors.ragionesociale }}'></i> {% endif %} {{ reservation.exhibitors.ragionesociale }}
                        </td>
                        <td>
                            <button class='btn btn-sm btn-outline-secondary fas fa-address-card dettcont' data-target='#dettagliocontatti' data-toggle='modal' data-title='dettaglio contatti' data-ragsoc='{{ reservation.exhibitors.ragionesociale }}' data-telaz='{{ reservation.exhibitors.telefono }}' data-emailaz='{{ reservation.exhibitors.emailaziendale }}' data-nomeref='{{ reservation.exhibitors.referentenome }}' data-telref='{{ reservation.exhibitors.referentetelefono }}' data-emailref='{{ reservation.exhibitors.referenteemail }}'></button>
                        </td>
                        <td>
                            <span class="badge" style="background-color:{{ reservation.areas.colore }}">{{ reservation.areas.nome }}</span>
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
                        <td>
                          {% if reservation.users_id > 0 %}
                          {{ reservation.users.username }}
                          {% endif %}
                        </td>
                        <td class="text-nowrap">
                            {{ link_to('exhibitors/edit/' ~ reservation.exhibitors.id, "<i class='fas fa-pencil-alt'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Modifica i dati anagrafici di fatturazione espositore", 'data-toggle' : 'tooltip') }}
                            {{ link_to('exhibitors/delete/' ~ reservation.exhibitors.id, "<i class='fas fa-trash-alt'></i>",'data-target' : '#deletetModal', 'data-idesp' : reservation.exhibitors.id, 'data-esp' : reservation.exhibitors.ragionesociale, 'class': 'btn btn-sm btn-outline-secondary cancellaespositore', 'data-toggle' : 'tooltip') }}
                            {{ link_to('reservations/edit/' ~ reservation.id, "<i class='fas fa-cogs'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Dettaglio di Stand, Servizi e pagamenti", 'data-toggle' : 'tooltip') }}
                            {% if reservation.padre_id is null %}
                            {{ link_to('exhibitors/coespositore/' ~ reservation.id, "<i class='fas fa-user-plus'></i>", 'class': 'btn btn-sm btn-outline-secondary', 'title' :  "Inserisci coespositore", 'data-toggle' : 'tooltip') }}
                            {% endif %}
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

<!-- Logout Modal-->
{{ partial('partials/logoutmodal') }}

<div class="modal fade" id="deletetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sei sicuro di voler eliminare questo espositore?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Clicca su "Elimina" per eliminare definitivamente l'espositore e tutti i servizi da lui prenotati.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annulla</button>
        <a class="btn btn-primary" href="#">Elimina</a>
      </div>
    </div>
  </div>
  </div>

  <div class="modal fade"  id="dettagliocontatti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Dettaglio contatti</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <p>ciao</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Chiudi</button>
        </div>
      </div>
    </div>
    </div>