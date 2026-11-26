
<?php
require_once '../protecao.php';
require_once '../conexao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Ação inválida.");
}

if (
    !isset($_POST['token']) ||
    !isset($_SESSION['token_mensagens']) ||
    !hash_equals($_SESSION['token_mensagens'], $_POST['token'])
) {
    exit("Solicitação inválida.");
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit("Mensagem inválida.");
}

$sql = "DELETE FROM mensagens WHERE id = :id";

$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

header("Location: index.php");
exit;
?>
