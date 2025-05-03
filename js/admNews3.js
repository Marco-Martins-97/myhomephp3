$(document).ready(function(){
    let newsQt = 2;
    loadNews(newsQt);
    function loadNews(newsQt){
        $.post("includes/loadNews.inc.php", { newsQt }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let newsHTML = '';
                data.forEach(article => {
                    newsHTML += `
                        <div class="article-container">
                            <div class="article">
                                <a href="${article.link}" target="_blank">${article.title}</a>
                                <p>${article.content}</p>
                                <p class="author">${article.author}</p>
                            </div>
                            <div class="btn-container">
                                <button class="edit-new" data-id="${article.newId}"><i class="fa fa-edit"></i></button>
                                <button class="delete-new" data-id="${article.newId}"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    `;
                });
                $('.news-container').html(newsHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }

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
        $("input[name='new-id']").val("");
        $("input[name='new-title']").val("");
        $("input[name='new-url']").val("");
        $("textarea[name='new-content']").val("");
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
        const confirm = window.confirm("Eliminar esta Notícia?");
        if (confirm) {
            const newId = $(this).data('id');
            $("input[name='new-action']").val("delete");
            $("input[name='new-id']").val(newId);
            $('form').submit();
        }
    });

    $(document).on('click', '#load-news-btn', function() {
        newsQt += 1;
        loadNews(newsQt);
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

