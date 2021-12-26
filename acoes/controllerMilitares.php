<?php
require_once "../config.php";
require_once "../checarSessao.php";

$acao = $_POST['acao'];

if ($acao == 'editar_militar') {
    if ($nivel >= 2) {
        $militar_alterado_id     = filter_input(INPUT_POST, 'militar_id', FILTER_SANITIZE_NUMBER_INT);
        $numero       = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING);
        $nomeGuerra   = filter_input(INPUT_POST, 'nomeguerra', FILTER_SANITIZE_STRING);
        $nomeCompleto = filter_input(INPUT_POST, 'nomecompleto', FILTER_SANITIZE_STRING);
        $subUnidade    = filter_input(INPUT_POST, 'subunidade', FILTER_SANITIZE_STRING);
        $graduacao    = filter_input(INPUT_POST, 'grad', FILTER_SANITIZE_STRING);
        $senha        = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
        $nivel_militar_editar        = filter_input(INPUT_POST, 'nivel', FILTER_SANITIZE_STRING);
        $status       = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

        if ($militar_alterado_id <= 0) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Identificador inválido!', 'status' => 'error'));
            return false;
        }
        if (empty($numero)) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo número.', 'status' => 'error'));
            return false;
        }
        if (empty($nomeGuerra)) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo nome de guerra.', 'status' => 'error'));
            return false;
        }
        if (empty($nomeCompleto)) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo nome completo.', 'status' => 'error'));
            return false;
        }

        if ($subUnidade < 0) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma subUnidade.', 'status' => 'error'));
            return false;
        }
        if (empty($graduacao)) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma graduação.', 'status' => 'error'));
            return false;
        }
        if (empty($senha)) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma senha.', 'status' => 'error'));
            return false;
        }
        if (empty($nivel_militar_editar) || $nivel_militar_editar <= 0) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione um nivel.', 'status' => 'error'));
            return false;
        }
        try {
            $atualizarDados = $pdo->prepare(
                'UPDATE militares SET nivel = :nivel, nomeGuerra = :nomeGuerra,
        numero = :numero, nomeCompleto = :nomeCompleto, subUnidade = :subUnidade,
        grad = :grad, senha = :senha, `status` = :statusMilitar WHERE id = :militar_alterado_id'
            );

            $atualizarDados->bindParam(':nivel', $nivel_militar_editar);
            $atualizarDados->bindParam(':nomeGuerra', $nomeGuerra);
            $atualizarDados->bindParam(':numero', $numero);
            $atualizarDados->bindParam(':nomeCompleto', $nomeCompleto);
            $atualizarDados->bindParam(':subUnidade', $subUnidade);
            $atualizarDados->bindParam(':grad', $graduacao);
            $atualizarDados->bindParam(':senha', $senha);
            $atualizarDados->bindParam(':statusMilitar', $status);
            $atualizarDados->bindParam(':militar_alterado_id', $militar_alterado_id);
            if ($atualizarDados->execute()) {
                echo json_encode(array('resposta' => 'Sucesso!', 'mensagem' => 'O ' . $graduacao . ' ' . $nomeGuerra . ' foi modificado!', 'status' => 'success'));
                return false;
            } else {
                echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Não foi possível alterar os dados do ' . $graduacao . ' ' . $nomeGuerra . ', tente novamente.', 'status' => 'error'));
                return false;
            }
        } catch (PDOException $e) {
            $erro = $e->getMessage();
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => $erro . ', tente novamente.', 'status' => 'error'));
            return false;
        }
    } else {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Você não tem permissão!', 'status' => 'error'));
    }
}

