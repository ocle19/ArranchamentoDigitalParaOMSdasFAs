<?php
set_time_limit(0);
require_once '../config.php';
require_once "../checarSessao.php";
$acao = $_POST['acao'];

if ($acao == 'relatorio_cafe_almoco_janta_do_dia_cb_sd' && $_SESSION['nivel'] >= 3) {
    $doDia   = filter_input(INPUT_POST, 'dataArranchamento', FILTER_SANITIZE_STRING);
    $data_inicio_formatada = date('d-m-Y', strtotime($doDia));
    $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
    $html .= "<div  align='center'> <b>  Lista de Cabos e Soldados que se arrancharam para o dia $data_inicio_formatada  </b> </div>";

    $consultar_militares_arranchados = $pdo->prepare('SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao as SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia AND (militares.grad ="Sd Ev" OR militares.grad ="Sd Ep" OR militares.grad ="Cb Ev" OR militares.grad ="Cb Ep") AND militares.status="ATIVADO" ORDER BY subUnidade, ABS(numero)');
    $consultar_militares_arranchados->bindParam(':doDia', $doDia);
    $consultar_militares_arranchados->execute();
    $TEM = $consultar_militares_arranchados->rowCount();
    if ($TEM >= 1) {
        $html .= '
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>SU</th>
                <th>Café</th>
                <th>Almoço</th>
                <th>Janta</th>
            </tr>
        </thead>
        <tbody>';

        while ($linha = $consultar_militares_arranchados->fetch(PDO::FETCH_ASSOC)) {
            $id      = $linha['MILITAR_ID'];
            $nome    = (mb_strtoupper($linha['nomeGuerra']));
            $numero  = $linha['numero'];
            $grad    = $linha['grad'];
            $bateria = $linha['SU_DESCRICAO'];

            if ($linha['cafe'] == '1') {
                $cafe = 'X';
            } else {
                $cafe = '-';
            }
            if ($linha['almoco'] == '1') {
                $almoco = 'X';
            } else {
                $almoco = '-';
            }
            if ($linha['janta'] == '1') {
                $janta = 'X';
            } else {
                $janta = '-';
            }
            $nomeGrande = (mb_strtoupper($nome));
            $html .= " <tr>
            <td> $grad $numero $nomeGrande</td>
            <td> $bateria </td>
            <td> <center> $cafe </td>
            <td> <center> $almoco </td>
            <td> <center> $janta </td>
            </tr>";
        }

        global $totalCafeCbSd;
        global $totalAlmocoCbSd;
        global $totalJantaCbSd;

        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId = $resu['id'];

            $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeCbSd->bindParam(':doDia', $doDia);
            $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeCbSd->execute();
            $totalCafeCbSd += $consultaCafeCbSd->rowCount();

            $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
            $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoCbSd->execute();
            $totalAlmocoCbSd += $consultaAlmocoCbSd->rowCount();

            $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaCbSd->bindParam(':doDia', $doDia);
            $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaCbSd->execute();
            $totalJantaCbSd += $consultaJantaCbSd->rowCount();
        }
        $html .= '</tbody>
    </table>
    <br> ';
        $html .= "<table>
                    <tr>
                    <th>CB e SD</th>
                    <th>TOTAL</th>
                    </tr>
                    <tr>
                <td>";



        //// exibe o nome das baterias e a quantidade para cada refeição
        global $totalGeralCafe;
        global $totalGeralAlmoco;
        global $totalGeralJanta;
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeCbSd->bindParam(':doDia', $doDia);
            $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeCbSd->execute();
            $totalCafeCbSd = $consultaCafeCbSd->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalCafeCbSd";
            $totalGeralCafe += $totalCafeCbSd;
        }

        $html .= "</td>
    <td><center>Café: $totalGeralCafe</td>
    </tr>
<tr>

<td>";

        ///  CB SD  ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
            $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoCbSd->execute();
            $totalAlmocoCbSd = $consultaAlmocoCbSd->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalAlmocoCbSd</center>";
            $totalGeralAlmoco += $totalAlmocoCbSd;
        }

        $html .= "</td>
    <td><center>Almoço: $totalGeralAlmoco</center></td>
</tr>
<tr>
<td>";


        /// CB SD JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaCbSd->bindParam(':doDia', $doDia);
            $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaCbSd->execute();
            $totalJantaCbSd = $consultaJantaCbSd->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalJantaCbSd";
            $totalGeralJanta += $totalJantaCbSd;
        }

        $html .= "</td>
    <td><center>Janta: $totalGeralJanta</td>
</tr>

</table> ";
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados!', 'status' => 'warning'));
        return false;
    }
?>
    </tbody>
    </table>
<?php
    $stylesheet = '
  table{
  border-collapse: collapse;
  width: 100%;
  border: 1px solid black;
  }
  th{
  border: 1px solid black;
  }
 td{
  border: 1px solid black;
  }
 ';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    $titulo = "APROV - Cabos e Soldados arranchados para o dia $data_inicio_formatada";
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    //$mpdf->Output("relatorios/APROV - Militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada.pdf", \Mpdf\Output\Destination::INLINE);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}

