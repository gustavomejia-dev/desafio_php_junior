<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
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

<div id="container_register" class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Cadastro</h2>
            
            <form id="registerForm">
                <div class="form-group">
                    <label for="registerName">Nome:</label>
                    <input type="text" class="form-control" id="registerName" name="registerName" required>
                </div>
                <div class="form-group">
                    <label for="userRole">Tipo de Usuário:</label>
                    <select id="group_id" name="group_id" class="form-control">
                        <option value="1">Administrador</option>
                        <option value="2">Usuário</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="registerEmail">Email:</label>
                    <input onblur="validateEmailOnBlur($(this).val())" type="email" class="form-control" id="registerEmail" name="registerEmail" required>
                </div>
                <div class="form-group">
                    <label for="registerPassword">Senha:</label>
                    <input type="password" class="form-control" id="registerPassword" name="registerPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar Senha:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                
                
            </form>
            <button id="btn_register_user" type="button" class="btn btn-primary">Cadastrar</button>
            <div class="mt-3">
                    <a href="/">Já tem uma conta? Faça login aqui</a>
                </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout/footer.php' ?>