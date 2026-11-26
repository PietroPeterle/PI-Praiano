<?php

require_once '../protecao.php';
require_once '../conexao.php';

if (!isset($_SESSION['token_cancelamento'])) {
    $_SESSION['token_cancelamento'] = bin2hex(random_bytes(32));
}

$sql = "SELECT reservas.id, reservas.data_reserva, eventos.titulo, eventos.data_evento, eventos.preco 
        FROM reservas 
        INNER JOIN eventos ON reservas.evento_id = eventos.id 
        WHERE reservas.usuario_id = :usuario_id 
        ORDER BY reservas.data_reserva DESC";

$comando = $conexao->prepare($sql);
$comando->execute([':usuario_id' => $_SESSION["usuario_id"]]);

$reservas = $comando->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/cabecalho.php'; ?>

<section class="lista-eventos">
    <h1>Minhas reservas</h1>

    <?php if (count($reservas) > 0): ?>

        <?php foreach ($reservas as $reserva): ?>

            <article class="card-evento">
                <div class="dados-evento">
                    <h2>
                        <?php echo htmlspecialchars($reserva['titulo']); ?>
                    </h2>

                    <p>
                        Data do evento:
                        <?php echo date('d/m/Y', strtotime($reserva['data_evento'])); ?>
                    </p>

                    <p>
                        Data da reserva:
                        <?php echo date('d/m/Y H:i', strtotime($reserva['data_reserva'])); ?>
                    </p>

                    <p>
                        Preço: R$
                        <?php echo number_format($reserva['preco'], 2, ',', '.'); ?>
                    </p>

                    <form action="cancelar_reserva.php" method="POST">
                        <input type="hidden" name="reserva_id"
                               value="<?php echo $reserva['id']; ?>">

                        <input type="hidden" name="token"
                               value="<?php echo $_SESSION['token_cancelamento']; ?>">

                        <button type="submit" class="botao-cancelar">
                            Cancelar reserva
                        </button>
                    </form>

                </div>
            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Você ainda não possui reservas.</p>

    <?php endif; ?>
</section>

<?php include '../includes/rodape.php'; ?>
