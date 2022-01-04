<?php
if (isset($_GET['idmilitar']) and $nivel > 0) {
    $id_militar   = (int) filter_input(INPUT_GET, 'idmilitar', FILTER_SANITIZE_NUMBER_INT);
    $nome_militar = filter_input(INPUT_GET, 'militar', FILTER_SANITIZE_STRING);
    $laranjeira = (int) filter_input(INPUT_GET, 'laranjeira', FILTER_SANITIZE_NUMBER_INT);
?>
    <div class="container">
        <div class="table">
            <tbody>  
                <CENTER>Selecione um dia da semana
                <input type="date" id="week" onchange="updateWeek(this, window.location.href)" value="<?php echo date('Y-m-d'); ?>">
                <br>
                <?php
                if ($nome_militar) {
                    echo "MILITAR -> " . $nome_militar;
                } else {
                    echo "MILITAR NÃO ENCONTRADO!";
                }
                ?>
                <table class="table">
                    <tr>
                        <?php
                        $semana = ltrim($semana, "0");
                        if ($semana < 10) {
                            $semana = '0' . $semana;
                        }
                        for ($dia_da_semana = 1; $dia_da_semana <= 7; $dia_da_semana++) {
                            $data_ano_W_semana_dia_da_semana = strtotime($ano . "W" . $semana . $dia_da_semana);
                            $dia_da_semana_string = (OS_HOST == 'LINUX') ? strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))) : utf8_encode(strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))));
                            echo "<td><center>" . $dia_da_semana_string . "<br>" . date('d/m/Y', $data_ano_W_semana_dia_da_semana) . "";
                            $dia_arranchar            = date('d', $data_ano_W_semana_dia_da_semana);
                            $data_arranchar = date('Y-m-d', $data_ano_W_semana_dia_da_semana);
                            $data_atual_Ymd        = date("Ymd");
                            $data_atual_Y_m_d        = date("Y-m-d");

                            $data_atual_add_15_dias_Ymd   = date('Ymd', strtotime($data_atual_Ymd . ' +  ' . DIAS_PARA_ARRANCHAR . ' days'));
                            $data_atual_add_15_dias_Y_m_d  = date('Y-m-d', strtotime($data_atual_Ymd . ' +  ' . DIAS_PARA_ARRANCHAR . ' days'));
                            $data_hora_atual_Y_m_d    = date("Y-m-d h:i");
                            $horario_limite_d_m_Y  = date('d-m-Y 15:00', strtotime($data_atual_Ymd));
                            $data_hora_atual_d_m_Y = date('d-m-Y H:i', strtotime($data_hora_atual_Y_m_d));

                            if ($horario_limite_d_m_Y >= $data_hora_atual_d_m_Y) {
                                $data_atual_add_margem_em_dias_Y_m_d = date('Y-m-d', strtotime($data_atual_Ymd . ' + ' . (DIAS_ANTECEDENCIA-1) . ' days'));
                            } else {
                                $data_atual_add_margem_em_dias_Y_m_d = date('Y-m-d', strtotime($data_atual_Ymd . ' + ' . DIAS_ANTECEDENCIA . ' days'));
                            }

                            $horario_limite_d_m_Y  = date('d-m-Y 12:00', strtotime($data_atual_Ymd));
                            $data_hora_atual_d_m_Y = date('d-m-Y H:i', strtotime($data_atual_Y_m_d));


                            $consultaa   = $pdo->prepare("SELECT * FROM diasarranchado WHERE data = :data and militar= :militar");
                            $consultaa->bindParam(':data', $data_arranchar);
                            $consultaa->bindParam(':militar', $id_militar);
                            $consultaa->execute();
                            $ResultadoBusca = $consultaa->fetch(PDO::FETCH_ASSOC);
                            $cafe           = $ResultadoBusca['cafe'] ?? 0;
                            $almoco         = $ResultadoBusca['almoco'] ?? 0;
                            $janta          = $ResultadoBusca['janta'] ?? 0;
                            $checkAprov = $data_arranchar >= $data_atual_Y_m_d and $data_arranchar <= $data_atual_add_15_dias_Y_m_d and $data_arranchar >= $data_atual_add_margem_em_dias_Y_m_d;
                            $checkFurriel = $data_arranchar != null;
                            $checagemDataHora = $nivel >= 3 ? $checkAprov : $checkFurriel;
                            if ($checagemDataHora) { ?>
                                <form method='post' name="FormArranchar<?php echo $dia_arranchar; ?>" id="FormArranchar<?php echo $dia_arranchar; ?>" action='acoes/controllerArranchar.php'>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="info">
                                                <th scope="col">CHECK</th>
                                                <th scope="col">REFEIÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">

                                                    <input type='checkbox' <?php if ($cafe == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fcf' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">

                                                    <i class="fa fa-coffee text-green" title="Café"></i>

                                                </th>
                                                <td>Café</td>
                                            </tr>
                                            <tr>

                                                <th scope="row"><input type='checkbox' <?php if ($almoco == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fal' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">
                                                    <i class="fa fa-cutlery text-orange" title="Almoço"></i>
                                                </th>
                                                <td>Almoço</td>
                                            </tr>
                                            <tr>

                                                <th scope="row"><input type='checkbox' <?php if ($janta == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fjt' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">
                                                    <i class="fa fa-cutlery text-navy" title="Janta"></i>
                                                </th>
                                                <td>Janta</td>

                                            </tr>
                                        </tbody>
                                    </table>

                                    <?php
                                    if ($id_militar) {
                                    ?>
                                        <input type="hidden" name="fdt_r" value="<?php echo $data_arranchar; ?>">
                                        <input type="hidden" name="militaridd" value="<?php echo $id_militar; ?>">
                                        <input type="hidden" name="nomemilitar" value="<?php echo $nome_militar; ?>">
                                        <input type="hidden" name="laranjeira" value="<?php echo $laranjeira; ?>">
                                        <input type="hidden" name="ano" value="<?php echo $ano; ?>">
                                        <input type="hidden" name="semana" value="<?php echo ltrim($semana, "0"); ?>">
                                        <input type="hidden" name="acao" id="acao" value="cadastrar">
                                    <?php
                                    }
                                    ?>

                                </form>
                            <?php

                            }
                            if ($nivel >= 3) {  ?>
                                <br><a onclick="relatorioArranchados('relatorio_aprovisionadores_arranchados', '<?php echo $data_arranchar; ?>')" class="btn btn-primary btn-SM active" role="button" aria-pressed="true">Imprimir</a>

                            <?php  } else { ?>

                                <br><a onclick="relatorioArranchados('relatorio_subunidade_arranchados', '<?php echo $data_arranchar; ?>')" class="btn btn-primary btn-SM active" role="button" aria-pressed="true">Imprimir</a>
                            <?php }
                            ?>

                        <?php
                            echo " </td>";
                        }
                        ?>
                    </tr>

                </table>
                <center><span class="label label-warning hidden-print">Clique em IMPRIMIR para ver a lista de todos os
                        militares da SubUnidade arranchados!</span></center>
                <br>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#DiasArranchado">
                    CONFIRMAR ARRANCHAMENTO DO <?php echo $nome_militar; ?>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="DiasArranchado" tabindex="-1" role="dialog" aria-labelledby="DiasArranchado" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <?php
                                $dia_inicio_relatorio = date("d-m-Y", strtotime($data_atual_Ymd));
                                $dia_fim_relatorio = date("d-m-Y", strtotime($data_atual_add_15_dias_Ymd));
                                ?>
                                <h5 class="modal-title" id="exampleModalLongTitle">Relatório de Arranchamento do dia
                                    <?php echo $dia_inicio_relatorio; ?> ao dia <?php echo $dia_fim_relatorio; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>

                                </button>
                            </div>
                            <div class="modal-body">
                                <?php
                                $consultar_arranchamento_do_militar = $pdo->prepare(
                                    "SELECT diasarranchado.data, diasarranchado.cafe, diasarranchado.almoco,diasarranchado.janta,
                                    militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares 
                                    INNER JOIN diasarranchado ON diasarranchado.militar=militares.id and (diasarranchado.data >=:inicio and 
                                    diasarranchado.data <= :fim) and (militares.id= :militar) 
                                    order by diasarranchado.data ASC"
                                );
                                $consultar_arranchamento_do_militar->bindParam(':inicio', $data_atual_Ymd);
                                $consultar_arranchamento_do_militar->bindParam(':fim', $data_atual_add_15_dias_Ymd);
                                $consultar_arranchamento_do_militar->bindParam(':militar', $id_militar);
                                $consultar_arranchamento_do_militar->execute();
                                $rowCount             = $consultar_arranchamento_do_militar->rowCount();

                                if ($rowCount <= 0) {
                                    echo "Não há arranchamento do " . $nome_militar . ". <BR> Clique nos campos para arranchar!";
                                } else {
                                    echo "" . $nome_militar . "";
                                ?>

                                    <table class="table table-hover table-striped table-bordered" id="">
                                        <thead>
                                            <tr class="success">
                                                <th>Data</th>
                                                <th>
                                                    <center>Café</center>
                                                </th>
                                                <th>
                                                    <center>Almoço</center>
                                                </th>
                                                <th>
                                                    <center>Janta</center>
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($linha = $consultar_arranchamento_do_militar->fetch(PDO::FETCH_ASSOC)) {
                                                $id             = (int) $linha['id'];
                                                $nome           = $linha['nomeGuerra'];
                                                $numero         = $linha['numero'];
                                                $grad           = $linha['grad'];
                                                $bateria        = $linha['subUnidade'];
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
                                                $consultaC->bindParam(':inicio', $data_atual_Ymd);
                                                $consultaC->bindParam(':fim', $data_atual_add_15_dias_Ymd);
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
                                                $consultaA->bindParam(':inicio', $data_atual_Ymd);
                                                $consultaA->bindParam(':fim', $data_atual_add_15_dias_Ymd);
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
                                                $consultaJ->bindParam(':inicio', $data_atual_Ymd);
                                                $consultaJ->bindParam(':fim', $data_atual_add_15_dias_Ymd);
                                                $consultaJ->bindParam(':militar', $id_militar);
                                                $consultaJ->execute();
                                                $totalJanta = $consultaJ->rowCount();

                                                if ($linha['cafe'] == "1") {
                                                    $cafe = "<CENTER>X";
                                                } else {
                                                    $cafe = "<CENTER>-";
                                                }
                                                if ($linha['almoco'] == "1") {
                                                    $almoco = "<CENTER>X";
                                                } else {
                                                    $almoco = "<CENTER>-";
                                                }
                                                if ($linha['janta'] == "1") {
                                                    $janta = "<CENTER>X</CENTER>";
                                                } else {
                                                    $janta = "<CENTER>-";
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
                                                <td><b>TOTAL:</b></td>
                                                <td><b>
                                                        <center><?php echo $totalCafe; ?></center>
                                                </td>
                                                <td><b>
                                                        <center><?php echo $totalAlmoco; ?></center>
                                                </td>
                                                <td><b>
                                                        <center><?php echo $totalJanta; ?></center>
                                                </td>

                                            </tr>

                                    </table>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Certo</button>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>
    </div>



<?php
} else {
?> <script>
        alert("Clique em listar militares e selecione UM");
    </script>
<?php
}
?>

<h6 align="center"><a target="a_blank" rel="nofollow noreferrer noopener external" href="https://clebersiqueira.com.br">Desenvolvido por Cleber Siqueira.</a></h6>