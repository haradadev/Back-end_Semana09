<?php

declare(strict_types=1);

abstract class Usuario
{
    public function __construct(
        protected string $nome,
        protected string $email
    ) {
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    abstract public function exibirInfo(): string;
}
