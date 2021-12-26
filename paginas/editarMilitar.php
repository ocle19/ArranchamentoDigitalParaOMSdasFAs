<?php
$batera = filter_var($_SESSION['bateria'], FILTER_SANITIZE_NUMBER_INT);

?>

<head>
    <title> <?php echo SITE_NOME; ?> - Editar Militar</title>
</head>

<script>
    function fnBuscaSelect(busca) {
        var valorABuscar = busca.value.toUpperCase();
        var selectedTrends = document.getElementById("militar");
        valorIndex = selectedTrends.selectedIndex;
        var soprineiro = false;
        for (var i = 0; i < selectedTrends.length; i++) {
            valorTextSelect = selectedTrends.options[i].text;
            if (valorTextSelect.indexOf(valorABuscar) > -1) {
                if (soprineiro) {
                    soprineiro = false;
                    valorIndex = i;
                }
                selectedTrends.options[i].style.display = "";

            } else {
                selectedTrends.options[i].style.display = "none";
                selectedTrends.options[i].selected = '';
            }
        }
        if ((soprineiro) && (valorABuscar != "")) {

        }
        selectedTrends.options[valorIndex].selected = 'selected';

    }
</script>

<body>
    <div class="container theme-showcase" role="main">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <form id="form_Relatorio" action="" method="POST" style="margin-bottom: 20px">
                        <?php
                        $consultar_militares = $pdo->prepare(
                            "SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO, M.id as MILITAR_ID  FROM militares as M 
                            JOIN subunidades as SU ON SU.id = M.subUnidade  WHERE M.status='ATIVADO' ORDER BY M.subUnidade,M.nomeGuerra asc"
                        );
                        $consultar_militares->execute();
                        ?>
                        <div class="form-group">
                            <label for="cpfvisitante" class="col-sm-2 control-label  hidden-print">Selecione um
                                militar</label>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" placeholder="Digite o nome ou uma parte do nome do militar aqui e selecione logo abaixo." onChange="fnBuscaSelect(this);" style='width:100%' /><br />
                                <select class="form-control hidden-print stripped" name="militar" id="militar" style="width:100%">
                                    <option value="Selecione"><?php echo "Selecione aqui"; ?></option>
                                    <?php while ($resu = $consultar_militares->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <option value="<?php echo $resu['MILITAR_ID']; ?>">
                                            <?php echo "" . (mb_strtoupper($resu['nomeGuerra'])); ?>
                                            <?php echo "[" . (mb_strtoupper($resu['grad'])) . ""; ?>
                                            <?php echo "" . mb_strtoupper($resu['numero']) . "] "; ?>
                                            <?php echo (($resu['SU_DESCRICAO'])); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="button" id="button" class="btn btn-success  hidden-print">Editar o
                            militar</button>
                    </form>
                    <br />

                    <?php
                    if (isset($_GET["militar"])) {
                        $militar = $_GET["militar"] ? $_GET["militar"] : 0;
                    }
                    if (isset($_POST["militar"])) {
                        $militar = $_POST["militar"] ? $_POST["militar"] : 0;
                    }


                    if (isset($_POST["button"]) and ($militar > 0)) {
                        $id_militar_editar = $militar > 0 ? filter_input(INPUT_POST, 'militar', FILTER_SANITIZE_NUMBER_INT) : filter_input(INPUT_GET, 'militar', FILTER_SANITIZE_NUMBER_INT);
                        $consultar_dados_militar = $pdo->prepare(
                            'SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO, M.id as MILITAR_ID FROM militares as M 
                            JOIN subunidades as SU ON SU.id = M.subUnidade WHERE M.id = :id'
                        );
                        $consultar_dados_militar->bindParam(':id', $id_militar_editar);
                        $consultar_dados_militar->execute();

                        $resultadoMilitar = $consultar_dados_militar->fetch(PDO::FETCH_ASSOC);
                        $idmilitar        = $resultadoMilitar['MILITAR_ID'];
                        $numero           = $resultadoMilitar['numero'];
                        $nomeCompleto     = $resultadoMilitar['nomeCompleto'];
                        $nomeGuerra       = $resultadoMilitar['nomeGuerra'];
                        $bateria          = $resultadoMilitar['subUnidade'];
                        $status           = $resultadoMilitar['status'];
                        $graduacao        = $resultadoMilitar['grad'];
                        $nivel            = $resultadoMilitar['nivel'];
                        $senha            = $resultadoMilitar['senha'];
                        $bateriastring    = $resultadoMilitar['SU_DESCRICAO'];

                        if ($nivel == 1) {
                            $nivelstring = "Normal";
                        }
                        if ($nivel == 2) {
                            $nivelstring = "Furriel";
                        }

                        if ($nivel == 3) {
                            $nivelstring = "Aprov";
                        }
                    ?>
                        <br />
                        <div class="panel-group">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <center>Editar militar</center>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" action="" enctype="multipart/form-data" id="form_edicao_militar" method="POST">

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">NIVEL</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="nivel" id="nivel">

                                                    <option value="<?php echo $nivel; ?>"><?php echo $nivelstring; ?>
                                                    </option>
                                                    <option value="1">Normal</option>
                                                    <option value="2">Furriel</option>
                                                    <option value="3">Aprov</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Graduação</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="grad" id="grad">

                                                    <option value="<?php echo $graduacao; ?>"><?php echo $graduacao; ?>
                                                    </option>
                                                    <optgroup label="Praça">
                                                        <option value="Sd Ev">Soldado EV</option>
                                                        <option value="Sd Ep">Soldado EP</option>
                                                        <option value="Cb Ev">Cabo EV</option>
                                                        <option value="Cb Ep">Cabo EP</option>
                                                        <option value="3º Sgt">3º Sargento</option>
                                                        <option value="2º Sgt">2º Sargento</option>
                                                        <option value="1º Sgt">1º Sargento</option>
                                                        <option value="S Ten">Sub Tenente</option>
                                                        <option value="Cad">Cadete</option>
                                                    </optgroup>
                                                    <optgroup label="Oficiais">
                                                        <option value="Asp Of">Aspirante</option>
                                                        <option value="2º Ten">2º Tenente</option>
                                                        <option value="1º Ten">1º Tenente</option>
                                                        <option value="Cap">Capitão</option>
                                                        <option value="Maj">Major</option>
                                                        <option value="TC">Tenente Coronel</option>
                                                        <option value="Cel">Coronel</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Número</label>
                                            <div class="col-sm-6 col-lg-2">
                                                <input type="text" class="form-control" maxlength="4" name="numero" value="<?php echo $numero; ?>" required autofocus />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Nome Completo</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" maxlength="50" name="nomecompleto" value="<?php echo $nomeCompleto; ?>" required autofocus />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Nome de Guerra</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" maxlength="20" name="nomeguerra" value="<?php echo $nomeGuerra; ?>" required autofocus />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">Senha</label>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" maxlength="20" name="senha" value="<?php echo $senha; ?>" required autofocus />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">STATUS</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" name="status" id="status">
                                                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                                    <option value="ATIVADO">ATIVADO (</option>
                                                    <option value="DESATIVADO">DESATIVADO (DEU BAIXA / NÃO ESTÁ MAIS NA OM)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-4 control-label">SubUnidade</label>
                                            <div class="col-sm-6">

                                                <select class="form-control" name="subunidade" id="subunidade">
                                                    <option value="<?php echo $bateria; ?>"><?php echo $bateriastring; ?>
                                                    </option>
                                                    <?php
                                                    $consultarBaterias = $pdo->prepare(
                                                        'SELECT SU.id as SU_ID, SU.descricao as SU_DESCRICAO from subunidades as SU order by SU.descricao asc'
                                                    );
                                                    $consultarBaterias->execute();
                                                    while ($resu = $consultarBaterias->fetch(PDO::FETCH_ASSOC)) {
                                                        $bateria_id        = $resu['SU_ID'];
                                                        $bateria_descricao = $resu['SU_DESCRICAO'];
                                                    ?>
                                                        <option value="<?php echo $bateria_id; ?>">
                                                            <?php echo $bateria_descricao; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>

                                        </div>
                                        <input type="hidden" name="militar_id" value="<?php echo $idmilitar; ?>">
                                        <input type="hidden" name="acao" value="editar_militar">
                                        <center><button type="buttom" id="btn_salvar_edicao_militar" class="btn btn-success">Atualizar dados do militar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                        echo "Selecione um militar!";
                    } ?>
                </div>
            </div>
        </div>
    </div>
</body>