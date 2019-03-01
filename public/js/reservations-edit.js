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
              $('#SuccessInsertModal').modal('show');
      });

    });
});