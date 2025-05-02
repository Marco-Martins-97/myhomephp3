$(document).ready(function(){
    let newsQt = 3;

    $.post("includes/loadNews.inc.php", { newsQt }, function(data, status){
        if (status ==="success") {
            data = JSON.parse(data);
            let newsHTML = '';
            data.forEach(article => {
                console.log(article);
                newsHTML += `
                    <div class="news-item">
                        <h2><a href="${article.link}">${article.title}</a></h2>
                        <p>${article.content}</p>
                        <p>${article.author}</p>
                        <button id="edit-new" data-id="${article.newId}"><i class="fa fa-edit"></i></button>
                        <button id="delete-new" data-id="${article.newId}"><i class="fa fa-trash"></i></button>
                    </div>
                `;
            });
            $('.news-container').html(newsHTML);
        } else {
            console.log("Error: " + status);
        }
    });


    $('#create-new').click(function(){
        $('#modal-title').html("Criar Notícia");
        $("input[name='new-action']").val("create");
        $('#submit-new').html("Criar");
        
        $('#news-modal').addClass('active');
    });

    $(document).on('click', '.edit-btn', function() {
        $('#modal-title').html("Editar Notícia");
        $("input[name='new-action']").val("edit");
        $("input[name='new-id']").val($(this).data('id'));
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

