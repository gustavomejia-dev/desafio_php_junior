<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
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
        color: #68F765;

        }
    </style>
</head>
<body>
<div class="flash-message"></div>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="loginEmail">Email:</label>
                    <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                </div>

                
                <div class="form-group">
                    <label for="loginPassword">Senha:</label>
                    <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                </div>
                
                <div class="mt-3">
                    <a href="/register/user">Ainda não tem uma conta? Cadastre-se aqui</a>
                </div>
            </form>
            <button id="btn_form_login"  class="btn btn-primary">Entrar</button>
        </div>
    </div>
</div>
<script>
    
    const BASE_URL = `${window.location.protocol}//${window.location.hostname}${window.location.port ? ':' + window.location.port : ''}`;
    $(document).ready(function() {
    
        $('#btn_form_login').on('click', function(e) {
            if(!$('#loginEmail').val() || !$('#loginPassword').val()){
                showErrorMessage('Preencha todos os Campos por favor<br>');
                return;
            }
            $.ajax({
                url: BASE_URL + '/login',
                type: 'POST',
                data:  $('#loginForm').serialize(),
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        window.location.href = '/register/room';
                    } 
                },
                error: function(xhr) {
                    console.log(xhr)
                    try {
                        const erroJSON = JSON.parse(xhr.responseText);
                        showErrorMessage(erroJSON.message + "<br>");
                    } catch (e) {
                   
                    }
                }
            });
        });
        function showErrorMessage(message) {
            
            removeMessages();
            const flashMessageDiv = $('<div class="error-message"></div>').html(message).show();
            $('form').prepend(flashMessageDiv);
            removeMessageAfterTimeout($('.error-message'), 3000);
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
    });
    
</script>
</body>
</html>