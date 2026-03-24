<?php

  namespace App;

  class Connection{

    public static function getDb(){

      try {
        $conn = new \PDO(
          "mysql:host=db;dbname=spte_db;charset=utf8",
          "admin",
          "123"
        );
        return $conn;
      } catch (\PDOException $e) {
        //throw $th;
        die("Erro de conexão com o banco: " . $e->getMessage());
      }
    }
  }

  define('BASE_URL', 'http://localhost:8000/');
?>