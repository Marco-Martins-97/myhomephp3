$(document).ready(function(){
    $('#create-new').click(function(){
        $('#modal-title').html("Criar Notícia");
        $("input[name='new-action']").val("create");
        $('#submit-new').html("Criar");
        
        $('#news-modal').addClass('active');
    });

    $('#edit-new').click(function(){
        $('#modal-title').html("Editar Notícia");
        $("input[name='new-action']").val("edit");
        $('#submit-new').html("Salvar");

        // loadNew();

        $('#news-modal').addClass('active');
    });


    $('#close-modal').on('click', function() {
        $('#news-modal').removeClass('active');
    });

    $('#news-modal').on('click', function(e) {
        if ($(e.target).is('#news-modal')) {
            $('#news-modal').removeClass('active');
        }
    });


    $('form').on('submit', function(e) {
        /* e.preventDefault(); 
        if (!checkEmptyFields() && !checkErrors()) {
            console.log("Formulario Valido!");
            $('form').unbind('submit').submit();
            }
            else{
                console.log("Formulario Invalido!");
        } */
       console.log("Formulario Valido!");
    });

});

