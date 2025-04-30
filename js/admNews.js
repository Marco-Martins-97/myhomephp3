$(document).ready(function(){
    $('#create-new').click(function(){
        $('#modal-title').html("Criar Notícia");
        $("input[name='action']").val("create");
        $('#submit').html("Criar");
        
        $('#news-modal').addClass('active');
    });

    $('#edit-new').click(function(){
        $('#modal-title').html("Editar Notícia");
        $("input[name='action']").val("edit");
        $('#submit').html("Salvar");

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




});

