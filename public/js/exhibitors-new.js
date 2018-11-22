$(document).ready(function(){

    $("[name=fasciadiprezzo]").change(function(obj){
        var fascia = this.value;

        var arrstand = $("#arraystand").val().split(',');
        if(arrstand.length > 0){
            $.each( arrstand, function( key, value ) {
               $("#prezzo-stand" + value).html('').append($("#stand"+value+fascia).val());
            });
        }

        var arrserv = $("#arrayservizi").val().split(',');
        if(arrserv.length > 0){
            $.each( arrserv, function( key, value ) {
               $("#prezzoserv" + value).val($("#prezzoserv"+value+fascia).val());
            });
        }

    });
/*
    var liscio = false;

    $("#fespositori").submit(function(event){
        if(liscio == false){

        event.preventDefault();
        // resetto la classe invalid su tutti i campi prima della chiamata ajax
        $("#fespositori input").removeClass("is-invalid");
        $("#fespositori textarea").removeClass("is-invalid");
        $("div .invalid-feedback").remove();
        $("div .darimuovere").remove();
        var request_method = $(this).attr("method");
        var form_data = new FormData(this);
        $.ajax({
            url : "validate",
            type: request_method,
            data : form_data,
            contentType: false,
            cache: false,
            processData:false
        }).done(function(response){
            if(response.status == "KO"){
                event.preventDefault();
                var selettore = '';
                var primoselettore = '';
                var indice = 0;
                $.each( response, function( nomecampo, messaggioerrore ) {
                    selettore = "[name="+ nomecampo +"]";
                    $(selettore).addClass(' is-invalid');
                    
                    // in base al campo input inietto il messaggio di errore in posti diversi..
                    switch (nomecampo) { 
                        case 'fasciadiprezzo': 
                        $( '#fdp' ).after( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                            break;
                        case 'areas_id':
                        $( '#msgerrareatema' ).html( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                            break;
                        case 'stand':
                        $( '#msgerrstand' ).html( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                        primoselettore = "[name^="+ nomecampo +"]";
                            break;
                        default:
                            $( selettore ).after( "<div class='invalid-feedback'>" + messaggioerrore + "</div>" );
                    }

                    if(indice==0){
                        primoselettore = selettore;
                    } 
                    indice++;
                });
            
                $(primoselettore).focus();
                return false;
            }
            else{
                liscio = true;
                alert($('input[type="submit"]').val());
                $('input[type="submit"]').click();
            }
            
        });

    } // end if liscio == false
    });
*/
});