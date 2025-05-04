$(document).ready(function(){
    function validateImg(input){
        const InputName = $(input).attr("name");
        const field = $(input).closest(".field-container");
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append(InputName, file); 
        
        $.ajax({
            url: "includes/validateInputs.inc.php",
            type: "POST",
            data: formData,
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



    $("input[name='model-img']").on('change', function(){
        validateImg(this);
    });
});