$(document).ready(function(){
    function checkEmptyFields() {
        let emptyFields = false;
        $("input").each(function() {
            if ($(this).val().trim() === "") {
                emptyFields = true;
            }
        });
        if (emptyFields){
            $(".form-container").addClass("empty").find(".error").html("Campos de preenchimento obrigatório!");
        } else {
            $(".form-container").removeClass("empty").find(".error").html("");
        }
        return emptyFields;
    }

    function checkErrors() {
        if ($(".form-container").hasClass("invalid") || $(".form-container").hasClass("empty")) {
            return true;
        } else {
            return false
        }
    }

    $("input[name='username']").on('input keyup', function () {
        const action = "login";
        const name = $(this).attr("name");
        const value = $(this).val();
        const field = $(".form-container");
        
        $.post("includes/validateInputs.inc.php", { [action+name]: value }, function(data){
            if (data){
                field.addClass("invalid").find(".error").html(data);
            } else{
                field.removeClass("invalid").find(".error").html("");
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