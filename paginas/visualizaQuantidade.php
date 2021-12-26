<?php
if ($_SESSION['nivel'] == 3) {
?>
    <div class="container-fluid">
        <div class="table table-responsive">
            <tbody>
                <center>
                    <?php
                    $year = ($ano) ? $ano : date("Y");
                    $week = ($semana) ? $semana : date('W', strtotime(date('Ymd') . ' + 0 days'));

                    if ($week > 52) {
                        $year++;
                        $week = 1;
                    } elseif ($week < 1) {
                        $year--;
                        $week = 52;
                    }
                    ?>
                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?semana=' . ($week == 1 ? 52 : $week - 1) . '&ano=' . ($week == 1 ? $year - 1 : $year); ?>" class="btn btn-default">
                        <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>Semana anterior
                    </a>

                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?semana=' . ($week == 52 ? 1 : 1 + $week) . '&ano=' . ($week == 52 ? 1 + $year : $year); ?>" class="btn btn-default">
                        Próxima semana<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                    </a>
                    <br>
                </center>
                <table class="table">
                    <tr>
                        <?php
                        $week = ltrim($week, "0");
                        if ($week < 10) {
                            $week = '0' . $week;
                        }
                        for ($day = 1; $day <= 7; $day++) {
                            $data_ano_W_semana_dia_da_semana = strtotime($year . "W" . $week . $day);
                            $dia_da_semana_string = (OS_HOST == 'LINUX') ? strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))) : utf8_encode(strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))));
                            echo "<td><center>" . $dia_da_semana_string . "<br>" . date('d/m/Y', $data_ano_W_semana_dia_da_semana) . "";
                            $ii            = date('d', $data_ano_W_semana_dia_da_semana);
                            $diaarranchar2 = date('Y-m-d', $data_ano_W_semana_dia_da_semana);
                            $data11        = date("Ymd");
                            $data12        = date("Y-m-d");

                            $data_actula   = date('Ymd', strtotime($data11 . ' + 8 days'));
                            $data_actulaa  = date('Y-m-d', strtotime($data11 . ' + 8 days'));
                            $data1eHora    = date("Y-m-d H:i");
                            $checaMeioDia  = date('d-m-Y 15:00', strtotime($data11));
                            $data_hora_atual_d_m_Y = date('d-m-Y H:i', strtotime($data1eHora));

                            if ($nivel == 3) {
                                //    if ( $nivel == 3){
                                $diaXXXA = date("Y-m-d", strtotime($diaarranchar2));
                                $diaaA   = $diaXXXA;

                                $consultaCafe = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and cafe =1");
                                $consultaCafe->bindParam(':doDia', $diaaA);
                                $consultaCafe->execute();

                                $consultaCafeF = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justcafe ='faltou')");
                                $consultaCafeF->bindParam(':doDia', $diaaA);
                                $consultaCafeF->execute();

                                $consultaCafeJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justcafe ='justificou')");
                                $consultaCafeJ->bindParam(':doDia', $diaaA);
                                $consultaCafeJ->execute();

                                $consultaAlmoco = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and almoco =1");
                                $consultaAlmoco->bindParam(':doDia', $diaaA);
                                $consultaAlmoco->execute();
                                $consultaAlmocoF = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justalmoco ='faltou')");
                                $consultaAlmocoF->bindParam(':doDia', $diaaA);
                                $consultaAlmocoF->execute();
                                $consultaAlmocoJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justalmoco ='justificou')");
                                $consultaAlmocoJ->bindParam(':doDia', $diaaA);
                                $consultaAlmocoJ->execute();

                                $consultaJanta = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and janta =1");
                                $consultaJanta->bindParam(':doDia', $diaaA);
                                $consultaJanta->execute();
                                $consultaJantaF = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justjanta ='faltou')");
                                $consultaJantaF->bindParam(':doDia', $diaaA);
                                $consultaJantaF->execute();
                                $consultaJantaJ = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and (justjanta ='justificou')");
                                $consultaJantaJ->bindParam(':doDia', $diaaA);
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
                        }
                    }
                    ?>
                    </tr>
                </table>
                Para tirar as <b>faltas</b>, clique na <b>QTD</b> da refeição desejada!<br>

                Para ver quantos <b>CB/SD & OF/ST/SGTS</b> estão arranchados para a DATA especifica clique em RELATÓRIO e
                selecione uma opção. <br>

                Para gerar relatórios de quantitativo por semana, mês e ano, clique no menu GERAR RELATÓRIOS!
        </DIV>

        <h6 align="center"><a target="a_blank" rel="nofollow noreferrer noopener external" href="https://clebersiqueira.com.br">Desenvolvido por Cleber Siqueira.</a></h6>