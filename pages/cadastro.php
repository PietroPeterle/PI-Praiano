<?php
require_once '../conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if ($nome == "" || $email == "" || $senha == "") {
        $mensagem = "Preencha todos os campos!";
    } else {
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_criptografada
            ]);

            $mensagem = "Cadastro realizado com sucesso!";
        } catch (PDOException $erro) {
            $mensagem = "Esse e-mail já está cadastrado!";
        }
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>Crie sua conta no Praiano</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="text" name="nome" placeholder="Seu nome" required>

        <input type="email" name="email" placeholder="Seu e-mail" required>

        <input type="password" name="senha" placeholder="Sua senha" required>

        <button type="submit">Cadastrar</button>
    </form>
</section>

<?php include '../includes/rodape.php'; ?>