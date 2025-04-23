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

    $("input[name='username']").on('input keyup', function () {
        const name = $(this).attr("name");
        const value = $(this).val();
        const field = $(this).closest(".field-container");
        const required = field.hasClass("required");
        
        $.post("includes/validateInputs.inc.php", { [name]: value, required: required}, function(data){
            if (data){
                field.addClass("invalid").find(".error").html(data);
            } else{
                field.removeClass("invalid").find(".error").html("");
            }
        });
    });

    $("input[name='pwd'], input[name='confirmPwd']").on('input blur', function () {
        const pwd = $("input[name='pwd']").val();
        const confirmPwd = $("input[name='confirmPwd']").val();
        const fieldPwd = $("input[name='pwd']").closest(".field-container");
        const fieldConfirmPwd = $("input[name='confirmPwd']").closest(".field-container");
        const requiredPwd = fieldPwd.hasClass("required");
    
        $.post("includes/validateInputs.inc.php", { pwd: pwd, confirmPwd: confirmPwd, required: requiredPwd }, function(data) {
            if (data) {
                fieldPwd.addClass("invalid").find(".error").html(data);
            } else {
                fieldPwd.removeClass("invalid").find(".error").html("");
            }
    
            if (data) {
                fieldConfirmPwd.addClass("invalid").find(".error").html(data);
            } else {
                fieldConfirmPwd.removeClass("invalid").find(".error").html("");
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