if ($acao == 'relatorio_cafe_almoco_janta_do_dia_of_st_sgt' && $_SESSION['nivel'] >= 3) {
    $doDia   = filter_input(INPUT_POST, 'dataArranchamento', FILTER_SANITIZE_STRING);
    $data_inicio_formatada = date('d-m-Y', strtotime($doDia));
    $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
    $html .= "<div  align='center'> <b>  Lista de Oficiais, Subtenentes e Sargentos que se arrancharam para o dia $data_inicio_formatada  </b> </div>";

    $consultar_militares_arranchados = $pdo->prepare('SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, militares.numero, 
        militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao AS SU_DESCRICAO,
        militares.id AS MILITAR_ID FROM militares JOIN subunidades ON subunidades.id = militares.subUnidade INNER JOIN diasarranchado 
        ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia 
        AND militares.grad !="Sd Ev" AND militares.grad !="Sd Ep" AND militares.grad !="Cb Ev" AND militares.grad !="Cb Ep" AND militares.status="ATIVADO" 
        ORDER BY subUnidade, ABS(numero)');
    $consultar_militares_arranchados->bindParam(':doDia', $doDia);
    $consultar_militares_arranchados->execute();
    $TEM = $consultar_militares_arranchados->rowCount();
    if ($TEM >= 1) {
        $html .= '
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>SU</th>
                <th>Café</th>
                <th>Almoço</th>
                <th>Janta</th>
            </tr>
        </thead>
        <tbody>';

        while ($linha = $consultar_militares_arranchados->fetch(PDO::FETCH_ASSOC)) {
            $id      = $linha['MILITAR_ID'];
            $nome    = (mb_strtoupper($linha['nomeGuerra']));
            $numero  = $linha['numero'];
            $grad    = $linha['grad'];
            $bateria = $linha['SU_DESCRICAO'];

            if ($linha['cafe'] == '1') {
                $cafe = 'X';
            } else {
                $cafe = '-';
            }
            if ($linha['almoco'] == '1') {
                $almoco = 'X';
            } else {
                $almoco = '-';
            }
            if ($linha['janta'] == '1') {
                $janta = 'X';
            } else {
                $janta = '-';
            }
            $nomeGrande = (mb_strtoupper($nome));
            $html .= " <tr>
            <td> $grad $numero $nomeGrande</td>
            <td> $bateria </td>
            <td> <center> $cafe </td>
            <td> <center> $almoco </td>
            <td> <center> $janta </td>
            </tr>";
        }

        global $totalCafeOfStSgt;
        global $totalAlmocoOfStSgt;
        global $totalJantaOfStSgt;

        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId = $resu['id'];

            $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
            $consultaCafeOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeOfStSgt->execute();
            $totalCafeOfStSgt += $consultaCafeOfStSgt->rowCount();

            $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
            $consultaAlmocoOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoOfStSgt->execute();
            $totalAlmocoOfStSgt += $consultaAlmocoOfStSgt->rowCount();

            $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
            $consultaJantaOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaOfStSgt->execute();
            $totalJantaOfStSgt += $consultaJantaOfStSgt->rowCount();
        }
        $html .= '</tbody>
    </table>
    <br> ';
        $html .= "<table>
                    <tr>
                    <th>OF, ST e SGT</th>
                    <th>TOTAL</th>
                    </tr>
                    <tr>
                <td>";



        //// exibe o nome das baterias e a quantidade para cada refeição
        global $totalGeralCafe;
        global $totalGeralAlmoco;
        global $totalGeralJanta;
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares 
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
            $consultaCafeOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeOfStSgt->execute();
            $totalCafeOfStSgt = $consultaCafeOfStSgt->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalCafeOfStSgt";
            $totalGeralCafe += $totalCafeOfStSgt;
        }

        $html .= "</td>
    <td><center>Café: $totalGeralCafe</td>
    </tr>
<tr>

<td>";

        /// OF ST SGT ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares 
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
            $consultaAlmocoOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoOfStSgt->execute();
            $totalAlmocoOfStSgt = $consultaAlmocoOfStSgt->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalAlmocoOfStSgt</center>";
            $totalGeralAlmoco += $totalAlmocoOfStSgt;
        }

        $html .= "</td>
    <td><center>Almoço: $totalGeralAlmoco</center></td>
</tr>
<tr>
<td>";


        /// OF ST SGT JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares 
                INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
                AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' 
                AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId 
                ORDER BY ABS(numero);");
            $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
            $consultaJantaOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaOfStSgt->execute();
            $totalJantaOfStSgt = $consultaJantaOfStSgt->rowCount();

            $html .= "<center>$subUnidadeDescricao: $totalJantaOfStSgt";
            $totalGeralJanta += $totalJantaOfStSgt;
        }

        $html .= "</td>
    <td><center>Janta: $totalGeralJanta</td>
</tr>

</table> ";
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados!', 'status' => 'warning'));
        return false;
    }
?>
    </tbody>
    </table>
