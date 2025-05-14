$(document).ready(function(){
    function changeStatus(appointmentId, username, statusA){
        $.post("includes/saveStatus.inc.php", { appointmentId: appointmentId, username: username, statusA: statusA }, function(status){
            if (status ==="success"){
                location.reload();
            } else {
                console.log("Error: " + status);
            }
        });
    }

    function loadAppointments(username, statusA){
        $.post("includes/loadAppointments.inc.php", { username: username, statusA: statusA }, function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                console.log(data);
                let appointmentHTML = '';
                data.forEach(appointment => {
                    const actionBtns = appointment.status === "cancelled" || appointment.status === "declined" ? `` 
                    : appointment.expired ? `
                            <button class="action-appointment" data-status="completed" data-id="${appointment.appointmentId}">Completo</button>
                            <button class="action-appointment" data-status="no-show" data-id="${appointment.appointmentId}">Sem Presença</button>
                        ` : `
                            <button class="action-appointment" data-status="confirmed" data-id="${appointment.appointmentId}">Confirmar</button>
                            <button class="action-appointment" data-status="declined" data-id="${appointment.appointmentId}">Recusar</button>
                        `;
                    ;
                    appointmentHTML += `
                        <li>
                            <div class="appointment-container">
                                <p>${appointment.reason}</p>
                                <p>${appointment.clientName}</p>
                                <div class="appointment-title">
                                    <p>${appointment.date} ${appointment.time}</p>
                                    <p><span class="status ${appointment.status}">${appointment.status}</span></p>
                                    <div class="btn-container">
                                        ${actionBtns}
                                    </div>
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



    $('#search-appointments').click(function(){
        const username = $("#username-search").val();
        const statusA = $('#appointment-status option:selected').val();
        loadAppointments(username, statusA);
    });

    $(document).on('click', '.action-appointment', function() {
        const username = $("#username-search").val();
        const appointmentId = $(this).data('id');
        const statusA = $(this).data('status');
        changeStatus(appointmentId, username, statusA);
        console.log(statusA);
    });



});