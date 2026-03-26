<?php
  namespace App\Models;
  use MF\Model\Model;
  
  class Turma extends Model{
    protected $db;

    public function __construct(\PDO $db){
      $this->db = $db;
    }

    public function getAll(){
      $query = "select * from Turma";
      return $this->db->query($query)->fetchAll();
    }

    public function getById($id){
      $query = "select * from Turma where id = :id";
      $stmt = $this->db->prepare($query);
      $stmt->bindValue(':id', $id);
      $stmt->execute();

      return $stmt->fetch();
    }

    public function searchAll($termoBusca) {
        $query = "SELECT * FROM Turma WHERE serie LIKE :busca_serie OR ano LIKE :busca_ano OR turno LIKE :busca_turno";
        $stmt = $this->db->prepare($query);
        $termo = '%' . $termoBusca . '%'; 
        $stmt->bindValue(':busca_serie', $termo);
        $stmt->bindValue(':busca_ano', $termo);
        $stmt->bindValue(':busca_turno', $termo);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchByIdUser($idUsuario, $termoBusca) {
        $query = "SELECT * FROM Turma WHERE id_usuario = :id_usuario AND (serie LIKE :busca_serie OR ano LIKE :busca_ano OR turno LIKE :busca_turno)";
                  
        $stmt = $this->db->prepare($query);
        
        $termo = '%' . $termoBusca . '%';
        
        $stmt->bindValue(':id_usuario', $idUsuario);
        $stmt->bindValue(':busca_serie', $termo);
        $stmt->bindValue(':busca_ano', $termo);
        $stmt->bindValue(':busca_turno', $termo);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByIdUser($id_usuario){
      $query = "select * from Turma";
      return $this->db->query($query)->fetchAll();
    }

    public function store($ano, $serie, $turno, $id_usuario) {
      try {
          $this->db->beginTransaction();

          $queryTurma = "INSERT INTO Turma (ano, serie, turno) VALUES (:ano, :serie, :turno)";
          $stmtTurma = $this->db->prepare($queryTurma);
          $stmtTurma->bindValue(':ano', $ano);
          $stmtTurma->bindValue(':serie', $serie);
          $stmtTurma->bindValue(':turno', $turno);
          $stmtTurma->execute();

          $id_turma_gerado = $this->db->lastInsertId();

          $queryUsuarioTurma = "INSERT INTO Usuario_Turma (id_usuario, id_turma) VALUES (:id_usuario, :id_turma)";
          $stmtUsuarioTurma = $this->db->prepare($queryUsuarioTurma);
          $stmtUsuarioTurma->bindValue(':id_usuario', $id_usuario);
          $stmtUsuarioTurma->bindValue(':id_turma', $id_turma_gerado);
          $stmtUsuarioTurma->execute();

          $this->db->commit();

          return true;

        }
        catch (\PDOException $e) {
            $this->db->rollBack();
            
            return false; 
        }
    }

    public function getTurmasPorUsuario($id_usuario) 
    {
        $query = "
            SELECT t.id, t.serie, t.ano, t.turno 
            FROM Turma t
            INNER JOIN Usuario_Turma ut ON t.id = ut.id_turma
            WHERE ut.id_usuario = :id_usuario
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $id_usuario);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
  }
?>