$(document).ready(function(){
    $("#excelgen").click(function(){
        $("#FiltroAreaexcel").val($("#FiltroArea").val());
        $("#FiltroStatoexcel").val($("#FiltroStato").val());
        $("#FiltroOrderbyexcel").val($("#OrderBy").val());
        if($('#Filtroprogcult:checked').length > 0){
           alert('filtro programma culturale checked');
            $("#Filtroprogcultexcel").val($("#Filtroprogcult").val());
        }
        $("#fexcelgen").submit();
     });

     $(".cancellaespositore").click(function(event){
         event.preventDefault();
         var recipient = $(this).attr('data-esp');
         var recipientid = $(this).attr('data-idesp');
         $('#deletetModal').modal('show');
         $('#deletetModal').find('.modal-body').html("Vuoi davvero eliminare l'espositore <strong>" + recipient + "</strong>?<br>Clicca su 'Elimina' per eliminare definitivamente l'espositore e tutti i servizi da lui prenotati.");
         var dest = '/exhibitors/delete/' + recipientid;
         $('#deletetModal').find('.btn-primary').attr('href', dest);
     });

     $(".dettcont").click(function(event){
        event.preventDefault();
        var telaz = $(this).attr('data-telaz');
        var emailaz = $(this).attr('data-emailaz');
        var nomeref = $(this).attr('data-nomeref');
        var telref = $(this).attr('data-telref');
        var emailref = $(this).attr('data-emailref');
        var ragsoc = $(this).attr('data-ragsoc');
        $('#dettagliocontatti').modal('show');
        $('#dettagliocontatti').find('.modal-title').html("Dettaglio contatti ");
        $('#dettagliocontatti').find('.modal-body').html("<h5>" + ragsoc + "</h5><p>Telefono azienda: <a href='tel:"+ telaz + "'>" + telaz + "</a></p><p>Email azienda: <a href='mailto:"+ emailaz + "'>" + emailaz + "</a></p><h5>Referente fiera: " + nomeref + "</h5><p>Telefono referente: <a href='tel:"+ telref + "'>" + telref + "</a></p><p>Email referente: <a href='mailto:"+ emailref + "'>" + emailref + "</a></p>");
    });

});