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
});