<?php
require "../config.php";
require_once "../checarSessao.php";
if ($_POST['acao'] == "cadastrar") {
    $data_arranchar = filter_input(INPUT_POST, 'fdt_r', FILTER_SANITIZE_STRING);
    $data_arranchar_Y_m_d = date("Y-m-d", strtotime($data_arranchar));
    $cafe = filter_input(INPUT_POST, 'fcf', FILTER_SANITIZE_STRING);
    $almoco = filter_input(INPUT_POST, 'fal', FILTER_SANITIZE_STRING);
    $janta = filter_input(INPUT_POST, 'fjt', FILTER_SANITIZE_STRING);
    $militar_id = filter_input(INPUT_POST, 'militaridd', FILTER_SANITIZE_NUMBER_INT);
    $militar_nome = filter_input(INPUT_POST, 'nomemilitar', FILTER_SANITIZE_STRING);
    $laranjeira = filter_input(INPUT_POST, 'laranjeira', FILTER_SANITIZE_STRING);
    $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_STRING);
    $semana = filter_input(INPUT_POST, 'semana', FILTER_SANITIZE_STRING);

    if ($cafe == "") {
        $cafe = 0;
    }
    if ($almoco == "") {
        $almoco = 0;
    }
    if ($janta == "") {
        $janta = 0;
    }

    $consulta = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and militar = :militar");
    $consulta->bindParam(':data', $data_arranchar_Y_m_d);
    $consulta->bindParam(':militar', $militar_id);
    $result = $consulta->execute();
    ///echo $consulta->rowCount();

    if ($consulta->rowCount() >= 1) {
        try {
            $statement = $pdo->prepare('UPDATE diasarranchado SET cafe = :cafe, almoco = :almoco, janta = :janta WHERE data = :data and militar = :militar');
            $statement->bindParam(':cafe', $cafe);
            $statement->bindParam(':almoco', $almoco);
            $statement->bindParam(':janta', $janta);
            $statement->bindParam(':militar', $militar_id);
            $statement->bindParam(':data', $data_arranchar_Y_m_d);
            $result = $statement->execute();
            ///echo $statement->rowCount();
            header("location: ../?pagina=visualizaArranchamentoIndividual&idmilitar=$militar_id&militar=$militar_id_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    } else {

        try {
            $insert = $pdo->prepare('insert into diasarranchado (data, cafe, almoco, janta, militar) values (:data, :cafe, :almoco, :janta, :militar)');
            $insert->bindParam(':data', $data_arranchar_Y_m_d);
            $insert->bindParam(':cafe', $cafe);
            $insert->bindParam(':almoco', $almoco);
            $insert->bindParam(':janta', $janta);
            $insert->bindParam(':militar', $militar_id);
            $resultIns = $insert->execute();
            ///echo $insert->rowCount();
            header("location: ../?pagina=visualizaArranchamentoIndividual&idmilitar=$militar_id&militar=$militar_id_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
