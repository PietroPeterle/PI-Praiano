<?php
function textoSeguro($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

?>