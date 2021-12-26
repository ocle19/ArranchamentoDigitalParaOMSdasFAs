<div class="container-fluid">
    <div class="col-xs-4">
        <div align="right">
            <a href="<?php echo "?pagina=visualizaQuantidadeTotal&mes=$mes_ant&ano=$ano_ant"; ?>" class="btn btn-default">
                <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
            </a>
        </div>
    </div>

    <div class="col-xs-4">
        <center>
            <h4>
                <?php echo $nomemes . " de " . $ano; ?>
            </h4>
        </center>
    </div>

    <div class="col-xs-4">
        <div align="left">
            <a href="<?php echo "?pagina=visualizaQuantidadeTotal&mes=$prox_mes&ano=$prox_ano"; ?>" class="btn btn-default">
                <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
            </a>
        </div>
    </div>

</div>

<div class="container-fluid">
    <div class="table table-responsive">
        <table class="table table-striped table-bordered">

            <thead>
                <tr style="text-align:center;">
                    <td width="15%"><b>Segunda Feira</b></td>
                    <td width="15%"><b>Terça Feira</b></td>
                    <td width="15%"><b>Quarta Feira</b></td>
                    <td width="15%"><b>Quinta Feira</b></td>
                    <td width="15%"><b>Sexta Feira</b></td>
                    <td width="10%"><b>Sábado</b></td>
                    <td width="10%"><b>Domingo</b></td>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<tr>";
                for ($i = 1; $i <= $dias; $i++) {

                    //Exibir dia com dois digitos
                    $ci = strlen($i);
                    if ($ci == 1) {
                        $ii = "0$i";
                    } else {
                        $ii = $i;
                    }

                    $diadasemana = date("w", mktime(0, 0, 0, $mes, $ii - 1, $ano));

                    $diames = $ii . "-" . $mes . "-" . $ano;

                    $data1  = date("d/m/Y");
                    $data11 = date("Ymd");
                    $data2  = date("d/m/Y", mktime(0, 0, 0, $mes, $ii, $ano));
                    $data22 = date("Ymd", mktime(0, 0, 0, $mes, $ii, $ano));

                    $d         = date("d");
                    $m         = date("m");
                    $a         = date("Y");
                    $diamesano = $ano . $mes . $ii;
                    $data3     = date("Ymd", mktime(0, 0, 0, $m, $d, $a));
                    $data4     = date("Ymd", mktime(0, 0, 0, $m, $d + 11, $a));

                    $cont = 0;

                    if ($i == 1) {
                        while ($cont < $diadasemana) {
                            echo "<td></td>";
                            $cont++;
                        }
                    }

                    if ($diames == $data) {
                        echo "<td  align=center valign=top bgcolor=#CCCCCC>";
                    } else {
                        echo "<td align=center valign=top>";
                    }
                    if ($diadasemana == 5 || $diadasemana == 6) {
                        $diaXXX = date("d-m-Y", strtotime($ano . "-" . $mes . "-" . $ii));
                        echo "<font color=#FF0000>" . $diaXXX . "</font>";
                    } elseif ($diadasemana == 4) {
                        $diaXXX = date("d-m-Y", strtotime($ano . "-" . $mes . "-" . $ii));
                        echo "<font color=#cdcd00>" . $diaXXX . "</font>";
                    } else {
                        $diaXXX = date("d-m-Y", strtotime($ano . "-" . $mes . "-" . $ii));
                        echo $diaXXX;
                    }
                    //Inicio da exibição dos eventos
                    $data_actula = date('Ymd', strtotime($data11 . ' + 6 days'));
                    //$datadata1122 = "2018-06-23";
                    if ($nivel == 3 and $data_actula >= $data22) {
                        //    if ( $nivel == 3){
                        $diaXXXA = date("Y-m-d", strtotime($diaXXX));
                        $diaaA   = $diaXXXA;

                        $consultaCafe = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and cafe =1");
                        $consultaCafe->bindParam(':data', $diaaA);
                        $consultaCafe->execute();
                        $consultaCafeF = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justcafe ='faltou')");
                        $consultaCafeF->bindParam(':data', $diaaA);
                        $consultaCafeF->execute();
                        $consultaCafeJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justcafe ='justificou')");
                        $consultaCafeJ->bindParam(':data', $diaaA);
                        $consultaCafeJ->execute();

                        $consultaAlmoco = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and almoco =1");
                        $consultaAlmoco->bindParam(':data', $diaaA);
                        $consultaAlmoco->execute();
                        $consultaAlmocoF = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justalmoco ='faltou')");
                        $consultaAlmocoF->bindParam(':data', $diaaA);
                        $consultaAlmocoF->execute();
                        $consultaAlmocoJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justalmoco ='justificou')");
                        $consultaAlmocoJ->bindParam(':data', $diaaA);
                        $consultaAlmocoJ->execute();

                        $consultaJanta = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and janta =1");
                        $consultaJanta->bindParam(':data', $diaaA);
                        $consultaJanta->execute();
                        $consultaJantaF = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justjanta ='faltou')");
                        $consultaJantaF->bindParam(':data', $diaaA);
                        $consultaJantaF->execute();
                        $consultaJantaJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and (justjanta ='justificou')");
                        $consultaJantaJ->bindParam(':data', $diaaA);
                        $consultaJantaJ->execute();

                        $quantidadecafe = $consultaCafe->rowCount();
                        $faltascafe     = $consultaCafeF->rowCount();
                        $justcafe       = $consultaCafeJ->rowCount();

                        $quantidadealmoco = $consultaAlmoco->rowCount();
                        $faltasalmoco     = $consultaAlmocoF->rowCount();
                        $justalmoco       = $consultaAlmocoJ->rowCount();

                        $quantidadejanta = $consultaJanta->rowCount();
                        $faltasjanta     = $consultaJantaF->rowCount();
                        $justjanta       = $consultaJantaJ->rowCount();
                ?>
                        <table class="table table-striped" style="font-size:15px;">
                            <thead>
                                <tr class="info">
                                    <th>REFEIÇÃO</th>
                                    <th scope="col">QTD</th>
                                    <th>FALTAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <a title=" CLIQUE PARA VER A LISTA DE TODOS QUE SE ARRANCHARAM PARA O CAFÉ SEM TIRAGEM DE FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDia&data=$diaaA&ref=cafe"; ?>">
                                            <span class="fa fa-coffee text-green" aria-hidden="true"> Café</span>
                                        </a>
                                    </th>

                                    <td>
                                        <a title=" CLIQUE PARA VER A LISTA E TIRAR FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$diaaA&ref=cafe"; ?>">
                                            <?php echo $quantidadecafe; ?>
                                        </a>

                                    </td>
                                    <th scope="row">
                                        F: <a title="FALTOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=cafe&situacao=faltou"; ?>">
                                            <?php echo $faltascafe; ?>
                                        </a>
                                        <br>
                                        J:<a title="JUSTIFICOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=cafe&situacao=justificou"; ?>">
                                            <?php echo $justcafe; ?>
                                        </a>
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <a title=" CLIQUE PARA VER A LISTA DE TODOS QUE SE ARRANCHARAM PARA O ALMOÇO SEM TIRAGEM DE FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDia&data=$diaaA&ref=almoco"; ?>">
                                            <span class="fa fa-cutlery text-orange" aria-hidden="true"> Almoço</span>
                                        </a>

                                    </th>
                                    <td>
                                        <a title=" CLIQUE PARA VER A LISTA E TIRAR FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$diaaA&ref=almoco"; ?>">
                                            <?php echo $quantidadealmoco; ?>
                                        </a>

                                    </td>
                                    <th scope="row">
                                        F: <a title="FALTOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=almoco&situacao=faltou"; ?>">
                                            <?php echo $faltasalmoco; ?>
                                        </a>
                                        <br>
                                        J: <a title="JUSTIFICOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=almoco&situacao=justificou"; ?>">
                                            <?php echo $justalmoco; ?>
                                        </a>


                                    </th>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <a title=" CLIQUE PARA VER A LISTA DE TODOS QUE SE ARRANCHARAM PARA A JANTA SEM TIRAGEM DE FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDia&data=$diaaA&ref=janta"; ?>">
                                            <span class="fa fa-cutlery text-navy" aria-hidden="true"> Janta</span>
                                        </a>
                                    </th>
                                    <td>


                                        <a title=" CLIQUE PARA VER A LISTA E TIRAR FALTA" href="<?php echo "?pagina=listarArranchadosDaRefeicaoDoDiaParaTirarFalta&data=$diaaA&ref=janta"; ?>">
                                            <?php echo $quantidadejanta; ?>
                                        </a>

                                    </td>

                                    <th scope="row">
                                        F: <a title="FALTOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=janta&situacao=faltou"; ?>">
                                            <?php echo $faltasjanta; ?>
                                        </a>
                                        <br>
                                        J: <a title="JUSTIFICOU" href="<?php echo "?pagina=listarTiragemFalta&data=$diaaA&ref=janta&situacao=justificou"; ?>">
                                            <?php echo $justjanta; ?>
                                        </a>


                                    </th>

                                </tr>


                                <div class="dropdown">
                                    <a class="btn btn-secondary btn-sm dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Relatório
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="#" onclick="relatorioArranchados('relatorio_cafe_almoco_janta_do_dia_cb_sd', '<?php echo $diaaA; ?>');return false">CB/SD</a><br>
                                        <a class="dropdown-item" href="#" onclick="relatorioArranchados('relatorio_cafe_almoco_janta_do_dia_of_st_sgt', '<?php echo $diaaA; ?>');return false">OF/ST/SGT</a><br>
                                        <a class="dropdown-item" href="#" onclick="relatorioArranchados('relatorio_cafe_almoco_janta_do_dia', '<?php echo $diaaA; ?>');return false">TODOS</a>
                                    </div>
                                </div>






                            </tbody>

                        </table>


                <?php
                    }

                    echo "</td>";
                    if ($diadasemana == 6) {
                        echo "</tr>";
                        echo "<tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<h6 align="center"><a target="a_blank" rel="nofollow noreferrer noopener external" href="https://clebersiqueira.com.br">Desenvolvido por Cleber Siqueira.</a></h6>