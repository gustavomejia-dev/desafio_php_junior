<?php 

namespace App\http;
/**
 * Classe Request para gerenciar solicitações HTTP.
 */
class Request
{    /**
    * Retorna o método HTTP da solicitação atual.
    * 
    * @return string
    */
    public static function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }
    /**
     * Retorna o corpo da solicitação atual como um array associativo.
     * 
     * @return array
     */
    public static function body()
    {   
        $requesData = json_decode(file_get_contents('php://input'), true) ?? [];
    
        if($requesData  == [] && $_SERVER['REQUEST_METHOD'] == 'POST') {
           
            $requesData = $_POST;
        }
        if($requesData  == [] && $_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents('php://input'), $requesData);

        }
        
        $data = match(self::method()) {
            'GET' => $_GET,
            'POST', 'PUT', 'DELETE' => $requesData,
        };

        return $data;
    }

    // public static function authorization()
    // {
    //     $authorization = getallheaders();

    //     if (!isset($authorization['Authorization'])) return ['error' => 'Sorry, no authorization header provided'];

    //     $authorizationPartials = explode(' ', $authorization['Authorization']);

    //     if (count($authorizationPartials) != 2) return ['error'=> 'Please, provide a valid authorization header.'];

    //     return $authorizationPartials[1] ?? '';
    // }
}