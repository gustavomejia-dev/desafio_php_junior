<?php
namespace App\controllers;

use App\http\Request;
use App\http\Response;
use App\models\User;

class RegisterUserController extends Controller {

    public function index () {
       
        return $this->view('register/user/index');
    }

    public function verifyEmailExist (Request $request, Response $response) {
        
        $emailUserIncoming = isset($request::body()['email']) ? $request::body()['email'] : '';
        $user = User::where('email', $emailUserIncoming)->get();
        
        if($user){
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Esse Endereço de email já existe'
            ], 400);

        }
        return  $response::json([
            'error'   => false,
            'success' => true,
            'message' => $emailUserIncoming,
        ], 200);
    }
    public function store (Request $request, Response $response) {
       
            /** validando senha */
            if($request::body()['confirmPassword'] != $request::body()['registerPassword']){
                return  $response::json([
                    'error'   => true,
                    'success' => false,
                    'message' => 'As Senhas não são Iguais',
                ], 400);
            }
            /** validando se o email ja existe no banco */
            if(User::where('email', $request::body()['registerEmail'])->get()){
                return  $response::json([
                    'error'   => true,
                    'success' => false,
                    'message' => 'Esse email já está cadastrado no sistema',
                ], 400);
            }
            
            $userCreate = User::create([
                'name' =>  $request::body()['registerName'],
                'group_id' => $request::body()['group_id'],
                'password' => password_hash($request::body()['registerPassword'], PASSWORD_DEFAULT),
                'email' => $request::body()['registerEmail']
            ]);

            if($userCreate) {
                return  $response::json([
                    'error'   => false,
                    'success' => true,
                    'message' => 'Usuario Cadastrado com Sucesso',
                ], 200);
            }

            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Opps!, Ocorreu algum erro !!',
            ], 200);
            
        }
    }


