<?php 

namespace App\kernel;

use App\http\Request;
use App\http\Response;
/**
 * Classe Kernel para gerenciamento de rotas e despacho de requisições.
 */
class Kernel 
{   /**
    * Despacha a requisição para o controlador e ação correspondentes.
    * 
    * @param array $routes Array de rotas configuradas.
    */
    public static function dispatch(array $routes)
    {   
        $url = $_SERVER['REQUEST_URI'];
        // echo "<pre>"; print_r($_SERVER);die;
        // echo "<pre>"; print_r(Request::method());die;
        isset($_GET['url']) && $url .= $_GET['url'];

        $url !== '/' && $url = rtrim($url, '/');

        $prefixController = 'App\\controllers\\';

        $routeFound = false;
        
        foreach ($routes as $route) {
           
            $pattern = '#^'. preg_replace('/{id}/', '([\w-]+)', $route['path']) .'$#';
           
            /** achou a rota */
            if($url == $route['path']) {

                if (preg_match($pattern, '/', $matches)) {
                    
                    array_shift($matches);
                }
                $routeFound = true;
              
                /** verifica se o metodo corresponde a rota configurada */
                if ($route['method'] !== Request::method()) {
                    Response::json([
                        'error'   => true,
                        'success' => false,
                        'message' => 'Sorry, method not allowed.'
                    ], 405);
                    return;
                
                }    
                /** obtem o nome da controller e o metodo que deve chamar */
                [$controller, $action] = explode('@', $route['action']);

                $controller = $prefixController . $controller;
                $extendController = new $controller();
                $extendController->$action(new Request, new Response, $matches);
            }
        }
      
      
    }
}