if ($acao == 'cadastrar_militar') {

    $nomeGuerra   = filter_input(INPUT_POST, 'nomeGuerra', FILTER_SANITIZE_STRING);
    $nomeCompleto = filter_input(INPUT_POST, 'nomeCompleto', FILTER_SANITIZE_STRING);
    $numero       = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_STRING);
    $subUnidade    = filter_input(INPUT_POST, 'subUnidade', FILTER_SANITIZE_STRING);
    $graduacao    = filter_input(INPUT_POST, 'grad', FILTER_SANITIZE_STRING);
    $laranjeira   = filter_input(INPUT_POST, 'laranjeira', FILTER_SANITIZE_STRING);

    if (empty($numero)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo número.', 'status' => 'error'));
        return false;
    }
    if (empty($nomeGuerra)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo nome de guerra.', 'status' => 'error'));
        return false;
    }
    if (empty($nomeCompleto)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Preencha o campo nome completo.', 'status' => 'error'));
        return false;
    }

    if ($subUnidade < 0) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma subUnidade.', 'status' => 'error'));
        return false;
    }
    if (empty($graduacao)) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma graduação.', 'status' => 'error'));
        return false;
    }
    if ($laranjeira < 0) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione se o militar é laranjeira.', 'status' => 'error'));
        return false;
    }

    $consulta = $pdo->prepare("SELECT * FROM militares WHERE nomeCompleto = :nomeCompleto and status ='ATIVADO'");
    $consulta->bindParam(':nomeCompleto', $nomeCompleto);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Militar já cadastrado.', 'status' => 'warning'));
        return false;
    } else {

        try {
            $insert = $pdo->prepare('insert into militares (nomeGuerra, nomeCompleto, numero, subUnidade, status, grad, senha, nivel, laranjeira) values (:nome, :nomeC, :numero, :subunidade, "ATIVADO", :graduacao, :nome, "1", :laranjeira)');
            $insert->bindParam(':nome', $nomeGuerra);
            $insert->bindParam(':nomeC', $nomeCompleto);
            $insert->bindParam(':numero', $numero);
            $insert->bindParam(':subunidade', $subUnidade);
            $insert->bindParam(':graduacao', $graduacao);
            $insert->bindParam(':laranjeira', $laranjeira);
            $insert->execute();

            echo json_encode(array('resposta' => 'Sucesso!', 'mensagem' => 'Militar cadastrado.', 'status' => 'success'));
            return false;
        } catch (PDOException $e) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => $e->getMessage(), 'status' => 'error'));
            return false;
        }
    }
}

if ($acao == 'trocar_senha') {
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $nova  = filter_input(INPUT_POST, 'nova_senha', FILTER_SANITIZE_STRING);
    $nova_r = filter_input(INPUT_POST, 'nova_senha_r', FILTER_SANITIZE_STRING);

    $consultaNomeGuerra = $pdo->prepare("SELECT * FROM militares WHERE nomeGuerra = :nomeGuerra AND id = :id AND STATUS ='ATIVADO'");
    $consultaNomeGuerra->bindParam(':id', $militar_logado_id);
    $consultaNomeGuerra->bindParam(':nomeGuerra', $nova);
    $consultaNomeGuerra->execute();

    if ($consultaNomeGuerra->rowCount() > 0) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Você não pode deixar a senha igual o seu nome de guerra!.', 'status' => 'warning'));
        return false;
    } else {
        if ($nova != $nova_r) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'A nova senha não confere com a repetição.', 'status' => 'error'));
            return false;
        }
        if (strlen($nova) < 5) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Digite uma senha com no mínimo 5 digitos.', 'status' => 'error'));
            return false;
        }
        $consultaSenhaAtual = $pdo->prepare("SELECT * FROM militares WHERE senha = :senha AND id = :id AND STATUS ='ATIVADO'");
        $consultaSenhaAtual->bindParam(':id', $militar_logado_id);
        $consultaSenhaAtual->bindParam(':senha', $senha);
        $consultaSenhaAtual->execute();

        if ($consultaSenhaAtual->rowCount() <= 0) {
            echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'A senha atual está incorreta.', 'status' => 'warning'));
            return false;
        } else {
            $atualizaSenha = $pdo->prepare("UPDATE militares SET senha = :nova WHERE id = :id");
            $atualizaSenha->bindParam(':id', $militar_logado_id);
            $atualizaSenha->bindParam(':nova', $nova);

            if ($atualizaSenha->execute()) {
                $_SESSION['senha'] = $nova;
                echo json_encode(array('resposta' => 'Sucesso!', 'mensagem' => 'Senha alterada!', 'status' => 'success'));
                return false;
            } else {
                echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Erro ao alterar a senha!', 'status' => 'error'));
                return false;
            }
        }
    }
}
