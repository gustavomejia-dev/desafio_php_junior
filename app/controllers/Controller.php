<?php 

namespace App\controllers;
use App\http\Response;

/**
     * Classe abstrata Controller para gerenciamento de visualizações e autenticação de usuários.
*/

abstract class Controller
{
    
    
    /**
     * Renderiza uma visualização.
     * 
     * @param string $dir Diretório da visualização.
     * @param array $dados Dados a serem passados para a visualização.
     * @param bool $private Indica se a visualização é privada (requer autenticação).
     * @return void
     */
    public  function view (string $dir, array $dados = [], $private = false) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        // Obtém o nome do host
        $host = $_SERVER['HTTP_HOST'];
        
        // Constrói a URL base
        $baseUrl = $protocol . '://' . $host . '/';
        
  
        if($private == true && $this->isUserLoggedIn() == false){
            header("Location: {$baseUrl}");
            // return Response::json([
            //     'error'   => true,
            //     'success' => false,
            //     'message' => 'Opps! Acesso Negado!'
            // ], 401);
            
            
            
        }

        extract($dados);

            ob_start();
            
            
            
            require  __DIR__ . '/../resources/views/' . $dir . '.php';

            $html = ob_get_clean();
        
            echo $html;
    }
    /**
     * Verifica se o usuário está logado.
     * 
     * @return bool
     */
    public function isUserLoggedIn() {
       
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }
    
     /**
     * Define o usuário como logado na sessão.
     * 
     * @param array $user Dados do usuário.
     * @return void
     */
    public function setUserLogged ($user) {
   
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $user;
    }
    /**
     * Destroi a sessão do usuário logado.
     * 
     * @return void
     */
    public function  destroyUserLogged () {
        session_start();
        session_unset();  // Remove todas as variáveis de sessão
        session_destroy(); // Destroi a sessão
    } 
    /**
     * Obtém o usuário logado.
     * 
     * @return array|null
     */
    function getLoggedInUser() {
      
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  
            return $_SESSION['user'];
                
            
        }
        // Retorna null se o usuário não estiver logado
        return null;
    }

}
