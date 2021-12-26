<?php
require_once "../config.php";
require_once "../checarSessao.php";
if ($_POST['acao'] == "arrancharPorXdias") {
    $cafe = filter_input(INPUT_POST, 'fcf', FILTER_SANITIZE_STRING);
    $almoco = filter_input(INPUT_POST, 'fal', FILTER_SANITIZE_STRING);
    $janta = filter_input(INPUT_POST, 'fjt', FILTER_SANITIZE_STRING);
    $militar_id = filter_input(INPUT_POST, 'militaridd', FILTER_SANITIZE_NUMBER_INT);
    $militar_nome = filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_STRING);
    $laranjeira = filter_input(INPUT_POST, 'laranjeira', FILTER_SANITIZE_STRING);
    $quantidade_dias = filter_input(INPUT_POST, 'qtdDias', FILTER_SANITIZE_NUMBER_INT);
    $contagem = 0;

    if ($cafe == "") {
        $cafe = 0;
    }
    if ($almoco == "") {
        $almoco = 0;
    }
    if ($janta == "") {
        $janta = 0;
    }

    $dateStart = date("d/m/Y");
    $dateStart = implode('-', array_reverse(explode('/', substr($dateStart, 0, 10)))) . substr($dateStart, 10);
    $dateStart = new DateTime($dateStart);
    $adiciona_x_dias = date('Y/m/d', strtotime("+" . $quantidade_dias . " days", strtotime(date("Y/m/d"))));
    $adiciona_x_dias = implode('-', array_reverse(explode('/', substr($adiciona_x_dias, 0, 10)))) . substr($adiciona_x_dias, 10);
    $dateEnd = $adiciona_x_dias;
    $dateEnd = implode('-', array_reverse(explode('/', substr($dateEnd, 0, 10)))) . substr($dateEnd, 10);
    $dateEnd = new DateTime($dateEnd);
    $dateRange = array();

    while ($dateStart <= $dateEnd) {
        $contagem++;
        $dateRange[] = $dateStart->format('Y-m-d');
        $dateStart = $dateStart->modify('+1day');
        $checaFinalSemana = date('w', strtotime($dateStart->format('Y-m-d')));

        if ($laranjeira == 0) {
            if ($checaFinalSemana != 6 and $checaFinalSemana != 0) {
                $data_arranchar_Y_m_d = $dateStart->format('Y-m-d');
            }

            $consulta = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and militar = :militar");
            $consulta->bindParam(':data', $data_arranchar_Y_m_d);
            $consulta->bindParam(':militar', $militar_id);
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                try {
                    $statement = $pdo->prepare('UPDATE diasarranchado SET cafe = :cafe, almoco = :almoco, janta = :janta WHERE data = :data and militar = :militar');
                    $statement->bindParam(':cafe', $cafe);
                    $statement->bindParam(':almoco', $almoco);
                    $statement->bindParam(':janta', $janta);
                    $statement->bindParam(':militar', $militar_id);
                    $statement->bindParam(':data', $data_arranchar_Y_m_d);

                    $statement->execute();
                    echo "ok";
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
                    $insert->execute();
                    echo "ok";
                    // header("location: ../?pagina=listarMilitares");
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            }
        } else {
            $data_arranchar_Y_m_d = $dateStart->format('Y-m-d');
            $consulta = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and militar = :militar");
            $consulta->bindParam(':data', $data_arranchar_Y_m_d);
            $consulta->bindParam(':militar', $militar_id);
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                try {
                    $statement = $pdo->prepare('UPDATE diasarranchado SET cafe = :cafe, almoco = :almoco, janta = :janta WHERE data = :data and militar = :militar');
                    $statement->bindParam(':cafe', $cafe);
                    $statement->bindParam(':almoco', $almoco);
                    $statement->bindParam(':janta', $janta);
                    $statement->bindParam(':militar', $militar_id);
                    $statement->bindParam(':data', $data_arranchar_Y_m_d);
                    $statement->execute();
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
                    $insert->execute();

                    // header("location: ../?pagina=listarMilitares");
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            }
        }
    }
} else {
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

    if ($nivel == '3') {
        if ($_POST['acao'] == "cadastrar") {
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
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                try {
                    $statement = $pdo->prepare('UPDATE diasarranchado SET cafe = :cafe, almoco = :almoco, janta = :janta WHERE data = :data and militar = :militar');
                    $statement->bindParam(':cafe', $cafe);
                    $statement->bindParam(':almoco', $almoco);
                    $statement->bindParam(':janta', $janta);
                    $statement->bindParam(':militar', $militar_id);
                    $statement->bindParam(':data', $data_arranchar_Y_m_d);
                    $statement->execute();
                    echo "ok";
                    header("location: ../?pagina=visualizaCalendario&idmilitar=$militar_id&militar=$militar_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
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
                    $insert->execute();
                    echo "ok";
                    header("location: ../?pagina=visualizaCalendario&idmilitar=$militar_id&militar=$militar_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            }
        }
    } else {
        if ($_POST['acao'] == "cadastrar") {
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
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                try {
                    $statement = $pdo->prepare('UPDATE diasarranchado SET cafe = :cafe, almoco = :almoco, janta = :janta WHERE data = :data and militar = :militar');
                    $statement->bindParam(':cafe', $cafe);
                    $statement->bindParam(':almoco', $almoco);
                    $statement->bindParam(':janta', $janta);
                    $statement->bindParam(':militar', $militar_id);
                    $statement->bindParam(':data', $data_arranchar_Y_m_d);

                    $statement->execute();
                    echo "ok";
                    header("location: ../?pagina=visualizaCalendario&idmilitar=$militar_id&militar=$militar_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
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
                    $insert->execute();
                    echo "ok";
                    header("location: ../?pagina=visualizaCalendario&idmilitar=$militar_id&militar=$militar_nome&laranjeira=$laranjeira&semana=$semana&ano=$ano");
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            }
        }
    }
}
