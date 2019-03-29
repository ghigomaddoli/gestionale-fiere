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

});