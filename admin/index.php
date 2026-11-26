<?php
require_once '../protecao.php';
require_once '../includes/funcoes.php';

if ($_SESSION["usuario_tipo"] !== "admin") {
    die("Acesso permitido somente para administradores!");
}

if (!isset($_SESSION['token_mensagens'])) {
    $_SESSION['token_mensagens'] = bin2hex(random_bytes(32));
}

require_once '../conexao.php';

$sql = "SELECT *FROM eventos ORDER BY data_evento ASC";
$comando1 = $conexao->query($sql);
$eventos = $comando1->fetchAll(PDO::FETCH_ASSOC);

// BUSCAR PRODUTOS
$sql = "SELECT * FROM produtos ORDER BY nome ASC";
$comando2 = $conexao->query($sql);
$produtos = $comando2->fetchAll(PDO::FETCH_ASSOC);

// Buscar todas as reservas
$sql = "SELECT reservas.id, usuarios.nome AS cliente, usuarios.email, eventos.titulo AS evento, reservas.data_reserva, reservas.quantidade_pessoas, reservas.observacoes 
FROM reservas 
INNER JOIN usuarios ON reservas.usuario_id = usuarios.id 
INNER JOIN eventos ON reservas.evento_id = eventos.id 
ORDER BY reservas.data_reserva DESC";

$comando3 = $conexao->query($sql);
$reservas = $comando3->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM mensagens ORDER BY data_envio DESC";
$comando = $conexao->query($sql);
$mensagens = $comando->fetchAll(PDO::FETCH_ASSOC);
?>


<?php include '../includes/cabecalho.php'; ?>

<section class="formulario painel-admin">
    <h1>Painel Administrativo</h1>
    
    <a href="cadastrar_evento.php">Cadastrar novo evento</a>

    <p>Bem-vindo ao gerenciamento do Praiano!</p>

    <h2>Eventos cadastrados</h2>

    <?php if (count($eventos) > 0): ?>

        <?php foreach ($eventos as $evento): ?>
            <div>
                <h3>
                    <?php echo htmlspecialchars($evento["titulo"]); ?>
                </h3>

                <p>
                    Data:
                    <?php echo htmlspecialchars($evento["data_evento"]); ?>
                </p>

                <p>
                    Preço: R$
                    <?php echo number_format($evento["preco"], 2, ",", "."); ?>
                </p>

                <a href="editar_evento.php?id=<?php echo $evento['id']; ?>">Editar evento</a>

                <form method="POST" action="excluir_evento.php" onsubmit="return confirm('Deseja realmente excluir este evento?');">
                    <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">

                    <button type="submit">Excluir evento</button>
                </form>
            </div>
        <?php endforeach; ?>

    <?php else: ?>

        <p>Nenhum evento cadastrado ainda.</p>

    <?php endif; ?>
    <h2>Produtos cadastrados</h2>

    <?php foreach ($produtos as $produto): ?>

        <div>
            <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>

            <p>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

            <a href="editar_produto.php?id=<?php echo $produto['id'] ?>">
                Editar produto
            </a>

            <form method="POST" action="excluir_produto.php" onsubmit="return confirm('Deseja realmente excluir este produto?');">
                <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">

                <button type="submit">Excluir produto</button>
            </form>
        </div>

        <?php endforeach; ?>

        <p><a href="cadastrar_produto.php">Cadastrar novo produto</a></p>

        
<h2>Reservas dos clientes</h2>

<?php if (count($reservas) > 0): ?>

    <?php foreach ($reservas as $reserva): ?>

        <div class="reserva-info">

            <h3>
                <?php echo htmlspecialchars($reserva['cliente']); ?>
            </h3>

            <p>
                <span>Evento:</span>
                <?php echo htmlspecialchars($reserva['evento']); ?>
            </p>

            <p>
                <span>Quantidade de pessoas:</span>
                <?php echo (int)$reserva['quantidade_pessoas']; ?>
            </p>

            <p>
                <span>Data da reserva:</span>
                <?php echo date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?>
            </p>

            <p>
                <span>Observações:</span>
                <?php echo $reserva['observacoes'] !== null && $reserva['observacoes'] !== '' 
                    ? nl2br(htmlspecialchars($reserva['observacoes'])) 
                    : 'Nenhuma observação.'; ?>
            </p>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>Nenhuma reserva realizada ainda.</p>

<?php endif; ?>


        <h2>Mensagens dos clientes</h2>

            <div class="lista-mensagens">

            <?php if (count($mensagens) > 0): ?>

                <?php foreach ($mensagens as $mensagem): ?>

                    <div class="card-mensagem">
                        <h3>
                            <?php echo textoSeguro($mensagem['nome']); ?>
                        </h3>

                        <p>
                            <strong>E-mail:</strong>
                            <?php echo textoSeguro($mensagem['email']); ?>
                        </p>

                        <p>
                            <strong>Assunto:</strong>
                            <?php echo textoSeguro($mensagem['assunto']); ?>
                        </p>

                        <div class="conteudo-mensagem">
                            <strong>Mensagem:</strong>

                            <p>
                                <?php echo nl2br(textoSeguro($mensagem['mensagem'])); ?>
                            </p>
                        </div>

                        <small>
                            Enviada em:
                            <?php echo date('d/m/Y H:i', strtotime($mensagem['data_envio'])); ?>
                        </small>

                        <form method="POST" action="excluir_mensagem.php"
                            onsubmit="return confirm('Deseja realmente excluir esta mensagem?');">

                            <input type="hidden" name="id"
                                value="<?php echo $mensagem['id']; ?>">

                            <input type="hidden" name="token"
                                value="<?php echo $_SESSION['token_mensagens']; ?>">

                            <button type="submit" class="botao-excluir-mensagem">
                                Excluir mensagem
                            </button>
                        </form>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>Nenhuma mensagem recebida.</p>

            <?php endif; ?>
            </div>

    <p>
        <a href="../pages/logout.php">Sair da conta</a>
    </p>
</section>

<?php include '../includes/rodape.php'; ?>