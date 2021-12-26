<?php
if ($_SESSION['nivel'] == 3) {
    $data = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $ref  = htmlentities(filter_input(INPUT_GET, 'ref', FILTER_SANITIZE_STRING), ENT_QUOTES);
    if (isset($_GET['msg'])) {
        $mensagem = $_GET['msg'];
        echo "<script> alert('$mensagem') </script>";
    }
    $juste    = htmlentities('just' . $ref, ENT_QUOTES);
    $consulta = $pdo->prepare("SELECT militar FROM diasarranchado WHERE diasarranchado.data= :doDia AND $ref = 1 AND ($juste ='aguardando' OR $juste='') ;");
    $consulta->bindParam(':doDia', $data);
    $consulta->execute();

?>
    <!doctype html>
    <html lang="pt-BR">
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
        <div class="container">
            <div class="alert alert-info" role="alert">
                Lista de militares que ainda não compareceram ao Rancho - > <?php $datax2 = date('d-m-Y', strtotime($data));
                                                                            echo $ref; ?> do dia <?php echo $datax2; ?>
            </div>
            <table class="table table-hover table-striped table-bordered" id="tabela">
                <thead>
                    <tr class="success">
                        <th>Nome</th>
                        <th>SubUnidade</th>
                        <th>Compareceu?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha1 = $consulta->fetch(PDO::FETCH_ASSOC)) {
                        $id_militar = $linha1['militar'];

                        $consultaMilitar = $pdo->prepare("SELECT * , subunidades.descricao AS SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades ON subunidades.id = militares.subUnidade WHERE militares.id= :id_militar ORDER BY ABS(militares.numero) ASC;");
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
                                <td><?php echo $grad . ' ' . $numero . ' ' . mb_strtoupper($nome); ?></td>
                                <td><?php echo $bateria; ?></td>
                                <td>
                                    <table>
                                        <tr>
                                            <form class="forms" method='post' name="ok" action='acoes/controllerFalta.php'>
                                                <button class="btn btn-success" type="submit" title="Compareceu"><span class="glyphicon glyphicon-ok"></span></button>
                                                <input type="hidden" name="data" value="<?php echo $data; ?>">
                                                <input type="hidden" name="ref" value="<?php echo $ref; ?>">
                                                <input type="hidden" name="militar" value="<?php echo $id; ?>">
                                                <input type="hidden" name="acao" id="acao" value="ok">
                                            </form>

                                            <form class="forms" method='post' name="just" action='acoes/controllerFalta.php'>
                                                <button class="btn btn-warning" type="submit" title="Justificou"><span class="glyphicon glyphicon-question-sign"></span></button>
                                                <input type="hidden" name="data" value="<?php echo $data; ?>">
                                                <input type="hidden" name="ref" value="<?php echo $ref; ?>">
                                                <input type="hidden" name="militar" value="<?php echo $id; ?>">
                                                <input type="hidden" name="acao" id="acao" value="justificou">
                                            </form>

                                            <form class="forms" method='post' name="falta" action='acoes/controllerFalta.php'>
                                                <button class="btn btn-danger" type="submit" title="Justificou"><span class="glyphicon glyphicon-remove"></span></button>
                                                <input type="hidden" name="data" value="<?php echo $data; ?>">
                                                <input type="hidden" name="ref" value="<?php echo $ref; ?>">
                                                <input type="hidden" name="militar" value="<?php echo $id; ?>">
                                                <input type="hidden" name="acao" id="acao" value="faltou">
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