<?php

require_once '../protecao.php';
require_once '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: minhas_reservas.php');
    exit;
}

if (!isset($_POST['token']) || 
    !isset($_SESSION['token_cancelamento']) ||
    $_POST['token'] !== $_SESSION['token_cancelamento']) {
    exit('Solicitação inválida.');
}

$reserva_id = filter_input(INPUT_POST, 'reserva_id', FILTER_VALIDATE_INT);

if (!$reserva_id) {
    header('Location: minhas_reservas.php');
    exit;
}

$sql = "DELETE FROM reservas WHERE id = :id AND usuario_id = :usuario_id";

$comando = $conexao->prepare($sql);

$comando->execute([
    ':id' => $reserva_id, ':usuario_id' => $_SESSION['usuario_id']
]);

header('Location: minhas_reservas.php');
exit;
?>