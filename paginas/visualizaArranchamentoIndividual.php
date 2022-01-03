<?php
if ($_SESSION['nivel'] == 1) {
    $laranjeira = filter_var($_SESSION['laranjeira'], FILTER_SANITIZE_NUMBER_INT);
    $especial   = filter_var($_SESSION['especial'], FILTER_SANITIZE_NUMBER_INT);
    $id_militar   = (int) filter_var($_SESSION['idUsuario'], FILTER_SANITIZE_NUMBER_INT);
?>
    <div class="container">
        <div class="table">
            <tbody>
                <?php
                $ano = ($ano) ? $ano : date("Y");
                $diaDoAno = date('z');
                if ($diaDoAno < 7) {
                    $semana = ($semana) ? $semana : 1;
                } else {
                    $semana = ($semana) ? $semana : date('W', strtotime(date('Ymd') . ' + ' . DIAS_ANTECEDENCIA . ' days'));
                }

                if ($semana > 52) {
                    $ano++;
                    $semana = 1;
                } elseif ($semana < 1) {
                    $ano--;
                    $semana = 52;
                }
                ?>
                <CENTER>
                <a href="<?php echo $_SERVER['PHP_SELF'] . '?semana=' . ($semana == 1 ? 52 : $semana - 1) . '&ano=' . ($semana == 1 ? $ano - 1 : $ano); ?>" class="btn btn-default">
                    <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>Semana anterior
                </a>

                <a href="<?php echo $_SERVER['PHP_SELF'] . '?semana=' . ($semana == 52 ? 1 : 1 + $semana) . '&ano=' . ($semana == 52 ? 1 + $ano : $ano); ?>" class="btn btn-default">
                    Próxima semana<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
                </a>
                <br>
                <?php
                if (isset($_SESSION['idUsuario'])) {
                    echo "MILITAR -> " . $_SESSION['grad'] . " " . $_SESSION['UsuarioNome'];
                } else {
                    require_once "config.php";
                    echo "MILITAR -> " . $nome_militar;
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
                            $data_ano_W_semana_dia_da_semana = strtotime($ano . "W" . $semana . $dia_da_semana); ////2021 W 37 1
                            $dia_da_semana_string = (OS_HOST == 'LINUX') ? strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))) : utf8_encode(strftime('%A', strtotime(date('l', $data_ano_W_semana_dia_da_semana))));

                            echo "<td><center>" . ($dia_da_semana_string) . "<br>" . date('d/m/Y', $data_ano_W_semana_dia_da_semana) . "";

                            $dia_arranchar            = date('d', $data_ano_W_semana_dia_da_semana);
                            $data_arranchar = date('Y-m-d', $data_ano_W_semana_dia_da_semana);
                            $data_atual_Ymd        = date("Ymd");
                            $data_atual_Y_m_d        = date("Y-m-d");

                            $data_atual_add_15_dias_Ymd   = date('Ymd', strtotime($data_atual_Ymd . ' + ' . DIAS_PARA_ARRANCHAR . ' days'));
                            $data_atual_add_15_dias_Y_m_d  = date('Y-m-d', strtotime($data_atual_Ymd . ' + ' . DIAS_PARA_ARRANCHAR . ' days'));

                            $horario_limite_d_m_Y  = date('d-m-Y 15:00', strtotime($data_atual_Ymd));
                            $data_hora_atual_d_m_Y = date('d-m-Y H:i', strtotime(date("Y-m-d H:i")));

                            if ($horario_limite_d_m_Y >= $data_hora_atual_d_m_Y) {
                                $data_atual_add_margem_em_dias_Y_m_d = date('Y-m-d', strtotime($data_atual_Ymd . ' + ' . (DIAS_ANTECEDENCIA-1) . ' days'));
                            } else {
                                $data_atual_add_margem_em_dias_Y_m_d = date('Y-m-d', strtotime($data_atual_Ymd . ' + ' . DIAS_ANTECEDENCIA . ' days'));
                            }

                            $mes_data_arranchar = date('m', strtotime($data_arranchar . ''));

                            $consultar_arranchamento_da_data_por_militar   = $pdo->prepare("select * from diasarranchado where data = :data and militar=:militar");
                            $consultar_arranchamento_da_data_por_militar->bindParam(':data', $data_arranchar);
                            $consultar_arranchamento_da_data_por_militar->bindParam(':militar', $id_militar);
                            $consultar_arranchamento_da_data_por_militar->execute();

                            $resultado_da_consulta = $consultar_arranchamento_da_data_por_militar->fetch(PDO::FETCH_ASSOC);
                            $cafe           = $resultado_da_consulta['cafe'] ?? 0;
                            $almoco         = $resultado_da_consulta['almoco'] ?? 0;
                            $janta          = $resultado_da_consulta['janta'] ?? 0;
                            if ($data_arranchar >= $data_atual_Y_m_d and $data_arranchar <= $data_atual_add_15_dias_Y_m_d and $data_arranchar >= $data_atual_add_margem_em_dias_Y_m_d) {; //echo $data_arranchar;
                        ?>
                                <form method='post' name="FormArranchar<?php echo $dia_arranchar; ?>" id="FormArranchar<?php echo $dia_arranchar; ?>" action='acoes/controllerArrancharIND.php'>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr class="info">
                                                <th scope="col">CHECK</th>
                                                <th scope="col">REFEIÇÃO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php
                                                if ($dia_da_semana == 6 and $laranjeira == 0 and $especial == 0 or $dia_da_semana == 7 and $laranjeira == 0 and $especial == 0) {
                                                ?>
                                                    <th scope="row"><input type='checkbox' disabled title="APENAS LARANJEIRAS E PESSOAL DE SV">
                                                        <i class="fa fa-coffee text-red" title="APENAS LARANJEIRAS E PESSOAL DE SV"></i>
                                                    </th>
                                                <?php
                                                } else {
                                                ?>
                                                    <th scope="row"><input type='checkbox' <?php if ($cafe == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fcf' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">
                                                        <i class="fa fa-coffee text-green" title="Café"></i>
                                                    </th>

                                                <?php
                                                }
                                                ?>

                                                <td>Café</td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if ($dia_da_semana == 5 and $laranjeira == 0 and $especial == 0 or $dia_da_semana == 6 and $laranjeira == 0 and $especial == 0 or $dia_da_semana == 7 and $laranjeira == 0 and $especial == 0) {

                                                ?>
                                                    <th scope="row"><input type='checkbox' disabled title="APENAS LARANJEIRAS E PESSOAL DE SV">
                                                        <i class="fa fa-cutlery text-red" title="APENAS LARANJEIRAS E PESSOAL DE SV"></i>
                                                    </th>
                                                <?php
                                                } else if (($mes_data_arranchar == 12 or $mes_data_arranchar == 01) and $laranjeira == 0 and $especial == 0) {
                                                    echo "<span class='label label-danger hidden-print'>MEIO EXPEDIENTE!</span>";

                                                ?>

                                                    <th scope="row"><input type='checkbox' disabled title="APENAS LARANJEIRAS E PESSOAL DE SV">
                                                        <i class="fa fa-cutlery text-red" title="APENAS LARANJEIRAS E PESSOAL DE SV"></i>
                                                    </th>

                                                <?php

                                                } else {
                                                ?>
                                                    <th scope="row"><input type='checkbox' <?php if ($almoco == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fal' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">
                                                        <i class="fa fa-cutlery text-orange" title="Almoço"></i>
                                                    </th>

                                                <?php
                                                }

                                                ?>
                                                <td>Almoço</td>
                                            </tr>
                                            <tr>
                                                <?php
                                                if (($mes == 12 or $mes == 01) and $dia_da_semana == 4 and $laranjeira == 0 and $especial == 0 or $dia_da_semana == 5 and $laranjeira == 0 and $especial == 0 or $dia_da_semana == 6 and $laranjeira == 0 or $dia_da_semana == 7 and $laranjeira == 0 and $especial == 0) {

                                                    if ($dia_da_semana == 5) {
                                                        echo "<span class='label label-danger hidden-print'>MEIO EXPEDIENTE!</span>";
                                                    }

                                                ?>
                                                    <th scope="row"><input type='checkbox' disabled title="APENAS LARANJEIRAS E PESSOAL DE SV">
                                                        <i class="fa fa-cutlery text-red" title="APENAS LARANJEIRAS E PESSOAL DE SV"></i>
                                                    </th>
                                                <?php
                                                } else if (($mes_data_arranchar == 12 or $mes_data_arranchar == 01) and $laranjeira == 0 and $especial == 0) {

                                                ?>
                                                    <th scope="row"><input type='checkbox' disabled title="APENAS LARANJEIRAS E PESSOAL DE SV">
                                                        <i class="fa fa-cutlery text-red" title="APENAS LARANJEIRAS E PESSOAL DE SV"></i>
                                                    </th>

                                                <?php

                                                } else {
                                                ?>
                                                    <th scope="row"><input type='checkbox' <?php if ($janta == 1) { ?> checked value='1' <?php } else { ?> value='1' <?php } ?> name='fjt' onchange="document.getElementById('FormArranchar<?php echo $dia_arranchar; ?>').submit()">
                                                        <i class="fa fa-cutlery text-navy" title="Janta"></i>
                                                    </th>

                                                <?php
                                                }
                                                ?>

                                                <td>Janta</td>

                                            </tr>
                                        </tbody>
                                    </table>

                                    <?php
                                    if (isset($_SESSION['idUsuario'])) {;
                                    ?>
                                        <input type="hidden" name="fdt_r" value="<?php echo $data_arranchar; ?>">
                                        <input type="hidden" name="militaridd" value="<?php echo $id_militar; ?>">
                                        <input type="hidden" name="nomemilitar" value="<?php echo filter_var($_SESSION['UsuarioNome'], FILTER_SANITIZE_NUMBER_INT); ?>">
                                        <input type="hidden" name="laranjeira" value="<?php echo $laranjeira; ?>">
                                        <input type="hidden" name="ano" value="<?php echo $ano; ?>">
                                        <input type="hidden" name="semana" value="<?php echo ltrim($semana, "0"); ?>">
                                        <input type="hidden" name="acao" id="acao" value="cadastrar">
                                    <?php
                                    } else {
                                        //echo "MILITAR -> ".$nome_militar;
                                    }
                                    ?>

                                </form>

                        <?php
                                echo " </td>";
                            }
                        }
                        ?>
                    </tr>
                </table>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#DiasArranchado">
                    CONFIRMAR ARRANCHAMENTO!
                </button>

                <!-- Modal -->
                <div class="modal fade" id="DiasArranchado" tabindex="-1" role="dialog" aria-labelledby="DiasArranchadoTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <?php
                                $dia_inicio_relatorio = date("d-m-Y", strtotime($data_atual_Ymd));
                                $dia_fim_relatorio = date("d-m-Y", strtotime($data_atual_add_15_dias_Ymd));
                                ?>
                                <h5 class="modal-title" id="exampleModalLongTitle">Relatório do meu Arranchamento do dia
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
                                    echo "Não há arranchamento <BR> Clique nos campos para se arranchar!";
                                } else {
                                    echo $_SESSION['grad'] . " " . $_SESSION['UsuarioNome'];
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