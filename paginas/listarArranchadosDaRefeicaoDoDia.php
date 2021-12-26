<?php
if ($_SESSION['nivel'] == 3) {
    $data = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_STRING);
    $ref  = htmlentities(filter_input(INPUT_GET, 'ref', FILTER_SANITIZE_STRING), ENT_QUOTES);
    $juste = 'just' . $ref;

    $consulta = $pdo->prepare("SELECT militar FROM diasarranchado WHERE diasarranchado.data = :doDia and $ref =1;");
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
                Lista de TODOS os militares que se arrancharam - > <?php $datax2 = date('d-m-Y', strtotime($data));
                                                                    echo $ref; ?> do dia <?php echo $datax2; ?>
            </div>
            <table class="table table-hover table-striped table-bordered" id="tabela">
                <thead>
                    <tr class="success">
                        <th>Nome</th>
                        <th>SubUnidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha1 = $consulta->fetch(PDO::FETCH_ASSOC)) {

                        $id_militar = $linha1['militar'];

                        $consultaMilitar = $pdo->prepare("SELECT *, subunidades.descricao AS SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade WHERE militares.id=:id_militar  ORDER BY ABS(militares.numero);");
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

                            </tr>
                    <?php
                        }
                    } ?>
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