<?php
require_once "../config.php";
require_once "../checarSessao.php";
if ($_POST['acao'] == "ok") {
    $militar = filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_NUMBER_INT);
    $esta_justificado = "1";

    try {
        $statement = $pdo->prepare('UPDATE militares SET laranjeira = :just WHERE id = :militar');
        $statement->bindParam(':just', $esta_justificado);
        $statement->bindParam(':militar', $militar);
        $statement->execute();

        header("location: ../?pagina=listarMilitares");
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}
if ($_POST['acao'] == "no") {
    $militar = filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_NUMBER_INT);
    $no = "0";

    try {
        $statement = $pdo->prepare('UPDATE militares SET laranjeira = :just WHERE id = :militar');
        $statement->bindParam(':just', $no);
        $statement->bindParam(':militar', $militar);
        $statement->execute();

        header("location: ../?pagina=listarMilitares");
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}
