<?php

namespace App\controllers;
use App\http\Request;
use App\http\Response;
use App\models\User;


class LoginController extends Controller
{

     /**
     * Exibe a página de login.
     * 
     * @return mixed
     */
    public function index () {

         return $this->view('login/index'); 
    }
    /**
     * Realiza o logout do usuário.
     * 
     * @return mixed
     */
    public function logout () {
        $this->destroyUserLogged();
        return $this->view('login/index'); 
    }
    /**
     * Realiza o login do usuário.
     * 
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function login (Request $request, Response $response) {
    
       
        $user = User::join('groups', 'users.group_id', 'groups.id')->where('email', $request::body()['loginEmail'])
                    ->get();

        if(!$user) {
            return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Usuario não encontrado '
            ], 404);
        }
        if(password_verify($request::body()['loginPassword'], $user[0]['password'])){
          $this->setUserLogged($user[0]);
         
          return  $response::json([
                'error'   => false,
                'success' => true,
                'message' => $user
            ], 200);

        } else{
           return  $response::json([
                'error'   => true,
                'success' => false,
                'message' => 'Usuario não encontrado'
            ], 404);
        }       
    }
}
