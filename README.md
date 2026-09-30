# Sistema Escolar — Semana 09

Aplicação PHP orientada a objetos para cadastrar, listar e excluir usuários, com persistência em JSON.

## Executar

Com o XAMPP iniciado, acesse:

`http://localhost/semana9/`

O projeto demonstra:

- classe abstrata `Usuario`;
- herança com `Aluno`, `Professor`, `Funcionario` e `Coordenador`;
- polimorfismo por meio de `exibirInfo()`;
- operações Create, Read e Delete;
- persistência no arquivo `dados/usuarios.json`.

O tipo `Coordenador` corresponde ao desafio final proposto na atividade.