<?php
    $stylesheet = '
  table{
  border-collapse: collapse;
  width: 100%;
  border: 1px solid black;
  }
  th{
  border: 1px solid black;
  }
 td{
  border: 1px solid black;
  }
 ';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    $titulo = "APROV - Oficiais, Subtenentes e Sargentos arranchados para o dia $data_inicio_formatada";
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    //$mpdf->Output("relatorios/APROV - Militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada.pdf", \Mpdf\Output\Destination::INLINE);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}

if ($acao == 'relatorio_cafe_almoco_janta_do_dia' && $_SESSION['nivel'] >= 3) {

    $doDia   = filter_input(INPUT_POST, 'dataArranchamento', FILTER_SANITIZE_STRING);
    $data_inicio_formatada = date('d-m-Y', strtotime($doDia));
    $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
    $html .= "<div  align='center'> <b>  Lista de TODOS os militares que se arrancharam para o dia $data_inicio_formatada  </b> </div>";

    $consultar_militares_arranchados = $pdo->prepare('SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao as SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia ORDER BY subUnidade, ABS(numero), grad');
    $consultar_militares_arranchados->bindParam(':doDia', $doDia);
    $consultar_militares_arranchados->execute();
    $TEM = $consultar_militares_arranchados->rowCount();
    if ($TEM >= 1) {
        $html .= '
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>SU</th>
                <th>Café</th>
                <th>Almoço</th>
                <th>Janta</th>
            </tr>
        </thead>
        <tbody>';

        while ($linha = $consultar_militares_arranchados->fetch(PDO::FETCH_ASSOC)) {
            $id      = $linha['MILITAR_ID'];
            $nome    = (mb_strtoupper($linha['nomeGuerra']));
            $numero  = $linha['numero'];
            $grad    = $linha['grad'];
            $bateria = $linha['SU_DESCRICAO'];

            if ($linha['cafe'] == '1') {
                $cafe = 'X';
            } else {
                $cafe = '-';
            }
            if ($linha['almoco'] == '1') {
                $almoco = 'X';
            } else {
                $almoco = '-';
            }
            if ($linha['janta'] == '1') {
                $janta = 'X';
            } else {
                $janta = '-';
            }
            $nomeGrande = (mb_strtoupper($nome));
            $html .= " <tr>
            <td> $grad $numero $nomeGrande</td>
            <td> $bateria </td>
            <td> <center> $cafe </td>
            <td> <center> $almoco </td>
            <td> <center> $janta </td>
            </tr>";
        }

        global $totalCafeCbSd;
        global $totalAlmocoCbSd;
        global $totalJantaCbSd;

        global $totalCafeOfStSgt;
        global $totalAlmocoOfStSgt;
        global $totalJantaOfStSgt;

        /// $totalCafeCbSdPorSubUnidade = array();
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId = $resu['id'];
            ///$subUnidadeDescricao = $resu['descricao'];

            $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeCbSd->bindParam(':doDia', $doDia);
            $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeCbSd->execute();
            $totalCafeCbSd += $consultaCafeCbSd->rowCount();

            $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
            $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoCbSd->execute();
            $totalAlmocoCbSd += $consultaCafeCbSd->rowCount();

            $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaCbSd->bindParam(':doDia', $doDia);
            $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaCbSd->execute();
            $totalJantaCbSd += $consultaJantaCbSd->rowCount();

            $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' ORDER BY ABS(numero);");
            $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
            $consultaCafeOfStSgt->execute();
            $totalCafeOfStSgt += $consultaCafeOfStSgt->rowCount();

            $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' ORDER BY ABS(numero);");
            $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
            $consultaAlmocoOfStSgt->execute();
            $totalAlmocoOfStSgt += $consultaAlmocoOfStSgt->rowCount();

            $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.janta='1' ORDER BY ABS(numero);");
            $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
            $consultaJantaOfStSgt->execute();
            $totalJantaOfStSgt += $consultaJantaOfStSgt->rowCount();
        }

        $html .= '</tbody>
    </table>
    <br> ';
        $html .= "<table>
<tr>

<th>CB e SD</th>
<th>OF, ST e SGT</th>
<th>TOTAL</th>
</tr>
<tr>
<td>";

        $totalGeralCafe;
        $totalGeralAlmoco;
        $totalGeralJanta;
        /// CB/SD CAFE
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeCbSd->bindParam(':doDia', $doDia);
            $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeCbSd->execute();
            $totalCafeCbSd = $consultaCafeCbSd->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalCafeCbSd";
            $totalGeralCafe += $totalCafeCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";
        /// OF ST SGT CAFE
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.cafe='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
            $consultaCafeOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeOfStSgt->execute();
            $totalCafeOfStSgt = $consultaCafeOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalCafeOfStSgt";
            $totalGeralCafe += $totalCafeOfStSgt;
        }

        $html .= "</td>
                    <td><CENTER>Café: $totalGeralCafe</td>
                    </tr>
                    <tr>
                <td>";
        /// CB/SD ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
            $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoCbSd->execute();
            $totalAlmocoCbSd = $consultaAlmocoCbSd->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoCbSd";
            $totalGeralAlmoco += $totalAlmocoCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";
        /// OF ST SGT ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.almoco='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
            $consultaAlmocoOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoOfStSgt->execute();
            $totalAlmocoOfStSgt = $consultaAlmocoOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoOfStSgt";
            $totalGeralAlmoco += $totalAlmocoOfStSgt;
        }

        $html .= "</td>
                    <td><CENTER>Almoço: $totalGeralAlmoco</td>
                    </tr>
                    <tr>
                <td>";
        /// CB/SD JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data= :doDia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaCbSd->bindParam(':doDia', $doDia);
            $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaCbSd->execute();
            $totalJantaCbSd = $consultaJantaCbSd->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalJantaCbSd";
            $totalGeralJanta += $totalJantaCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";

        /// OF ST SGT JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {

            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data= :doDia AND diasarranchado.janta='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
            $consultaJantaOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaOfStSgt->execute();
            $totalJantaOfStSgt = $consultaJantaOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalJantaOfStSgt";
            $totalGeralJanta += $totalJantaOfStSgt;
        }

        $html .= "</td>
                <td><CENTER>Janta: $totalGeralJanta</td>
                </tr>
                </table>";
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados!', 'status' => 'warning'));
        return false;
    }
