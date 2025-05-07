$(document).ready(function(){
    let newsQt = 3;
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

    /* ------------------------------------------------------------------------------------ */
    function checkEmptyFields() {
        let emptyFields = false;
        $.each($("input:not([type='hidden']), textarea"), function() {
            if ($(this).val() === "") {
                emptyFields = true;
                $(this).closest(".field-container").addClass("invalid").find(".error").html("Campo de preenchimento obrigatório!");
            }
        });
        return emptyFields;
    }

    function checkErrors() {
        let errors = false;
        $.each($("input:not([type='hidden']), textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                errors = true;
            }
        });
        return errors;
    }

    function validateField(input){
        const name = $(input).attr("name");
        const value = $(input).val();
        const field = $(input).closest(".field-container");
        
        $.post("includes/validateInputs.inc.php", { [name]: value }, function(data){
            if (data) {
                field.addClass("invalid").find(".error").html(data);
            } else {
                field.removeClass("invalid").find(".error").html("");
            }
        });
    }

    $("input[name='new-title']").on('input keyup', function () { 
        validateField(this);
    });
    $("input[name='new-url']").on('input keyup', function () { 
        validateField(this);
    });
    $("textarea[name='new-content']").on('input keyup', function () { 
        validateField(this);
    });
    /* ------------------------------------------------------------------------------------ */
    $('.create-new').click(function(){
        /* remove os erros ao abrir*/
        $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        $('#modal-title').html("Criar Notícia");
        $("input[name='new-action']").val("create");
        $('#submit-new').html("Criar");
        /* Apaga todos os valores do modal */
        $.each($("input:not([type='hidden']), textarea"), function() {
            $(this).val("");
        });
        
        $('#news-modal').addClass('active');
    });

    $(document).on('click', '.edit-new', function() {
        $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
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
            $('form').off('submit').submit();
        }
    });

    $(document).on('click', '#load-news-btn', function() {
        newsQt += 3;
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
        e.preventDefault(); 
        if (!checkEmptyFields() && !checkErrors()) {
            console.log("Formulario Valido!");
            $('form').unbind('submit').submit();
        } else{
            console.log("Formulario Invalido!");
        }
    });

});

