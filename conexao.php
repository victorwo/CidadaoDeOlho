<?php				
	#Cria conexão com o banco de dados
	$servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $bd = "deputados";

    $conexao = new mysqli($servidor, $usuario, $senha, $bd);
	mysqli_set_charset($conexao, "utf8");
	
    // Verifica se ouve um erro na conexão com o banco de dados
	if ($conexao->connect_error) {
        die("Falha de conexão: " . $conexao ->connect_error);
    } 
	  
?>