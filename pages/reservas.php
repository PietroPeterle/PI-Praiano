
<?php
require_once '../protecao.php';
require_once '../conexao.php';

$mensagem = "";
$evento_selecionado = "";
$quantidade = 1;
$observacoes = "";

$sql = "SELECT * FROM eventos WHERE data_evento >= CURDATE() ORDER BY data_evento ASC";

$comando = $conexao->query($sql);
$eventos = $comando->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $evento_id = filter_var(
        $_POST["evento_id"] ?? null,
        FILTER_VALIDATE_INT
    );

    $quantidade = filter_var(
        $_POST["quantidade_pessoas"] ?? null,
        FILTER_VALIDATE_INT
    );

    $observacoes = trim($_POST["observacoes"] ?? "");
    $evento_selecionado = $_POST["evento_id"] ?? "";

    if (!$evento_id) {
        $mensagem = "Selecione um evento válido!";
    } elseif (!$quantidade || $quantidade < 1 || $quantidade > 100) {
        $mensagem = "Informe uma quantidade de pessoas entre 1 e 100!";
    } elseif (strlen($observacoes) > 500) {
        $mensagem = "As observações devem ter no máximo 500 caracteres!";
    } else {

        $sql = "SELECT id FROM eventos 
                WHERE id = :id AND data_evento >= CURDATE()";

        $comando = $conexao->prepare($sql);
        $comando->execute([':id' => $evento_id]);

        $evento = $comando->fetch(PDO::FETCH_ASSOC);

        if (!$evento) {
            $mensagem = "Evento não encontrado ou já realizado!";
        } else {

            $sql = "INSERT INTO reservas 
                    (usuario_id, evento_id, quantidade_pessoas, observacoes) 
                    VALUES (:usuario_id, :evento_id, :quantidade, :observacoes)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ':usuario_id' => $_SESSION["usuario_id"],
                ':evento_id' => $evento_id,
                ':quantidade' => $quantidade,
                ':observacoes' => $observacoes !== "" ? $observacoes : null
            ]);

            $mensagem = "Reserva realizada com sucesso!";

            $evento_selecionado = "";
            $quantidade = 1;
            $observacoes = "";
        }
    }
}
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="formulario">

    <h1>Faça sua reserva</h1>

    <p><?php echo htmlspecialchars($mensagem); ?></p>

    <?php if (count($eventos) > 0): ?>

        <form method="POST">

            <label>Escolha seu evento:</label>

            <select name="evento_id" required>
                <option value="">Selecione um evento</option>

                <?php foreach ($eventos as $evento): ?>

                    <option 
                        value="<?php echo $evento['id']; ?>"
                        <?php echo $evento_selecionado == $evento['id'] ? 'selected' : ''; ?>>

                        <?php echo htmlspecialchars($evento['titulo']); ?>
                        - <?php echo date('d/m/Y', strtotime($evento['data_evento'])); ?>

                    </option>

                <?php endforeach; ?>
            </select>

            <label for="quantidade_pessoas">
                Quantidade de pessoas:
            </label>

            <input 
                type="number" 
                name="quantidade_pessoas" 
                id="quantidade_pessoas"
                min="1"
                max="100"
                value="<?php echo htmlspecialchars((string)$quantidade); ?>"
                required>

            <label for="observacoes">
                Observações (opcional):
            </label>

            <textarea 
                name="observacoes" 
                id="observacoes"
                maxlength="500"
                rows="4"
                placeholder="Ex: Gostaria de uma mesa perto do mar, aniversário..."><?php echo htmlspecialchars($observacoes); ?></textarea>

            <button type="submit">Confirmar reserva</button>

        </form>

    <?php else: ?>

        <p>Não existem eventos disponíveis para reservas.</p>

    <?php endif; ?>

</section>

<?php include '../includes/rodape.php'; ?>
