<?php
    define('HOST', 'localhost');
    define('DBNAME', 'aula');
    define('USER', 'root');
    define('PASSWORD', '');

    class Conexao {  
        private static $pdo;  

        private function __construct() {      
        }  

        public static function getInstance() {   
            if (!isset(self::$pdo)) {   
                try {    
                    self::$pdo = new PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . ";", USER, PASSWORD);
                    self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
                    } catch (PDOException $e) {   
                    print "Erro: " . $e->getMessage();   
                }   
            }   
            return self::$pdo;   
        }
    }
?>