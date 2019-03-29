
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
    <li class="breadcrumb-item active">Domanda di iscrizione {{ evento|e }} per il coespositore di <strong>{{ reservation.exhibitors.ragionesociale }}</strong></li>
    </ol>

    <div id="incima"></div>
    
    {{ content() }}
    
    {{ form('exhibitors/coespositorecreate', 'id' : 'fespositori', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class': 'form-horizontal') }} 
    
    <div class="form-group">

        <div class="form-row fascia-rossa-flcgu">
            <div class="col">
                <h4>Dati del co-espositore per la fatturazione</h4>
            </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>        

        <div class="form-row">
            <div class="col-md-6">
                <label for="fieldRagionesociale" class="col-sm-2 control-label">Ragione&nbsp;Sociale</label>
                {{ text_field("ragionesociale", "size" : 30, "class" : "form-control", "id" : "fieldRagionesociale") }}
            </div>
            <div class="col-md-6">
                    <label for="fieldIndirizzo" class="col-sm-2 control-label">Indirizzo</label>
                    {{ text_field("indirizzo", "size" : 100, "class" : "form-control", "id" : "fieldIndirizzo") }}
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                    <label for="fieldCap" class="col-sm-2 control-label">Cap</label>
                    <div>
                            {{ text_field("cap", "size" : 30, "class" : "form-control", "id" : "fieldCap") }}
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldCitta" class="col-sm-2 control-label">Citta</label>
                    <div>
                            {{ text_field("citta", "size" : 30, "class" : "form-control", "id" : "fieldCitta") }}
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldProvincia" class="col-sm-2 control-label">Provincia</label>
                    <div>
                            {{ select('provincia', province, 'using': ['sigla_province', 'nome_province'], 'class' : 'form-control', 'value' : provinciadefault ) }}
                    </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-3">
                    <label for="fieldTelefono" class="col-sm-2 control-label">Telefono</label>
                    <div>
                            {{ text_field("telefono", "size" : 30, "class" : "form-control", "id" : "fieldTelefono") }}
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldEmailaziendale" class="col-sm-2 control-label">Email&nbsp;Azienda</label>
                    <div>
                            {{ text_field("emailaziendale", "size" : 30, "class" : "form-control", "id" : "fieldEmailaziendale") }}
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldPartitaiva" class="col-sm-2 control-label">Partita&nbsp;iva</label>
                    <div>
                            {{ text_field("piva", "type" : "number", "size" : "11", "maxlength" : "11", "class" : "form-control", "id" : "fieldPartitaiva") }}
                    </div>
            </div>
            <div class="col-md-3">
                <label for="fieldCodFisc" class="col-sm-2 control-label">Cod.&nbsp;Fisc.</label>
                <div>
                        {{ text_field("codfisc", "size" : "16", "maxlength" : "16", "class" : "form-control", "id" : "fieldCodFisc") }}
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                    <label for="fieldPec" class="control-label">PEC (Posta elettronica certificata)</label>
                    <div>
                            {{ text_field("pec", "size" : 255, "class" : "form-control", "id" : "fieldPec") }}
                    </div>
            </div>
            <div class="col">
                    <label for="fieldCodiceSDI" class="control-label">Codice del Sistema di Interscambio</label>
                    <div>
                            {{ text_field("codicesdi", "size" : 255, "class" : "form-control", "id" : "fieldCodiceSDI") }}
                    </div>
            </div>
        </div>

    </div> <!-- Fine form group dati fatturazione azienda --> 

    <!-- Form group dati espositore --> 
    <div class="form-group">

        <div class="form-row fascia-rossa-flcgu">
                <div class="col">
                    <h4>Referente co-espositore per contatti prima e durante l'evento</h4>
                </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>

        <div class="form-row">
                <div class="col-md-6">
                        <label for="fieldReferentenome" class="col-md-2 col-sm-2 control-label">Nome&nbsp;espositore</label>
                        <div>
                                {{ text_field("referentenome", "size" : 30, "class" : "form-control", "id" : "fieldReferentenome") }}
                        </div>
                </div>
                <div class="col-md-3">
                        <label for="fieldReferentetelefono" class="col-sm-2 control-label">Cell.&nbsp;espositore</label>
                        <div>
                                {{ text_field("referentetelefono", "size" : 30, "class" : "form-control", "id" : "fieldReferentetelefono") }}
                        </div>
                </div>
                <div class="col-md-3">
                        <label for="fieldReferenteemail" class="col-sm-2 control-label">Email&nbsp;espositore</label>
                        <div>
                                {{ text_field("referenteemail", "size" : 30, "class" : "form-control", "id" : "fieldReferenteemail") }}
                        </div>
                </div>
        </div>

    </div>
    <!-- Fine Form group dati espositore --> 
    
    <div class="form-group">

            <div class="form-row fascia-rossa-flcgu">
                    <div class="col">
                        <h4>Elenco prodotti</h4>
                    </div>
            </div>

            <div class="form-row">
                <div class="col">&nbsp;</div>
            </div>

            <div class="form-row">
                    <div class="col">
                            <div>
                                    {{ text_field("prodottiesposti", "class" : "form-control", "id" : "fieldProdottiesposti") }}
                            </div>
                    </div>
            </div>


    </div>    
   
    <div class="form-group">

        <div class="form-row fascia-rossa-flcgu">
                <div class="col">
                    <h4>Dati per il catalogo</h4>
                </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>        

        <div class="form-row">
                <div class="col-sm-2"><i class="far fa-clone fa-3x copiagiu" data-toggle="tooltip" title="Copia i dati di fatturazione dove possibile"></i><i class="fas fa-long-arrow-alt-down fa-3x copiagiu"  data-toggle="tooltip" title="Copia i dati di fatturazione dove possibile"></i></div>
                <div class="col-sm-10">
                    N.B. La correttezza e la completezza delle informazioni fornite in questo riquadro sono a cura ed interesse dell'espositore. 
                    L'organizzazione non risponde di eventuali errori e/o carenze dovute ad una compilazione non accurata di questo modulo.
                </div>
        </div>

        <div class="form-row">
                <div class="col-sm-12">&nbsp;</div>
        </div>
        
        <div class="form-row">
            
            <div class="col">
                <label for="fieldCatalogonome" class="control-label">Nome</label>
                {{ text_field("catalogonome", "size" : 30, "class" : "form-control", "id" : "fieldCatalogonome") }}
            </div>

            <div class="col">
                    <label for="fieldCatalogoindirizzo" class="control-label">Indirizzo</label>
                    {{ text_field("catalogoindirizzo", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoindirizzo") }}
            </div>

        </div>

        <div class="form-row">
            
            <div class="col-sm-4">
                <label for="fieldCatalogocap" class="control-label">Cap</label>
                {{ text_field("catalogocap", "size" : 30, "class" : "form-control", "id" : "fieldCatalogocap") }}
            </div>

            
            <div class="col-sm-6">
                <label for="fieldCatalogocitta" class="control-label">Città</label>
                {{ text_field("catalogocitta", "size" : 30, "class" : "form-control", "id" : "fieldCatalogocitta") }}
            </div>

            
            <div class="col-sm-2">
                <label for="fieldCatalogoprovincia" class="control-label">Prov</label>
                {{ select('catalogoprovincia', province, 'using': ['sigla_province', 'nome_province'], 'class' : 'form-control') }}
            </div>
        </div>
        
        <div class="form-row">

            
            <div class="col">
                    <label for="fieldCatalogotelefono" class="control-label">Telefono</label>
                    {{ text_field("catalogotelefono", "size" : 30, "class" : "form-control", "id" : "fieldCatalogotelefono") }}
            </div>

            
            <div class="col">
                <label for="fieldCatalogoemail" class="control-label">Email</label>
                {{ text_field("catalogoemail", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoemail") }}
            </div>

            
            <div class="col">
                <label for="fieldCatalogositoweb" class="control-label">Sito web</label>
                {{ text_field("catalogositoweb", "size" : 30, "class" : "form-control", "id" : "fieldCatalogositoweb") }}
            </div>

        </div>

        <div class="form-row">
       
            <div class="col">
                <label for="fieldCatalogofacebook" class="control-label">Facebook</label>
                {{ text_field("catalogofacebook", "size" : 30, "class" : "form-control", "id" : "fieldCatalogofacebook") }}
            </div>
            
            <div class="col">
                <label for="fieldCatalogoinstagram" class="control-label">Instagram</label>
                {{ text_field("catalogoinstagram", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoinstagram") }}
            </div>
            
            <div class="col">
                <label for="fieldCatalogotwitter" class="control-label">Twitter</label>
                {{ text_field("catalogotwitter", "size" : 30, "class" : "form-control", "id" : "fieldCatalogotwitter") }}
            </div>

        </div>

        <div class="form-row">
            
            <div class="col">
                <label for="fieldCatalogodescrizione" class="control-label">Descrizione (massimo 300 caratteri)</label>
                {{ text_field("catalogodescrizione", "maxlength" : 300, "class" : "form-control", "id" : "fieldCatalogodescrizione") }}
            </div>

        </div>

    </div>
    
    <div class="form-row">
            <div class="col">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            {{ check_field('interventoprogrammaculturale', 'value' : 1, 'id' : 'interventoprogrammaculturale' ) }} 
                            </div>
                        </div>
                        {{ text_field('descintervento', "class" : "form-control", "value" : 'Desidero intervenire al programma culturale', 'disabled' : 'disabled') }}
                    </div>

            </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 smooth-scroll">
            {{ hidden_field('reservation', 'id' : 'reservation', "value" : reservation.id) }}
            {{ submit_button('Salva',"class" : "btn btn-primary") }}
        </div>
    </div>

    {{ end_form() }}
    
    {{ hidden_field('redirect', 'id' : 'redirect', "value" : redirect) }}


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

<div class="modal fade" id="SuccessInsertModal" tabindex="-1" role="dialog" aria-labelledby="SuccessInsertModalLabel" aria-hidden="true">
    <div class="modal-dialog .modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="SuccessInsertModalLabel">Inserimento Co-espositore</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="contenutosuccess">I dati del co-espositore sono stati salvati con successo!</div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="{{ redirect }}">OK</a>
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