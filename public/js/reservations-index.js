$(document).ready(function(){
    $("#excelgen").click(function(){
        $("#FiltroAreaexcel").val($("#FiltroArea").val());
        $("#FiltroStatoexcel").val($("#FiltroStato").val());
        $("#FiltroOrderbyexcel").val($("#OrderBy").val());
        if($('#Filtroprogcult:checked').length > 0){
           alert('filtro programma culturale checked');
            $("#Filtroprogcultexcel").val($("#Filtroprogcult").val());
        }
        console.log($("#fexcelgen"));
        $("#fexcelgen").submit();
     });
});