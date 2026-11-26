<?php
require_once '../conexao.php';

$busca = trim($_GET['busca'] ?? '');

$sql = "SELECT * FROM produtos WHERE nome LIKE :busca OR categoria LIKE :busca ORDER BY categoria ASC, nome ASC";

$comando = $conexao->prepare($sql);
$comando->execute([
    ':busca' => '%' . $busca . '%'
]);

$produtos = $comando->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/cabecalho.php'; ?>

<main>
    <section class="eventos-banner">
        <h1>Sabores do Praiano</h1>
        <p>Conheça nossas opções para aproveitar seu dia na praia.</p>
    </section>

    <section class="lista-eventos">
        <h2>Nosso cardápio</h2>

        <form method="GET">
            <input type="text" name="busca" placeholder="Buscar produto ou categoria" value="<?php echo htmlspecialchars($busca); ?>">

            <button type="submit">Buscar</button>
        </form>

        <?php if (count($produtos) > 0): ?>

            <?php foreach ($produtos as $produto): ?>

                <article class="card-evento">
                    <div class="dados-evento">
                        <span class="categoria-evento">
                            <?php echo htmlspecialchars($produto['categoria']); ?>
                        </span>

                        <h2>
                            <?php echo htmlspecialchars($produto['nome']); ?>
                        </h2>

                        <p>
                            <?php echo htmlspecialchars($produto['descricao']); ?>
                        </p>

                        <p>
                            <strong>Preço:</strong>
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </p>
                    </div>
                </article>

            <?php endforeach; ?>

        <?php else: ?>
            
            <p>
                Nosso cardápio está sendo preparado. Em breve teremos novidades!
            </p>

        <?php endif; ?>
    </section>
</main>

<?php include '../includes/rodape.php'; ?>