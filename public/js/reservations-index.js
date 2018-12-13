$(document).ready(function(){
    $("#ResetFiltri").click(function(){
       $("#FiltroArea option:first").attr('selected','selected'); 
       $("#FiltroStato").val($("#FiltroStato option:first").val());
       $("#OrderBy").val($("#OrderBy option:first").val());
    });
});