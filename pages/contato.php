<?php include '../includes/cabecalho.php'; ?>

<?php
require_once '../conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (
        $nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $assunto === '' || $mensagem === ''
    ) {
        $erro = 'Preencha todos os campos corretamente.';
    } else {
        $sql = "INSERT INTO mensagens (nome, email, assunto, mensagem) VALUES (:nome, :email, :assunto, :mensagem)";

        $comando = $conexao->prepare($sql);
        $comando->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':assunto' => $assunto,
            ':mensagem' => $mensagem
        ]);

        $sucesso = 'Mensagem enviada com sucesso!';
    }

}
?>

<main>
    <section class="eventos-banner">
        <h1>Entre em contato</h1>
        <p>Estamos esperando por você no Praiano!</p>
    </section>

    <section class="formulario">
        <h2>Fale conosco</h2>

        <p>
            Tem alguma dúvida, sugestão ou deseja saber mais sobre nossos eventos? Entre em contato!
        </p>

        <?php if ($erro): ?>
            <p><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p><?php echo htmlspecialchars($sucesso); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nome:</label>
            <input type="text" name="nome" placeholder="Seu nome" required>

            <label>E-mail:</label>
            <input type="email" name="email" placeholder="Seu e-mail" required>

            <label>Telefone:</label>
            <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000" maxlength="15" required>

            <label>Assunto:</label>
            <input type="text" name="assunto" required>

            <label>Mensagem:</label>
            <textarea name="mensagem" placeholder="Digite sua mensagem" required></textarea>

            <button type="submit">Enviar mensagem</button>
        </form>

        <p>Endereço: Av. Governador Mario Covas 2078 - Peruíbe - SP</p>

        <p>Atendimento: Quarta a Domingo das 9:00 as 4:00</p>
    </section>
</main>

<?php include '../includes/rodape.php'; ?>