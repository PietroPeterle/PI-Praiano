<?php
require_once '../conexao.php';

$sql = "SELECT * FROM eventos ORDER BY data_evento ASC";
$comando = $conexao->query($sql);
$eventos = $comando->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/cabecalho.php'; ?>

<main>

    <!-- BANNER DOS EVENTOS -->
    <section class="eventos-banner">
        <p class="titulo-secao">MÚSICA • MAR • EXPERIÊNCIAS</p>

        <h1>
            O verão acontece<br>
            <span>no Praiano</span>
        </h1>

        <p>
            Encontre seu próximo momento favorito.
            Confira nossa programação e venha viver experiências inesquecíveis à beira-mar.
        </p>
    </section>

    <!-- EVENTOS VISUAIS -->
    <section class="lista-eventos">

        <div class="eventos-titulo">
            <div>
                <p class="titulo-secao">NOSSA PROGRAMAÇÃO</p>
                <h2>Encontre sua <span>vibe.</span></h2>
            </div>
        </div>

        <!-- EVENTO 1 -->
        <article class="card-evento">

            <div class="foto-evento foto-sunset">
                <span class="etiqueta-evento">MÚSICA AO VIVO</span>
            </div>

            <div class="dados-evento">

                <p class="categoria-evento">SUNSET • MÚSICA</p>

                <h3>Sunset Praiano</h3>

                <p>
                    Música ao vivo, drinks refrescantes e um pôr do sol incrível para aproveitar com os amigos.
                </p>

                <div class="detalhes-evento">
                    <span>☀ Tarde</span>
                    <span>♪ Música ao vivo</span>
                </div>

                <a href="../index.php#contato" class="link-evento">
                    Saiba mais →
                </a>

            </div>
        </article>

        <!-- EVENTO 2 -->
        <article class="card-evento">

            <div class="foto-evento foto-dj">
                <span class="etiqueta-evento">DJ NA PRAIA</span>
            </div>

            <div class="dados-evento">

                <p class="categoria-evento">DJ • FESTA</p>

                <h3>Praiano Beats</h3>

                <p>
                    Os melhores ritmos, energia contagiante e uma noite especial para dançar perto do mar.
                </p>

                <div class="detalhes-evento">
                    <span>☾ Noite</span>
                    <span>♫ DJ ao vivo</span>
                </div>

                <a href="../index.php#contato" class="link-evento">
                    Saiba mais →
                </a>

            </div>
        </article>

        <!-- EVENTO 3 -->
        <article class="card-evento">

            <div class="foto-evento foto-acustico">
                <span class="etiqueta-evento">VOZ & VIOLÃO</span>
            </div>

            <div class="dados-evento">

                <p class="categoria-evento">ACÚSTICO • RELAX</p>

                <h3>Luau Praiano</h3>

                <p>
                    Uma noite tranquila, com música acústica, luzes, pé na areia e clima de luau.
                </p>

                <div class="detalhes-evento">
                    <span>☾ Noite</span>
                    <span>♪ Acústico</span>
                </div>

                <a href="../index.php#contato" class="link-evento">
                    Saiba mais →
                </a>

            </div>
        </article>

    </section>

    <!-- EVENTOS CADASTRADOS PELO ADMIN -->
    <section class="lista-eventos eventos-banco">

        <h2>Próximos eventos</h2>

        <?php if (count($eventos) > 0): ?>

            <?php foreach ($eventos as $evento): ?>

                <article class="card-evento">

                    <div class="dados-evento">

                        <span class="categoria-evento">
                            EVENTO PRAIANO
                        </span>

                        <h3>
                            <?php echo htmlspecialchars($evento['titulo']); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($evento['descricao'] ?? ''); ?>
                        </p>

                        <p>
                            <strong>Data:</strong>
                            <?php echo date('d/m/Y', strtotime($evento['data_evento'])); ?>
                        </p>

                        <p>
                            <strong>Preço:</strong>
                            R$ <?php echo number_format($evento['preco'], 2, ',', '.'); ?>
                        </p>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Ainda não temos eventos cadastrados.</p>

        <?php endif; ?>

    </section>

    <!-- CHAMADA FINAL -->
    <section class="eventos-convite">

        <p class="titulo-secao">VOCÊ FAZ PARTE</p>

        <h2>
            Momentos bons<br>
            merecem <span>ser vividos.</span>
        </h2>

        <p>
            Te esperamos para viver tudo isso com a gente.
        </p>

        <a href="../index.php" class="botao-principal">
            Voltar para o início
        </a>

    </section>

</main>

<?php include '../includes/rodape.php'; ?>