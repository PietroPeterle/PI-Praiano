<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    die("Produto inválido!");
}

$sql = "SELECT * FROM produtos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

$produto = $comando->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado!");
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $preco = $_POST["preco"];
    $categoria = trim($_POST["categoria"]);

    if ($nome === "" || $descricao === "" || $preco === "" || $categoria === "") {
        $mensagem = "Preencha os campos obrigatórios!";
    } elseif (!is_numeric($preco) || $preco < 0) {
        $mensagem = "Informe um preço válido!";
    } else {
        $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, categoria = :categoria WHERE id = :id";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':categoria' => $categoria,
            ':id' => $id
        ]);

        header("Location: index.php");
        exit;
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>Editar produto</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <textarea name="descricao"><?php echo htmlspecialchars($produto['descricao'] ?? ''); ?></textarea>

        <label>Preço:</label>
        <input type="number" name="preco" step="0.01" min="0" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>

        <input type="text" name="categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>" required>

        <button type="submit">Salvar alterações</button>
    </form>

    <p><a href="index.php">Voltar ao painel</a></p>
</section>

<?php include '../includes/rodape.php'; ?>