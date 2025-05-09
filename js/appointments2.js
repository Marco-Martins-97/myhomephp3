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
                console.log(data);
                let appointmentHTML = '';
                data.forEach(appointment => {
                    appointmentHTML += `
                        <li>
                            <p>ID: ${appointment.appointmentId}</p>
                            <p>Data/Hora: ${appointment.date}, ${appointment.time}</p>
                            <p>Reason: ${appointment.reason}</p>
                            <p>Status: <span class="status ${appointment.status}">${appointment.status}</span></p>
                            <div class="btn-container">
                                <button class="reschedule-appointment" data-id="${appointment.appointmentId}">Remarcar</button>
                                <button class="cancel-appointment" data-id="${appointment.appointmentId}">Cancelar</button>
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
                $('#default').val(data.time).text(data.time);
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
                console.log(hoursDiff);
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
    $('.create-appointment').click(function(){
        /* remove os erros ao abrir*/
        // $.each($("input, textarea"), function() {
        //     if ($(this).closest(".field-container").hasClass("invalid")) {
        //         $(this).closest(".field-container").removeClass("invalid");
        //     }
        // });
        $('#modal-title').html("Criar Marcação");
        $("input[name='appointment-action']").val("create");
        $('#submit-appointment').html("Marcar");
        /* Apaga todos os valores do modal */
        $.each($("input:not([type='hidden']), textarea"), function() {
            $(this).val("");
        });
        $('#default').val("").text("--Selecione uma Hora--");
        
        $('#appointment-modal').addClass('active');
    });

    $(document).on('click', '.reschedule-appointment', function() {
       /*  $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        }); */
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
                    $('form').off('submit').submit();
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
});