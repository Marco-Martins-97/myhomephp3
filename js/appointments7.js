$(document).ready(function(){
    loadAppointments();
    const appointmentTimes = ["08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30"];

    appointmentTimes.forEach(function(time){
        $("#times").append($("<option>",{
            value: time,
            text: time
        }));
    });
    function loadAppointments(){
        $.post("includes/loadAppointments.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                let appointmentHTML = '';
                data.forEach(appointment => {
                    /* if(appointment.status === "cancelled" || appointment.status === "expired"){ // nao mostra na lista marcaçoes canceladas e expiradas
                        return;
                    } */
                    const actionBtns = appointment.status === "pending" || appointment.status === "rescheduled" || appointment.status === "confirmed"? `
                    <div class="btn-container">
                            <button class="reschedule-appointment" data-id="${appointment.appointmentId}">Remarcar</button>
                            <button class="cancel-appointment" data-id="${appointment.appointmentId}">Cancelar</button>
                        </div>
                    ` : ``;
                    
                    appointmentHTML += `
                        <li>
                            <div class="appointment-container">
                                <p>${appointment.reason}</p>
                                <div class="appointment-title">
                                    <p>${appointment.date} ${appointment.time}</p>
                                    <p><span class="status ${appointment.status}">${appointment.status}</span></p>
                                    
                                    ${actionBtns}
                                </div>
                            </div>
                        </li>
                    `;
                });
                $('.schedule-appointments').html(appointmentHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }
    function loadAppointment(appointmentId){
        $.post("includes/loadAppointments.inc.php", { appointmentId }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                $('input[name="appointment-date"]').val(data.date);
                $("#times option").each(function() {
                    if ($(this).val() === data.time) {
                        $(this).prop("selected", true);
                    }
                });
                $('textarea[name="appointment-reason"]').val(data.reason);
            } else {
                console.log("Error: " + status);
            }
        });
    }

    function allowedToModify(appointmentId, canModify){
        $.post("includes/loadAppointments.inc.php", { appointmentId }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                if (data.status === "cancelled"){
                    warning("A marcação já se encontra cancelada!");
                    return canModify(false);
                } 
                
                const appointmentDateTime = new Date(`${data.date}T${data.time}`);
                const currentDateTime = new Date();
                const hoursDiff = (appointmentDateTime - currentDateTime) / (1000 * 60 * 60); //milisegundos para horas
                if (hoursDiff < 72){
                    warning("Falta menos de 72h, não é possivel alterar ou cancelar a marcação.");
                    return canModify(false);
                } 
                
                return canModify(true);
            } else {
                console.log("Error: " + status);
                warning("Error: " + status);
                return canModify(false);
            }
        });
    }
    function warning($text){
        $('#warning-title').html($text);
        $('#warning-modal').addClass('active');
    }

    function checkEmptyFields() {
        let emptyFields = false;
        $.each($("input:not([type='hidden']), textarea, select"), function() {
            const tagName = this.tagName.toLowerCase();
            let value = $(this).val();

            if(tagName === "select"){
                const selectedOption = $(this).find("option:selected");
                value = selectedOption.val();
            }

            if (!value || value === "") {
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

    $("input[name='appointment-date']").on('input keyup', function () { 
        validateField(this);
    });
    $("select[name='appointment-time']").on('change', function () { 
        validateField(this);
    });
    $("textarea[name='appointment-reason']").on('input keyup', function () { 
        validateField(this);
    });

    $('.create-appointment').click(function(){
        /* remove os erros ao abrir*/
        $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        $('#modal-title').html("Criar Marcação");
        $("input[name='appointment-action']").val("create");
        $('#submit-appointment').html("Marcar");
        /* Apaga todos os valores do modal */
        $.each($("input:not([type='hidden']), textarea"), function() {
            $(this).val("");
        });
        $("#times").prop("selectedIndex", 0);
        
        $('#appointment-modal').addClass('active');
    });

    $(document).on('click', '.reschedule-appointment', function() {
        $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        const appointmentId = $(this).data('id');
        allowedToModify(appointmentId, function(canModify){
            if (canModify){
                $('#modal-title').html("Alterar Marcação");
                $("input[name='appointment-action']").val("reschedule");
                $("input[name='appointment-id']").val(appointmentId);
                $('#submit-appointment').html("Remarcar");
                loadAppointment(appointmentId);
                $('#appointment-modal').addClass('active');
            }
        });
    });

    $(document).on('click', '.cancel-appointment', function() {
        const appointmentId = $(this).data('id');
        allowedToModify(appointmentId, function(canModify){
            if (canModify){
                const confirm = window.confirm("Cancelar esta Marcação?");
                if (confirm) {
                    $("input[name='appointment-action']").val("cancel");
                    $("input[name='appointment-id']").val(appointmentId);
                    $('#appointment-form').off('submit').submit();
                }
            }
        });
    });












    $('#close-warning').on('click', function() {
        $('#warning-modal').removeClass('active');
    });

    $('#warning-modal').on('click', function(e) {
        if ($(e.target).is('#warning-modal')) {
            $('#warning-modal').removeClass('active');
        }
    });

    $('#close-modal').on('click', function() {
        $('#appointment-modal').removeClass('active');
    });

    $('#appointment-modal').on('click', function(e) {
        if ($(e.target).is('#appointment-modal')) {
            $('#appointment-modal').removeClass('active');
        }
    });

    $('#appointment-form').on('submit', function(e) {
        e.preventDefault(); 
        if (!checkEmptyFields() && !checkErrors()) {
            console.log("Formulario Valido!");
            $('#appointment-form').unbind('submit').submit();
        } else{
            console.log("Formulario Invalido!");
        }
    });
});