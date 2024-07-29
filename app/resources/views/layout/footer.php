

<script>
     const BASE_URL = `${window.location.protocol}//${window.location.hostname}${window.location.port ? ':' + window.location.port : ''}`;

    /** funções da tela de reserva de salas */

   
    

    function validateBookingData(data, hourStart, hourEnd) {
        const now = new Date();
        const bookingDate = new Date(data);
        const startTime = new Date(`${data}T${hourStart}`);
        const endTime = new Date(`${data}T${hourEnd}`);

        if(data && hourEnd && hourStart) {
            if (bookingDate < now.setHours(0, 0, 0, 0)) {
            showErrorMessage('A data da reserva deve ser maior ou igual à data atual.<br>');
            return false;
            }

            if (startTime >= endTime) {
                showErrorMessage('A hora de início deve ser menor que a hora de término.<br>');
                return false;
            }

            return true;
        }else{
    
            showErrorMessage('Preencha todos os campos, por favor !');
        }
        
    }
    function deleteRoomBooking (id) {
        
            $.ajax({
            url: BASE_URL + '/delete/room/booking',
            type: 'DELETE',
            data: id ,
            success: function(response) {
                showSuccessMessage('Reserva deletado com sucesso');
                
            },
            error: function() {
                showErrorMessage('Erro ao deletar a reserva' );
            }
        });
        loadBookings();
    }
    function createRoomBooking () {
        // reservationForm
        const sala = $('#bookingRoomId').val();
        const data = $('#bookingRoomDate').val();
        const horaInicio = $('#bookingHourStart').val();
        const horaTermino = $('#bookingHourEnd').val();

        if (validateBookingData(data, horaInicio, horaTermino)) {
            $.ajax({
                url: BASE_URL + '/register/booking', 
                type: 'POST',
                dataType: 'json',
                data: $('#bookingForm').serialize(),
                success: function(data) {
                   
                    showSuccessMessage('Reserva Criada com Sucesso');
                },
                error: function(xhr, status, error) {
                   
                    try {
                        const erroJSON = JSON.parse(xhr.responseText);
                        showErrorMessage(erroJSON.message + "<br>");
                    } catch (e) {
                   
                    }
                   
                }
            });
        }
        loadBookings();
    }
    // Função para formatar a data no padrão dd/mm/aaaa
   
    function loadBookings () {
   
        $.ajax({
            url: BASE_URL + '/room/list/bookings', 
            type: 'POST',
            dataType: 'json',
            success: function(bookings) {
                $('#bookingTableBody').empty();
                bookings.forEach(function(booking) {
                    $('#bookingTableBody').append(`
                        <tr>
                            <td>${booking?.name}</td>
                            <td>${booking?.start_time}</td>
                            <td>${booking?.end_time}</td>
                            <td>
                                <button class="btn btn-danger btn-sm delete-booking" onclick="deleteRoomBooking('${booking.id}')">Excluir</button>
                            </td>
                        </tr>
                    `);
                });
            },
            error: function(xhr, status, error) {
                // showErrorMessage('Erro ao carregar as reservas: ' + error);
            }
        });
    }

    /** fim das funções da reserva de salas */

    /** funções da tela de gerenciar salas */
     function loadRooms() {
            $.ajax({
                url: `${BASE_URL}/room/list`,
                type: 'POST',
                dataType: 'json',
                success: function(rooms) {
                    
                    $('#roomTableBody').empty();
                    rooms.forEach(function(room) {

                        $('#roomTableBody').append(`
                            <tr>
                                <td>${room?.name}</td>
                                <td>${room?.capacity}</td>
                                <td>${room?.location}</td>
                                <td>
                                    <button 
                                        class="btn btn-warning btn-sm edit-room" 
                                        onclick="modalEditRoom('${room.id}',
                                                                '${room?.name}',
                                                                '${room?.location}', 
                                                                '${room?.capacity}')"
                                        ">Editar</button>
                                    <button onclick="deleteRoom('${room.id}')" class="btn btn-danger btn-sm delete-room" 
                                            data-id="${room.id}">Excluir</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            });
        }
        

        function modalEditRoom (id, name, location, capacity) {
      
            const modalHtml = `
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Editar Sala</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="editForm">
                                        <input type="hidden" id="roomId" name="roomId" value="${id}">
                                        <div class="form-group">
                                            <label for="name">Nome:</label>
                                            <input type="text" class="form-control" id="name" name="name" value="${name}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="location">Localização:</label>
                                            <input type="text" class="form-control" id="location" name="location" value="${location}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="capacity">Capacidade:</label>
                                            <input type="number" class="form-control" id="capacity" name="capacity" value="${capacity}" required>
                                        </div>
                                        
                                    </form>
                                    <button type="button" onclick="editRoom()" class="btn btn-primary">Editar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                // Adiciona o HTML da modal ao corpo da página
                $('body').append(modalHtml);
                // Exibe a modal
                $('#editModal').modal('show');

        }

        function editRoom () {

                $.ajax({
                url: '/edit/room',
                type: 'PUT',
                data:  $('#editForm').serialize(),
                success: function(response) {
                    showSuccessMessage(response.message);
                    loadRooms();
                },
                error: function(xhr) {
                    try {
                        const erroJSON = JSON.parse(xhr.responseText);
                        showErrorMessage(erroJSON.message + "<br>");
                    } catch (e) {
                   
                    }
                }
            });
        }
        function deleteRoom (id) {
            console.log($(this));
            $.ajax({
            url: '/delete/room',
            type: 'DELETE',
            data: id ,
            success: function(response) {
                showSuccessMessage(response.message);
                loadRooms();
            },
            error: function(xhr) {
                try {
                        const erroJSON = JSON.parse(xhr.responseText);
                        showErrorMessage(erroJSON.message + "<br>");
                    } catch (e) {
                   
                    }
                
            }
        });
        }
        function createRoomAndValidate() {
            const name = $('#roomName').val().trim();
            const capacity = $('#roomCapacity').val().trim();
            const location = $('#roomLocation').val().trim();
            let isValid = true;
            let message = '';

            if (name === '') {
                message += 'O nome da sala é obrigatório.<br>';
                isValid = false;
            }
            if (capacity === '' || isNaN(capacity) || capacity <= 0) {
                message += 'A capacidade deve ser um número maior que zero.<br>';
                isValid = false;
            }
            if (location === '') {
                message += 'A localização é obrigatória.<br>';
                isValid = false;
            }

            if (!isValid) {
            return showErrorMessage(message);
            }
            /** fazendo a requisição para criar a sala */
           
            $.ajax({
                    url: BASE_URL + '/register/room/store',
                    type: 'POST',
                    data: $('#registerRoomForm').serialize(),
                    success: function(response) {
                        showSuccessMessage(response.message);
                        loadRooms();
                        $('#registerRoomForm')[0].reset();
                    }
                });
       
    }
    /**fim das funções de gerenciar salas */

    /** funções e variaveis da tela de registrar usuario */
    let emailAlreadyExists = false;  
        function validateEmailOnBlur(email) {
            console.log();
            $.ajax({
                url: `${BASE_URL}/register/email/validar` ,
                type: 'POST',
                data : {email: email},
                success: function () {
                    console.log('pode cadastrar agr');
                    emailAlreadyExists = false;
                },
                error: function(e) {
                    console.log('email ja existe');
                    showErrorMessage('Esse Endereço de email já está cadastrado');
                    emailAlreadyExists = true;
                    
                }
            });
        }
   
            $('#btn_register_user').on('click',  function() {
                   
                    if(!emailAlreadyExists){

                    
                        const email = $('#registerEmail').val();
                        const password = $('#registerPassword').val();
                        const confirmPassword = $('#confirmPassword').val();
                        let errorMessage = '';

                        // Validação do campo de email
                        if (!email) {
                            errorMessage += 'O campo de email é obrigatório.<br>';
                        } else if (!validateEmail(email)) {
                            errorMessage += 'Por favor, insira um email válido.<br>';
                        }

                        // Validação do campo de password
                        if (!password) {
                            errorMessage += 'O campo de senha é obrigatório.<br>';
                        }

                        // Validação do campo de confirm password
                        if (!confirmPassword) {
                            errorMessage += 'O campo de confirmação de senha é obrigatório.<br>';
                        } else if (password !== confirmPassword) {
                            errorMessage += 'As senhas não coincidem.<br>';
                        }

                        if (errorMessage) {
                            showErrorMessage(errorMessage);
                        } else {

                            $.ajax({
                            url: `${BASE_URL}/register/user/store` ,
                            type: 'POST',
                            data : $('#registerForm').serialize(),
                            success: function (e) {
                                showSuccessMessage(e.message);

                                setTimeout(() => window.location.href = `${BASE_URL}/`, 2000);
                            },
                            
                        });
                            
                        }
                    }
                });


            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

     
    /** fim das funções de registrar usuario  */ 

    /** funções padrões  */
    function showErrorMessage(message) {
            
            removeMessages();
            const flashMessageDiv = $('<div class="error-message"></div>').html(message).show();
            $('form').prepend(flashMessageDiv);
            removeMessageAfterTimeout($('.error-message'), 3000);
        }

    function showSuccessMessage(message) {
            removeMessages();
            const successMessageDiv = $('<div class="success-message"></div>').html(message).show();
            $('form').prepend(successMessageDiv);
            removeMessageAfterTimeout($('.success-message'), 3000);
        }

    function removeMessages() {
            $('.flash-message').remove();
            $('.success-message').remove();
           
    }
        
    function removeMessageAfterTimeout(element, timeout) {
        setTimeout(function() {
            element.remove();
        }, timeout);
    }
      

    function logout () {

    }  
    /** fim das funções padrões */
</script>

</body>
</html>