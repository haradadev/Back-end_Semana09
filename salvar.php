<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$tiposPermitidos = ['Aluno', 'Professor', 'Funcionario', 'Coordenador'];
$tipo = trim((string) ($_POST['tipo'] ?? ''));
$nome = trim((string) ($_POST['nome'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$extra = trim((string) ($_POST['extra'] ?? ''));

if (
    !in_array($tipo, $tiposPermitidos, true)
    || $nome === ''
    || $extra === ''
    || filter_var($email, FILTER_VALIDATE_EMAIL) === false
) {
    header('Location: index.php?status=erro');
    exit;
}

$arquivo = __DIR__ . '/dados/usuarios.json';
$conteudo = is_file($arquivo) ? file_get_contents($arquivo) : '[]';
$usuarios = json_decode($conteudo ?: '[]', true);

if (!is_array($usuarios)) {
    $usuarios = [];
}

$usuarios[] = [
    'id' => uniqid('', true),
    'tipo' => $tipo,
    'nome' => $nome,
    'email' => $email,
    'extra' => $extra,
];

$json = json_encode(
    $usuarios,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

if ($json === false || file_put_contents($arquivo, $json, LOCK_EX) === false) {
    header('Location: index.php?status=erro_arquivo');
    exit;
}

header('Location: index.php?status=cadastrado');
exit;
