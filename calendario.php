<?php
//Definindo o dia e mes atual
$data = date('d-m-Y');

//transição de meses e anos pelos links
if (!isset($_GET['ano'])) {
    $_GET['ano'] = date('Y');
}

if (!isset($_GET['mes'])) {
    $_GET['mes'] = date('m');
}

if (!isset($_GET['semana'])) {
    $_GET['semana'] = date('W');
}


$mes = (int) $_GET['mes'];
$ano = (int) $_GET['ano'];
$semana = (int) $_GET['semana'];

if ($mes == 12) {
    $prox_mes = "01";
    $prox_ano = $ano + 1;
} else {

    $prox_mess = $mes + 1;
    $prox_ano  = $ano;

    $mess = strlen($prox_mess);

    if ($mess == 1) {
        $prox_mes = "0$prox_mess";
    } else {
        $prox_mes = $prox_mess;
    }
}

if ($mes == 01) {
    $mes_ant = "12";
    $ano_ant = $ano - 1;
} else {
    $mes_antt = $mes - 1;
    $ano_ant  = $ano;

    $mess2 = strlen($mes_antt);

    if ($mess2 == 1) {
        $mes_ant = "0$mes_antt";
    } else {
        $mes_ant = $mes_antt;
    }
}
//Fim da transição de meses e anos
//Definição dos dias e meses para montar o calendário

if ($mes == 1) {
    $dias    = 31;
    $nomemes = "Janeiro";
}

if ($mes == 2) {
    //Verificando se o ano é bissexto
    if ((($ano % 4) == 0 && ($ano % 100) != 0) || ($ano % 400) == 0) {
        $dias = 29;
    } else {
        $dias = 28;
    }
    $nomemes = "Fevereiro";
}

if ($mes == 3) {
    $dias    = 31;
    $nomemes = "Março";
}

if ($mes == 4) {
    $dias    = 30;
    $nomemes = "Abril";
}

if ($mes == 5) {
    $dias    = 31;
    $nomemes = "Maio";
}

if ($mes == 6) {
    $dias    = 30;
    $nomemes = "Junho";
}

if ($mes == 7) {
    $dias    = 31;
    $nomemes = "Julho";
}

if ($mes == 8) {
    $dias    = 31;
    $nomemes = "Agosto";
}

if ($mes == 9) {
    $dias    = 30;
    $nomemes = "Setembro";
}

if ($mes == 10) {
    $dias    = 31;
    $nomemes = "Outubro";
}

if ($mes == 11) {
    $dias    = 30;
    $nomemes = "Novembro";
}

if ($mes == 12) {
    $dias    = 31;
    $nomemes = "Dezembro";
}
//Fim da definição de dias e meses