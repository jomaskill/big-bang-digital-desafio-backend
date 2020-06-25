
## Projeto

Esse é o código referente ao desafio https://gitlab.com/bigbangdigital/desafio-back-end

## Funcionamento

Construir container `docker-compose up --build`

requisição GET para a rota _localhost:8000/suggestion_

Deve ser enviado um body json de duas formas diferentes:

`{
    "city": "nome_da_cidade"
}`

ou

`{
    "latitude" : "-34.6083",
    "longitude" : "-30.3715"
}`

A resposta será um json contendo nomes de músicas.

## Testes

testes são feitos rodando o comando

`vendo/bin/phpunit ` Dentro do container bigbang-app
