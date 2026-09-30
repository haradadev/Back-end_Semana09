<?php

declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Professor extends Usuario
{
    public function __construct(
        string $nome,
        string $email,
        private string $disciplina
    ) {
        parent::__construct($nome, $email);
    }

    public function exibirInfo(): string
    {
        return "Professor: {$this->nome} | Disciplina: {$this->disciplina}";
    }
}
