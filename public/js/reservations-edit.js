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
});