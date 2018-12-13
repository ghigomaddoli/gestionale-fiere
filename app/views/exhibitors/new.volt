
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <div class="page-header">
        <h2>
            Domanda di iscrizione {{ evento|e }}
        </h2>
    </div>

    <div id="incima"></div>
    
    {{ content() }}
    
    {{ form('exhibitors/create', 'id' : 'fespositori', 'role': 'form', 'method': 'POST', 'autocomplete': 'off', 'class': 'form-horizontal') }} 
    
    <div class="form-group">

        <div class="form-row fascia-rossa-flcgu">
            <div class="col">
                <h4>Dati espositore per la fatturazione</h4>
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
                    <h4>Referente espositore per contatti prima e durante l'evento</h4>
                </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>

        <div class="form-row">
                <div class="col-md-6">
                        <label for="fieldReferentenome" class="col-sm-2 control-label">Nome&nbsp;espositore</label>
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


    <!-- Form group area tematica --> 
    <div class="form-group" id="grupposeztematica">

            <div class="form-row fascia-rossa-flcgu">
                    <div class="col">
                        <h4>Sezione Tematica</h4>
                    </div>
            </div>

            <div class="form-row">
                <div class="col">&nbsp;</div>
            </div>            

            {% for index, area in areas %}
            {% if loop.first or (index % 3 == 0) %}
            <div class="form-row">
            {% endif %}

                {% set identificativo = 'area-id-' ~ area.id %}
            
                        <div>
                            {{ radio_field('areas_id', 'value' : area.id, 'class' : 'radio-left-pad', 'id' : identificativo ) }} 
                        </div>
                        <label class="col-sm-3 col-form-label" for="{{ identificativo }}"> {{ area.nome }}</label>

            {% if loop.last or (loop.index % 3 == 0) %}
            </div>
            {% endif %}
            {% endfor %} 
            <div class="form-row">
                <div class="col" id="msgerrareatema">
                </div>
        </div> 
    </div>
    
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
                        <h4>Fascia di prezzo</h4>
                    </div>
            </div>

            <div class="form-row">
                <div class="col">&nbsp;</div>
            </div>

            <div class="form-row">            
                <div>
                    {{ radio_field('fasciadiprezzo', 'value' : 'a', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-a' ) }} 
                </div>
                <label class="col-sm-2 col-form-label" for="{{ identificativo }}"> Fascia A</label>
                <div class="col-sm-9">
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
                    {{ radio_field('fasciadiprezzo', 'value' : 'b', 'class' : 'radio-left-pad', 'id' : 'fasciadiprezzo-b' ) }} 
                </div>
                <label class="col-sm-2 col-form-label" for="{{ identificativo }}"> Fascia B</label>
                <div class="col-sm-9">
                        Associazioni locali di categoria, associazioni di secondo livello, cooperative sociali, medieditori, 
                        distributori librari, parchi provinciali, regionali e nazionali, enti locali, consorzi di cooperative, 
                        imprese, associazioni nazionali, grandi produttori diretti (oltre 50 ettari) e altre imprese agricole 
                        non comprese nella fascia A, associazioni nazionali di categoria (profit e no profit), istituzioni, 
                        università, grandi case editrici, botteghe del commercio equo con più di un punto vendita.
                </div>
            </div>            

    </div>


    <div class="form-group">

        <div class="form-row fascia-rossa-flcgu">
                <div class="col">
                    <h4>Scelta dello spazio</h4>
                </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>

        <div class="form-row">
            <div class="col">
                <label for="fieldCodiceStand" class="control-label">Codice Stand</label>
                {{ text_field("codicestand", "class" : "form-control", "id" : "fieldCodiceStand", "maxlength" : "20", "data-toggle" : "tooltip", "data-placement" : "top", "title" : "Se hai consultato la mappa degli spazi espositivi nel nostro sito puoi indicare la tua preferenza") }}
            </div>
            <div class="col"></div>
        </div>

        <div class="form-row">
                <div class="col">&nbsp;</div>
        </div>

        <div class="form-row">
            <div class="col"><p class="text-center">Tutti i prezzi di seguito indicati sono da intendersi <strong>IVA ESCLUSA</strong></p></div>
        </div>

        <div class="form-row">
        <div class="col">&nbsp;</div>
        </div>

        <div class="form-row">
            <div class="col">
                    {% for index, stand in stands %}
                    {% set checked = null %}
                    {% set nome = 'services[' ~ stand.id ~ ']' %}
                    {% set identificativo = 'stand' ~ stand.id %}
                    {% if stand.tipologia == 1 %}
                        {% set onclick = 'return false;' %}
                        {% set checked = 'checked' %}
                    {% else %}
                        {% set onclick = null %}
                        {% set checked = null %}
                    {% endif %}

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                            {{ check_field(nome, 'value' : 1, 'id' : identificativo, 'aria-label' : stand.descrizione, 'checked' : checked, 'onclick' : onclick ) }} 
                            </div>
                        </div>
                        {{ text_field('descserv', "class" : "form-control", "value" : stand.descrizione, 'disabled' : 'disabled') }}
                        <div class="input-group-append">
                                <span class="input-group-text">€</span>
                                <span class="input-group-text" id="prezzo-{{ identificativo }}">{{ '%.2f'|format(stand.prezzofasciaa) ~ ' +IVA' }}</span>
                                <span class="input-group-text"></span>
                        </div>
                    </div>
                    {{ hidden_field('stand' ~ stand.id ~ 'a', 'id' : 'stand' ~ stand.id ~ 'a', "value" : '%.2f'|format(stand.prezzofasciaa), 'disabled' : 'disabled') }}
                    {{ hidden_field('stand' ~ stand.id ~ 'b', 'id' : 'stand' ~ stand.id ~ 'b', "value" : '%.2f'|format(stand.prezzofasciab), 'disabled' : 'disabled') }}
                    {% endfor %}
            </div>
        </div>

    <div class="form-row">
        <div class="col">
            <label for="fieldStandpersonalizzato" class="control-label">Spazio personalizzato concordato con l'organizzazione</label>
            {{ text_area('standpersonalizzato', "rows" : 2, "class" : "form-control", "id" : "fieldStandpersonalizzato" ) }}
        </div>
    </div>

    <div class="form-row">
        <div class="col-sm-12" id="msgerrstand">&nbsp;</div>
    </div>

    <div class="form-row">
        <div class="col">
            <h4>Altri servizi</h4>
        </div>
    </div>


                {% for index, service in services %}
                    {% set identificativo = 'servizio' ~ service.id %}
                    {% set nome = 'services[' ~ service.id ~ ']' %}

                    <div class="form-row">
                            <div class="col">
                <div class="input-group mb-3">

                    <div class="input-group-prepend">
                            <div class="input-group-text">
                                {{  service.descrizione|trim }}
                            </div>
                    </div>

                    {{ numeric_field(nome, 'value' : 0, 'class' : 'form-control', 'id' : identificativo, 'min' : '0', 'max' : '20' ) }}

                    <div class="input-group-append">

                            <span class="input-group-text">€</span>
                            {{ text_field('prezzo', "id": 'prezzoserv' ~ service.id, "class" : "form-control", "value" : '%.2f'|format(service.prezzofasciaa), 'disabled' : 'disabled') }}

                    </div>
                    
                </div>   
                {{ hidden_field('prezzoserv' ~ service.id ~ 'a', 'id' : 'prezzoserv' ~ service.id ~ 'a', "value" : '%.2f'|format(service.prezzofasciaa)) }}
                {{ hidden_field('prezzoserv' ~ service.id ~ 'b', 'id' : 'prezzoserv' ~ service.id ~ 'b', "value" : '%.2f'|format(service.prezzofasciab)) }}      
            </div>
        </div>               
                {% endfor %}

        <!-- text area per il campo altriservizi -->
        <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Altri servizi non in elenco:</span>
                </div>
                {{ text_area('altriservizi', "rows" : 2, "class" : "form-control", "id" : "fieldAltriservizi" ) }}
        </div>


    <div class="form-row">
        <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-row">
        <div class="col-sm-12"><hr></div>
    </div>    

</div>

<div class="form-group">

    <div class="form-row fascia-rossa-flcgu">
            <div class="col">
                <h4>Co-espositore</h4>
            </div>
    </div>

    <div class="form-row">
        <div class="col">&nbsp;</div>
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
                {{ numeric_field('numerocoespositore', "size" : 3, "class" : "form-control", "id" : "fieldNumerocoespositore", "value" : 0) }}
            </div>
            <div class="col-sm-8">
                <label for="fieldNomecoespositore" class="control-label">Nome delle realtà espositive ospitate</label>
                {{ text_field('nomecoespositore', "size" : 30, "class" : "form-control", "id" : "fieldNomecoespositore") }}
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

        <div class="form-row fascia-rossa-flcgu">
                <div class="col">
                    <h4>Dati per il catalogo</h4>
                </div>
        </div>

        <div class="form-row">
            <div class="col">&nbsp;</div>
        </div>        

        <div class="form-row">
                <div class="col-xs-12">
                    Saranno inseriti nel catalogo solo gli espositori che si iscrivono entro il 20 luglio e che compilano la seguente sezione
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
                <label for="fieldCatalogodescrizione" class="control-label">Descrizione</label>
                {{ text_field("catalogodescrizione", "cols" : 30, "rows" : 4, "class" : "form-control", "id" : "fieldCatalogodescrizione") }}
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
            {{ submit_button('Salva',"class" : "btn btn-primary") }}
        </div>
    </div>
    
    {{ end_form() }}
    

    {{ hidden_field('arraystand', 'id' : 'arraystand', "value" : arraystand) }}
    {{ hidden_field('arrayservizi', 'id' : 'arrayservizi', "value" : arrayservizi) }}


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