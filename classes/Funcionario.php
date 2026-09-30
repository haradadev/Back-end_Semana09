<?php

declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Funcionario extends Usuario
{
    public function __construct(
        string $nome,
        string $email,
        private string $cargo
    ) {
        parent::__construct($nome, $email);
    }

    public function exibirInfo(): string
    {
        return "Funcionário: {$this->nome} | Cargo: {$this->cargo}";
    }
}