?>
    </tbody>
    </table>
<?php
    $stylesheet = '
    table{
        border-collapse: collapse;
        width: 100%;
        border: 1px solid black;
    }
    th{
        border: 1px solid black;
    }
    td{
        border: 1px solid black;
    }';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    $titulo = "APROV - Militares arranchados para o dia $data_inicio_formatada";
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    //$mpdf->Output("relatorios/APROV - Militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada.pdf", \Mpdf\Output\Destination::INLINE);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}


if ($acao == 'relatorio_entre_periodos' && $_SESSION['nivel'] >= 3) {
    $tipoRelatorio   = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING) ?? 'quantitativo';
    $doDia   = filter_input(INPUT_POST, 'iniciodata', FILTER_SANITIZE_STRING);
    $ateOdia = filter_input(INPUT_POST, 'fimdata', FILTER_SANITIZE_STRING);
    $data_inicio_formatada  = date("d-m-Y", strtotime($doDia));
    $data_fim_formatada  = date("d-m-Y", strtotime($ateOdia));
    if (empty($doDia)  || empty($ateOdia)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Insira a data inicial e a data final', 'status' => 'warning'));
        return false;
    }

    if (strtotime($doDia) > strtotime($ateOdia)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'A data inicial precisa ser maior ou igual a data final', 'status' => 'warning'));
        return false;
    }


    $consultar_militares_arranchados = $pdo->prepare('SELECT diasarranchado.data as DATA_ARRANCHADO, diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, 
        militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao as SU_DESCRICAO, 
        militares.id AS MILITAR_ID FROM militares 
        JOIN subunidades on subunidades.id = militares.subUnidade 
        INNER JOIN diasarranchado ON diasarranchado.militar=militares.id 
        AND diasarranchado.data>=:doDia AND diasarranchado.data<=:ateodia 
        ORDER BY subUnidade, diasarranchado.data, ABS(numero), grad');
    $consultar_militares_arranchados->bindParam(':doDia', $doDia);
    $consultar_militares_arranchados->bindParam(':ateodia', $ateOdia);
    $consultar_militares_arranchados->execute();
    $TEM = $consultar_militares_arranchados->rowCount();
    if ($tipoRelatorio == 'completo') {
        $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
        $html .= "<div  align='center'> <b>  Lista de TODOS os militares que se arrancharam do dia $data_inicio_formatada  até o dia $data_fim_formatada  </b> </div>";

        if ($TEM >= 1) {
            $html .= '
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>SU</th>
                            <th>Data</th>
                            <th>Café</th>
                            <th>Almoço</th>
                            <th>Janta</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($linha = $consultar_militares_arranchados->fetch(PDO::FETCH_ASSOC)) {
                $id      = $linha['MILITAR_ID'];
                $nome    = (mb_strtoupper($linha['nomeGuerra']));
                $numero  = $linha['numero'];
                $grad    = $linha['grad'];
                $bateria = $linha['SU_DESCRICAO'];
                $data_arranchado = date('d-m-y', strtotime($linha['DATA_ARRANCHADO']));

                if ($linha['cafe'] == '1') {
                    $cafe = 'X';
                } else {
                    $cafe = '-';
                }
                if ($linha['almoco'] == '1') {
                    $almoco = 'X';
                } else {
                    $almoco = '-';
                }
                if ($linha['janta'] == '1') {
                    $janta = 'X';
                } else {
                    $janta = '-';
                }
                $nomeGrande = (mb_strtoupper($nome));
                $html .= " <tr>
            <td> $grad $numero $nomeGrande</td>
            <td> $bateria </td>
            <td> $data_arranchado </td>
            <td> <center> $cafe </td>
            <td> <center> $almoco </td>
            <td> <center> $janta </td>
            </tr>";
            }


            global $totalCafeCbSd;
            global $totalAlmocoCbSd;
            global $totalJantaCbSd;

            global $totalCafeOfStSgt;
            global $totalAlmocoOfStSgt;
            global $totalJantaOfStSgt;

            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId = $resu['id'];

                $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaCafeCbSd->bindParam(':doDia', $doDia);
                $consultaCafeCbSd->bindParam(':ateodia', $ateOdia);
                $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaCafeCbSd->execute();
                $totalCafeCbSd += $consultaCafeCbSd->rowCount();

                $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
                $consultaAlmocoCbSd->bindParam(':ateodia', $ateOdia);
                $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaAlmocoCbSd->execute();
                $totalAlmocoCbSd += $consultaCafeCbSd->rowCount();

                $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaJantaCbSd->bindParam(':doDia', $doDia);
                $consultaJantaCbSd->bindParam(':ateodia', $ateOdia);
                $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaJantaCbSd->execute();
                $totalJantaCbSd += $consultaJantaCbSd->rowCount();

                $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1' ORDER BY ABS(numero);");
                $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
                $consultaCafeOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaCafeOfStSgt->execute();
                $totalCafeOfStSgt += $consultaCafeOfStSgt->rowCount();

                $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1' ORDER BY ABS(numero);");
                $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
                $consultaAlmocoOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaAlmocoOfStSgt->execute();
                $totalAlmocoOfStSgt += $consultaAlmocoOfStSgt->rowCount();

                $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1' ORDER BY ABS(numero);");
                $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
                $consultaJantaOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaJantaOfStSgt->execute();
                $totalJantaOfStSgt += $consultaJantaOfStSgt->rowCount();
            }

            $html .= '</tbody>
                    </table>
                    <br> ';
            $html .= "<table>
                    <tr>

                    <th>CB e SD</th>
                    <th>OF, ST e SGT</th>
                    <th>TOTAL</th>
                    </tr>
                    <tr>
                    <td>";

            global $totalGeralCafe;
            global $totalGeralAlmoco;
            global $totalGeralJanta;
            /// CB/SD CAFE
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resuCafe = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resuCafe['id'];
                $subUnidadeDescricao = $resuCafe['abreviatura'];

                $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaCafeCbSd->bindParam(':doDia', $doDia);
                $consultaCafeCbSd->bindParam(':ateodia', $ateOdia);
                $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaCafeCbSd->execute();
                $totalCafeCbSd = $consultaCafeCbSd->rowCount();

                $html .= "<CENTER>$subUnidadeDescricao: $totalCafeCbSd";
                $totalGeralCafe += $totalCafeCbSd;
            }
            $html .= "</td>";
            $html .= "<td>";
            /// OF ST SGT CAFE
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resuAlmoco = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resuAlmoco['id'];
                $subUnidadeDescricao = $resuAlmoco['abreviatura'];

                $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
                $consultaCafeOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaCafeOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaCafeOfStSgt->execute();
                $totalCafeOfStSgt = $consultaCafeOfStSgt->rowCount();

                $html .= "<CENTER>$subUnidadeDescricao: $totalCafeOfStSgt";
                $totalGeralCafe += $totalCafeOfStSgt;
            }

            $html .= "</td>
    <td><CENTER>Café: $totalGeralCafe</td>
    </tr>
