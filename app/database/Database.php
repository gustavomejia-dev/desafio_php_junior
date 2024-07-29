<?php
namespace App\database;

use ErrorException;
use PDO;
use PDOException;
/**
 * Classe Database para gerenciamento de banco de dados usando PDO.
 */
class Database{

    private $_server_name;
    private $_username;
    private $_password;
    private $_db_name;
    private string $_query;

    private static $_model;
    private static string $_tableName;
    public $_conn;

    private array $_query_where = [];

    private $_query_inner_join = '';

    public  $_select_query  ;

    /**
     * Construtor da classe Database.
     * Inicializa os parâmetros de conexão e conecta ao banco de dados.
     */
    function __construct() {
        $this->_server_name = 'desafio-tecnico.cf1afo0ns4vr.us-west-2.rds.amazonaws.com';
        $this->_username = 'gustavo_mejia';
        $this->_password = 'DesafioAvant@2024';
        $this->_db_name = 'gustavo_mejia';
        /** obtem o nome da tabela baseada na propiedade table da model */
        $this->getTableName();
        
        try{
            
            $this->_conn = new PDO("mysql:host=$this->_server_name;dbname=$this->_db_name", $this->_username, $this->_password);
            $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conexão Realizada \n";
        } 
        catch (PDOException $e) {
            echo "Falha ao conectar ao banco de dados ! \n";
            // echo "erro: " . $e->getMessage();
        }
    }

     /**
     * Método mágico para chamada estática de métodos.
     * @param string $method
     * @param array $parameters
     * @return Database
     */
    public static function __callStatic($method, $parameters)
    {   
        
        if(!self::$_model) {
            self::$_model = new static();
        }
        if(in_array($method, ['where', 'andWhere'])) {
            
            self::$_model->_where($parameters[0], $parameters[1]);

        }
        if(in_array($method, ['orWhere'])) {
          
            self::$_model->_where($parameters[0], $parameters[1], 'OR' );

        }

        if($method == 'join'){
            self::$_model->join($parameters[0], $parameters[1], $parameters[2]);
        }

        
        
       
        return self::$_model;
      
    }

    /**
     * Método mágico para chamada de métodos.
     * @param string $method
     * @param array $parameters
     * @return Database
     */
    public function __call ($method, $parameters) {
        
        if(!self::$_model) {
            self::$_model = new static();
        }
        if(in_array($method, ['where', 'andWhere'])) {
    
   
            self::$_model->_where($parameters[0], $parameters[1]);
            
        }
        if($method == 'join'){
            self::$_model->join($parameters[0], $parameters[1], $parameters[2]);
        }
      
        return self::$_model;
    }   
    
    /**
     * Obtém o nome da tabela a partir da propriedade $table definida na model.
     * @throws ErrorException
     */
    private function getTableName() : void
    {      
            $reflectionClass = new \ReflectionClass($this);
            $property = $reflectionClass->getProperty('table');
            if (!$property) {
                throw new ErrorException('A propriedade $table não está definida na model.');
            }

            $property->setAccessible(true);
            self::$_tableName = $property->getDefaultValue();
   

       
    }
    /**
     * Cria um novo registro na tabela.
     * @param array $data
     * @return int
     */

    public static function create (array $data) : int {
        self::$_model = new static();
        $tableName = self::$_tableName;
         /** obtem as keys do array  */
         $fields = array_keys($data);
         /** array com o mesmo número de posições do array fields, e caso não tenha preenchido ele preenche com "?"*/
         $binds = array_pad([], count($fields), '?');
         /** colunas da tabela */
         $fields_query =  implode(',', $fields);
         /** valores da query */
         $values_query = implode(',' ,$binds);
         // echo "<pre>"; print_r($binds);die;
         self::$_model->_query = "INSERT INTO 
                     `gustavo_mejia`.{$tableName}
                     ({$fields_query}) 
                  VALUES 
                     ({$values_query})"; 
        // echo "<pre>"; print_r(array_values($data));
        // die(self::$_model->_query);
        self::$_model->executeQuery(self::$_model->_query, array_values($data));

        return self::$_model->_conn->lastInsertId();       
        
    }
      /**
     * Executa uma consulta com parâmetros.
     * @param string $query
     * @param array $params
     */
    private function executeQuery(string $query, $params = []) : void {
        try {
            $stat = $this->_conn->prepare($query);
            $stat->execute($params); 
        } catch (PDOException $e) {
            echo 'erro: ' . $e->getMessage();
        }
    }

   
     /**
     * Atualiza registros na tabela.
     * @param array $values
     * @param int $where
     * @return bool
     */
    public static function update($values, $where){
        self::$_model = new static();
        /** obtem as keys do array  */
        $fields = array_keys($values);
        
        $query = 'UPDATE '. self::$_tableName .' SET '.implode('=?,',$fields).'=? WHERE id = '.$where;
        
  
        self::$_model->executeQuery($query,array_values($values));
 
        return true;
      }
       /**
     * Deleta um registro da tabela pelo ID.
     * @param int $id
     * @return bool
     */
    public static function delete (int $id) {
            $model = new static();
            $query = 'DELETE FROM `gustavo_mejia`.' .  self::$_tableName  . " WHERE id = {$id}";
 
            return $model->executeQuery($query);
    }  
      /**
     * Obtém todos os registros da tabela.
     * @return array
     */
    public static function all () {
           
            $model = new static();
            
            $query = 'SELECT * FROM `gustavo_mejia`.' .  self::$_tableName ;

            $result = $model->_conn->query($query);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
    }
     /**
     * Obtém registros da tabela com base nos critérios definidos.
     * @return array
     */
    public function get () {
        $table_alias = self::$_tableName;
        $query = "SELECT * FROM `gustavo_mejia`." . self::$_tableName . " {$table_alias} " . $this->_query_inner_join;

        // echo "<pre>"; print_r(self::$_query_where);die;
        $where = '';
        if($this->_query_where){
            $where = ' WHERE ' . implode(' AND ', $this->_query_where);
        }
        
       
        $this->_query = $query . $where;
     
        // die($this->_query);
        $result = $this->_conn->query($this->_query);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }  

   
    /**
     * Adiciona uma cláusula JOIN à consulta.
     * @param string $table
     * @param string $fk
     * @param string $table_fk
     * @return Database
     */
    private static function join (string $table, string $fk, string $table_fk) {
        self::$_model->_query_inner_join = " INNER JOIN 
                                                gustavo_mejia.{$table}
                                            ON {$fk} = {$table_fk}  ";
                                             
        // die(self::$_model->_query_inner_join);                                       
        return self::$_model;                                       
    }

    /**
     * Executa uma consulta SQL diretamente.
     * @param string $query
     * @return array
     */
    public static function query (string $query) {
        $model = new static();
        
        $result = $model->_conn->query($query);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    /**
     * Adiciona uma cláusula WHERE à consulta.
     * @param string $col
     * @param string|array $value
     * @param string $type
     * @param string $sinal
     * @return Database
     */
    private function _where(string $col, string|array $value, $type = 'AND', $sinal = '=') {
        

        $table_alias = self::$_tableName;
        if(is_array($value)){
            $this->_query_where[] =  "{$table_alias}." . $col  . " IN ('" . implode("','", $value) . "')";
        }else{
            $this->_query_where[] = "{$table_alias}.{$col}  {$sinal}  '$value'";
        }
        
        return $this;
        
        // return $rows;

        
      
      
      
}



}