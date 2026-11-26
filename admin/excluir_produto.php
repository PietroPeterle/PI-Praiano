<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Ação inválida!");
}

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    die("Produto inválido!");
}

$sql = "DELETE FROM produtos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

header("Location: index.php");
exit;
?>
