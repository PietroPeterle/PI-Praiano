<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"]);
    $descricao = trim($_POST["descricao"]);
    $data = $_POST["data_evento"];
    $preco = $_POST["preco"];

    if ($titulo === "" || $data === "" || $preco === "") {
        $mensagem = "Preencha todos os campos obrigatórios!";
    } else {
        $sql = "INSERT INTO eventos (titulo, descricao, data_evento, preco) VALUES (:titulo, :descricao, :data, :preco)";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data' => $data,
            ':preco' => $preco
        ]);

        $mensagem = "Eventos cadastrados com sucesso!";
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>Cadastrar evento</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="text" name="titulo" placeholder="Nome do evento" required>

        <textarea name="descricao" placeholder="Descrição do evento"></textarea>

        <label>Data do evento:</label>
        <input type="date" name="data_evento" required>

        <label>Preço:</label>
        <input type="number" name="preco" step="0.01" min="0" placeholder="Ex: 50.00" required>

        <button type="submit">Cadastrar evento</button>
    </form>

    <a href="index.php">Voltar ao painel</a>
</section>

<?php include '../includes/rodape.php'; ?>