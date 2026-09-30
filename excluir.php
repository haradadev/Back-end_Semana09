<?php

declare(strict_types=1);

$arquivo = __DIR__ . '/dados/usuarios.json';
$id = trim((string) ($_GET['id'] ?? ''));

if ($id === '' || !is_file($arquivo)) {
    header('Location: index.php?status=erro');
    exit;
}

$usuarios = json_decode(file_get_contents($arquivo) ?: '[]', true);

if (!is_array($usuarios)) {
    $usuarios = [];
}

$quantidadeAnterior = count($usuarios);
$usuarios = array_values(array_filter(
    $usuarios,
    static fn (array $usuario): bool => (string) ($usuario['id'] ?? '') !== $id
));

$json = json_encode(
    $usuarios,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

if ($json === false || file_put_contents($arquivo, $json, LOCK_EX) === false) {
    header('Location: index.php?status=erro_arquivo');
    exit;
}

$status = count($usuarios) < $quantidadeAnterior ? 'excluido' : 'nao_encontrado';
header("Location: index.php?status={$status}");
exit;
