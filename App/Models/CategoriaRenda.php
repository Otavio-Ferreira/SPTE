<?php
  namespace App\Models;
  use MF\Model\Model;
  
  class CategoriaRenda extends Model{
    protected $db;

    public function __construct(\PDO $db){
      $this->db = $db;
    }

    public function getAll(){
      $query = "select * from Categoria_Renda";
      return $this->db->query($query)->fetchAll();
    }
}