<?php

declare(strict_types=1);

require_once __DIR__ . '/Usuario.php';

class Coordenador extends Usuario
{
    public function __construct(
        string $nome,
        string $email,
        private string $setor
    ) {
        parent::__construct($nome, $email);
    }

    public function exibirInfo(): string
    {
        return "Coordenador: {$this->nome} | Setor: {$this->setor}";
    }
}
