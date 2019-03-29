<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="container">
                <div class="row">
                    <div class="col-10">
                            <h3 class="text-center">Diario dell'espositore</h3>
                            <p>
                            Di seguito sono riportate le fasi salienti del flussso di lavoro per questo espositore, e le vostre note.
                            </p>
                    </div>
                    <div class="col-2"><i class="fas fa-plus-circle fa-5x" id="aggiungilog" data-toggle="tooltip" data-placement="top" title="Scrivi una nota sul diario di questo espositore"></i></div>
                </div>
            </div>

            <ul class="timeline">
                {% for index, log in logstatireservations %}

                {% if index is odd %}
                {% set timelineinverted = 'timelineinverted' %}
                {% else %}
                {% set timelineinverted = '' %}
                {% endif %}

                <?php $dataoramessaggio = (new DateTime($log->dataora))->format('d-m-Y H:i'); ?>

                <li class="{{ timelineinverted }}">
                <div class="timeline-image">
                {% if log.users_id %}
                <img class="rounded-circle img-responsive" src="http://lorempixel.com/150/150/cats/{{ log.users_id }}" alt="">
                {% endif %}
                </div>
                <div class="timeline-panel">
                    <div class="timeline-heading">
                        <h4>{{ dataoramessaggio }}</h4>
                        <h4 class="subheading">{% if log.users_id %} {{ log.users.username }} {% endif %}</h4>
                    </div>
                    <div class="timeline-body">
                        <p class="text-muted">
                        {{ log.messaggio }}
                        </p>
                    </div>
                </div>
                {% if !loop.last %}
                <div class="line"></div>
                {% endif %}
                </li>
                {% endfor %}
            </ul>
        </div>
    </div>
</div>

<!-- finestra modale per inserimento log -->
     <!-- Modal -->
      <div class="modal fade" id="msgdiario" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle"><img class="rounded-circle img-responsive" src="http://lorempixel.com/50/50/cats/{{ elements.getUserId() }}"> Inserisci la tua nota sul diario dell'espositore</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="bodymsgdiario">
                <form id="formnotadiario" class="form-inline">
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" cols="50" name="messaggio"></textarea>
                        <input type="hidden" name="reservations_id" value="{{ reservation.id }}">
                        <input type="hidden" name="users_id" value="{{ elements.getUserId() }}">
                        <input type="hidden" name="stati_id" value="{{ reservation.stato }}">
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
              <button type="button" class="btn btn-primary" id="inseriscinotadiario">Salva</button>
            </div>
          </div>
        </div>
      </div>