$(document).ready(function(){
    function checkEmptyFields() {
        let emptyFields = false;
        $.each($("input, select"), function() {
            if ($(this).val() === "" && $(this).closest(".field-container").hasClass("required")) {
                emptyFields = true;
                $(this).closest(".field-container").addClass("invalid").find(".error").html("Campo de preenchimento obrigatório!");
            }
        });
        return emptyFields;
    }
    function checkErrors() {
        let errors = false;
        $.each($("input, select"), function() {
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
        const required = field.hasClass("required");

        $.post("includes/validateInputs.inc.php", { [name]: value, required: required }, function(data){
            if (data) {
                field.addClass("invalid").find(".error").html(data);
            } else {
                field.removeClass("invalid").find(".error").html("");
            }
        });
    }

    $("input[name='firstName']").on('input keyup', function () { 
        validateField(this);
    });
    $("input[name='lastName']").on('input keyup', function () {
        validateField(this);
    });
    $("input[name='email']").on('input keyup', function () {
        validateField(this);
    });
    $("input[name='birthDate']").on('input keyup', function () {
        validateField(this);
    });
    $("input[name='nif']").on('input keyup', function () {
        validateField(this);
    });
    $("input[name='phone']").on('input keyup', function () {
        validateField(this);
    });
    $("input[name='clientAddress']").on('input keyup', function () {
        validateField(this);
    });
    $("select[name='district']").on('input keyup', function () {
        validateField(this);
    });


    $('form').on('reset', function() {
        // loadClientProfile();
        $.each($("input, select"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
    });

    $('form').on('submit', function(e) {
        e.preventDefault(); 
        if (!checkEmptyFields() && !checkErrors()) {
            console.log("Formulario Valido!")
            $('form').unbind('submit').submit();
        }
    });
});