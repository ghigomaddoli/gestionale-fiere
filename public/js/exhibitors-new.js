$(document).ready(function(){

    var redirect = $("#redirect").val();

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

        $('#modalspinner').modal('show');
        event.preventDefault();
        // resetto la classe invalid su tutti i campi prima della chiamata ajax
        $("#fespositori input").removeClass("is-invalid");
        $("#fespositori textarea").removeClass("is-invalid");
        $("div .invalid-feedback").remove();
        $("div .darimuovere").remove();
        var action = $(this).attr("action");
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
                var linkcoespositore = 'gulp';
                
                $.each( response, function( nomecampo, messaggioerrore ) {
   
                    selettore = "[name="+ nomecampo +"]";
                    $(selettore).addClass(' is-invalid');
                    
                    if(indice==0 && nomecampo != 'status'){
                        primoselettore = selettore;
                    } 
                    // in base al campo input inietto il messaggio di errore in posti diversi..
                    switch (nomecampo) { 
                        case 'reservation_id':
                         linkcoespositore = 'exhibitors/coespositore/' + messaggioerrore;
                            break;
                        case 'fasciadiprezzo': 
                        $( '#fdp' ).after( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                            break;
                        case 'areas_id':
                        $( '#msgerrareatema' ).html( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                            break;
                        case 'stand':
                        $( '#msgerrstand' ).html( "<div class=\"alert alert-danger darimuovere\" role=\"alert\">" + messaggioerrore + "</div>" );
                        primoselettore = "#fieldStandpersonalizzato";
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
                            $('#modalspinner').fadeOut(1500, function(){
                                $('#modalspinner').modal('hide');
                                $('#daticoesp').attr('href',linkcoespositore);
                                $('#SuccessInsertModal').modal('show');
                            });
                        }
                        else{
                            $('#modalspinner').fadeOut(1000, function(){
                                $('#modalspinner').modal('hide');
                            });
                        }
                            break;                           
                        default:
                            $( selettore ).after( "<div class='invalid-feedback'>" + messaggioerrore + "</div>" );
                    }

                    indice++;
                });
                //$(primoselettore).focus();
                $('html, body').stop().animate({
                  scrollTop: ($(primoselettore).offset().top - 80)
                }, 1000, 'easeInOutExpo');
            
        });


    });

    $('.copiagiu').click(function(){
        $("#fieldCatalogonome").val($("#fieldRagionesociale").val());
        $("#fieldCatalogoindirizzo").val($("#fieldIndirizzo").val());
        $("#fieldCatalogocap").val($("#fieldCap").val());
        $("#fieldCatalogocitta").val($("#fieldCitta").val());
        $("#catalogoprovincia").val($("#provincia").val());
        $("#fieldCatalogotelefono").val($("#fieldTelefono").val());
        $("#fieldCatalogoemail").val($("#fieldEmailaziendale").val());
    });

    $('#espositoreestero').click(function(){
        if(this.checked){
            $("#provincia").val('XX');
            $("#provincia").attr('readonly',true);
        }
        else{
            $("#provincia").attr('readonly',false);
            $("#provincia").val('PG');
        }
    });

});