<?php include __DIR__ . '/../../layout/header.php' ?>

<div class="container mt-5">
    <h2>Reserva de Salas</h2>
    <form id="bookingForm">
        <div class="form-group">
            <label for="reservationRoom">Sala:</label>
            <select class="form-control" id="bookingRoomId" name="bookingRoomId" required>
                <!-- Opções de salas serão adicionadas aqui dinamicamente -->
            </select>
        </div>
        <div class="form-group">
            <label for="reservationDate">Data:</label>
            <input type="date" class="form-control" id="bookingRoomDate" name="bookingRoomDate" required>
        </div>
        <div class="form-group">
            <label for="reservationStartTime">Hora de Início:</label>
            <input type="time" class="form-control" id="bookingHourStart" name="bookingHourStart" required>
        </div>
        <div class="form-group">
            <label for="reservationEndTime">Hora de Término:</label>
            <input type="time" class="form-control" id="bookingHourEnd" name="bookingHourEnd" required>
        </div>
        
    </form>
    <button type="button" onclick="createRoomBooking()" class="btn btn-primary">Reservar</button>
    <div class="table-container">
        <h3>Reservas Existentes</h3>
        <div class="table-scroll">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Hora de Início</th>
                        <th>Hora de Término</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">
                    <!-- Linhas de reservas serão adicionadas aqui dinamicamente -->
                </tbody>
            </table>
       
    </div>
    
</div>

<?php include __DIR__ . '/../../layout/footer.php' ?>

    <script> 
        $(document).ready(() => {
            
            function fillSelectRooms () {
                $.ajax({
                    url: BASE_URL + '/room/list',
                    method: 'POST',
                    dataType: 'json',
                    success: function(data) {
                        const $select = $('#bookingRoomId');
                        data.forEach(function(option) {
                            $select.append(
                                $('<option></option>').val(option.id).text(option.name)
                            );
                        });
                    }
                });
            }

            loadBookings();
            fillSelectRooms()
        
        })
</script>
