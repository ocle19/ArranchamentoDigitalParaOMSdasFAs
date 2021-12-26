<?php
require_once "config.php";
require_once "checarSessao.php";

$militar_logado_id = filter_var($_SESSION['idUsuario'], FILTER_SANITIZE_NUMBER_INT);
$nomee     = filter_var($_SESSION['UsuarioNome'], FILTER_SANITIZE_STRING);
$nivel     = filter_var($_SESSION['nivel'], FILTER_SANITIZE_NUMBER_INT);
if ($nivel == 1) {
    $permitidos = array('visualizaCalendario', 'novoMilitar', 'trocarSenha');
}

if ($nivel == 2) {
    $permitidos = array('editarMilitar', 'visualizaCalendario', 'listarMilitares', 'novoMilitar', 'trocarSenha');
}

if ($nivel == 3) {
    $permitidos = array('enviarCardapio', 'editarMilitar', 'gerarRelatorios', 'visualizaCalendario', 'listarMilitares', 'visualizarCalendario', 'visualizaQuantidade', 'visualizaQuantidadeTotal', 'listarArranchadosDaRefeicaoDoDiaParaTirarFalta', 'listarTiragemFalta', 'listarArranchadosDaRefeicaoDoDia', 'trocarSenha', 'novoMilitar');
}

if ($nivel == 4) {
    $permitidos = array('enviarCardapio', 'editarMilitar', 'visualizaCalendario', 'listarMilitares', 'visualizaQuantidade', 'listarUsuario', 'trocarSenha');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SITE_NOME; ?></title>
    <link rel="icon" href="favicon.ico">
    <link rel="shortcut icon" href="favicon.ico" title="Favicon" />
    <link rel="stylesheet" href="assets/css/font-awesome/css/AdminLTE.min.css">
    <link type="text/css; " href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css;" rel="stylesheet" href="assets/css/font-awesome/css/font-awesome.min.css">
    <script src="assets/js/swal.js"></script>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if ($nivel == 1) { ?>
                        <li><a href="?pagina=visualizaArranchamentoIndividual">Arranchamento</a></li>
                        <li><a href="?pagina=trocarSenha">Alterar senha</a></li>
                    <?php } ?>

                    <?php if ($nivel == 2) { ?>
                        <li><a href="?pagina=listarMilitares">Arranchar militares</a></li>
                        <li><a href="?pagina=novoMilitar">Adicionar novo militar</a></li>
                        <li><a href="?pagina=editarMilitar">EDITAR MILITAR / TROCAR SENHA DOS MILITARES</a></li>
                        <li><a href="?pagina=trocarSenha">Alterar senha</a></li>
                    <?php } ?>

                    <?php if ($nivel == 3) { ?>
                        <li><a href="?pagina=trocarSenha">Alterar senha</a></li>
                        <li><a href="?pagina=novoMilitar">Adicionar novo militar</a></li>
                        <li><a href="?pagina=visualizaQuantidade">Ver Arranchamentos</a></li>
                        <li><a href="?pagina=enviarCardapio">Enviar Cardápios</a></li>
                        <li><a href="?pagina=gerarRelatorios">Gerar relatórios</a></li>
                        <li><a href="?pagina=listarMilitares">ARRANCHAR MILITARES DO APROV</a></li>
                        <li><a href="?pagina=editarMilitar">EDITAR MILITAR / TROCAR SENHA</a></li>
                    <?php } ?>
                    <li><a href="logout.php?token=<?php echo md5(session_id()); ?>">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Fim do menu-->

    <?php
    //Verificando se a variavel $_GET['pagina'] existe e se ela faz parte da lista de arquivos permitidos
    if (isset($_GET['pagina']) && (array_search($_GET['pagina'], $permitidos) !== false)) {
        //Pega o valor da variavel $_GET['pagina']
        $arquivo = $_GET['pagina'];
    } else {

        if ($nivel == 1) {

            $arquivo = 'visualizaArranchamentoIndividual';
        }
        if ($nivel == 2) {
            $arquivo = 'listarMilitares';
        }
        if ($nivel == 3) {
            $arquivo = 'visualizaQuantidade';
        }
    }
    if (mb_strtoupper(filter_var($_SESSION['senha'], FILTER_SANITIZE_STRING)) == mb_strtoupper(filter_var($_SESSION['UsuarioNome'], FILTER_SANITIZE_STRING))) {
        echo "<center><div class='alert alert-danger'>TROQUE SUA SENHA PARA USAR O SISTEMA!</div></center>";
        //    $arquivo = 'trocarSenha';
        require_once "paginas/trocarSenha.php"; //Incluir o arquivo
    } else {

        require_once "paginas/" . $arquivo . ".php"; //Incluir o arquivo
    }
    ?>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/scripts.js?<?php echo VERSAO; ?>" defer></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>