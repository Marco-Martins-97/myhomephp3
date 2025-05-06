$(document).ready(function(){
    loadModels();

    function loadModels(){
        const imgDir = "img/uploads/";
        $.post("includes/loadModels.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let modelHTML = '';
                data.forEach(model => {
                    modelHTML += `
                        <p>Model id: ${model.modelId}</p>
                        <p>Model Name: ${model.modelName}</p>
                        <img src="${imgDir + model.imgName}" alt="${model.imgName}" width="200px">
                        <p>Area: ${model.area}</p>
                        <p>Quartos: ${model.bedrooms}</p>
                        <p>"Wc: ${model.bathrooms}</p>
                        <p>User: ${model.userId}</p>
                        <button class="edit-model" data-id="${model.modelId}"><i class="fa fa-edit"></i></button>
                        <button class="delete-model" data-id="${model.modelId}"><i class="fa fa-trash"></i></button>
                        <br>
                        <br>
                    `;
                });
                $('.models-container').html(modelHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }
    
    function loadModel(modelId){
        $.post("includes/loadModels.inc.php", { modelId }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                $("input[name='model-name']").val(data.modelName);
                $("input[name='model-area']").val(data.area);
                $("input[name='model-bedrooms']").val(data.bedrooms);
                $("input[name='model-bathrooms']").val(data.bathrooms);
            } else {
                console.log("Error: " + status);
            }
        });
    }
    
    
    function validateImg(input){
        const InputName = $(input).attr("name");
        const field = $(input).closest(".field-container");
        const required = field.hasClass("required");
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append(InputName, file); 
        
        $.ajax({
            url: "includes/validateInputs.inc.php",
            type: "POST",
            data: formData, required: required,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data) {
                    field.addClass("invalid").find(".error").html(data);
                } else {
                    field.removeClass("invalid").find(".error").html("");
                }
            }
        });
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

    function checkEmptyFields() {
        let emptyFields = false;
        $.each($("input:not([type='hidden'])"), function() {
            if ($(this).val() === "" && $(this) !== "input[name='model-img']" || $(this) === "input[name='model-img']" && $(this).closest(".field-container").hasClass("required")) {
                emptyFields = true;
                $(this).closest(".field-container").addClass("invalid").find(".error").html("Campo de preenchimento obrigatório!");
            }
        });
        return emptyFields;
    }

    function checkErrors() {
        let errors = false;
        $.each($("input:not([type='hidden'])"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                errors = true;
            }
        });
        return errors;
    }


    $("input[name='model-name']").on('input keyup', function () { 
        validateField(this);
    });

    $("input[name='model-img']").on('change', function(){
        validateImg(this);
    });

    $("input[name='model-area']").on('input keyup', function () { 
        validateField(this);
    });
    
    $("input[name='model-bedrooms']").on('input keyup', function () { 
        validateField(this);
    });

    $("input[name='model-bathrooms']").on('input keyup', function () { 
        validateField(this);
    });

    $('.create-model').click(function(){
        /* remove os erros ao abrir*/
        $.each($("input"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        $('#modal-title').html("Criar Modelo");
        $("input[name='model-action']").val("create");
        $("input[name='model-img']").closest(".field-container").addClass("required");
        $('#submit-model').html("Criar");
        /* Apaga todos os valores do modal */
        
        $.each($("input:not([type='hidden'])"), function() {
            $(this).val("");
        });
        
        $('#catalog-modal').addClass('active');
    });

    $(document).on('click', '.edit-model', function() {
        $.each($("input"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        const modelId = $(this).data('id');

        $('#modal-title').html("Editar Modelo");
        $("input[name='model-action']").val("edit");
        
        if($("input[name='model-img']").closest(".field-container").hasClass("required")){
            $("input[name='model-img']").closest(".field-container").removeClass("required");
        }
        $('#submit-model').html("Salvar");

        $("input[name='model-id']").val(modelId);
        loadModel(modelId);

        $('#catalog-modal').addClass('active');
    });






    $('#close-modal').on('click', function() {
        $('#catalog-modal').removeClass('active');
    });

    $('#catalog-modal').on('click', function(e) {
        if ($(e.target).is('#catalog-modal')) {
            $('#catalog-modal').removeClass('active');
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