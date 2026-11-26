<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Ação inválida!");
}

$id = filter_var($_POST["id"], FILTER_VALIDATE_INT);

if (!$id) {
    die("ID inválido!");
}

$sql = "DELETE FROM eventos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

header("Location: index.php");
exit;
?>