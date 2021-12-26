<?php
require_once "../config.php";
require_once "../checarSessao.php";
if ($_POST['acao'] == "arrancharPorXdias") {
    $id_militar = filter_input(INPUT_POST, 'militaridd', FILTER_SANITIZE_STRING);
    $militar_nome = filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_STRING);
    $quantidade_dias = filter_input(INPUT_POST, 'qtdDias', FILTER_SANITIZE_STRING);

    $data_atual = date("Y/m/d");
    $adiciona_x_dias = date('Y/m/d', strtotime("+" . $quantidade_dias . " days", strtotime(date("Y/m/d"))));
    $data_atual_Y_m_d = date("Y-m-d", strtotime($data_atual));
    $data_final_Y_m_d = date("Y-m-d", strtotime($adiciona_x_dias));

    $data_atual_d_m_Y = date("d-m-Y", strtotime($data_atual));
    $data_final_d_m_Y = date("d-m-Y", strtotime($adiciona_x_dias));

    $consultar_arranchamento_do_militar = $pdo->prepare(
        "SELECT diasarranchado.data, diasarranchado.cafe, diasarranchado.almoco,diasarranchado.janta,
            militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares 
            INNER JOIN diasarranchado ON diasarranchado.militar=militares.id and (diasarranchado.data >=:inicio and 
            diasarranchado.data <= :fim) and (militares.id= :militar) 
            order by diasarranchado.data ASC"
    );

    $consultar_arranchamento_do_militar->bindParam(':inicio', $data_atual_Y_m_d);
    $consultar_arranchamento_do_militar->bindParam(':fim', $data_final_Y_m_d);
    $consultar_arranchamento_do_militar->bindParam(':militar', $id_militar);
    $consultar_arranchamento_do_militar->execute();
    $rowCount             = $consultar_arranchamento_do_militar->rowCount();

    if ($rowCount <= 0) {
        echo "0";
    } else {
        echo mb_strtoupper($militar_nome);
?>

        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr class="success">
                    <th>
                        <center>Data</center>
                    </th>
                    <th>
                        <center>Cafe</center>
                    </th>
                    <th>
                        <center>Almoco</center>
                    </th>
                    <th>
                        <center>Janta</center>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($linha = $consultar_arranchamento_do_militar->fetch(PDO::FETCH_ASSOC)) {
                    $nome = $linha['nomeGuerra'];
                    $numero = $linha['numero'];
                    $grad = $linha['grad'];
                    $bateria = $linha['subUnidade'];
                    $dataarranchado = $linha['data'];

                    $consultaC = $pdo->prepare(
                        "SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, 
                            militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                            INNER JOIN diasarranchado ON 
                            diasarranchado.militar=militares.id and
                            (diasarranchado.data >= :inicio and diasarranchado.data <= :fim) and 
                            (militares.id= :militar) and diasarranchado.cafe='1' 
                            order by ABS(numero);"
                    );
                    $consultaC->bindParam(':inicio', $data_atual_Y_m_d);
                    $consultaC->bindParam(':fim', $data_final_Y_m_d);
                    $consultaC->bindParam(':militar', $id_militar);
                    $consultaC->execute();
                    $totalCafe = $consultaC->rowCount();

                    $consultaA = $pdo->prepare(
                        "SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, 
                            militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                            INNER JOIN diasarranchado ON 
                            diasarranchado.militar=militares.id and
                            (diasarranchado.data >= :inicio and diasarranchado.data <= :fim) and 
                            (militares.id= :militar) and diasarranchado.almoco='1' 
                            order by ABS(numero);"
                    );
                    $consultaA->bindParam(':inicio', $data_atual_Y_m_d);
                    $consultaA->bindParam(':fim', $data_final_Y_m_d);
                    $consultaA->bindParam(':militar', $id_militar);
                    $consultaA->execute();
                    $totalAlmoco = $consultaA->rowCount();

                    $consultaJ = $pdo->prepare(
                        "SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, 
                            militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                            INNER JOIN diasarranchado ON 
                            diasarranchado.militar=militares.id and
                            (diasarranchado.data >= :inicio and diasarranchado.data <= :fim) and 
                            (militares.id= :militar) and diasarranchado.janta='1' 
                            order by ABS(numero);"
                    );
                    $consultaJ->bindParam(':inicio', $data_atual_Y_m_d);
                    $consultaJ->bindParam(':fim', $data_final_Y_m_d);
                    $consultaJ->bindParam(':militar', $id_militar);
                    $consultaJ->execute();
                    $totalJanta = $consultaJ->rowCount();

                    if ($linha['cafe'] == "1") {
                        $cafe = "<center>X";
                    } else {
                        $cafe = "<center>-";
                    }
                    if ($linha['almoco'] == "1") {
                        $almoco = "<center>X";
                    } else {
                        $almoco = "<center>-";
                    }
                    if ($linha['janta'] == "1") {
                        $janta = "<center>X</center>";
                    } else {
                        $janta = "<center>-";
                    }
                ?>

                    <tr>
                        <td><?php echo date("d-m-Y", strtotime($dataarranchado)); ?></td>
                        <td>
                            <center><?php echo $cafe; ?></center>
                        </td>
                        <td>
                            <center><?php echo $almoco; ?></center>
                        </td>
                        <td>
                            <center><?php echo $janta; ?></center>
                        </td>

                    </tr>

                <?php
                }
                ?>
                <tr>
                    <td><strong>TOTAL:</strong></td>
                    <td><strong>
                            <center><?php echo $totalCafe; ?></center>
                        </strong></td>
                    <td><strong>
                            <center><?php echo $totalAlmoco; ?></center>
                        </strong></td>
                    <td><strong>
                            <center><?php echo $totalJanta; ?></center>
                        </strong>
                    </td>

                </tr>
        </table>

<?php
    }
}
