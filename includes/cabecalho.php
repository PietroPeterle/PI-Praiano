<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ultimaVisita = $_COOKIE['ultima_visita'] ?? null;

setcookie(
    'ultima_visita',
    date('d/m/Y H:i:s'),
    time() + (86400 * 30),
    '/'
);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Praiano Lounge</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<header class="cabecalho">

    <div class="topo-site">

        <a href="/index.php" class="logo">
            PRAIANO LOUNGE 16
        </a>

        <a href="/pages/eventos.php" class="botao-header">
            Viva o Praiano
        </a>

    </div>

    <nav class="menu">

        <a href="/index.php">Início</a>
        <a href="/pages/sobre.php">Sobre</a>
        <a href="/pages/eventos.php">Eventos</a>
        <a href="/pages/cardapio.php">Cardápio</a>
        <a href="/pages/contato.php">Contato</a>

        <?php if (isset($_SESSION['usuario_id'])): ?>

            <a href="/pages/reservas.php">Reservas</a>
            <a href="/pages/minhas_reservas.php">Minhas reservas</a>

            <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                <a href="/admin/index.php">Painel Admin</a>
            <?php endif; ?>

            <a href="/pages/logout.php" class="botao-sair">
                Sair da conta
            </a>

        <?php else: ?>

            <a href="/pages/login.php" class="botao-login">Entrar</a>
            <a href="/pages/cadastro.php" class="botao-cadastro">Cadastrar</a>

        <?php endif; ?>

    </nav>

    <div class="visita <?php echo (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin') ? 'visita-admin' : ''; ?>">

        <?php if ($ultimaVisita): ?>

            <p>
                Sua última visita foi em:
                <?php echo htmlspecialchars($ultimaVisita); ?>
            </p>

        <?php else: ?>

            <p>Bem-vindo ao Praiano!</p>

        <?php endif; ?>

    </div>

</header>