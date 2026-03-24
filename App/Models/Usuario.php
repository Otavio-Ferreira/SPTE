<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model
{
  protected $db;

  public function __construct(\PDO $db)
  {
    $this->db = $db;
  }

  public function getAll()
  {
    $query = "SELECT id, nome, email, tipo  FROM Usuario";
    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getByEmail($email)
  {
    $query = "SELECT * FROM Usuario WHERE email = :email";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function verifyUser($email, $senha)
  {
    $query = "SELECT * FROM Usuario WHERE email = :email AND senha = :senha";
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->execute();

    return $stmt->fetchAll();
  }
}