<tr>

<td>";
            /// CB/SD ALMOCO
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resu['id'];
                $subUnidadeDescricao = $resu['abreviatura'];

                $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
                $consultaAlmocoCbSd->bindParam(':ateodia', $ateOdia);
                $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaAlmocoCbSd->execute();
                $totalAlmocoCbSd = $consultaAlmocoCbSd->rowCount();


                $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoCbSd";
                $totalGeralAlmoco += $totalAlmocoCbSd;
            }
            $html .= "</td>";
            $html .= "<td>";
            /// OF ST SGT ALMOCO
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resu['id'];
                $subUnidadeDescricao = $resu['abreviatura'];

                $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
                $consultaAlmocoOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaAlmocoOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaAlmocoOfStSgt->execute();
                $totalAlmocoOfStSgt = $consultaAlmocoOfStSgt->rowCount();

                $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoOfStSgt";
                $totalGeralAlmoco += $totalAlmocoOfStSgt;
            }

            $html .= "</td>
    <td><CENTER>Almoço: $totalGeralAlmoco</td>
</tr>
<tr>

<td>";
            /// CB/SD JANTA
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resu['id'];
                $subUnidadeDescricao = $resu['abreviatura'];

                $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaJantaCbSd->bindParam(':doDia', $doDia);
                $consultaJantaCbSd->bindParam(':ateodia', $ateOdia);
                $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaJantaCbSd->execute();
                $totalJantaCbSd = $consultaJantaCbSd->rowCount();


                $html .= "<CENTER>$subUnidadeDescricao: $totalJantaCbSd";
                $totalGeralJanta += $totalJantaCbSd;
            }
            $html .= "</td>";
            $html .= "<td>";

            /// OF ST SGT JANTA
            $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
            $consultar_subUnidades->execute();
            while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
                $subUnidadeId        = $resu['id'];
                $subUnidadeDescricao = $resu['abreviatura'];

                $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
                $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
                $consultaJantaOfStSgt->bindParam(':ateodia', $ateOdia);
                $consultaJantaOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
                $consultaJantaOfStSgt->execute();
                $totalJantaOfStSgt = $consultaJantaOfStSgt->rowCount();

                $html .= "<CENTER>$subUnidadeDescricao: $totalJantaOfStSgt";
                $totalGeralJanta += $totalJantaOfStSgt;
            }

            $html .= "</td>
    <td><CENTER>Janta: $totalGeralJanta</td>
