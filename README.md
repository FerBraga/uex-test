## ContactsListPlatform


## Contexto:
Este projeto trata-se de um aplicação web onde o usuário pode se cadastrar na plataforma, listar seus contatos, editá-los ou excluí-los bem como também localizar seus
endereços no mapa interativo.


## Desenvolvimento:
- Front e Back
    Feito em PHP v8.1 com o framework Laravel v10.10.

- Banco de dados
    Implementado com o banco de dados relacional MySQL. O banco contará com as tabelas de contacts, userContacts, addresses e users.

- Testes unitários:
   PHPunit.


## Instalando dependências:

- Plataforma
    Você precisará ter PHP 8.1 instalado e também o Laravel 10.10 e também o composer para instalações de dependências. 
    Após clonar este repositório em seu diretório local, acesse a pasta onde foi clonado, 
    então rode o comando `composer install` para instalar todas as dependências. Crie um arquivo
    .env contendo suas credencias para acesso ao banco de dados e servidor. Um Exemplo do que
    precisará em seu arquivo está no arquivo '.env.example', crie seu próprio arquivo a partir dele.

- Banco de dados
    Após instalar back-end você irá configurar seu banco de dados. Caso já
    tenha o serviço rodando use as credencias no arquivo .env, caso não
    tenha instalado poderá rodar também via container Docker.
    Ex: `docker run --name my-db -e MYSQL_PASSWORD=mysecretpassword -d mysql`.

  
- Mailer
    Para efetuar a recuperação de senha precisamos do Mailpit para rodar., caso não
    tenha instalado poderá rodar também via container Docker. Precisará do docker-compose instalado e
    então apenas rode o `docker-compose up` pois o arquivo para isso já está na pasta raíz do projeto.

## Executando aplicação:

  - Para implementar as tabelas do banco de dados:
      Acesse a pasta raíz do projeto e rode  `php artisan migrate`
      para rodar as migrations e também rode  `php artisan db:seed` que geram o user no qual você
      irá testar os endpoints e também as migrations do banco
     
- Iniciando o servidor Laravel:
      Rode `php artisan serve` e pronto, só acessar via 'http://127.0.0.1:8000/'. E também rode `npm run build` para buildar o front-end.


## Testes e documentação:

 - Testes:
     Este projeto conta com cases de testes unitários de todos os endpoints, para rodá-los: `php artisan test`.


Obs: No lugar da API Google Maps, que não pode ser usada neste momento, utiliazmos MapBox no lugar, que atende o propósito.
