<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Construção do ambiente de desenvolvimento

No Windows, instale o [Docker](https://www.docker.com/)

Clone o projeto do repositório:<br>
`git clone https://github.com/fabioLealN/AgendaInteligente.git`


Entre no diretório raiz do projeto e copie o conteúdo do .env-example para o .env:<br>
`cp ./env-example ./env`


Entre no diretório raiz do projeto e rode o comando:<br>
`docker-compose up -d --build`


Caso dê algum conflito de portas, altere as portas definidas no arquivo [docker-compose.yaml](./docker-compose.yaml)


É necessário habilitar as permissões de edição da base de dados na pasta do container usr/share/nginx. Para isso rode o comando:<br>
`chmod -R 777 storage/*`<br>
ou, caso este comando não funcione, rode (sudo em caso de terminais linux):<br>
`sudo chmod o+w ./storage/ -R`


Caso não consiga acessar a api do Laravel rode o comando na raiz do projeto:<br>
`composer install`


- Api Laravel: http://localhost:8888/
- Adminer: http://localhost:8081/

Login Adminer


| Campo | Credenciais |
| --- | --- |
| Sistema | PostgreSQL |
| Servidor | postgres |
| Usuário | postgres |
| Senha | postgres |
