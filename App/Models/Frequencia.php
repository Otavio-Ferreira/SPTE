<?php
namespace App\Models;
use MF\Model\Model;

// 1. O nome da classe deve ser preferencialmente no singular e igual ao nome do arquivo.
class Frequencia extends Model {

    // 2. É uma boa prática definir os atributos da tabela como propriedades privadas da classe.
    private $id;
    private $id_aluno;
    private $id_turma;
    private $id_calendario;
    private $assiduidade;

    // 3. Métodos Mágicos Getters e Setters (Facilitam na hora de salvar os dados)
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    /* ========================================================================
       MÉTODO 1: BUSCAR COM FILTRO (Exemplo: Frequencias de uma turma específica)
       ======================================================================== */
    public function getByAlunoId($id_aluno) {
        // INSTRUÇÃO: Sempre que a consulta depender de um dado externo (variável),
        // use :nome_do_parametro para marcar o lugar onde o valor vai entrar.
        $query = "SELECT id_turma, id_calendario, assiduidade FROM Frequencia WHERE id_aluno = :id_aluno";
        
        // INSTRUÇÃO: Use $this->db->prepare() em vez de query(). Isso prepara a consulta com segurança.
        $stmt = $this->db->prepare($query);
        
        // INSTRUÇÃO: Use bindValue para substituir o :id_turma pelo valor real da variável.
        $stmt->bindValue(':id_aluno', $id_aluno);
        
        // INSTRUÇÃO: Execute a consulta.
        $stmt->execute();

        // INSTRUÇÃO: Use fetchAll() quando esperar VÁRIOS resultados (uma lista).
        // Use fetch() quando esperar APENAS UM resultado.
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}
?>