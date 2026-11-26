<?php
require_once '../protecao.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

require_once '../conexao.php';

if (!isset($_GET["id"])) {
    die("Evento não encontrado!");
}

$id = filter_var($_GET["id"], FILTER_VALIDATE_INT);

if (!$id) {
    die("ID inválido!");
}

$sql = "SELECT * FROM eventos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

$evento = $comando->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    die("Evento não encontrado!");
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST["titulo"]);
    $descricao = trim($_POST["descricao"]);
    $data = $_POST["data_evento"];
    $preco = $_POST["preco"];

    if ($titulo === "" || $data === "" || $preco === "") {
        $mensagem = "Preencha os campos obrigatórios!";
    } elseif (!is_numeric($preco) || $preco < 0) {
        $mensagem = "Informe um preço válido!";
    } else {
        $sql = "UPDATE eventos SET titulo = :titulo, descricao = :descricao, data_evento = :data, preco = :preco WHERE id = :id";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':data' => $data,
            ':preco' => $preco,
            ':id' => $id
        ]);

        header("Location: index.php");
        exit;
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">
    <h1>Editar evento</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <form method="POST">
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>

        <textarea name="descricao"><?php echo htmlspecialchars($evento['descricao'] ?? ''); ?></textarea>

        <label>Data do evento:</label>
        <input type="date" name="data_evento" value="<?php echo htmlspecialchars($evento['data_evento']); ?>" required>

        <label>Preço:</label>
        <input type="number" name="preco" step="0.01" min="0" value="<?php echo htmlspecialchars($evento['preco']); ?>" required>

        <button type="submit">Salvar alterações</button>
    </form>

    <p>
        <a href="index.php">Voltar ao painel</a>
    </p>
</section>

<?php include '../includes/rodape.php'; ?>