$(document).ready(function(){

    $("[name=fasciadiprezzo]").change(function(obj){
        var fascia = this.value;

        var arrstand = $("#arraystand").val().split(',');
        if(arrstand.length > 0){
            $.each( arrstand, function( key, value ) {
               $("#prezzo-stand" + value).html('').append($("#stand"+value+fascia).val() + ' +IVA');
            });
        }

        var arrserv = $("#arrayservizi").val().split(',');
        if(arrserv.length > 0){
            $.each( arrserv, function( key, value ) {
               $("#prezzoserv" + value).val($("#prezzoserv"+value+fascia).val() + ' +IVA');
            });
        }

    });



    $("#fespositori").submit(function(event){

        event.preventDefault();
        // resetto la classe invalid su tutti i campi prima della chiamata ajax
        $("#fespositori input").removeClass("is-invalid");
        $("#fespositori textarea").removeClass("is-invalid");
        $("div .invalid-feedback").remove();
        $("div .darimuovere").remove();
        var action = $(this).attr("action");
        console.log(action,'action');
        var request_method = $(this).attr("method");
        var form_data = new FormData(this);
        $.ajax({
            url : action,
            type: request_method,
            data : form_data,
            contentType: false,
            cache: false,
            processData:false
        }).done(function(response){
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
                        //primoselettore = "[name^="+ nomecampo +"]";
                        primoselettore = "#fieldStandpersonalizzato";
                        console.log(nomecampo);
                            break;
                        case 'modale':
                        $( '#contenutosuccess' ).html( "<div class=\"alert alert-success darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                        primoselettore = "#contenutosuccess";
                            break;  
                        case 'incima':
                        $( '#incima' ).html( "<div class=\"alert alert-success darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                        primoselettore = "#incima";
                            break;                              
                        case 'status':
                        if(messaggioerrore == 'OK'){
                            $('#SuccessInsertModal').modal('show');
                        }
                            break;                           
                        default:
                            $( selettore ).after( "<div class='invalid-feedback'>" + messaggioerrore + "</div>" );
                    }

                    if(indice==0 && nomecampo != 'status'){
                        primoselettore = selettore;
                    } 
                    indice++;
                });
                $(primoselettore).focus();
            
            
        });


    });

    $('#SuccessInsertModal').on('hidden.bs.modal', function (e) {
        window.location = "/index";
    });

    $('.copiagiu').click(function(){
        $("#fieldCatalogonome").val($("#fieldRagionesociale").val());
        $("#fieldCatalogoindirizzo").val($("#fieldIndirizzo").val());
        $("#fieldCatalogocap").val($("#fieldCap").val());
        $("#fieldCatalogocitta").val($("#fieldCitta").val());
        $("#catalogoprovincia").val($("#provincia").val());
        $("#fieldCatalogotelefono").val($("#fieldTelefono").val());
        $("#fieldCatalogoemail").val($("#fieldEmailaziendale").val());
        $("#fieldCatalogodescrizione").val($("#fieldProdottiesposti").val());
    });

});