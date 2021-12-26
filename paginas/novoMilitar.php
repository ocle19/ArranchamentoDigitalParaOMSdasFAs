<?php
if ($nivel >= 2) {
?>

    <head>
        <title> Adicionar Militar</title>
    </head>
    <script>
        function formatar(mascara, documento) {
            var i = documento.value.length;
            var saida = mascara.substring(0, 1);
            var texto = mascara.substring(i)

            if (texto.substring(0, 1) != saida) {
                documento.value += texto.substring(0, 1);
            }

        }
    </script>
    <div class="container theme-showcase" role="main">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <center>Adicionar militar</center>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" action="" id="form_cadastrar_militar" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Graduação</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="grad" id="grad">
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
                            <label for="inputEmail3" class="col-sm-4 control-label">Laranjeira</label>
                            <div class="col-sm-2">
                                <select class="form-control" name="laranjeira" id="laranjeira">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Número</label>
                            <div class="col-sm-6 col-lg-1">
                                <input type="text" class="form-control" maxlength="4" name="numero" required autofocus />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Nome Completo</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" maxlength="50" name="nomeCompleto" required autofocus />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Nome de Guerra</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" maxlength="20" name="nomeGuerra" required autofocus />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">SubUnidade</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="subUnidade">
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
                                            <?php echo $bateria_descricao; ?>
                                        </option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="acao" value="cadastrar_militar">
                            <center><button type="buttom" id="btn_cadastrar_militar" class="btn btn-success">Cadastrar militar</button>
                        </div>
                    </form>
                <?php
            } else {
                echo "Você não tem permissão.";
            } ?>