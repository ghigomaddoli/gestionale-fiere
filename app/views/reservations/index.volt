
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    {{ content() }}
    
          <!-- DataTables Example -->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Lista richieste</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-sm" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th class="text-nowrap">Ragione sociale</th>
                        <th>Contatti Azienda</th>
                        <th>Referente Fiera</th>
                        <th>Area Tematica</th>
                        <th>Stato</th>
                        <th>Info</th>
                        <th></th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th class="text-nowrap">Ragione sociale</th>
                        <th>Contatti Azienda</th>
                        <th>Referente Fiera</th>
                        <th>Area Tematica</th>
                        <th>Stato</th>
                        <th>Info</th>
                        <th></th>
                </tr>
                  </tfoot>
                  <tbody>
                        <?php foreach ($page->items as $reservation): ?>
                        <tr>
                        <td class="text-nowrap"><?php echo $reservation->getExhibitors()->ragionesociale ?></td>
                        <td><a href='tel:<?php echo $reservation->getExhibitors()->telefono ?>'><i class="fas fa-phone-square"> <?php echo $reservation->getExhibitors()->telefono ?></i></a><br>
                            <a href='mailto:<?php echo $reservation->getExhibitors()->emailaziendale ?>'><i class="far fa-envelope"> <?php echo $reservation->getExhibitors()->emailaziendale ?></i></a></td>
                        <td>
                            <?php echo $reservation->getExhibitors()->referentenome ?> 
                            <a href='tel:<?php echo $reservation->getExhibitors()->referentetelefono ?>'><i class="fas fa-phone-square"> <?php echo $reservation->getExhibitors()->referentetelefono ?></i></a> 
                            <a href='mailto:<?php echo $reservation->getExhibitors()->referenteemail ?>'><i class="far fa-envelope"> <?php echo $reservation->getExhibitors()->referenteemail ?></i></a></td>
                        <td>
                            <span class="badge"><?php echo $reservation->getAreas()->nome ?></span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $reservation->getStati()->colore ?>"><?php echo $reservation->getStati()->descrizionebreve ?></span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">Fascia <?php echo $reservation->getExhibitors()->fasciadiprezzo ?></span>
                            <?php if (!empty($reservation->getExhibitors()->nomecoespositore)){ ?><a href="#" class="badge badge-info" id="<?php echo $reservation->id ?>" data-toggle="popover" title="<?php echo $reservation->getExhibitors()->nomecoespositore ?>" data-content="<?php echo $reservation->getExhibitors()->numerocoespositore ?>">Coespos.</a><?php } ?>
                        </td>
                        <td class="text-nowrap">
                            <?php echo $this->tag->linkTo(["exhibitors/edit/" . $reservation->getExhibitors()->id, "<i class='fas fa-pencil-alt'></i>", "class" => "btn btn-sm btn-outline-secondary","title" => "Modifica i dati anagrafici dell'espositore"]); ?>
                            <?php echo $this->tag->linkTo(["exhibitors/delete/" . $reservation->getExhibitors()->id, "<i class='fas fa-trash-alt'></i>", "class" => "btn btn-sm btn-outline-secondary","title" => "Elimina espositore e tutte le sue richieste!"]); ?>
                            <?php echo $this->tag->linkTo(["reservations/edit/" . $reservation->id, "Dettaglio Stand", "class" => "btn btn-sm btn-outline-secondary","title" => "Apre il dettaglio di Stand e servizi richiesti"]); ?>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                               Pagina <?php echo $page->current, "/", $page->total_pages ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
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
                </div>
            </div>
            <div class="card-footer small text-muted">Ci sono {{ richieste }} richieste in gestione</div>
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
