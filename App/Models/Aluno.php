<?php
namespace App\Models;
use MF\Model\Model;

// 1. O nome da classe deve ser preferencialmente no singular e igual ao nome do arquivo.
class Aluno extends Model {

    // 2. É uma boa prática definir os atributos da tabela como propriedades privadas da classe.
    private $id;
    private $nome;
    private $matricula;
    private $id_turma;

    // 3. Métodos Mágicos Getters e Setters (Facilitam na hora de salvar os dados)
    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    /* ========================================================================
       MÉTODO 1: BUSCAR COM FILTRO (Exemplo: Alunos de uma turma específica)
       ======================================================================== */
    public function getByTurmaId($id_turma) {
        // INSTRUÇÃO: Sempre que a consulta depender de um dado externo (variável),
        // use :nome_do_parametro para marcar o lugar onde o valor vai entrar.
        $query = "SELECT id, nome, id_categoria, id_turma FROM Aluno WHERE id_turma = :id_turma";
        
        // INSTRUÇÃO: Use $this->db->prepare() em vez de query(). Isso prepara a consulta com segurança.
        $stmt = $this->db->prepare($query);
        
        // INSTRUÇÃO: Use bindValue para substituir o :id_turma pelo valor real da variável.
        $stmt->bindValue(':id_turma', $id_turma);
        
        // INSTRUÇÃO: Execute a consulta.
        $stmt->execute();

        // INSTRUÇÃO: Use fetchAll() quando esperar VÁRIOS resultados (uma lista).
        // Use fetch() quando esperar APENAS UM resultado.
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id_aluno) {
        // INSTRUÇÃO: Sempre que a consulta depender de um dado externo (variável),
        // use :nome_do_parametro para marcar o lugar onde o valor vai entrar.
        $query = "SELECT id, nome, id_categoria, id_turma FROM Aluno WHERE id = :id_aluno";
        
        // INSTRUÇÃO: Use $this->db->prepare() em vez de query(). Isso prepara a consulta com segurança.
        $stmt = $this->db->prepare($query);
        
        // INSTRUÇÃO: Use bindValue para substituir o :id_aluno pelo valor real da variável.
        $stmt->bindValue(':id_aluno', $id_aluno);
        
        // INSTRUÇÃO: Execute a consulta.
        $stmt->execute();

        // INSTRUÇÃO: Use fetchAll() quando esperar VÁRIOS resultados (uma lista).
        // Use fetch() quando esperar APENAS UM resultado.
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function salvar() {
        // INSTRUÇÃO: Montando o INSERT com parâmetros nomeados de segurança.
        $query = "INSERT INTO Aluno (nome, matricula, id_turma) VALUES (:nome, :matricula, :id_turma)";
        
        $stmt = $this->db->prepare($query);
        
        // INSTRUÇÃO: Pegamos os valores das propriedades da classe que foram setadas no Controller.
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':matricula', $this->__get('matricula'));
        $stmt->bindValue(':id_turma', $this->__get('id_turma'));
        
        $stmt->execute();

        // Retorna a própria classe atualizada (útil se precisar do objeto logo após salvar)
        return $this;
    }


}
?>