</tr>

</table> ";
        } else {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados nesse período!', 'status' => 'warning'));
            return false;
        }
    } else {
        $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
        $html .= "<div  align='center'> <b> Quantitativo de militares que se arrancharam do dia $data_inicio_formatada  até o dia $data_fim_formatada  </b> </div>";

        $html .= "<table>
                    <tr>

                    <th>CB e SD</th>
                    <th>OF, ST e SGT</th>
                    <th>TOTAL</th>
                    </tr>
                    <tr>
                    <td>";

        global $totalGeralCafe;
        global $totalGeralAlmoco;
        global $totalGeralJanta;
        /// CB/SD CAFE
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resuCafe = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resuCafe['id'];
            $subUnidadeDescricao = $resuCafe['abreviatura'];

            $consultaCafeCbSd = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeCbSd->bindParam(':doDia', $doDia);
            $consultaCafeCbSd->bindParam(':ateodia', $ateOdia);
            $consultaCafeCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeCbSd->execute();
            $totalCafeCbSd = $consultaCafeCbSd->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalCafeCbSd";
            $totalGeralCafe += $totalCafeCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";
        /// OF ST SGT CAFE
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resuAlmoco = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resuAlmoco['id'];
            $subUnidadeDescricao = $resuAlmoco['abreviatura'];

            $consultaCafeOfStSgt = $pdo->prepare("SELECT diasarranchado.cafe, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.cafe='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaCafeOfStSgt->bindParam(':doDia', $doDia);
            $consultaCafeOfStSgt->bindParam(':ateodia', $ateOdia);
            $consultaCafeOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaCafeOfStSgt->execute();
            $totalCafeOfStSgt = $consultaCafeOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalCafeOfStSgt";
            $totalGeralCafe += $totalCafeOfStSgt;
        }

        $html .= "</td>
    <td><CENTER>Café: $totalGeralCafe</td>
    </tr>
<tr>

<td>";
        /// CB/SD ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoCbSd = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoCbSd->bindParam(':doDia', $doDia);
            $consultaAlmocoCbSd->bindParam(':ateodia', $ateOdia);
            $consultaAlmocoCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoCbSd->execute();
            $totalAlmocoCbSd = $consultaAlmocoCbSd->rowCount();


            $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoCbSd";
            $totalGeralAlmoco += $totalAlmocoCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";
        /// OF ST SGT ALMOCO
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaAlmocoOfStSgt = $pdo->prepare("SELECT diasarranchado.almoco, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.almoco='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaAlmocoOfStSgt->bindParam(':doDia', $doDia);
            $consultaAlmocoOfStSgt->bindParam(':ateodia', $ateOdia);
            $consultaAlmocoOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaAlmocoOfStSgt->execute();
            $totalAlmocoOfStSgt = $consultaAlmocoOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalAlmocoOfStSgt";
            $totalGeralAlmoco += $totalAlmocoOfStSgt;
        }

        $html .= "</td>
    <td><CENTER>Almoço: $totalGeralAlmoco</td>
</tr>
<tr>

<td>";
        /// CB/SD JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaCbSd = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND (militares.grad ='Sd Ev' OR militares.grad ='Sd Ep' OR militares.grad ='Cb Ev' OR militares.grad ='Cb Ep') AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1' AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaCbSd->bindParam(':doDia', $doDia);
            $consultaJantaCbSd->bindParam(':ateodia', $ateOdia);
            $consultaJantaCbSd->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaCbSd->execute();
            $totalJantaCbSd = $consultaJantaCbSd->rowCount();


            $html .= "<CENTER>$subUnidadeDescricao: $totalJantaCbSd";
            $totalGeralJanta += $totalJantaCbSd;
        }
        $html .= "</td>";
        $html .= "<td>";

        /// OF ST SGT JANTA
        $consultar_subUnidades = $pdo->prepare("SELECT * FROM subunidades ORDER BY id ASC");
        $consultar_subUnidades->execute();
        while ($resu = $consultar_subUnidades->fetch(PDO::FETCH_ASSOC)) {
            $subUnidadeId        = $resu['id'];
            $subUnidadeDescricao = $resu['abreviatura'];

            $consultaJantaOfStSgt = $pdo->prepare("SELECT diasarranchado.janta, militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND militares.grad !='Sd Ev' AND militares.grad !='Sd Ep' AND militares.grad !='Cb Ev' AND militares.grad !='Cb Ep' AND diasarranchado.data>= :doDia AND diasarranchado.data<= :ateodia AND diasarranchado.janta='1'AND militares.subUnidade= :subUnidadeId ORDER BY ABS(numero);");
            $consultaJantaOfStSgt->bindParam(':doDia', $doDia);
            $consultaJantaOfStSgt->bindParam(':ateodia', $ateOdia);
            $consultaJantaOfStSgt->bindParam(':subUnidadeId', $subUnidadeId);
            $consultaJantaOfStSgt->execute();
            $totalJantaOfStSgt = $consultaJantaOfStSgt->rowCount();

            $html .= "<CENTER>$subUnidadeDescricao: $totalJantaOfStSgt";
            $totalGeralJanta += $totalJantaOfStSgt;
        }

        $html .= "</td>
    <td><CENTER>Janta: $totalGeralJanta</td>
