# Trabalho Prático 3 - Banco de Dados

Repositório destinado à implementação e consolidação do projeto de banco de dados para a disciplina de Banco de Dados. O objetivo desta etapa é aplicar o esquema relacional (3FN) em um SGBD, garantindo as regras de negócio e a integridade dos dados. Equipe:

* Otavio da Silva Ferreira
* Bruno da Silva Macedo
* Kayky Moreira Morais
* Rafael da Silva Sousa

## Modelagem Física (DDL)

### Tecnologias Utilizadas:
- **SGBD:** MySQL
- **Linguagem de programação:** PHP

---

## Principais Decisões de Ajuste no Modelo

Para garantir a coerência estrutural e prevenir anomalias, algumas decisões técnicas foram tomadas durante a tradução do modelo relacional para o script:

1. **Integridade Referencial e Exclusão em Cascata:**
   Optou-se por utilizar `ON DELETE CASCADE` nas chaves estrangeiras. Isso garante que, caso um registro pai (como um `Usuario` ou uma `Turma`) seja removido, todos os registros dependentes (como `Nucleo_Familiar` ou `Aluno`) sejam automaticamente excluídos, evitando dados órfãos e mantendo a consistência da base.

2. **Resolução de Relacionamentos N:N:**
   Para atender às necessidades do sistema e aos requisitos do projeto (que exige ao menos uma tabela associativa), foi implementada a tabela `Usuario_Turma`. Ela resolve o relacionamento muitos-para-muitos entre usuários e turmas, permitindo o gerenciamento flexível de quem tem acesso a quais turmas.

3. **Padronização de Chaves Primárias Sintéticas:**
   Todas as tabelas utilizam chaves primárias do tipo `bigint UNSIGNED AUTO_INCREMENT`. Essa abordagem facilita a indexação do SGBD e simplifica o mapeamento futuro das chaves estrangeiras nas junções (`JOINs`), além de deixar a estrutura escalável e aderente a padrões modernos de desenvolvimento.

---

## Script de Criação do Banco (DDL)

Abaixo estão os comandos executáveis utilizados para a criação das tabelas e suas respectivas restrições de integridade, ordenados para evitar erros de dependência:

```sql
-- ==========================================
-- 1. Criação do Banco de Dados
-- ==========================================
CREATE DATABASE IF NOT EXISTS `spte_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `spte_db`;

-- ==========================================
-- 2. TABELAS INDEPENDENTES (Sem FK)
-- ==========================================

CREATE TABLE `Categoria_Renda` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `maximo` double NOT NULL,
  `minimo` double NOT NULL
);

CREATE TABLE `Usuario` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL
);

CREATE TABLE `Calendario_Mes` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `mes` int NOT NULL,
  `ano` int NOT NULL,
  `total_aulas_prevista` int NOT NULL
);

CREATE TABLE `Criterio_Frequencia` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `maximo` double NOT NULL,
  `minimo` double NOT NULL,
  `valor` double NOT NULL
);

-- ==========================================
-- 3. TABELAS DEPENDENTES (Com FK)
-- ==========================================

CREATE TABLE `Turma` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `serie` int NOT NULL,
  `ano` int NOT NULL,
  `turno` varchar(255) NOT NULL
);

CREATE TABLE `Usuario_Turma` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_usuario` bigint UNSIGNED NOT NULL,
  `id_turma` bigint UNSIGNED NOT NULL,
  CONSTRAINT `usuario_turma_id_usuario_foreign` 
    FOREIGN KEY (`id_usuario`) REFERENCES `Usuario` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuario_turma_id_turma_foreign` 
    FOREIGN KEY (`id_turma`) REFERENCES `Turma` (`id`) ON DELETE CASCADE
);

CREATE TABLE `Aluno` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_categoria` bigint UNSIGNED NOT NULL,
  `id_turma` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `cidade` varchar(255) NOT NULL,
  `estado` varchar(255) NOT NULL,
  CONSTRAINT `aluno_id_categoria_foreign` 
    FOREIGN KEY (`id_categoria`) REFERENCES `Categoria_Renda` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aluno_id_turma_foreign` 
    FOREIGN KEY (`id_turma`) REFERENCES `Turma` (`id`) ON DELETE CASCADE
);

CREATE TABLE `Nucleo_Familiar` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_aluno` bigint UNSIGNED NOT NULL,
  `ano` varchar(255) NOT NULL,
  `parentesco` varchar(255) NOT NULL,
  CONSTRAINT `nucleo_familiar_id_usuario_foreign` 
    FOREIGN KEY (`id_aluno`) REFERENCES `Aluno` (`id`) ON DELETE CASCADE
);

CREATE TABLE `Frequencia` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_aluno` bigint UNSIGNED NOT NULL,
  `id_turma` bigint UNSIGNED NOT NULL,
  `id_calendario` bigint UNSIGNED NOT NULL,
  `assiduidade` double NOT NULL,
  CONSTRAINT `frequencia_id_aluno_foreign` 
    FOREIGN KEY (`id_aluno`) REFERENCES `Aluno` (`id`) ON DELETE CASCADE,
  CONSTRAINT `frequencia_id_turma_foreign` 
    FOREIGN KEY (`id_turma`) REFERENCES `Turma` (`id`) ON DELETE CASCADE
);

