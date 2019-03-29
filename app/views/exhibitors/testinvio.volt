
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <div class="page-header">
        <h2>
            test invio
        </h2>
    </div>
    
    {{ content() }}

</div>
<!-- /.content-wrapper -->


</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>
{% if logged === false %}
  {% set destinazione = '/index' %}
{% else %}
  {% set destinazione = '/reservations' %}
{% endif %}
<div class="modal fade" id="SuccessInsertModal" tabindex="-1" role="dialog" aria-labelledby="SuccessInsertModalLabel" aria-hidden="true">
    <div class="modal-dialog .modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="SuccessInsertModalLabel">Inserimento Espositore</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="contenutosuccess">I dati dell'espositore sono stati salvati con successo!</div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="{{ destinazione }}">OK</a>
        </div>
      </div>
    </div>
</div>
<div class="modal" id="modalspinner" tabindex="-1" role="dialog" aria-labelledby="modalspinner" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <i class="fas fa-spinner fa-spin"></i> controllo dati in corso...
        </div>
      </div>
    </div>
  </div>