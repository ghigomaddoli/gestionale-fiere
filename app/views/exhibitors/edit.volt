
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">


    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <a href="/reservations/index">Dati Anagrafici</a>
            </li>
            <li class="breadcrumb-item active">{{ exhibitor.ragionesociale }}</li>
            </ol>

    <div id="incima"></div>
    
    {{ content() }}
    
    {{ form('exhibitors/save', 'id' : 'fespositori', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class': 'form-horizontal') }} 
    
    <div class="form-group">

        <div class="form-row">
            <div class="col">
                <h4>Dati espositore per la fatturazione</h4>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <label for="fieldRagionesociale" class="col-sm-2 control-label">Ragionesociale</label>
                {{ text_field("ragionesociale", "size" : 30, "class" : "form-control", "id" : "fieldRagionesociale", "value" : exhibitor.ragionesociale) }}
            </div>
            <div class="col-md-6">
                    <label for="fieldIndirizzo" class="col-sm-2 control-label">Indirizzo</label>
                    {{ text_field("indirizzo", "size" : 100, "class" : "form-control", "id" : "fieldIndirizzo", "value" : exhibitor.indirizzo) }}
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                    <label for="fieldCap" class="col-sm-2 control-label">Cap</label>
                    <div>
                            {{ text_field("cap", "size" : 30, "class" : "form-control", "id" : "fieldCap", "value" : exhibitor.cap) }}
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldCitta" class="col-sm-2 control-label">Citta</label>
                    <div>
                            {{ text_field("citta", "size" : 30, "class" : "form-control", "id" : "fieldCitta", "value" : exhibitor.citta) }}
                    </div>
            </div>
            <div class="col-md-4">
                    <label for="fieldProvincia" class="col-sm-2 control-label">Provincia</label>
                    <div>
                            {{ select('provincia', province, 'using': ['sigla_province', 'nome_province'], 'class' : 'form-control', "value" : exhibitor.provincia) }}
                    </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-3">
                    <label for="fieldTelefono" class="col-sm-2 control-label">Telefono</label>
                    <div>
                            {{ text_field("telefono", "size" : 30, "class" : "form-control", "id" : "fieldTelefono", "value" : exhibitor.telefono) }}
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldEmailaziendale" class="col-sm-2 control-label">Email&nbsp;Azienda</label>
                    <div>
                            {{ text_field("emailaziendale", "size" : 30, "class" : "form-control", "id" : "fieldEmailaziendale", "value" : exhibitor.emailaziendale) }}
                    </div>
            </div>
            <div class="col-md-3">
                    <label for="fieldPartitaiva" class="col-sm-2 control-label">Partita&nbsp;iva</label>
                    <div>
                            {{ text_field("piva", "type" : "number", "class" : "form-control", "id" : "fieldPartitaiva", "value" : exhibitor.piva) }}
                    </div>
            </div>
            <div class="col-md-3">
                <label for="fieldCodFisc" class="col-sm-2 control-label">Cod.&nbsp;Fisc.</label>
                <div>
                        {{ text_field("codfisc", "type" : "number", "class" : "form-control", "id" : "fieldCodFisc", "value" : exhibitor.codfisc) }}
                </div>
            </div>
        </div>

        <div class="form-row">
                <div class="col">
                        <label for="fieldPec" class="control-label">PEC (Posta elettronica certificata)</label>
                        <div>
                                {{ text_field("pec", "size" : 255, "class" : "form-control", "id" : "fieldPec", "value" : exhibitor.pec) }}
                        </div>
                </div>
                <div class="col">
                        <label for="fieldCodiceSDI" class="control-label">Codice del Sistema di Interscambio</label>
                        <div>
                                {{ text_field("codicesdi", "size" : 255, "class" : "form-control", "id" : "fieldCodiceSDI", "value" : exhibitor.codicesdi) }}
                        </div>
                </div>
        </div>

    </div> <!-- Fine form group dati fatturazione azienda --> 

    <!-- Form group dati espositore --> 
    <div class="form-group">

        <div class="form-row">
                <div class="col">
                    <h4>Referente espositore per contatti durante l'evento</h4>
                </div>
        </div>

        <div class="form-row">
                <div class="col-md-6">
                        <label for="fieldReferentenome" class="col-sm-2 control-label">Nome&nbsp;espositore</label>
                        <div>
                                {{ text_field("referentenome", "size" : 30, "class" : "form-control", "id" : "fieldReferentenome", "value" : exhibitor.referentenome) }}
                        </div>
                </div>
                <div class="col-md-3">
                        <label for="fieldReferentetelefono" class="col-sm-2 control-label">Tel.&nbsp;espositore</label>
                        <div>
                                {{ text_field("referentetelefono", "size" : 30, "class" : "form-control", "id" : "fieldReferentetelefono", "value" : exhibitor.referentetelefono) }}
                        </div>
                </div>
                <div class="col-md-3">
                        <label for="fieldReferenteemail" class="col-sm-2 control-label">Email&nbsp;espositore</label>
                        <div>
                                {{ text_field("referenteemail", "size" : 30, "class" : "form-control", "id" : "fieldReferenteemail", "value" : exhibitor.referenteemail) }}
                        </div>
                </div>
        </div>

    </div>
    <!-- Fine Form group dati espositore --> 
    
    <div class="form-group">

            <div class="form-row">
                    <div class="col">
                        <h4>Elenco prodotti</h4>
                    </div>
            </div>

            <div class="form-row">
                    <div class="col">
                            <div>
                                    {{ text_field("prodottiesposti", "class" : "form-control", "id" : "fieldProdottiesposti", "value" : exhibitor.prodottiesposti) }}
                            </div>
                    </div>
            </div>


    </div>


    <div class="form-group">

            <div class="form-row">
                    <div class="col">
                        <h4>Fascia di prezzo</h4>
                    </div>
            </div>


            <div class="form-row">            
                <div>
                        {% if exhibitor.fasciadiprezzo == 'a' %}
                        {{ radio_field('fasciadiprezzo', 'value' : 'a', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-a', 'checked' : 'checked' ) }} 
                        {% else %}
                        {{ radio_field('fasciadiprezzo', 'value' : 'a', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-a') }} 
                        {% endif %}
                    
                </div>
                <label class="col-sm-2 col-form-label" for="fasciadiprezzo-a"> Fascia A</label>
                <div class="col-sm-9">{{ exhibitor.fasciadiprezzo }} -
                        Associazioni no profit, piccoli e medi produttori diretti (fino a 50 ettari), artigiani,autoproduttori, 
                        imprese individuali e familiari, aziende agricole trasformatrici individuali e familiari, botteghe del 
                        commercio equo con un solo punto vendita, cooperative sociali agricole, singoli siti web o riviste, 
                        piccoli editori, parchi locali, piccoli agriturismi o rifugi, comunità montane, comuni con meno di 
                        15.000 abitanti, gruppi informali, cooperative carcerarie.
                </div>
            </div>

            <div class="form-row">            
                    <div class="col">
                        <hr>
                    </div>
            </div>  

            <div class="form-row" id = "fdp">            
                <div>
                        {% if exhibitor.fasciadiprezzo == 'b' %}
                        {{ radio_field('fasciadiprezzo', 'value' : 'b', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-b', 'checked' : 'checked'  ) }} 
                        {% else %}
                        {{ radio_field('fasciadiprezzo', 'value' : 'b', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-b'  ) }} 
                        {% endif %}
                    
                </div>
                <label class="col-sm-2 col-form-label" for="fasciadiprezzo-b"> Fascia B</label>
                <div class="col-sm-9">
                        Associazioni locali di categoria, associazioni di secondo livello, cooperative sociali, medieditori, 
                        distributori librari, parchi provinciali, regionali e nazionali, enti locali, consorzi di cooperative, 
                        imprese, associazioni nazionali, grandi produttori diretti (oltre 50 ettari) e altre imprese agricole 
                        non comprese nella fascia A, associazioni nazionali di categoria (profit e no profit), istituzioni, 
                        università, grandi case editrici, botteghe del commercio equo con più di un punto vendita.
                </div>
            </div>            

    </div>

    <div class="form-row">
        <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-row">
        <div class="col-sm-12"><hr></div>
    </div>    



<div class="form-group">

    <div class="form-row">
            <div class="col">
                <h4>Co-espositore</h4>
            </div>
    </div>

    <div class="form-row">
        <div class="col-xs-12">
            &Egrave; possibile ospitare una o più realtà espositive all’interno del proprio stand. 
            Indicare il numero e il nome dei co-espositori. N.B. Per ogni co-espositore dovrà essere 
            compilato l’apposito form presente su www.falacosagiustaumbria.it nella sezione "esponi in fiera".
        </div>
    </div>

    <div class="form-row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-row">
            <div class="col-sm-4">
                <label for="fieldNumerocoespositore" class="control-label">Numero</label>
                {{ numeric_field('numerocoespositore', "size" : 3, "class" : "form-control", "id" : "fieldNumerocoespositore", "value" : exhibitor.numerocoespositore) }}
            </div>
            <div class="col-sm-8">
                <label for="fieldNomecoespositore" class="control-label">Nome delle realtà espositive ospitate</label>
                {{ text_field('nomecoespositore', "size" : 30, "class" : "form-control", "id" : "fieldNomecoespositore", "value" : exhibitor.nomecoespositore) }}
            </div>
    </div>

    <div class="form-row">
            <div class="col-sm-12">&nbsp;</div>
    </div>
    
    <div class="form-row">
        <div class="col-sm-12"><hr></div>
    </div>   

</div>
    
   
    <div class="form-group">

        <div class="form-row">
                <div class="col">
                    <h4>Dati per il catalogo</h4>
                </div>
        </div>

        <div class="form-row">
                <div class="col-xs-12"></div>
        </div>

        <div class="form-row">
                <div class="col-sm-12">&nbsp;</div>
        </div>
        
        <div class="form-row">
            
            <div class="col">
                <label for="fieldCatalogonome" class="control-label">Nome</label>
                {{ text_field("catalogonome", "size" : 30, "class" : "form-control", "id" : "fieldCatalogonome", "value" : exhibitor.catalogonome) }}
            </div>

            <div class="col">
                    <label for="fieldCatalogoindirizzo" class="control-label">Indirizzo</label>
                    {{ text_field("catalogoindirizzo", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoindirizzo", "value" : exhibitor.catalogoindirizzo) }}
            </div>

        </div>

        <div class="form-row">
            
            <div class="col-sm-4">
                <label for="fieldCatalogocap" class="control-label">Cap</label>
                {{ text_field("catalogocap", "size" : 30, "class" : "form-control", "id" : "fieldCatalogocap", "value" : exhibitor.catalogocap) }}
            </div>

            
            <div class="col-sm-6">
                <label for="fieldCatalogocitta" class="control-label">Città</label>
                {{ text_field("catalogocitta", "size" : 30, "class" : "form-control", "id" : "fieldCatalogocitta", "value" : exhibitor.catalogocitta) }}
            </div>

            
            <div class="col-sm-2">
                <label for="fieldCatalogoprovincia" class="control-label">Prov</label>
                {{ select('catalogoprovincia', province, 'using': ['sigla_province', 'nome_province'], 'class' : 'form-control', "value" : exhibitor.catalogoprovincia) }}
            </div>
        </div>
        
        <div class="form-row">

            
            <div class="col">
                    <label for="fieldCatalogotelefono" class="control-label">Telefono</label>
                    {{ text_field("catalogotelefono", "size" : 30, "class" : "form-control", "id" : "fieldCatalogotelefono", "value" : exhibitor.catalogotelefono) }}
            </div>

            
            <div class="col">
                <label for="fieldCatalogoemail" class="control-label">Email</label>
                {{ text_field("catalogoemail", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoemail", "value" : exhibitor.catalogoemail) }}
            </div>

            
            <div class="col">
                <label for="fieldCatalogositoweb" class="control-label">Sito web</label>
                {{ text_field("catalogositoweb", "size" : 30, "class" : "form-control", "id" : "fieldCatalogositoweb", "value" : exhibitor.catalogositoweb) }}
            </div>

        </div>

        <div class="form-row">
       
            <div class="col">
                <label for="fieldCatalogofacebook" class="control-label">Facebook</label>
                {{ text_field("catalogofacebook", "size" : 30, "class" : "form-control", "id" : "fieldCatalogofacebook", "value" : exhibitor.catalogofacebook) }}
            </div>
            
            <div class="col">
                <label for="fieldCatalogoinstagram" class="control-label">Instagram</label>
                {{ text_field("catalogoinstagram", "size" : 30, "class" : "form-control", "id" : "fieldCatalogoinstagram", "value" : exhibitor.catalogoinstagram) }}
            </div>
            
            <div class="col">
                <label for="fieldCatalogotwitter" class="control-label">Twitter</label>
                {{ text_field("catalogotwitter", "size" : 30, "class" : "form-control", "id" : "fieldCatalogotwitter", "value" : exhibitor.catalogotwitter) }}
            </div>

        </div>

        <div class="form-row">
            
            <div class="col">
                <label for="fieldCatalogodescrizione" class="control-label">Descrizione</label>
                {{ text_field("catalogodescrizione", "cols" : 30, "rows" : 4, "class" : "form-control", "id" : "fieldCatalogodescrizione", "value" : exhibitor.catalogodescrizione) }}
            </div>

        </div>

    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 smooth-scroll">
            {{ hidden_field('id', "value" : exhibitor.id) }}
            {{ submit_button('Salva',"class" : "btn btn-primary") }}
        </div>
    </div>
    
    {{ end_form() }}


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
          <h5 class="modal-title" id="SuccessInsertModalLabel">Modifiche dati Anagrafici Espositore</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="contenutosuccess">I dati anagrafici dell'espositore <i>{exhibitor.ragionesociale}</i> sono stati modificati con successo!</div>
        <div class="modal-footer">
          <a class="btn btn-primary" href="/reservations">OK</a>
        </div>
      </div>
    </div>
</div>