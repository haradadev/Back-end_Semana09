<?php

declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Aluno extends Usuario
{
    public function __construct(
        string $nome,
        string $email,
        private string $matricula
    ) {
        parent::__construct($nome, $email);
    }

    public function exibirInfo(): string
    {
        return "Aluno: {$this->nome} | Matrícula: {$this->matricula}";
    }
}
