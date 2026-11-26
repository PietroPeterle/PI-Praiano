<?php
session_start();
require_once '../conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $comando = $conexao->prepare($sql);
    $comando->execute([':email' => $email]);

    $usuario = $comando->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["usuario_nome"] = $usuario["nome"];
        $_SESSION["usuario_tipo"] = $usuario["tipo"];

        header("Location: ../index.php");
        exit;

    } else {
        $mensagem = "E-mail ou senha incorretos!";
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>Entrar no Praiano</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="email" name="email" placeholder="Seu e-mail" required>

        <input type="password" name="senha" placeholder="Sua senha" required>

        <button type="submit">Entrar</button>
    </form>
</section>

<?php include '../includes/rodape.php'; ?>