<?php

namespace App\Models;

use MF\Model\Model;

class Aluno extends Model
{

    private $id;
    private $nome;
    private $matricula;
    private $id_turma;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function getByTurmaId($id_turma)
    {
        $query = "SELECT * FROM Aluno WHERE id_turma = :id_turma";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_turma', $id_turma);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function searchByTurmaId($id_turma, $termoBusca) {
        $query = "SELECT * FROM Aluno WHERE id_turma = :id_turma AND (nome LIKE :busca_nome)";
                  
        $stmt = $this->db->prepare($query);
        
        $termo = '%' . $termoBusca . '%';
        
        $stmt->bindValue(':id_turma', $id_turma);
        $stmt->bindValue(':busca_nome', $termo);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id_aluno)
    {
        $query = "SELECT A.*, C.nome as nome_categoria, T.serie as serie_turma, T.ano as ano_turma, T.turno as turno_turma FROM Aluno A INNER JOIN Categoria_Renda C ON A.id_categoria = C.id INNER JOIN Turma T ON A.id_turma = T.id WHERE A.id = :id_aluno";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_aluno', $id_aluno);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function store($id_turma, $nome, $matricula, $rua, $bairro, $cidade, $estado, $id_categoria)
    {
        try {
            $query = "INSERT INTO Aluno (id_turma, nome, matricula, rua, bairro, cidade, estado, id_categoria) VALUES (:id_turma, :nome, :matricula, :rua, :bairro, :cidade, :estado, :id_categoria)";
    
            $stmt = $this->db->prepare($query);
        
            $stmt->bindValue(':id_turma', $id_turma);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':matricula', $matricula);
            $stmt->bindValue(':rua', $rua);
            $stmt->bindValue(':bairro', $bairro);
            $stmt->bindValue(':cidade', $cidade);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':id_categoria', $id_categoria);
    
            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            return false;
        }

        return $this;
    }
}
