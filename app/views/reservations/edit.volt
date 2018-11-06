
{{ partial('partials/navbar') }}

<div id="wrapper">

<!-- Sidebar -->
{{ partial('partials/sidebar') }}

<div id="content-wrapper">

  <div class="container-fluid">

    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
    <li class="breadcrumb-item">
    <a href="/exhibitors/index">Stand</a>
    </li>
    <li class="breadcrumb-item active">{{ reservation.getexhibitors().ragionesociale }}</li>
    </ol>
    
    {{ content() }}

    {% if !reservation.padre_id is empty %}

    <div class="form-group">
        <h2>Attenzione! Questo espositore è co-espositore di {{ reservation.getpadri().ragionesociale}}
    </div>

    {% else %}

    {{ form('reservations/save', 'method': 'post', "autocomplete" : "off", "class" : "form-horizontal") }}

    <div class="row">
        <div class="col-sm-6">
            <label for="fieldAreasId" class="control-label">Area Tematica</label>
            {{ select('areas_id', areas, 'using': ['id', 'nome'],'class' : 'form-control') }}
        </div>
        <div class="col-sm-6">
            <label for="fieldCodicestand" class="control-label">Codice stand</label>
            {{ text_field('codicestand', 'size': 20,  "class" : "form-control", "id" : "fieldCodicestand", "value" : reservation.codicestand) }}
        </div>
    </div>

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="row">
            <div class="col-sm-6"><h4>Stand richiesto dell'espositore:</h4><p>Tipo di fascia: <span class="badge badge-success">fascia {{ reservation.getExhibitors().fasciadiprezzo }}</span></p></div>
            <div class="col-sm-6"><h4>Servizi richiesti dell'espositore:</h4><p></p></div>
    </div>

    <div class="row">
            <div class="col-sm-6">
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
                                <label class="form-check-label" for="inlineCheckbox1">{{ stand.descrizione }}
                                    {% if reservation.getExhibitors().fasciadiprezzo === 'A' %}
                                        € {{ '%.2f'|format(stand.prezzofasciaa) }}
                                    {% else %} 
                                        € {{ '%.2f'|format(stand.prezzofasciab) }}
                                    {% endif %}
                                </label>
                    </div>         
                     
                    {% endfor %}
            </div>
            <div class="col-sm-6">
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
                            {% if reservation.getExhibitors().fasciadiprezzo === 'A' %}
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
                            <label for="fieldAltriservizi" class="col-sm-6">Altri servizi non in elenco:</label>
                            <div class="col-sm-6">
                            {{ text_area('altriservizi', "rows" : 2, "class" : "form-control", "id" : "fieldAltriservizi", "value" : reservation.altriservizi ) }}
                            </div>
                    </div>
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
        <div class="col-sm-12">
            <label for="fieldNotepagamento" class="control-label">Note pagamento</label>
            {{ text_area('notepagamento', "rows" : 4, "class" : "form-control", "id" : "fieldNotepagamento", "value" : reservation.notepagamento ) }}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="fieldprezzofinale">Prezzo totale calcolato €</span>
                    </div>
                    {{ numeric_field('prezzofinale', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldprezzofinale", "value" : '%.2f'|format(reservation.prezzofinale),'disabled' : 'disabled') }}
            </div>
        </div> 
        <div class="col-sm-6">
            <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="fieldprezzofinale">Prezzo totale concordato €</span>
                    </div>
                    {{ numeric_field('prezzofinale', 'min': 0, "max" : 20000, "step" : 1, "class" : "form-control", "id" : "fieldprezzofinale", "value" : '%.2f'|format(reservation.prezzofinale)) }}
            </div>
        </div>           
    </div>

    {{ hidden_field("id", "value" : reservation.id) }}
    {# { hidden_field("stato", "value" : reservation.stato, "id" : "statohidden") } #}

    <div class="row">
            <div class="col-sm-12">&nbsp;</div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            {{ submit_button('Salva le modifiche',"class" : "btn btn-primary") }}
        </div>
    </div>

    {% endif %}

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