$(document).ready(function(){
    $("[type=range]").change(function(){
      var newval=$(this).val();
      $("#statohidden").val(newval);
      $('span[id^="badge-stato-"]').each(function(){
        var pezzetti = $(this).attr('id').split("-")
        ident = pezzetti[2];

        if(ident == newval) {
            $(this).show();
        }
        else{
          $(this).hide();
        }
      });
     
    });

    $("#fieldprezzofinale").change(function(){
      var prezzofinale = $(this).val();
      // calcolare lo sconto e visualizzare la percentuale
      var prezzoufficiale = $("#prezzocalcolato").val();
      var sconto = 1 - prezzofinale/prezzoufficiale;
      sconto = (sconto * 100).toFixed(0);
      $("#sconto").html('Sconto: ' + sconto + '%');
      var prezzoivato = parseInt(prezzofinale) + parseInt(prezzofinale) * 0.22;
      $("#fieldprezzofinaleivato").html("Prezzo totale concordato + iva € " + prezzoivato.toFixed(2));
      var widthbarra = 100 - sconto;
      $("#sconto").css('width', widthbarra + '%').attr('aria-valuenow', widthbarra);
    });

    $("#inviolettera").click(function(event){
      $('#modalspinner').modal('show');
      event.preventDefault();
      var reservationid = $("#reservationid").val();

      $.ajax({
          url : '/reservations/invialettera',
          type: 'POST',
          data: { 
            'reservationid': reservationid
          }
      }).done(function(response){
                var status = response.status;
                var messaggioerrore = response.incima;
                switch (status) { 
                  case 'OK':
                      $('#contenutosuccess').html( "<div class=\"alert alert-success darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                      break;                              
                  case 'KO':
                  $('#contenutosuccess').html( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                      break;                           
              }
              $('#modalspinner').fadeOut(1000, function(){
                $('#modalspinner').modal('hide');
                $('#SuccessInsertModal').modal('show');
            });
      });

    });

    $("#aggiungilog").click(function(){
      $('#msgdiario').modal();
    });

    /* codice per invio ajax nota diario */
    $("#inseriscinotadiario").click(function(event){
      event.preventDefault();
      var form = document.getElementById("formnotadiario");
      var form_data = new FormData(form);
      $.ajax({
          url : '/reservations/scrivinota',
          type: 'POST',
          data : form_data,
          contentType: false,
          cache: false,
          processData:false
      }).done(function(response){
          var status = response.status;
          var messaggioerrore = response.risposta;
          switch (status) { 
            case 'OK':
                $('#bodymsgdiario').html( "<div class=\"alert alert-success\" role=\"alert\">" + messaggioerrore + "</div>" );
                break;                              
            case 'KO':
                $('#bodymsgdiario').html( "<div class=\"alert alert-danger\" role=\"alert\">" + messaggioerrore + "</div>" );
                break;                           
          }
          $('#msgdiario').fadeOut(2000, function(){
            $('#msgdiario').modal('hide');
            location.reload();
        });
      });

    });

});