<head>
    <title> Trocar senha</title>
</head>

<body>
    <div class="container theme-showcase" role="main">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <center>Trocar senha</center>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="form_trocar_senha" action="" enctype="multipart/form-data" method="POST">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <p class="text-center">Use o formulário abaixo para alterar sua senha. Sua senha não
                                        pode ser igual o seu nome de guerra.</p>
                                    <input type="password" class="input-lg form-control" name="senha" id="senha" placeholder="Senha atual" autocomplete="off">
                                    <br>
                                    <input type="password" class="input-lg form-control" name="nova_senha" id="nova_senha" placeholder="Nova senha" autocomplete="off">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <br>
                                        </div>
                                    </div>
                                    <input type="password" class="input-lg form-control" name="nova_senha_r" id="nova_senha_r" placeholder="Repita a senha" autocomplete="off">

                                    <div class="row" id="erro_repeticao_senha" style="display:none">
                                        <div class="col-sm-12" style="margin: 10px">
                                            <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span><span id="txtpwmatch"></>
                                        </div>
                                    </div>
                                    <input type="hidden" name="acao" value="trocar_senha">
                                    <input type="buttom" id="btn_trocar_senha" name="trocar" class="col-xs-12 btn btn-primary btn-load btn-lg" value="Alterar senha" style="margin-top:10px">
                                </div>
                                <!--/col-sm-6-->
                            </div>
                            <!--/row-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>