</tr>

</table> ";
    }
    $stylesheet = '
    table{
        border-collapse: collapse;
        width: 100%;
        border: 1px solid black;
    }
    th{
        border: 1px solid black;
    }
    td{
        border: 1px solid black;
    }
 ';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    if ($tipoRelatorio == 'completo') {
        $titulo = "APROV - Militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada";
    } else {
        $titulo = "APROV - Quantitativo de militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada";
    }
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    //$mpdf->Output("relatorios/APROV - Militares arranchados do dia $data_inicio_formatada ao dia $data_fim_formatada.pdf", \Mpdf\Output\Destination::INLINE);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}

if ($acao == 'relatorio_aprovisionadores_arranchados' && $_SESSION['nivel'] >= 3) {
    $data_arranchamento = filter_input(INPUT_POST, 'dataArranchamento', FILTER_SANITIZE_STRING);
    $bateria       = filter_var($_SESSION['bateria'], FILTER_SANITIZE_NUMBER_INT);
    $gradFurriel   = filter_var($_SESSION['grad'], FILTER_SANITIZE_STRING);
    $nomedoFurriel = filter_var($_SESSION['UsuarioNome'], FILTER_SANITIZE_STRING);
    $bateriaX      = filter_var($_SESSION['bateriaString'], FILTER_SANITIZE_STRING);
    $data_inicio_formatada        = date('d-m-Y', strtotime($data_arranchamento));

    $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
    $html .= "<div  align='center'> <b> Militares do Aprov que se arrancharam para o dia $data_inicio_formatada .<BR> Furriel:  $gradFurriel $nomedoFurriel</div>";
    $consultar_arranchamentos = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao as SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :data_arranchamento AND militares.nivel='3' ORDER BY ABS(numero);");
    $consultar_arranchamentos->bindParam(':data_arranchamento', $data_arranchamento);
    $consultar_arranchamentos->execute();
    $TEM = $consultar_arranchamentos->rowCount();
    if ($TEM >= 1) {
        $html .= '<table>
        <tr>
        <td>Nome</td>
        <th>SU</th>
        <th>Café</th>
        <th>Almoço</th>
        <th>Janta</th>
        </tr>';

        while ($linha = $consultar_arranchamentos->fetch(PDO::FETCH_ASSOC)) {
            $id      = $linha['MILITAR_ID'];
            $nome    = $linha['nomeGuerra'];
            $numero  = $linha['numero'];
            $grad    = $linha['grad'];
            $bateria = $linha['SU_DESCRICAO'];

            $consultaC = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :data_arranchamento AND diasarranchado.cafe='1'AND militares.nivel='3' ORDER BY ABS(numero);");
            $consultaC->bindParam(':data_arranchamento', $data_arranchamento);
            $consultaC->execute();
            $totalCafe = $consultaC->rowCount();

            $consultaA   = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data=:data_arranchamento AND diasarranchado.almoco='1' AND militares.nivel='3' ORDER BY ABS(numero);");
            $consultaA->bindParam(':data_arranchamento', $data_arranchamento);
            $consultaA->execute();
            $totalAlmoco = $consultaA->rowCount();

            $consultaJ  = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data=:data_arranchamento AND diasarranchado.janta='1' AND militares.nivel='3' ORDER BY ABS(numero);");
            $consultaJ->bindParam(':data_arranchamento', $data_arranchamento);
            $consultaJ->execute();
            $totalJanta = $consultaJ->rowCount();

            if ($linha['cafe'] == '1') {
                $cafe = 'X';
            } else {
                $cafe = '-';
            }
            if ($linha['almoco'] == '1') {
                $almoco = 'X';
            } else {
                $almoco = '-';
            }
            if ($linha['janta'] == '1') {
                $janta = 'X';
            } else {
                $janta = '-';
            }
            $nomeGrande = mb_strtoupper($nome);
            $html .= " <tr>
        <td> $grad $numero $nomeGrande</td>
        <td> $bateria </td>
        <td> <center> $cafe </td>
        <td> <center> $almoco </td>
        <td> <center> $janta </td>
        </tr>";
        }

        $html .= '</table> <br> ';
        $html .= "<b>TOTAL:</b><br>
      Café: $totalCafe<br>
     Almoço: $totalAlmoco<br>
     Janta: $totalJanta
     ";
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados nessa data!', 'status' => 'warning'));
        return false;
    } ?>
    </tbody>
    </table>
<?php
    $stylesheet = '
table{
border-collapse: collapse;
width: 100%;
border: 1px solid black;
}
th{
border: 1px solid black;
}
td{
border: 1px solid black;
}
';
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    $titulo = "APROV - Militares do aprov arranchandos para o dia $data_inicio_formatada";
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}

