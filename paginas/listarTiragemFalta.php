<?php
if ($_SESSION['nivel'] == 3) {
    $doDia     = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $doDia_formatada = date('d-m-Y', strtotime($doDia));
    $ref      = filter_input(INPUT_GET, 'ref', FILTER_SANITIZE_STRING);
    $situacao = filter_input(INPUT_GET, 'situacao', FILTER_SANITIZE_STRING);
    if (isset($_GET['msg'])) {
        $mensagem = $_GET['msg'];
        echo "<script> alert('$mensagem') </script>";
    }
    $juste    = 'just' . $ref;
    $consulta = $pdo->prepare("SELECT * FROM diasarranchado WHERE diasarranchado.data = :doDia and $ref = 1  and $juste =:situacao");
    $consulta->bindParam(':doDia', $doDia);
    $consulta->bindParam(':situacao', $situacao);
    $consulta->execute();
?>
    <!doctype html>
    <html lang='pt-BR'>
    <style>
        .forms {
            display: block;
        }

        .forms>form {
            display: inline-block;
            width: 500px;
        }
    </style>

    <body>
        <div class='container'>
            <div class='alert alert-info' role='alert'>
                <?php if ($situacao == 'faltou') {
                ?>
                    Militares que FALTARAM SEM JUSTIFICATIVA ao rancho - ><?php echo $ref; ?> do dia <?php echo $doDia_formatada; ?>
                <?php
                }
                if ($situacao == 'justificou') {
                ?>
                    Militares que JUSTIFICARAM A FALTA ao rancho - > <?php echo $ref; ?> do dia <?php echo $doDia_formatada; ?>
                <?php
                } ?>
            </div>
            <table class='table table-hover table-striped table-bordered' id='tabela'>
                <thead>
                    <tr class='success'>
                        <th>Nome</th>
                        <th>SubUnidade</th>
                        <th>Corrigir FALTA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha1 = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        $id_militar = $linha1['militar'];

                        $consultaMilitar = $pdo->prepare("SELECT *, subunidades.descricao as SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade WHERE militares.id= :id_militar order by ABS(militares.numero);");
                        $consultaMilitar->bindParam(':id_militar', $id_militar);
                        $consultaMilitar->execute();
                        while ($linha = $consultaMilitar->fetch(PDO::FETCH_ASSOC)) {
                            $id      = $linha['MILITAR_ID'];
                            $nome    = $linha['nomeGuerra'];
                            $numero  = $linha['numero'];
                            $grad    = $linha['grad'];
                            $bateria = $linha['SU_DESCRICAO'];
                    ?>
                            <tr>
                                <td><?php echo $grad . ' ' . $numero . ' ' . $nome; ?></td>
                                <td><?php echo $bateria; ?></td>
                                <td>
                                    <table>
                                        <tr>
                                            <form class='forms' method='post' name='ok' action='acoes/controllerTiragemDeFaltas.php'>
                                                <button class='btn btn-success' type='submit' title='Compareceu'><span class='glyphicon glyphicon-ok'></span></button>
                                                <input type='hidden' name='data' value="<?php echo $doDia; ?>">
                                                <input type='hidden' name='ref' value="<?php echo $ref; ?>">
                                                <input type='hidden' name='militar' value="<?php echo $id; ?>">
                                                <input type='hidden' name='situacao' id='situacao' value="<?php echo $situacao; ?>">
                                                <input type='hidden' name='acao' id='acao' value='ok'>
                                            </form>

                                            <form class='forms' method='post' name='just' action='acoes/controllerTiragemDeFaltas.php'>
                                                <button class='btn btn-warning' type='submit' title='Justificou'><span class='glyphicon glyphicon-question-sign'></span></button>
                                                <input type='hidden' name='data' value="<?php echo $doDia; ?>">
                                                <input type='hidden' name='ref' value="<?php echo $ref; ?>">
                                                <input type='hidden' name='militar' value="<?php echo $id; ?>">
                                                <input type='hidden' name='situacao' id='situacao' value="<?php echo $situacao; ?>">
                                                <input type='hidden' name='acao' id='acao' value='justificou'>
                                            </form>

                                            <form class='forms' method='post' name='falta' action='acoes/controllerTiragemDeFaltas.php'>
                                                <button class='btn btn-danger' type='submit' title='Justificou'><span class='glyphicon glyphicon-remove'></span></button>
                                                <input type='hidden' name='data' value="<?php echo $doDia; ?>">
                                                <input type='hidden' name='ref' value="<?php echo $ref; ?>">
                                                <input type='hidden' name='militar' value="<?php echo $id; ?>">
                                                <input type='hidden' name='situacao' id='situacao' value="<?php echo $situacao; ?>">
                                                <input type='hidden' name='acao' id='acao' value='faltou'>
                                            </form>
                                    </table>

                            <?php
                        }
                    } ?>
                                </td>
                            </tr>
                </tbody>
            </table>
        </div>
    </body>

    </html>
<?php
} else {
    echo 'Área Proibida';
}
?>