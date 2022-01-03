<?php
require __DIR__ . '/vendor/autoload.php';
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
define('OS_HOST', 'LINUX'); /// WIN // LINUX
$path = __DIR__ . '/';
define('VERSAO', MD5(1.81));
/* alterar conforme a necessidade de gerar relatórios, testei com um relatório de 6 meses

Consulta no mysql: (37545 total, A consulta demorou 4,5228 segundos.)
Total em páginas: 835
Demorou cerca de 5 min para completar, e utilizou 2048mb de memória ram
O tamanho total do arquivo ficou de 5.6 mb

*/

ini_set('max_execution_time', '600'); /// tempo máximo de execução em segundos
ini_set('pcre.backtrack_limit', '15200000'); /// tamanho máximo do html que o MPDF vai criar para gerar o PDF
ini_set('memory_limit', '2560M'); /// máximo de ram

$local = false;
if ($local) {
    error_reporting(E_ALL);
    define('SITE_NOME', "Arranchamento- LOCAL");
    define('SITE_URL', "http://www.localhost/Arranchamento");
    define('BANCO_HOST', "localhost");
    define('BANCO_TABELA', "arranchamento");
    define('BANCO_USUARIO', "root");
    define('BANCO_SENHA', "");

    define('DIAS_ANTECEDENCIA', 3); // O militar só vai conseguir se arranchar x-1 dias após a data atual. No caso, 2 dias
    define('DIAS_PARA_ARRANCHAR', 15); /// HABILITA PARA O militar se arranchar para x-1 dias a frente. No caso, 14 dias
    define('EXIBIR_CARDAPIO', true);
    define('CARDAPIO_CB_SD', 'cardapios/cardapiocbsd.pdf'); /// caminho/nomedoPDF para onde será feito os uploads
    define('CARDAPIO_OF_ST_SGT', 'cardapios/cardapioof.pdf'); /// caminho/nomedoPDF para onde será feito os uploads

} else {
    error_reporting(0);
    define('SITE_NOME', "Arranchamento");
    define('SITE_URL', "http://www.localhost/Arranchamento");
    define('BANCO_HOST', "localhost");
    define('BANCO_TABELA', "arranchamento");
    define('BANCO_USUARIO', "usuario");
    define('BANCO_SENHA', "senha");

    define('DIAS_ANTECEDENCIA', 3); // O militar só vai conseguir se arranchar x-1 dias após a data atual. No caso, 2 dias
    define('DIAS_PARA_ARRANCHAR', 15); /// HABILITA PARA O militar se arranchar para x-1 dias a frente. No caso, 14 dias
    define('EXIBIR_CARDAPIO', true);
    define('CARDAPIO_CB_SD', 'cardapios/cardapiocbsd.pdf'); /// caminho/nomedoPDF para onde será feito os uploads
    define('CARDAPIO_OF_ST_SGT', 'cardapios/cardapioof.pdf'); /// caminho/nomedoPDF para onde será feito os uploads

}

try {
    $pdo = new PDO(
        'mysql:host=' . BANCO_HOST . ';dbname=' . BANCO_TABELA . '',
        BANCO_USUARIO,
        BANCO_SENHA,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
} catch (PDOException $e) {
    echo "Falha ao conectar com o banco de dados: " . $e->getMessage();
    die;
}

require_once('calendario.php');
