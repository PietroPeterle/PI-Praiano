<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $preco = $_POST["preco"];
    $categoria = trim($_POST["categoria"]);


    if ($nome === "" || $preco === "" || $categoria === "") {
        $mensagem = "Preencha os campos obrigatórios!";
    } elseif (!is_numeric($preco) || $preco < 0) {
        $mensagem = "Informe um preço válido!";
    } else {
        $sql = "INSERT INTO produtos (nome, descricao, preco, categoria) VALUES (:nome, :descricao, :preco, :categoria)";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':categoria' => $categoria
        ]);

        $mensagem = "Produto cadastrado com sucesso!";
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>
        Cadastrar produto
    </h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do produto" required>

        <textarea name="descricao" placeholder="Descrição do produto"></textarea>

        <input type="number" name="preco" step="0.01" min="0" placeholder="Preço" required>

        <input type="text" name="categoria" placeholder="Categoria" required>

        <button type="submit">Cadastrar produto</button>
    </form>

    <p><a href="index.php">Voltar ao painel</a></p>
</section>

<?php include '../includes/rodape.php'; ?>