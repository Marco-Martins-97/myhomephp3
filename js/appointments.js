$(document).ready(function(){
    loadAppointments();

    function loadAppointments(){
        $.post("includes/loadAppointments.inc.php", function(data, status){
            if (status ==="success") {
                data = JSON.parse(data);
                console.log(data);
                let appointmentHTML = '';
                data.forEach(model => {
                    appointmentHTML += `
                        <li>
                            <p>ID: ${model.appointmentId}</p>
                            <p>Data/Hora: ${model.date}, ${model.time}</p>
                            <p>Reason: ${model.reason}</p>
                            <p>Status: <span class="status ${model.status}">${model.status}</span></p>
                        </li>
                    `;
                });
                $('.schedule-appointments').html(appointmentHTML);
            } else {
                console.log("Error: " + status);
            }
        });
    }
});