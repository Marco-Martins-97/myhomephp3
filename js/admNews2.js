$(document).ready(function(){
    let newsQt = 3;

    $.post("includes/loadNews.inc.php", { newsQt }, function(data, status){
        if (status ==="success") {
            data = JSON.parse(data);
            let newsHTML = '';
            data.forEach(article => {
                newsHTML += `
                    <div class="news-item">
                        <h2><a href="${article.link}">${article.title}</a></h2>
                        <p>${article.content}</p>
                        <p>${article.author}</p>
                        <button class="edit-new" data-id="${article.newId}"><i class="fa fa-edit"></i></button>
                        <button class="delete-new" data-id="${article.newId}"><i class="fa fa-trash"></i></button>
                    </div>
                `;
            });
            $('.news-container').html(newsHTML);
        } else {
            console.log("Error: " + status);
        }
    });

    function loadNew(newId){
        $.post("includes/loadNews.inc.php", { newId }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                $("input[name='new-title']").val(data.title);
                $("input[name='new-url']").val(data.link);
                $("textarea[name='new-content']").val(data.content);
            } else {
                console.log("Error: " + status);
            }
        });
    }

    $('.create-new').click(function(){
        $('#modal-title').html("Criar Notícia");
        $("input[name='new-action']").val("create");
        $('#submit-new').html("Criar");
        
        $('#news-modal').addClass('active');
    });

    $(document).on('click', '.edit-new', function() {
        const newId = $(this).data('id');
        $('#modal-title').html("Editar Notícia");
        $("input[name='new-action']").val("edit");
        $("input[name='new-id']").val(newId);
        $('#submit-new').html("Salvar");
        loadNew(newId);
        $('#news-modal').addClass('active');
    });

    $(document).on('click', '.delete-new', function() {
        const newId = $(this).data('id');
        $("input[name='new-action']").val("delete");
        $("input[name='new-id']").val(newId);
        $('form').submit();
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

