{{ form('reservations/save', 'method': 'post', "autocomplete" : "off", "class" : "form-horizontal") }}
<div class="row">
        <div class="col-sm-3">
            
            <p><strong>Area Tematica:</strong> {{ reservation.areas.nome }}</p>
        </div>
        <div class="col-sm-3">
                {% set cipc = null %} 
                {% if reservation.interventoprogrammaculturale == 1 %} 
                    {% set cipc = 'checked' %} 
                {% endif %}
                <div class="form-check form-control-lg">
                {{ check_field('interventoprogrammaculturale', 'value': 1, 'checked' : cipc, 'class' : 'form-check-input', 'id' : 'cbipc') }}
                <label class="form-check-label" for="cbipc"> Intervento Programma Culturale</label>
                </div>
        </div>
        <div class="col-sm-3">
            
            <p><strong>Codice Stand:</strong> {{ reservation.codicestand }}</p>
        </div>          
        <div class="col-sm-3">
            
            <p><strong>Padiglione:</strong> {{ reservation.padiglione }}</p>
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
                    <h4>Stand e servizi già richiesti da {{ mainreservation.exhibitors.ragionesociale}}:</h4>
                    <ul>
                    {% set totale = 0 %}
                    {% for indice, reservationservice in mainreservationservices %}
                    <li>
                        {{ reservationservice.quantita }} {{ reservationservice.services.descrizione }}
                            {% if reservation.exhibitors.fasciadiprezzo === 'a' %}
                                € {{ '%.2f'|format(reservationservice.services.prezzofasciaa) }}
                                {% set totale = totale + reservationservice.services.prezzofasciaa * reservationservice.quantita %}
                            {% else %} 
                                € {{ '%.2f'|format(reservationservice.services.prezzofasciab) }}
                                {% set totale = totale + reservationservice.services.prezzofasciaa * reservationservice.quantita %}
                            {% endif %}
                    </li>         
                     
                    {% endfor %}

                    {% if mainreservation.standpersonalizzato is defined %}

                    <li>
                            Stand personalizzato: {{ mainreservation.standpersonalizzato }} € {{ '%.2f'|format(mainreservation.prezzostandpersonalizzato) }}
                    </li>  
                    {% set totale = totale + mainreservation.prezzostandpersonalizzato %}
                    
                    {% endif %}

                    {% if mainreservation.altriservizi is defined %}

                    <li>
                            Altri servizi: {{ mainreservation.altriservizi }} € {{ '%.2f'|format(mainreservation.prezzoaltriservizi) }}
                    </li>  
                    {% set totale = totale + mainreservation.prezzoaltriservizi %}
                    
                    {% endif %}                    
                    </ul>
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
                      <span class="input-group-text" id="fieldprezzocalcolato">Prezzo totale calcolato € {{ '%.2f'|format(totale) }}</span>
                    </div>
                    
            </div>
        </div> 
        {% if reservation.prezzofinale is empty or reservation.prezzofinale == 0 %}
        {% set prezzofinale = totale %}
        {% else %}
        {% set prezzofinale = mainreservation.prezzofinale %}
        {% endif %}

        <div class="col-sm-6">
            <div class="row">
                <div class="col">
                        <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">Prezzo finale € {{ '%.2f'|format(prezzofinale) }}</span>
                                </div>

                                <span class="input-group-text" id="fieldprezzofinaleivato">Prezzo finale + iva € {{ '%.2f'|format(prezzofinale + prezzofinale * 0.22) }}</span>
                        </div>
                </div>
            </div>
        </div>           
    </div>
    {# { hidden_field("stato", "value" : mainreservation.stato, "id" : "statohidden") } #}

    <div class="row">
            <div class="col-sm-3">
                    <label for="fieldStandpersonalizzato" class="control-label">Numero Fattura</label>
                    {{ mainreservation.numerofattura }}
            </div>
            <div class="col-sm-9">
                <label for="fieldNotepagamento" class="control-label">Note pagamento: {{ mainreservation.notepagamento }}</label>
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
                <label id="descrizionestato">Stato: {{ mainreservation.stati.descrizionestato }}</label>
            </div>
            <div class="col-sm-1 hidden-xs">&nbsp;</div>
    </div>    

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ hidden_field("id", "value" : reservation.id, "id" : "reservationid") }}
            {{ submit_button('Salva le modifiche',"class" : "btn btn-lg btn-primary") }}
        </div>
    </div>
        {{ end_form() }}
