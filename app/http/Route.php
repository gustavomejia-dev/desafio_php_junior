<?php 

namespace App\Http;
/**
 * Classe Route para gerenciar as rotas da aplicação.
 */
class Route 
{   /**
    * @var array $routes Armazena todas as rotas registradas.
    */
    private static array $routes = [];
      /**
     * Registra uma rota GET.
     * 
     * @param string $path Caminho da rota.
     * @param string $action Controlador e método da rota no formato 'Controller@method'.
     * @return void
     */
    public static function get(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'GET'
        ];
    }
 /**
     * Registra uma rota POST.
     * 
     * @param string $path Caminho da rota.
     * @param string $action Controlador e método da rota no formato 'Controller@method'.
     * @return void
     */
    public static function post(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'POST'
        ];
    }
     /**
     * Registra uma rota PUT.
     * 
     * @param string $path Caminho da rota.
     * @param string $action Controlador e método da rota no formato 'Controller@method'.
     * @return void
     */
    public static function put(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'PUT'
        ];
    }
 /**
     * Registra uma rota DELETE.
     * 
     * @param string $path Caminho da rota.
     * @param string $action Controlador e método da rota no formato 'Controller@method'.
     * @return void
     */
    public static function delete(string $path, string $action)
    {
        self::$routes[] = [
            'path'   => $path,
            'action' => $action,
            'method' => 'DELETE'
        ];
    }
     /**
     * Retorna todas as rotas registradas.
     * 
     * @return array
     */
    public static function routes()
    {
        return self::$routes;
    }
}