<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Construção do ambiente de desenvolvimento

No Windows, instale o [Docker](https://www.docker.com/)

Clone o projeto do repositório:
`git clone https://github.com/fabioLealN/AgendaInteligente.git`

Entre no diretório raiz do projeto e copie o conteúdo do .env-example para o .env:
`cp ./env-example ./env`

Entre no diretório raiz do projeto e rode o comando:
`docker-compose up -d --build`

Caso dê algum conflito de portas, altere as portas definidas no arquivo [docker-compose.yaml](./docker-compose.yaml)

É necessário habilitar as permissões de edição da base de dados na pasta do container usr/share/nginx. Para isso rode o comando:
`chmod -R 777 storage/*`
ou, caso este comando não funcione, rode (sudo em caso de terminais linux):
`sudo chmod o+w ./storage/ -R`

Caso não consiga acessar a api do Laravel rode o comando na raiz do projeto:
`composer install`

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- Api Laravel: http://localhost:8888/
- Adminer: http://localhost:8081/

Login Adminer
----------
Sistema     | PostgreSQL
Servidor    | postgres
Usuário     | postgres
Senha       | postgres
