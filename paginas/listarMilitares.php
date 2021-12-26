<script src="assets/js/jquery.min.js"></script>
<style>
    .swal-wide {
        width: 45% !important;
        height: 450px;
        overflow: auto;
    }
</style>

<?php
if ($_SESSION['nivel'] >= 2) {
    $batera   = filter_var($_SESSION['bateria'], FILTER_SANITIZE_NUMBER_INT);
    if ($_SESSION['nivel'] == 2) {
        $consulta = $pdo->prepare("SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO, M.id as MILITAR_ID from militares as M JOIN subunidades as SU ON SU.id = M.subUnidade WHERE M.subUnidade= :subUnidade and M.status='ATIVADO' order by M.laranjeira DESC, M.numero;");
        $consulta->bindParam(':subUnidade', $batera);
        $consulta->execute();
    } else {
        $consulta = $pdo->prepare("SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO, M.id as MILITAR_ID from militares as M JOIN subunidades as SU ON SU.id = M.subUnidade WHERE M.status='ATIVADO' order by M.laranjeira DESC, M.numero;");
        $consulta->execute();
    }
    ?>
    <!doctype html>
    <html lang="pt-BR">

    <body onload="bodyOnloadHandler()">
        <div class="container">
            <div class="alert alert-info" role="alert">
                Listagem de Militares Cadastrados
            </div>
            <br>
            <script>
                function adicionaDiasDataAtual() {
                    var time = new Date();
                    var qtdDias = Number(document.getElementById("diasArranchar").value)
                    time.setDate(time.getDate() + qtdDias)
                    document.getElementsByClassName("ultimoDiaArranchado")[0].innerHTML = "<strong>" + time.getDate() + "/" + (
                        time.getMonth() + 1) + "/" + time.getFullYear() + "</strong>"

                    for (let index = 0; index < document.getElementsByClassName("spnBtnDiasArranchar").length; index++) {
                        document.getElementsByClassName("spnBtnDiasArranchar")[index].innerHTML = "<strong>" + qtdDias +
                            "</strong> "
                        document.getElementsByClassName("inputQtdDias")[index].value = qtdDias
                    }


                }
            </script>
            Quantidade de dias que deseja arrachar: <input type="number" onkeypress="return false" onchange="adicionaDiasDataAtual()" name="diasArranchar" id="diasArranchar" min="1" max="60" value="15"> dias
            <br>
            Serão arranchados até o dia <span class="ultimoDiaArranchado"><?php echo $adiciona10 ?? '***Carregando...***'; ?></span><br>

            <div class="pull-right">

                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-filter" data-target="Sd Ev">EV</button>
                    <button type="button" class="btn btn-success btn-filter" data-target="Sd Ep">Sd EP</button>
                    <button type="button" class="btn btn-dark btn-filter" data-target="Cb Ep">Cb EP</button>
                    <button type="button" class="btn btn-warning btn-filter" data-target="3º Sgt">3º Sgt</button>
                    <button type="button" class="btn btn-warning btn-filter" data-target="2º Sgt">2º Sgt</button>
                    <button type="button" class="btn btn-warning btn-filter" data-target="1º Sgt">1º Sgt</button>
                    <button type="button" class="btn btn-primary btn-filter" data-target="S Ten">S Ten</button>
                    <button type="button" class="btn btn-danger btn-filter" data-target="2º Ten">2º Ten</button>
                    <button type="button" class="btn btn-danger btn-filter" data-target="1º Ten">1º Ten</button>
                    <button type="button" class="btn btn-info btn-filter" data-target="Cap">Cap</button>
                    <button type="button" class="btn btn-info btn-filter" data-target="Maj">Major</button>
                    <button type="button" class="btn btn-info btn-filter" data-target="TC">TC</button>
                    <button type="button" class="btn btn-default btn-filter" data-target="all">Todos</button>
                </div>
            </div>
            <table class="table table-hover table-striped table-bordered" id="tabela">
                <thead>
                    <tr class="success">
                        <th>Nome <input id="Pesquisar" type="text" placeholder="Pesquisar..."></th>
                        <th>SubUnidade</th>
                        <th>Arranchar</th>
                        <th>LARANJEIRA?</th>
                    </tr>
                </thead>
                <tbody id="tabela2">
                    <?php
                    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        $id         = $linha['MILITAR_ID'];
                        $nome       = $linha['nomeGuerra'];
                        $numero     = $linha['numero'];
                        $grad       = $linha['grad'];
                        $bateria    = $linha['SU_DESCRICAO'];
                        $laranjeira = $linha['laranjeira'];
                        $dateStart  = date('d/m/Y');
                        $dateStart  = implode('-', array_reverse(explode('/', substr($dateStart, 0, 10)))) . substr($dateStart, 10);
                        $dateStart  = new DateTime($dateStart);
                        $adiciona10 = date('Y/m/d', strtotime('+ ' . DIAS_PARA_ARRANCHAR . ' days', strtotime(date('Y/m/d'))));
                        $BUSCADATA  = $adiciona10;
                        $adiciona10 = implode('-', array_reverse(explode('/', substr($adiciona10, 0, 10)))) . substr($adiciona10, 10);
                        $dataHoje   = date('Y/m/d');
                        $idmilitar  = $id;

                        $consultaa = $pdo->prepare('SELECT * FROM diasarranchado WHERE data = :data and militar= :militar');
                        $consultaa->bindParam(':data', $BUSCADATA);
                        $consultaa->bindParam(':militar', $idmilitar);
                        $consultaa->execute();

                        $ResultadoBusca = $consultaa->fetch(PDO::FETCH_ASSOC);
                        $cafe           = $ResultadoBusca['cafe'] ?? 0;
                        $almoco         = $ResultadoBusca['almoco'] ?? 0;
                        $janta          = $ResultadoBusca['janta'] ?? 0;
                        if ($laranjeira == 0) {
                            $laranja = 0;
                        } else {
                            $laranja = 1;
                        } ?>
                        <tr data-status="<?php echo $grad; ?>">

                            <td> <a href="?pagina=editarMilitar&militar=<?php echo $id; ?>" role="button">
                                    <?php echo $grad . ' ' . $numero . ' ' . mb_strtoupper($nome); ?>
                                </a>


                            </td>
                            <td><?php echo $bateria; ?></td>
                            <td>
                                Arranchar para dias específicos ->
                                <a class="btn btn-default" title="Arranchar" href="?pagina=visualizaCalendario&idmilitar=<?php echo $id; ?>&militar=<?php echo $grad . ' ' . $nome; ?>&laranjeira=<?php echo $laranja; ?>" role="button">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </a>
                                <hr style="    border-top: 1px solid #c18b8b;">
                                <form method='post' name="FormArrancharDias<?php echo $id; ?>" id="FormArrancharDias<?php echo $id; ?>" action=''>
                                    Selecione caso queira arranchar<br>
                                    <input type='checkbox' <?php if ($cafe == 1) { ?> value='1' <?php } else { ?> value='1' <?php } ?> name='fcf'>

                                    <i class="fa fa-coffee text-green" title="Caf�"></i>


                                    Cafe

                                    <input type='checkbox' <?php if ($almoco == 1) { ?> value='1' <?php } else { ?> value='1' <?php } ?> name='fal'>
                                    <i class="fa fa-cutlery text-orange" title="Almo�o"></i>

                                    Almoco

                                    <input type='checkbox' <?php if ($janta == 1) { ?> value='1' <?php } else { ?> value='1' <?php } ?> name='fjt'>
                                    <i class="fa fa-cutlery text-navy" title="Janta"></i>

                                    Janta
                                    <br>
                                    <font size='1' color="red">Selecionado vai arranchar e nao selecionado vai desaranchar
                                    </font>
                                    <br>
                                    <button class="btn btn-sm btn-primary btn-sm" onclick="arranchar('<?php echo $id; ?>');return false;" style="width: 350px;font-size: 1.3rem;border-radius: 1rem;background-color:#317b1c;border:0px;height:30px">Arranchar
                                        ou
                                        desaranchar os
                                        proximos <span class="spnBtnDiasArranchar"> 15 </span>dias</button>

                                    <?php if ($laranja == 0) { ?>
                                        <br>OBS: <font color="red"><B>NAO</B> inclui finais de semana</font><br>
                                    <?php } ?>
                                    <button style="width:350px;font-size: 1rem;border-radius: 1rem;margin-top:5px;height:30px" type="button" class="btn btn-sm btn-primary btn-sm" onclick="buscarArranchamentoListaMilitaresIndividual('<?php echo $id; ?>');return false;">
                                        VERIFICAR ARRANCHAMENTO DO
                                        <?php echo $grad . ' ' . $numero . ' ' . mb_strtoupper($nome); ?>
                                    </button>
                                    <input type="hidden" name="militaridd" value="<?php echo $id; ?>">
                                    <input type="hidden" name="laranjeira" svalue="<?php echo $laranja; ?>">
                                    <input type="hidden" name="militar" value="<?php echo $grad . ' ' . $nome; ?>">
                                    <input type="hidden" name="qtdDias" class="inputQtdDias" value="15">
                                    <input type="hidden" name="acao" value="arrancharPorXdias">

                                    <center>


                                </form>

                            </td>
                            <?php
                            if ($laranjeira == 0) {
                            ?>
                                <td>

                                    <form class="forms" method='post' name="ok" action='acoes/controllerLaranjeira.php'>
                                        <button class="btn btn-success" type="submit" title="TORNAR LARANJEIRA">TORNAR
                                        LARANJEIRA</button>
                                        <input type="hidden" name="militar" value="<?php echo $id; ?>">
                                        <input type="hidden" name="situacao" value="1">
                                        <input type="hidden" name="acao" value="ok">
                                    </form>
                                </TD>
                            <?php
                            } else {
                            ?>
                                <td>
                                    ESSE MILITAR É LARANJEIRA!
                                    <form class="forms" method='post' name="no" action='acoes/controllerLaranjeira.php'>
                                        <button class="btn btn-danger" type="submit" title="NÃO É LARANJEIRA">TORNAR NÃO
                                        LARANJEIRA</button>
                                        <input type="hidden" name="militar" value="<?php echo $id; ?>">
                                        <input type="hidden" name="situacao" value="0">
                                        <input type="hidden" name="acao" value="no">
                                    </form>
                                </td>
                            <?php
                            } ?>
                        <?php
                    } ?>
                </tbody>
            </table>
        </div>

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="assets/js/bootstrap.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->


    </body>
    <script>
        Swal.showLoading()

        function bodyOnloadHandler() {
            swal.close()
        }


        $(document).ready(function() {

            $('.star').on('click', function() {
                $(this).toggleClass('star-checked');
            });

            $('.ckbox label').on('click', function() {
                $(this).parents('tr').toggleClass('selected');
            });

            $('.btn-filter').on('click', function() {
                var $target = $(this).data('target');
                if ($target != 'all') {
                    $('.table tr').css('display', 'none');
                    $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
                } else {
                    $('.table tr').css('display', 'none').fadeIn('slow');
                }
            });

            $("#Pesquisar").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tabela2 tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            adicionaDiasDataAtual()

        });
    </script>
<?php
} else {
    echo 'Error';
}
?>
</html>