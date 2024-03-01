# CidadaoDeOlho
Projeto para verificar os 5 deputados de Minas Gerais que tiveram o maior reemboolso de verbas indenizatórias em um determinado mês.


Para rodar o projeto é necessário criar um banco de dados chamado deputados e criar uma tabela com o nome info_deputados no phpMyAdmin.

A primeira página que deve ser acessada é a conexao.php que mostra a conexão com o banco de dados, necessário acessá-la caso seja necessário alterar algum dado de conexão.

Depois deve ser acessado a página addDeputados.php que faz uma requisição à API da Assembleia e pega os resultados dessa requisição para popular o banco de dados. Esse arquivo deve ser rodado apenas uma vez.

Em seguida podemos acessar a página index.php

Para exibir os resultados na página index.php é necessário selecionar um mês do menu flutuante e o sistema irá exibir os deputados que mais gastaram nesse determinado mês
