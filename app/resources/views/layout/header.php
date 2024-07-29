<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Salas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .table-scroll {
            max-height: 400px; /* Ajuste conforme necessário */
            overflow-y: auto;  /* Barra de rolagem vertical */
            overflow-x: auto;  /* Barra de rolagem horizontal */
        }
        table {
            width: 100%; /* Garantir que a tabela use 100% da largura do contêiner */
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            width: 100%;
        }
       
        .table-container {
            margin-top: 20px;
        }
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
        .error-message {
     
            padding: 10px;
            margin-bottom: 10px;
            color: #f44336;
     
        }
        .success-message {

            padding: 10px;
            margin-bottom: 10px;
            color: #1CD929;

        }
        .table-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a  style="cursor:pointer;" class="nav-link">Gerenciar Salas</a>
                </li>
                <li class="nav-item">
                    <a  style="cursor:pointer;" class="nav-link" id="reservar_sala">Reservar uma Sala</a>
                </li>
                <li class="nav-item">
                    <a  style="cursor:pointer;" id="logout" class="nav-link" href="">Logout</a>
                </li>
            </ul>
        </div>
        <script>
            $(document).ready(() => {
                $('#reservar_sala').on ('click', () => {
                    window.open(BASE_URL +  '/register/room/reserve', '_blank');
                });
                $('#gerenciar_sala').on ('click', () => {
                    window.open(BASE_URL +  'register/room', '_blank');
                })

                $('#logout').on ('click', () => {
                  
                    $.ajax({
                    url: BASE_URL + '/user/logout',
                    type: 'POST',
                    success: function(response) {
                       
                    },
                    error: function() {
                        // showErrorMessage('Erro ao deletar a reserva' );
                    }
                        
                    });
                    setTimeout(() => {window.location.href = BASE_URL}, 1000);
                })
            });
            
            // $('#reservar_sala').on ('click', () => {
            //     window.open(BASE_URL +  , '_blank');
            // })

        </script>
    </nav>