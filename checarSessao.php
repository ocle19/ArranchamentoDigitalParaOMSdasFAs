<?php
if (!isset($_SESSION)) {
    session_start();
}

$militar_logado_id = filter_var($_SESSION['idUsuario'], FILTER_SANITIZE_NUMBER_INT);
$nivel = filter_var($_SESSION['nivel'], FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['sessiontime'])) {

    if ($_SESSION['sessiontime'] < time()) {
        session_unset();
        session_destroy();
        header("Location: login.php");
    } else {
        $_SESSION['sessiontime'] = time() + 60 * 30;
    }
} else {
    session_unset();
    session_destroy();
    header("Location: login.php");
}

if (!isset($_SESSION['idUsuario']) && !isset($_SESSION['nivel'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
}
