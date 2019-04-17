
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
    <li class="breadcrumb-item">
    <a href="/reservations/index">Gestione Prenotazioni</a>
    </li>
    <li class="breadcrumb-item active">
        Stand
    </li>
    <li class="breadcrumb-item active">{{ reservation.exhibitors.ragionesociale }} </li>
    </ol>
    
    {{ content() }}

    

    {% if !reservation.padre_id is empty %}

    <div class="form-group">
        <h2>{{ reservation.exhibitors.ragionesociale }} è co-espositore di {{ mainreservation.exhibitors.ragionesociale}}</h2>
    </div>

    {{ partial('partials/coespositoreedit') }}


    {% else %}

    {{ form('reservations/save', 'method': 'post', "autocomplete" : "off", "class" : "form-horizontal") }}

    <div class="row">
        <div class="col-sm-3">
            <label for="fieldAreasId" class="control-label">Area Tematica</label>
            {{ select('areas_id', areas, 'using': ['id', 'nome'],'class' : 'form-control') }}
        </div>
        <div class="col-sm-2">
                <label class="control-label">&nbsp;</label>
                {% set cipc = null %} 
                {% if reservation.interventoprogrammaculturale == 1 %} 
                    {% set cipc = 'checked' %} 
                {% endif %}
                <div class="form-check form-control-lg">
                {{ check_field('interventoprogrammaculturale', 'value': 1, 'checked' : cipc, 'class' : 'form-check-input', 'id' : 'cbipc') }}
                <label class="form-check-label" for="cbipc"> Intervento Prog. Cult.</label>
                </div>
        </div>
        <div class="col-sm-2">
            <label for="fieldCodicestand" class="control-label">Codice stand</label>
            {{ text_field('codicestand', 'size': 20,  "class" : "form-control", "id" : "fieldCodicestand", "value" : reservation.codicestand) }}
        </div>          
        <div class="col-sm-2">
            <label for="fieldPadiglione" class="control-label">Padiglione</label>
            {{ text_field('padiglione', 'size': 20,  "class" : "form-control", "id" : "fieldPadiglione", "value" : reservation.padiglione) }}
        </div>    
        <div class="col-sm-3">
            <label for="fieldCommerciale" class="control-label">Commerciale di riferimento</label>
            {{ select('users_id', users, 'using': ['id', 'username'],'class' : 'form-control', 'useEmpty' : true, 'emptyText'  : '------') }}
        </div>    
    </div>

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="row">
            <div class="col-sm-12"><h4>Tipo di fascia: <span class="badge badge-success">fascia {{ reservation.Exhibitors.fasciadiprezzo }}</span></h4></div>
    </div>

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="row">
            <div class="col-sm-4">
                    <h4>Stand richiesto dall'espositore:</h4>
                    {% for index, stand in stands %}
                    {% set checked = null %}
                    {% set nome = 'services[' ~ stand.id ~ ']' %}
                    {% set identificativo = 'servizio' ~ stand.id %}
                    {% for indice, reservationservice in reservationservices %}
                        {% if reservationservice.services_id == stand.id %} 
                            {% set checked = 'checked' %} 
                            {% break %}
                        {% endif %}
                    {% endfor %}
                
                    <div class="form-check form-control-lg">
                            {{ check_field(nome, 'value' : 1, 'checked' : checked, 'class' : 'form-check-input', 'id' : identificativo ) }} 
                                <label class="form-check-label" for="{{ identificativo }}">{{ stand.descrizione }}
                                    {% if reservation.exhibitors.fasciadiprezzo === 'a' %}
                                        € {{ '%.2f'|format(stand.prezzofasciaa) }}
                                    {% else %} 
                                        € {{ '%.2f'|format(stand.prezzofasciab) }}
                                    {% endif %}
                                </label>
                    </div>         
                     
                    {% endfor %}
            </div>
            <div class="col-sm-4">
                    <h4>Servizi richiesti dall'espositore:</h4>
                    {% for index, service in services %}
                        {% set valore = 0 %}
                        {% set identificativo = 'servizio' ~ service.id %}
                        {% set nome = 'services[' ~ service.id ~ ']' %}
                        {% for indice, reservationservice in reservationservices %}

                            {% if reservationservice.services_id == service.id %} 
                                {% set valore = reservationservice.quantita %}
                                {% break %}
                            {% endif %}
                
                        {% endfor %}
                
                    <div class="form-group row">
                        <label for="{{ identificativo }}" class="col-sm-6">{{ service.descrizione|trim }}
                            {% if reservation.exhibitors.fasciadiprezzo === 'a' %}
                            € {{ '%.2f'|format(service.prezzofasciaa) }}
                            {% else %}
                            € {{ '%.2f'|format(service.prezzofasciab) }}
                            {% endif %}
                        </label>
                        <div class="col-sm-6">
                            {{ numeric_field(nome, 'value' : valore, 'class' : 'form-control', 'id' : identificativo, 'min' : '0', 'max' : '20' ) }}
                        </div>
                    </div>         
                     
                    {% endfor %}
                    <!-- text area per il campo altriservizi -->
                    <div class="form-group row">
                            <label for="fieldAltriservizi" class="col-sm-3">Altri servizi:</label>
                            <div class="col-sm-5">
                            {{ text_field('altriservizi', "class" : "form-control", "id" : "fieldAltriservizi", "value" : reservation.altriservizi ) }}
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="fieldPrezzoaltriservizi">€</span>
                                    </div>
                                    {{ numeric_field('prezzoaltriservizi', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldPrezzoaltriservizi", "value" : '%.2f'|format(reservation.prezzoaltriservizi)) }}
                                </div>
                            </div>
                    </div>
            </div>
            <div class="col-sm-4"><h4>Riepilogo contabile</h4>
                <table id="riepilogocontabile" class="table-responsive table-sm table-riepilogo">
                    <tr><th>descrizione</th><th class="text-right">costo</th><th class="text-right">q.</th><th class="text-right">tot</th><th class="text-right">iva</th></tr>
                    {% set totale = 0 %}
                    {% set totiva = 0 %}
                    {% for indice, reservationservice in reservationservices %}
                    <tr>                    
                    <td>{{ reservationservice.services.descrizione }}</td>
                    {% if reservation.exhibitors.fasciadiprezzo === 'a' %}
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciaa)}}</td>
                        <td class="text-right">{{ reservationservice.quantita }}</td>
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciaa * reservationservice.quantita) }}</td>
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciaa * reservationservice.quantita * 0.22) }}</td>
                        {% set totale = totale + reservationservice.services.prezzofasciaa * reservationservice.quantita %}
                        {% set totiva = totiva + reservationservice.services.prezzofasciaa * reservationservice.quantita * 0.22 %}
                    {% else %} 
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciab) }}</td>
                        <td class="text-right">{{ reservationservice.quantita }}</td>
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciab * reservationservice.quantita) }}</td>
                        <td class="text-right">€&nbsp;{{ '%.2f'|format(reservationservice.services.prezzofasciab * reservationservice.quantita * 0.22) }}</td>
                        {% set totale = totale + reservationservice.services.prezzofasciab * reservationservice.quantita %}
                        {% set totiva = totiva + reservationservice.services.prezzofasciab * reservationservice.quantita * 0.22 %}
                    {% endif %}
                    </tr>
                    {% endfor %}
                    <!-- prezzo stand personalizzato -->
                    {% if reservation.prezzostandpersonalizzato > 0 %}
                    <tr><td>Stand Personalizzato</td><td class="text-right">€&nbsp;{{ '%.2f'|format(reservation.prezzostandpersonalizzato) }}</td><td class="text-right">1</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzostandpersonalizzato) }}</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzostandpersonalizzato + reservation.prezzostandpersonalizzato * 0.22) }}</td></tr>
                    {% set totale = totale + reservation.prezzostandpersonalizzato %}
                    {% set totiva = totiva + reservation.prezzostandpersonalizzato * 0.22 %}
                    {% endif %}
                    <tr></tr>
                    <!-- prezzo altri servizi -->
                    {% if reservation.prezzoaltriservizi > 0 %}
                    <tr><td>Altri servizi: {{ reservation.altriservizi }}</td><td class="text-right">€&nbsp;{{ '%.2f'|format(reservation.prezzoaltriservizi) }}</td><td class="text-right">1</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzoaltriservizi) }}</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzoaltriservizi + reservation.prezzoaltriservizi * 0.22) }}</td></tr>
                    {% set totale = totale + reservation.prezzoaltriservizi %}
                    {% set totiva = totiva + reservation.prezzoaltriservizi * 0.22 %}
                    {% endif %}
                    <tr></tr>                    
                    <tr><td></td><td></td><td></td><th class="text-right">€&nbsp;{{ '%.2f'|format(totale) }}</th><th class="text-right">€&nbsp;{{ '%.2f'|format(totiva) }}</th></tr>
                    <tr><td></td><th class="text-right" colspan="2">Tot. iva comp.</th><th class="text-right" colspan="2">€&nbsp;{{ '%.2f'|format(totale + totale * 0.22) }}</th></tr>
                </table>
            </div>
    </div>

    <div class="row">
            <div class="col">
                <label for="fieldStandpersonalizzato" class="control-label">Stand personalizzato</label>
                {{ text_area('standpersonalizzato', "rows" : 2, "class" : "form-control", "id" : "fieldStandpersonalizzato", "value" : reservation.standpersonalizzato ) }}
            </div>
    </div>

    <div class="row">
        <div class="col-sm-12">&nbsp;</div>
    </div>  

    <div class="row">
            <div class="col">

                <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fieldPrezzostandpersonalizzato">Prezzo stand personalizzato €</span>
                        </div>
                        {{ numeric_field('prezzostandpersonalizzato', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldPrezzostandpersonalizzato", "value" : '%.2f'|format(reservation.prezzostandpersonalizzato)) }}
                </div>
                    
            </div>
    </div>

    <div class="row">
            <div class="col-sm-12"><hr></div>
    </div>    

    <div class="row">
            <div class="col-sm-12"><h4>Pagamento</h4></div>
    </div>   

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="fieldprezzocalcolato">Prezzo totale calcolato €</span>
                    </div>
                    {{ text_field('prezzocalcolato', "class" : "form-control", "id" : "prezzocalcolato", "value" : '%.2f'|format(totale),'disabled' : 'disabled') }}
            </div>
        </div> 
        <div class="col-sm-6">
            <div class="row">
                <div class="col">
                        <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Prezzo finale €</span>
                                </div>
                                {% if reservation.prezzofinale is empty or reservation.prezzofinale == 0 %}
                                {% set prezzofinale = totale %}
                                {% else %}
                                {% set prezzofinale = reservation.prezzofinale %}
                                {% endif %}
                                {{ numeric_field('prezzofinale', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldprezzofinale", "value" : '%.2f'|format(prezzofinale)) }}
                                <span class="input-group-text" id="fieldprezzofinaleivato">Prezzo finale + iva € {{ '%.2f'|format(prezzofinale + prezzofinale * 0.22) }}</span>
                        </div>
                </div>
            </div>
            <div class="row">
                    <div class="col">
                            <div class="progress" style="margin-top: 2px;">
                                {% set sconto = (1 - prezzofinale/totale) * 100 %}
                                {% set valorecompementare = (prezzofinale/totale) * 100 %}
                                    <div id="sconto" class="progress-bar bg-success" role="progressbar" style="width: {{ '%d'|format(valorecompementare) }}%;" aria-valuenow="{{ '%d'|format(sconto) }}" aria-valuemin="0" aria-valuemax="100">sconto {{ '%d'|format(sconto) }}%</div>
                            </div>                    
                    </div>
                </div>

        </div>           
    </div>

    {{ hidden_field("id", "value" : reservation.id, "id" : "reservationid") }}
    {# { hidden_field("stato", "value" : reservation.stato, "id" : "statohidden") } #}

    <div class="row">
            <div class="col-sm-3">
                    <label for="fieldStandpersonalizzato" class="control-label">Numero Fattura</label>
                    {{ text_field('numerofattura', "class" : "form-control", "id" : "numerofattura", "value" : reservation.numerofattura) }}
            </div>
            <div class="col-sm-9">
                <label for="fieldNotepagamento" class="control-label">Note pagamento (staff only)</label>
                {{ text_area('notepagamento', "rows" : 4, "class" : "form-control", "id" : "fieldNotepagamento", "value" : reservation.notepagamento ) }}
            </div>
    </div>

    <div class="row">
            <div class="col">
                <label for="fieldNotecondivise" class="control-label">Note condivise con l'espositore</label>
                {{ text_area('notecondivise', "rows" : 4, "class" : "form-control", "id" : "fieldNotecondivise", "value" : reservation.notecondivise ) }}
            </div>
    </div>

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>    
    
    <div class="row">
            <div class="col-sm-12"><h4>Stato pagamenti</h4></div>
    </div>   
    
    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>
    
    <div class="row">
            <div class="col-sm-1 hidden-xs">&nbsp;</div>
            <div class="col-sm-10 col-xs-12">
                <label for="customRange2" id="descrizionestato">Stato: 
                    {% for i, stato in stati  %}
                    {% if reservation.stato == stato.id %} {% set visible = 'inline' %} {% else %} {% set visible = 'none' %} {% endif %}
                    <span id="badge-stato-{{ stato.id }}" class="badge badge-{{ stato.colore }}" title="{{ stato.descrizionestato }}" style="display:{{ visible }}">{{ stato.descrizionestato }}</span>
                    {% endfor %}
                </label>
                <input type="range" name="stato" class="custom-range" min="1" max="{{ statimax }}" step="1" value="{{ reservation.stato }}">
            </div>
            <div class="col-sm-1 hidden-xs">&nbsp;</div>
    </div>

    <div class="row">
                {% for i, stato in stati  %}
                <div class="col hidden-xs text-center">
                <span id="legenda-stato-{{ stato.id }}" class="badge badge-{{ stato.colore }}" title="{{ stato.descrizionestato }}" >{{ stato.descrizionebreve }}</span>            
                </div>
                {% endfor %}
    </div>
    

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ submit_button('Salva le modifiche',"class" : "btn btn-lg btn-primary") }}
        </div>
    </div>

    <!-- Sezione per la generazione della Lettera di ammissione -->
    <div class="jumbotron">
        <h3>Lettera di ammissione</h3>
        <p>Cliccando sul pulsante sottostante puoi decidere di generare il pdf in anteprima oppure di inviarlo anche all'espositore come allegato email</p>
        <div class="row">
            <div class="col">{{ link_to('exhibitors/testinvio/' ~ reservation.exhibitors.id, "Test invio email conferma", 'role': 'button', 'class': 'btn btn-danger btn-lg') }}</div>
            <div class="col"><div class="form-group">{{ link_to('reservations/anteprimalettera/' ~ reservation.id, "Anteprima Lettera di ammissione", 'target' : '_blank', 'role': 'button', 'class': 'btn btn-primary btn-lg') }}</div></div>
            <div class="col"><div class="form-group">{{ link_to('reservations/invialettera/' ~ reservation.id, "&nbsp;<i class='far fa-envelope'></i>&nbsp;&nbsp;Invia Lettera di ammissione", 'id':'inviolettera', 'role': 'button', 'class': 'btn btn-lg btn-warning', "data-toggle" : "tooltip", "data-placement" : "top", "title" : "La lettera di ammissione verrà inviata all'indirizzo " ~ reservation.exhibitors.emailaziendale) }}</div></div>
        </div>
    </div>


    <div class="row">
            <div class="col-sm-12 col-md-6">
                    <div class="jumbotron">
                            <h3>Generazione dati per il catalogo</h3>
                            <p>Cliccando sul bottone sottostante puoi generare un file di testo con i dati del catalogo di questo espositore.</p>
                    
                            <div class="row">
                                    <div class="col-8">{{ link_to('reservations/daticatalogo/' ~ reservation.id, "&nbsp;<i class='fas fa-file-invoice-dollar'></i>&nbsp;&nbsp;Genera i dati per il catalogo", 'role': 'button', 'class': 'btn btn-lg btn-warning', "data-toggle" : "tooltip", "data-placement" : "top", "title" : "Verranno generati in formato txt tutti i dati del catalogo") }}</div>
                            </div>
                    
                    </div>
            </div>
            <div class="col-sm-12 col-md-6">    <!-- Sezione per la generazione della fattura -->
                <div class="jumbotron">
                        <h3>Generazione Fattura</h3>
                        <p>Cliccando sul pulsante sottostante puoi generare il fac simile della la fattura di questo espositore</p>
                        <div class="row">
                            <div class="col">{{ link_to('reservations/facsimilefattura/' ~ reservation.id, "&nbsp;<i class='fas fa-file-invoice-dollar'></i>&nbsp;&nbsp;Genera il Fac Simile della fattura", 'role': 'button', 'class': 'btn btn-lg btn-warning', "data-toggle" : "tooltip", "data-placement" : "top", "title" : "Verranno generati in formato txt tutti i dati necessari alla fattura") }}</div>
                        </div>
                </div>
            </div>
    </div>






        {{ end_form() }}

    <!-- Sezione dei dati catalogo -->
    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>


    {% endif %}

    <!-- Sezione del diario/log-->
    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

<!-- zigzag timeline -->
<div class="jumbotron">
    {{ partial('partials/diario') }}
</div>
<!-- end zigzag timeline-->


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

<!-- modale per la risposta ajax -->
<div class="modal fade" id="SuccessInsertModal" tabindex="-1" role="dialog" aria-labelledby="SuccessInsertModalLabel" aria-hidden="true">
    <div class="modal-dialog .modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="SuccessInsertModalLabel">Invio della lettare di ammissione</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="contenutosuccess"></div>
        <div class="modal-footer">
          <a class="btn btn-primary" data-dismiss="modal">OK</a>
        </div>
      </div>
    </div>
</div>
<!-- spinner per attesa alla risposta ajax -->
<div class="modal fade" id="modalspinner" tabindex="-1" role="dialog" aria-labelledby="modalspinner" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <i class="fas fa-spinner fa-spin"></i> elaborazione in corso...
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Modal-->
{{ partial('partials/logoutmodal') }}