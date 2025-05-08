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
        // $.each($("input:not([type='hidden']), textarea"), function() {
        //     $(this).val("");
        // });
        
        $('#appointment-modal').addClass('active');
    });

    $(document).on('click', '.reschedule-appointment', function() {
        $.each($("input, textarea"), function() {
            if ($(this).closest(".field-container").hasClass("invalid")) {
                $(this).closest(".field-container").removeClass("invalid");
            }
        });
        const appointmentId = $(this).data('id');
        $('#modal-title').html("Alterar Marcação");
        $("input[name='appointment-action']").val("reschedule");
        $("input[name='appointment-id']").val(appointmentId);
        $('#submit-appointment').html("Remarcar");
        loadAppointment(appointmentId);
        $('#appointment-modal').addClass('active');
    });

    $(document).on('click', '.cancel-appointment', function() {
        // const confirm = window.confirm("Eliminar esta Notícia?");
        // if (confirm) {
        //     const newId = $(this).data('id');
        //     $("input[name='new-action']").val("delete");
        //     $("input[name='new-id']").val(newId);
        //     $('form').off('submit').submit();
        // }
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