CREATE TABLE `Media` (
  `id` bigint UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `id_aluno` bigint UNSIGNED NOT NULL,
  `nota` double NOT NULL,
  `ano` varchar(255) NOT NULL,
  `semestre` varchar(255) NOT NULL,
  CONSTRAINT `media_id_aluno_foreign` 
    FOREIGN KEY (`id_aluno`) REFERENCES `Aluno` (`id`) ON DELETE CASCADE
);
```

## Script de Povoamento do Banco

```sql

-- Inserindo Categorias de Renda
INSERT INTO Categoria_Renda (nome, maximo, minimo) VALUES
('Classe E (Classe Baixa)', 600.00, 0.00),
('Classe D (Classe Média)', 2000.00, 601.00),
('Classe C (Classe Alta)', 10000.00, 2001.00);

-- Inserindo Usuários do Sistema
INSERT INTO Usuario (nome, email, senha, tipo) VALUES
('Roberto Mendes', 'roberto@escola.com', 'hash_123', 'PROFESSOR'),
('Julia Lima', 'julia@escola.com', 'hash_123', 'COORDENADOR'),
('Mônica Silva', 'monica@escola.com', 'hash_123', 'DIRETOR');

-- Inserindo Calendário Letivo
INSERT INTO Calendario_Mes (mes, ano, total_aulas_prevista) VALUES
(2, 2024, 20),
(3, 2024, 22);

-- Inserindo Critérios de Frequência (Ex: Alerta abaixo de 75%)
INSERT INTO Criterio_Frequencia (maximo, minimo, valor) VALUES
(100.00, 75.00, 75.00);

-- Inserindo Turmas
INSERT INTO Turma (serie, ano, turno) VALUES
(9, 2024, 'Matutino'),
(1, 2024, 'Vespertino');

-- Vinculando Usuários às Turmas (Tabela Associativa N:N)
INSERT INTO Usuario_Turma (id_usuario, id_turma) VALUES
(1, 1),
(1, 2);

-- Inserindo Alunos
INSERT INTO Aluno (id_categoria, id_turma, nome, rua, bairro, cidade, estado) VALUES
(2, 1, 'Carlos Silva', 'Rua das Flores', 'Centro', 'Juazeiro do Norte', 'CE'),
(1, 1, 'Ana Souza', 'Av. Brasil', 'Lagoa', 'Crato', 'CE');

-- Inserindo o Núcleo Familiar dos Alunos
INSERT INTO Nucleo_Familiar (id_aluno, ano, parentesco) VALUES
(1, '2024', 'Mãe'),
(2, '2024', 'Avó');

-- Inserindo Frequência Diária
INSERT INTO Frequencia (id_aluno, id_turma, id_calendario, assiduidade) VALUES
(1, 1, 1, 19.0),
(2, 1, 1, 12.0);

-- Inserindo Médias Acadêmicas
INSERT INTO Media (id_aluno, nota, ano, semestre) VALUES
(1, 8.5, '2024', '1'),
(2, 4.5, '2024', '1');

```

## Scritp de Consultas

```sql
-- Listar os Alunos, as suas Turmas e a Categoria de Renda
SELECT 
    A.nome AS Aluno, 
    T.serie AS Ano_Escolar, 
    T.turno AS Turno, 
    C.nome AS Categoria_Socioeconomica
FROM Aluno A
JOIN Turma T ON A.id_turma = T.id
JOIN Categoria_Renda C ON A.id_categoria = C.id;

-- Consultar as Notas de uma Turma Específica
SELECT 
    A.nome AS Aluno, 
    M.nota AS Media_Final, 
    M.semestre AS Semestre
FROM Media M
JOIN Aluno A ON M.id_aluno = A.id
JOIN Turma T ON A.id_turma = T.id
WHERE T.serie = 3;

-- Consultar o Núcleo Familiar do Aluno

SELECT 
    A.nome AS Aluno, 
    N.parentesco AS Responsavel, 
    N.ano AS Ano_Registo
FROM Nucleo_Familiar N
JOIN Aluno A ON N.id_aluno = A.id;

```
para rodar: baixar docker e docker compose

na raiz do projeto:
- sudo docker compose up -d
- sudo docker exec app-mvc bash
- composer dump-autoload
- composer update

-abrir app: localhost:8000 
-abrir phpmyadmin: localhost:8080 