if ($acao == 'relatorio_subunidade_arranchados' && $_SESSION['nivel'] == 2) {
    $doDia          = filter_input(INPUT_POST, 'dataArranchamento', FILTER_SANITIZE_STRING);
    $bateria       = filter_var($_SESSION['bateria'], FILTER_SANITIZE_NUMBER_INT);
    $gradFurriel   = filter_var($_SESSION['grad'], FILTER_SANITIZE_STRING);
    $nomedoFurriel = filter_var($_SESSION['UsuarioNome'], FILTER_SANITIZE_STRING);
    $bateriaX      = filter_var($_SESSION['bateriaString'], FILTER_SANITIZE_STRING);
    $data_inicio_formatada        = date('d-m-Y', strtotime($doDia));

    $html    = " <div  align='center'> <img src='" . $path . 'logo.jpg' . "' height='90' width='150'> </div>";
    $html .= "<div  align='center'> <b> Militares da $bateriaX que se arrancharam para o dia $data_inicio_formatada .<BR> Furriel:  $gradFurriel $nomedoFurriel</div>";
    $consulta_arranchados = $pdo->prepare('SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad, subunidades.descricao as SU_DESCRICAO, militares.id AS MILITAR_ID FROM militares JOIN subunidades on subunidades.id = militares.subUnidade INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia AND militares.subUnidade= :bateria ORDER BY ABS(numero);');
    $consulta_arranchados->bindParam(':doDia', $doDia);
    $consulta_arranchados->bindParam(':bateria', $bateria);
    $consulta_arranchados->execute();

    $TEM = $consulta_arranchados->rowCount();
    if ($TEM >= 1) {
        $html .= '
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Café</th>
                    <th>Almoço</th>
                    <th>Janta</th>
                </tr>
            </thead>
            <tbody>';

        while ($linha = $consulta_arranchados->fetch(PDO::FETCH_ASSOC)) {
            $id      = $linha['MILITAR_ID'];
            $nome    = $linha['nomeGuerra'];
            $numero  = $linha['numero'];
            $grad    = $linha['grad'];
            $bateria = $linha['SU_DESCRICAO'];

            $consultaC = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia AND militares.subUnidade= :bateria AND diasarranchado.cafe='1' ORDER BY ABS(numero);");
            $consultaC->bindParam(':doDia', $doDia);
            $consultaC->bindParam(':bateria', $bateria);
            $consultaC->execute();
            $totalCafe = $consultaC->rowCount();

            $consultaA   = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia AND militares.subUnidade= :bateria AND diasarranchado.almoco='1' ORDER BY ABS(numero);");
            $consultaA->bindParam(':doDia', $doDia);
            $consultaA->bindParam(':bateria', $bateria);
            $consultaA->execute();
            $totalAlmoco = $consultaA->rowCount();

            $consultaJ  = $pdo->prepare("SELECT diasarranchado.cafe,diasarranchado.almoco,diasarranchado.janta,  militares.id, militares.numero, militares.nomeGuerra, militares.subUnidade, militares.grad FROM militares INNER JOIN diasarranchado ON diasarranchado.militar=militares.id AND diasarranchado.data= :doDia AND militares.subUnidade= :bateria AND diasarranchado.janta='1' ORDER BY ABS(numero);");
            $consultaJ->bindParam(':doDia', $doDia);
            $consultaJ->bindParam(':bateria', $bateria);
            $consultaJ->execute();
            $totalJanta = $consultaJ->rowCount();

            if ($linha['cafe'] == '1') {
                $cafe = 'X';
            } else {
                $cafe = '-';
            }
            if ($linha['almoco'] == '1') {
                $almoco = 'X';
            } else {
                $almoco = '-';
            }
            if ($linha['janta'] == '1') {
                $janta = 'X';
            } else {
                $janta = '-';
            }
            $nomeGrande = mb_strtoupper($nome);
            $html .= " <tr>
    <td> $grad $numero $nomeGrande</td>
    <td> <center> $cafe </td>
    <td> <center> $almoco </td>
    <td> <center> $janta </td>
    </tr>";
        }

        $html .= '</tbody>
</table>
<br> ';
        $html .= "<b>TOTAL:</b><br>
  Café: $totalCafe<br>
 Almoço: $totalAlmoco<br>
 Janta: $totalJanta
 ";
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não há militares arranchados nessa data!', 'status' => 'warning'));
        return false;
    } ?>
    </tbody>
    </table>
<?php
    $stylesheet = '
    table{
        border-collapse: collapse;
        width: 100%;
        border: 1px solid black;
    }
    th{
        border: 1px solid black;
    }
    td{
        border: 1px solid black;
    }';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');
    $titulo = "Arranchamento da $bateriaX para o dia $data_inicio_formatada";
    $mpdf->SetTitle($titulo);
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html);
    ob_clean();
    $mpdf->Output($path . "relatorios/$titulo.pdf", \Mpdf\Output\Destination::FILE);
    $pdf = "relatorios/$titulo.pdf";
    echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'pdf' => $pdf, 'status' => 'success'));
}
