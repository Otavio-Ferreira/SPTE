<?php
namespace App\Models;
use MF\Model\Model;

// 1. O nome da classe deve ser preferencialmente no singular e igual ao nome do arquivo.
class Media extends Model {

    // 2. É uma boa prática definir os atributos da tabela como propriedades privadas da classe.
    private $id;
    private $id_aluno;
    private $nota;
    private $ano;
    private $semestre;

    // 3. Métodos Mágicos Getters e Setters (Facilitam na hora de salvar os dados)
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