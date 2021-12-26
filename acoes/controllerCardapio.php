<?php
require_once "../config.php";
require_once "../checarSessao.php";
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
$acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

if ($acao == "enviar_cardapio") {
    if (isset($_FILES['pdf']['name']) && $_FILES['pdf']['error'] == 0) {
        $arquivo_tmp = $_FILES['pdf']['tmp_name'];
        $nomePDF     = $_FILES['pdf']['name'];
        $extensao = strtolower(pathinfo($nomePDF, PATHINFO_EXTENSION));
        if (strstr('.pdf;.PDF;.Pdf;.pdF', $extensao)) {
            $destino = $tipo == "cb_sd" ? $path . CARDAPIO_CB_SD : $path . CARDAPIO_OF_ST_SGT;
            if (@move_uploaded_file($arquivo_tmp, $destino)) {
                echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'status' => 'success'));
                return false;
            } else {
                echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'PASTA cardapios sem permissão de escrita.', 'status' => 'error'));
                return false;
            }
        } else {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Envie apenas arquivos .PDF', 'status' => 'error'));
            return false;
        }
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Envie apenas arquivos .PDF', 'status' => 'error'));
        return false;
    }
}
