<?php
require_once '../config.php';

$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
$graduacao = filter_input(INPUT_POST, 'grad', FILTER_SANITIZE_STRING);

if (empty($usuario)) {
    echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Digite o nome de guerra!', 'status' => 'error'));
    return false;
}
if (empty($senha)) {
    echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Digite uma senha!', 'status' => 'error'));
    return false;
}
if ($graduacao == "Selecione" or empty($graduacao)) {
    echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Selecione uma graduação!', 'status' => 'error'));
    return false;
}

try {
    $consulta = $pdo->prepare("SELECT *, SU.id as SU_ID, SU.descricao as SU_DESCRICAO, M.id as MILITAR_ID  from militares as M join subunidades as SU ON SU.id = M.subUnidade WHERE M.nomeGuerra = :usuario and M.senha = :senha and M.grad = :graduacao limit 1");
    $consulta->bindParam(':usuario', $usuario);
    $consulta->bindParam(':senha', $senha);
    $consulta->bindParam(':graduacao', $graduacao);
    $consulta->execute();

    if ($consulta->rowCount() != 1) {
        echo json_encode(array('resposta' => 'Oops', 'mensagem' => 'Credenciais incorretas.', 'status' => 'error'));
    } else {
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['idUsuario'] = $linha['MILITAR_ID'];
        $_SESSION['UsuarioNome'] = $linha['nomeGuerra'];
        $_SESSION['senha'] = $linha['senha'];
        $_SESSION['nivel'] = $linha['nivel'];
        $_SESSION['bateria'] = $linha['subUnidade'];
        $_SESSION['grad'] = $linha['grad'];
        $_SESSION['laranjeira'] = $linha['laranjeira'];
        $_SESSION['especial'] = $linha['especial'];
        $_SESSION['bateriaString'] = $linha['SU_DESCRICAO'];
        $_SESSION['sessiontime'] = time() + 60 * 10;
        echo json_encode(array('resposta' => 'Sucesso', 'mensagem' => 'ok', 'status' => 'success', 'irpara' => 'index.php'));
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
