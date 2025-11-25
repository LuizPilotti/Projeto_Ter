<?php
require_once '../config.php';
require_once INCLUDE_PATH.'/classes/mysql.php';
if($_GET['action'] === 'criar'){
    $idUsuario = $_POST['id_usuario'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?? null;
    $prazo = $_POST['prazo'] ?? null;
    $prioridade = $_POST['prioridade'] ?? 'media';
    $meta = $_POST['meta'] ?? null;

    $sql = MySql::conectar()->prepare("
        INSERT INTO tarefas (id_usuario, titulo, descricao, prazo, prioridade, meta)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $sql->execute([$idUsuario, $titulo, $descricao, $prazo, $prioridade, $meta]);

    echo json_encode(['sucesso' => true]);
    exit;
}

if($_GET['action'] === 'listar'){
    $idUsuario = $_GET['id_usuario'];

    $sql = MySql::conectar()->prepare("SELECT * FROM tarefas WHERE id_usuario = ? ORDER BY prioridade DESC, prazo ASC");
    $sql->execute([$idUsuario]);
    $tarefas = $sql->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'tarefas' => $tarefas]);
    exit;
}

if($_GET['action'] === 'status'){
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = MySql::conectar()->prepare("UPDATE tarefas SET status = ? WHERE id = ?");
    $sql->execute([$status, $id]);

    echo json_encode(['sucesso' => true]);
    exit;
}

if($_GET['action'] === 'excluir'){
    $id = $_POST['id'];

    $sql = MySql::conectar()->prepare("DELETE FROM tarefas WHERE id = ?");
    $sql->execute([$id]);

    echo json_encode(['sucesso' => true]);
    exit;
}
