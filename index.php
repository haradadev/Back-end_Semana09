<?php

declare(strict_types=1);

require_once __DIR__ . '/classes/Aluno.php';
require_once __DIR__ . '/classes/Professor.php';
require_once __DIR__ . '/classes/Funcionario.php';
require_once __DIR__ . '/classes/Coordenador.php';

$arquivo = __DIR__ . '/dados/usuarios.json';
$conteudo = is_file($arquivo) ? file_get_contents($arquivo) : '[]';
$usuarios = json_decode($conteudo ?: '[]', true);
$usuarios = is_array($usuarios) ? $usuarios : [];

$mensagens = [
    'cadastrado' => ['sucesso', 'Usuário cadastrado com sucesso.'],
    'excluido' => ['sucesso', 'Usuário excluído com sucesso.'],
    'erro' => ['erro', 'Confira os dados informados e tente novamente.'],
    'erro_arquivo' => ['erro', 'Não foi possível atualizar o arquivo JSON.'],
    'nao_encontrado' => ['aviso', 'O usuário informado não foi encontrado.'],
];
$status = (string) ($_GET['status'] ?? '');

function criarUsuario(array $dados): ?Usuario
{
    $nome = (string) ($dados['nome'] ?? '');
    $email = (string) ($dados['email'] ?? '');
    $extra = (string) ($dados['extra'] ?? '');

    return match ($dados['tipo'] ?? '') {
        'Aluno' => new Aluno($nome, $email, $extra),
        'Professor' => new Professor($nome, $email, $extra),
        'Funcionario' => new Funcionario($nome, $email, $extra),
        'Coordenador' => new Coordenador($nome, $email, $extra),
        default => null,
    };
}

function h(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="topo">
        <div class="topo__conteudo">
            <div>
                <span class="etiqueta">Programação Back-end I</span>
                <h1>Sistema Escolar</h1>
                <p>Cadastro de usuários com POO e persistência em JSON.</p>
            </div>
            <div class="marca" aria-label="SENAI">SENAI</div>
        </div>
    </header>

    <main class="container">
        <?php if (isset($mensagens[$status])): ?>
            <div class="mensagem mensagem--<?= h($mensagens[$status][0]) ?>" role="status">
                <?= h($mensagens[$status][1]) ?>
            </div>
        <?php endif; ?>

        <section class="painel painel--formulario">
            <div class="secao-titulo">
                <div>
                    <span class="numero">01</span>
                    <h2>Novo usuário</h2>
                </div>
                <p>Preencha todos os campos para realizar o cadastro.</p>
            </div>

            <form action="salvar.php" method="post" class="formulario">
                <div class="campo">
                    <label for="tipo">Tipo de usuário</label>
                    <select id="tipo" name="tipo" required>
                        <option value="Aluno">Aluno</option>
                        <option value="Professor">Professor</option>
                        <option value="Funcionario">Funcionário</option>
                        <option value="Coordenador">Coordenador</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="nome">Nome completo</label>
                    <input id="nome" type="text" name="nome" maxlength="120" autocomplete="name" required>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" maxlength="160" autocomplete="email" required>
                </div>

                <div class="campo">
                    <label for="extra" id="rotulo-extra">Matrícula</label>
                    <input id="extra" type="text" name="extra" maxlength="120" required>
                    <small id="ajuda-extra">Informe a matrícula do aluno.</small>
                </div>

                <button type="submit">Cadastrar usuário</button>
            </form>
        </section>

        <section class="painel">
            <div class="secao-titulo secao-titulo--lista">
                <div>
                    <span class="numero">02</span>
                    <h2>Usuários cadastrados</h2>
                </div>
                <span class="contador"><?= count($usuarios) ?> registro<?= count($usuarios) === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($usuarios === []): ?>
                <div class="vazio">
                    <span>∅</span>
                    <h3>Nenhum usuário cadastrado</h3>
                    <p>Os novos registros aparecerão aqui e permanecerão salvos no JSON.</p>
                </div>
            <?php else: ?>
                <div class="lista">
                    <?php foreach ($usuarios as $dados): ?>
                        <?php $objeto = is_array($dados) ? criarUsuario($dados) : null; ?>
                        <?php if ($objeto !== null): ?>
                            <article class="usuario">
                                <div class="usuario__icone" aria-hidden="true">
                                    <?= h(mb_strtoupper(mb_substr($objeto->getNome(), 0, 1))) ?>
                                </div>
                                <div class="usuario__dados">
                                    <span class="usuario__tipo"><?= h((string) $dados['tipo']) ?></span>
                                    <h3><?= h($objeto->getNome()) ?></h3>
                                    <p><?= h($objeto->exibirInfo()) ?></p>
                                    <a href="mailto:<?= h($objeto->getEmail()) ?>"><?= h($objeto->getEmail()) ?></a>
                                </div>
                                <a
                                    class="excluir"
                                    href="excluir.php?id=<?= urlencode((string) ($dados['id'] ?? '')) ?>"
                                    onclick="return confirm('Deseja realmente excluir este usuário?')"
                                >Excluir</a>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>Atividade Semana 09 · POO + JSON · SENAI</footer>

    <script>
        const tipo = document.querySelector('#tipo');
        const rotulo = document.querySelector('#rotulo-extra');
        const ajuda = document.querySelector('#ajuda-extra');
        const extra = document.querySelector('#extra');
        const campos = {
            Aluno: ['Matrícula', 'Informe a matrícula do aluno.'],
            Professor: ['Disciplina', 'Informe a disciplina do professor.'],
            Funcionario: ['Cargo', 'Informe o cargo do funcionário.'],
            Coordenador: ['Setor', 'Informe o setor do coordenador.']
        };

        tipo.addEventListener('change', () => {
            const [nome, instrucao] = campos[tipo.value];
            rotulo.textContent = nome;
            ajuda.textContent = instrucao;
            extra.placeholder = nome;
        });
    </script>
</body>
</html>
