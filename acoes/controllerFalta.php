<?php
require_once "../config.php";
require_once "../checarSessao.php";
$data_arranchar = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$data_arranchar_Y_m_d = date("Y-m-d", strtotime($data_arranchar));
$ref = filter_input(INPUT_POST, 'ref', FILTER_SANITIZE_STRING);
$militar = filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_NUMBER_INT);
$turno_justificado = "just" . $ref;
$esta_justificado = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

if ($esta_justificado == "ok") {
    try {
        $statement = $pdo->prepare('UPDATE diasarranchado SET ' . $turno_justificado . ' = :just WHERE data = :data and militar = :militar and ' . $ref . ' ="1"');
        $statement->bindParam(':just', $esta_justificado);
        $statement->bindParam(':militar', $militar);
        $statement->bindParam(':data', $data_arranchar_Y_m_d);
        $statement->execute();

        header("location: ../?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$data_arranchar&ref=$ref&msg=Falta tirada como: COMPARECEU");
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}

if ($esta_justificado == "justificou") {
    try {
        $statement = $pdo->prepare('UPDATE diasarranchado SET ' . $turno_justificado . ' = :just WHERE data = :data and militar = :militar and ' . $ref . ' ="1"');
        $statement->bindParam(':just', $esta_justificado);
        $statement->bindParam(':militar', $militar);
        $statement->bindParam(':data', $data_arranchar_Y_m_d);
        $statement->execute();

        header("location: ../?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$data_arranchar&ref=$ref&msg=Falta tirada como: JUSTIFICOU");
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}

if ($esta_justificado == "faltou") {
    try {
        $statement = $pdo->prepare('UPDATE diasarranchado SET ' . $turno_justificado . ' = :just WHERE data = :data and militar = :militar and ' . $ref . ' ="1"');
        $statement->bindParam(':just', $esta_justificado);
        $statement->bindParam(':militar', $militar);
        $statement->bindParam(':data', $data_arranchar_Y_m_d);
        $statement->execute();

        header("location: ../?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$data_arranchar&ref=$ref&msg=Falta tirada como: FALTOU");
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}
