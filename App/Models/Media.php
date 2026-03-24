<?php
namespace App\Models;
use MF\Model\Model;

class Media extends Model {

    private $id;
    private $id_aluno;
    private $nota;
    private $ano;
    private $semestre;
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function getByAlunoId($id_aluno) {
        $query = "SELECT nota, ano, semestre FROM Media WHERE id_aluno = :id_aluno";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id_aluno', $id_aluno);
        
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}
?>