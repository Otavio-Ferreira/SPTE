<?php
namespace App\Models;
use MF\Model\Model;

class Frequencia extends Model {

    private $id;
    private $id_aluno;
    private $id_turma;
    private $id_calendario;
    private $assiduidade;
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function getByAlunoId($id_aluno) {
        $query = "SELECT id_turma, id_calendario, assiduidade, C.* FROM Frequencia F INNER JOIN Calendario_Mes C ON C.id = F.id_calendario  WHERE id_aluno = :id_aluno";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindValue(':id_aluno', $id_aluno);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
?>