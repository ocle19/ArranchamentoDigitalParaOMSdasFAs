<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo SITE_NOME; ?></title>
    <link rel="icon" href="favicon.ico">
    <link rel="shortcut icon" href="favicon.ico" title="Favicon" />
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/estilo.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/font-awesome/css/font-awesome.min.css">
    <script src="assets/js/swal.js"></script>
    <script src="assets/js/scripts.js?<?php echo VERSAO; ?>" defer></script>
    <script src="assets/js/jquery.min.js" defer></script>

</head>

<body>
    <div class="container">
        <h3 align="center"><?php echo SITE_NOME; ?></h3></br>

        <div class="row">
            <div class="col-lg-8">
                <center>
                    <H4>AVISOS</H4>
                </center>
                <ul class="list-group">
                    <li class="list-group-item list-group-item-danger">Caso não consiga acessar o sistema, contate o
                        Furriel de sua SU. <br>
                        <p><B>A RESPONSABILIDADE DE MANTER OS DADOS DOS MILITARES ATUALIZADOS É DE CADA UM DOS
                                FURRIÉIS</B><br>
                            Os dados são utilizados por outros sistemas da OM </p>


                        Furriéis: <br>
                        <?php
                        $consultarDadosMilitar = $pdo->prepare(
                            'SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO  FROM militares as M JOIN subunidades as SU ON SU.id = M.subUnidade WHERE nivel =2 order by M.subUnidade'
                        );
                        $consultarDadosMilitar->execute();

                        while ($resu = $consultarDadosMilitar->fetch(PDO::FETCH_ASSOC)) {
                            $numero        = $resu['numero'];
                            $nomeCompleto  = $resu['nomeCompleto'];
                            $nomeGuerra    = $resu['nomeGuerra'];
                            $bateria       = $resu['SU_ID'];
                            $status        = $resu['status'];
                            $graduacao     = $resu['grad'];
                            $bateriastring = $resu['SU_DESCRICAO'];

                            echo ($bateriastring) . ": " . ($graduacao) . " " . (mb_strtoupper($nomeGuerra)) . "<br>";
                        }

                        ?>
                        <br>
                        <b>Caso tenha mudanças nas funções, o furriel que está saindo poderá dar as permissões para o
                            próximo através do menu ALTERAR MILITAR.</b>
                    </li>
                    <li class="list-group-item list-group-item-default">Sexta, Sábado e Domingo, ALMOÇO e JANTA estará
                        disponível somente para os LARANJEIRAS e a Gu Sv (FURRIEL PODE ARRANCHAR).
                    </li>
                    <li class="list-group-item list-group-item-warning">Qualquer militar pode se arranchar e se
                        desaranchar <b>com no minimo 2 dias de antecedência </b> a data desejada!
                    </li>
                    <li class="list-group-item list-group-item-info">Existem 3 tipos de perfis:<br>
                        <p>1º -> Perfil COMUM: Pode se arranchar usando seu usuário e senha ( TODOS OS MILITARES DA OM
                            ESTÃO REGISTRADOS ).<br>
                            2º -> Perfil FURRIEL. Pode arranchar TODOS os militares da sua SU e a si mesmo ( arrancha os
                            militares que não tem acesso ao sistema ), tem a lista dos militares da bateria que estão
                            arranchados para determinadas datas e pode ADICIONAR NOVOS MILITARES.<br>
                            3º -> Perfil APROVISIONADOR. Tem acesso ao controle de militares arranchados para cada dia,
                            tiragem de faltas e relatórios.<br>
                        </p>
                    </li>
                </ul>
            </div>


            <div class="col-lg-4">
                <form class="form" id="form_login" action="" method="post">
                    <div align="center">
                        <h4>Entre com suas credenciais</h4>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user fa-fw">
                                </i>
                            </span>
                            <select class="form-control" name="grad" id="grad" required autofocus>
                                <optgroup label="Praça">
                                    <option value="">Selecione um</option>
                                    <option value="Sd Ev">Soldado EV</option>
                                    <option value="Sd Ep">Soldado EP</option>
                                    <option value="Cb Ev">Cabo EV</option>
                                    <option value="Cb Ep">Cabo EP</option>
                                    <option value="Al">Aluno CFST</option>
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

                            <input type="text" maxlength="25" name="usuario" class="form-control" placeholder="Nome Guerra" required autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key fa-fw">
                                </i>
                            </span>
                            <input type="password" maxlength="25" name="senha" class="form-control" placeholder="Senha" required />

                        </div>
                        A senha inicial é o Nome Guerra!
                    </div>
                    <button id="btn_logar" class="btn btn-primary btn-lg btn-block">Entrar</button>
                    <br>
                    <?php if (EXIBIR_CARDAPIO) { ?>
                        <a type="button" href="<?php echo CARDAPIO_CB_SD ?>" target="A_BLANK" class="btn btn-success btn-lg btn-block">CARDÁPIO DA SEMANA CB/SD</a>
                        <a type="button" href="<?php echo CARDAPIO_OF_ST_SGT ?>" target="A_BLANK" class="btn btn-success btn-lg btn-block">CARDÁPIO DA SEMANA OF/ST/SGT </a>
                    <?php } ?>
                </form>
            </div>
        </div>

    </div>

    <h6 align="center"><a target="a_blank" rel="nofollow noreferrer noopener external" href="https://clebersiqueira.com.br">Desenvolvido por Cleber Siqueira.</a></h6>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- require_once all compiled plugins (below), or require_once individual files as needed -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>