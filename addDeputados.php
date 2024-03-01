<?php

	include_once "./conexao.php";
	
	
	//Faz a conexão com a api da Assembleia
	$deputados = file_get_contents("http://dadosabertos.almg.gov.br/ws/deputados/situacao/1?formato=json");
	$decode = json_decode($deputados, true);
	$numero = 0;
	$numDeDeputados = 77;
	
	#Percorre cada index do json e salva cada um deles no banco de dados
	foreach($decode as $chave => $valor){
		while ($numero < $numDeDeputados){
							
			$id = $valor[$numero]["id"];
			$nome = $valor[$numero]["nome"];
			$partido = $valor[$numero]["partido"];
				
			$sql = "INSERT INTO info_deputados(id, nome, partido)
						VALUES('$id' , '$nome' , '$partido')";
	
			if ($conexao->query($sql) === TRUE) {
			}else {
				echo "Erro ao inserir dados na tabela! Erro:" . $sql . "<br>" . $conexao->error."<br>";
			}
				
			
				

					
			$numero = $numero +1;
		}		
	}
?>