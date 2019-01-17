
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
    <li class="breadcrumb-item">
    <a href="/reservations/index">Gestione Stand</a>
    </li>
    <li class="breadcrumb-item active">{{ reservation.exhibitors.ragionesociale }} </li>
    </ol>
    
    {{ content() }}

    

    {% if !reservation.padre_id is empty %}

    <div class="form-group">
        <h2>Attenzione! Questo espositore è co-espositore di {{ reservation.getpadri().ragionesociale}}
    </div>

    {% else %}

    {{ form('reservations/save', 'method': 'post', "autocomplete" : "off", "class" : "form-horizontal") }}

    <div class="row">
        <div class="col-sm-4">
            <label for="fieldAreasId" class="control-label">Area Tematica</label>
            {{ select('areas_id', areas, 'using': ['id', 'nome'],'class' : 'form-control') }}
        </div>
        <div class="col-sm-4">
                <label class="control-label">&nbsp;</label>
                {% set cipc = null %} 
                {% if reservation.interventoprogrammaculturale == 1 %} 
                    {% set cipc = 'checked' %} 
                {% endif %}
                <div class="form-check form-control-lg">
                {{ check_field('interventoprogrammaculturale', 'value': 1, 'checked' : cipc, 'class' : 'form-check-input', 'id' : 'cbipc') }}
                <label class="form-check-label" for="cbipc"> Intervento Programma Culturale</label>
                </div>
        </div>
        <div class="col-sm-4">
            <label for="fieldCodicestand" class="control-label">Codice stand</label>
            {{ text_field('codicestand', 'size': 20,  "class" : "form-control", "id" : "fieldCodicestand", "value" : reservation.codicestand) }}
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
                <table id="riepilogocontabile" class="table table-sm table-riepilogo">
                    <tr><th>descrizione</th><th class="text-right">costo&nbsp;un.</th><th class="text-right">quant.</th><th class="text-right">tot</th><th class="text-right">tot+iva</th></tr>
                    {% set totale = 0 %}
                    {% for indice, reservationservice in reservationservices %}
                    <tr>                    
                    <td>{{ reservationservice.services.descrizione }}</td>
                    {% if reservation.exhibitors.fasciadiprezzo === 'a' %}
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciaa)}}</td>
                        <td class="text-right">{{ reservationservice.quantita }}</td>
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciaa * reservationservice.quantita) }}</td>
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciaa * reservationservice.quantita + reservationservice.services.prezzofasciaa * reservationservice.quantita * 0.22) }}</td>
                        {% set totale = totale + reservationservice.services.prezzofasciaa * reservationservice.quantita %}
                    {% else %} 
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciab) }}</td>
                        <td class="text-right">{{ reservationservice.quantita }}</td>
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciab * reservationservice.quantita) }}</td>
                        <td class="text-right">€ {{ '%.2f'|format(reservationservice.services.prezzofasciab * reservationservice.quantita + reservationservice.services.prezzofasciab * reservationservice.quantita * 0.22) }}</td>
                        {% set totale = totale + reservationservice.services.prezzofasciab * reservationservice.quantita %}
                    {% endif %}
                    </tr>
                    {% endfor %}
                    <!-- prezzo stand personalizzato -->
                    {% if reservation.prezzostandpersonalizzato > 0 %}
                    <tr><td>Stand Personalizzato</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzostandpersonalizzato) }}</td><td class="text-right">1</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzostandpersonalizzato) }}</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzostandpersonalizzato + reservation.prezzostandpersonalizzato * 0.22) }}</td></tr>
                    {% set totale = totale + reservation.prezzostandpersonalizzato %}
                    {% endif %}
                    <tr></tr>
                    <!-- prezzo altri servizi -->
                    {% if reservation.prezzoaltriservizi > 0 %}
                    <tr><td>Altri servizi: {{ reservation.altriservizi }}</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzoaltriservizi) }}</td><td class="text-right">1</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzoaltriservizi) }}</td><td class="text-right">€ {{ '%.2f'|format(reservation.prezzoaltriservizi + reservation.prezzoaltriservizi * 0.22) }}</td></tr>
                    {% set totale = totale + reservation.prezzoaltriservizi %}
                    {% endif %}
                    <tr></tr>                    
                    <tr><td></td><td></td><td></td><th class="text-right">€ {{ '%.2f'|format(totale) }}</th><th class="text-right">€ {{ '%.2f'|format(totale + totale * 0.22) }}</th></tr>
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
                                  <span class="input-group-text">Prezzo totale concordato €</span>
                                </div>
                                {% if reservation.prezzofinale is empty or reservation.prezzofinale == 0 %}
                                {% set prezzofinale = totale %}
                                {% else %}
                                {% set prezzofinale = reservation.prezzofinale %}
                                {% endif %}
                                {{ numeric_field('prezzofinale', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldprezzofinale", "value" : '%.2f'|format(prezzofinale)) }}
                                <span class="input-group-text" id="fieldprezzofinaleivato">Prezzo totale concordato + iva € {{ '%.2f'|format(prezzofinale + prezzofinale * 0.22) }}</span>
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

    {{ hidden_field("id", "value" : reservation.id) }}
    {# { hidden_field("stato", "value" : reservation.stato, "id" : "statohidden") } #}

    <div class="row">
            <div class="col-sm-12">
                <label for="fieldNotepagamento" class="control-label">Note pagamento</label>
                {{ text_area('notepagamento', "rows" : 4, "class" : "form-control", "id" : "fieldNotepagamento", "value" : reservation.notepagamento ) }}
            </div>
    </div>

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ submit_button('Salva le modifiche',"class" : "btn btn-lg btn-primary") }}
        </div>
    </div>



    {% endif %}
    
    <!-- Sezione per la generazione della Lettera di ammissione -->
    <div class="jumbotron">
        <h3>Lettera di ammissione</h3>
        <p>Cliccando sul pulsante sottostante puoi decidere di generare il pdf in anteprima oppure di inviarlo anche all'espositore come allegato email</p>
        <hr class="my-4">
        <div class="row text-center">
            <div class="col">{{ link_to('reservations/anteprimalettera/' ~ reservation.id, "Anteprima Lettera di ammissione", 'target' : '_blank', 'role': 'button', 'class': 'btn btn-primary btn-lg') }}</div>
            <div class="col">{{ link_to('#', "&nbsp;<i class='far fa-envelope'></i>&nbsp;&nbsp;Invia la Lettera di ammissione all'espositore", 'role': 'button', 'class': 'btn btn-lg btn-warning', "data-toggle" : "tooltip", "data-placement" : "top", "title" : "La lettera di ammissione verrà inviata all'indirizzo " ~ reservation.exhibitors.emailaziendale) }}</div>
        </div>
    </div>

    <!-- Sezione per la generazione della fattura -->
    <div class="jumbotron">
            <h3>Generazione Fattura</h3>
            <p>Cliccando sul pulsante sottostante puoi generare la fattura</p>
            <hr class="my-4">
            <div class="row text-center">
                    <div class="col-4">
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="umerofatturalabel">Numero Fattura</span>
                                    </div>
                                    {{ text_field('numerofattura', "class" : "form-control", "id" : "numerofattura", "value" : reservation.numerofattura) }}
                            </div>
                    </div>
                <div class="col-4">{{ link_to('reservations/facsimilefattura/' ~ reservation.id, "&nbsp;<i class='fas fa-file-invoice-dollar'></i>&nbsp;&nbsp;Genera il Fac Simile della fattura", 'role': 'button', 'class': 'btn btn-lg btn-warning', "data-toggle" : "tooltip", "data-placement" : "top", "title" : "Verranno generati in formato txt tutti i dati necessari alla fattura") }}</div>
                <div class="col-4 text-left">
                        {% set car = null %} 
                        {% if reservation.anticiporichiesto == 1 %} 
                            {% set car = 'checked' %} 
                        {% endif %}
                        <div class="form-check form-control-lg">
                        {{ check_field('anticiporichiesto', 'value': 1, 'checked' : car, 'class' : 'form-check-input', 'id' : 'cbar') }}
                        <label class="form-check-label" for="cbipc">Richiesta anticipo inviata</label>
                        </div>  

                        {% set capag = null %} 
                        {% if reservation.anticipopagato == 1 %} 
                            {% set capag = 'checked' %} 
                        {% endif %}
                        <div class="form-check form-control-lg">
                        {{ check_field('anticipopagato', 'value': 1, 'checked' : capag, 'class' : 'form-check-input', 'id' : 'cbap') }}
                        <label class="form-check-label" for="cbipc">Anticipo Pagato</label>
                        </div>  

                        {% set cpc = null %} 
                        {% if reservation.pagamentocompleto == 1 %} 
                            {% set cpc = 'checked' %} 
                        {% endif %}
                        <div class="form-check form-control-lg">
                        {{ check_field('pagamentocompleto', 'value': 1, 'checked' : cpc, 'class' : 'form-check-input', 'id' : 'cbpc') }}
                        <label class="form-check-label" for="cbipc">Pagamento Completo</label>
                        </div>  
                </div>
            </div>
    </div>


    <div class="row">
            <div class="col-sm-12"><hr></div>
    </div>    
    
    <div class="row">
            <div class="col-sm-12"><h4>Stato della richiesta</h4></div>
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