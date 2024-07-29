<?php 

namespace App\http;
/**
 * Classe Response para gerenciar respostas HTTP.
 */
class Response
{   /**
    * Retorna uma resposta JSON.
    * 
    * @param array $data Dados a serem retornados na resposta JSON.
    * @param int $status Código de status HTTP da resposta. Default é 200.
    * @return void
    */
    public static function json(array $data = [], int $status = 200)
    {
        http_response_code($status);

        header("Content-Type: application/json");
       
        echo json_encode($data);
    }
}