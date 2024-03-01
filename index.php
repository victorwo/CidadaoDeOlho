 <html>
 <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="estilos.css" rel="stylesheet" >

	<title>Cidadão de olho</title>
 </head>
	<body>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<?php
		
	//Incluir o arquivo com a conexão com o banco de dados
	include_once "./conexao.php";
	
	
	//Formulário para escolher o mês de escolha
?>
	<h4>Selecione um mês abaixo para exibir os deputados que mais pediram reembolso de verbas indenizatórias nesse mês </h4>
	<form class="row g-3">
		<div class="mb-3">
			<select class="form-select" aria-label="Default select example" name="mes">
				<option value="">Escolha um mês</option>
				<option value="1">Janeiro</option>
				<option value="2">Fevereiro</option>
				<option value="3">Março</option>
				<option value="4">Abril</option>
				<option value="5">Maio</option>
				<option value="6">Junho</option>
				<option value="7">Julho</option>
				<option value="8">Agosto</option>
				<option value="9">Setembro</option>
				<option value="10">Outubro</option>
				<option value="11">Novembro</option>
				<option value="12">Dezembro</option>
			</select>
			
			<button type="submit" class="btn btn-primary mb-3 botao">Enviar</button>
		</div>
	</form>
		
		
		
		
<?php

	$meses = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
	$mes = htmlspecialchars($_GET["mes"]);
	
	
	if($mes >= 1 || $mes <=12){
		echo "<h3> Deputados que mais pediram reembolso de verbas indenizatórias no mês de ".$meses[$mes-1]."</h3><br>";
		
		
		$sql = "SELECT id FROM info_deputados";
		$resultado = mysqli_query($conexao, $sql); 
		
		
		$mes = htmlspecialchars($_GET["mes"]);
		if($mes < 1){
			$mes = 1;
		}else if($mes > 12){
			$mes = 12;
		}
		
		
		// Faz uma conexão com a api de verbas indenizatórias e verifica os gastos de cada deputado por mês
		while($dados = mysqli_fetch_array($resultado)){
			
			$valorIndenizado = 0;
			$idDeputado = $dados['id'];		
			
			$verbas = file_get_contents("https://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/$idDeputado/2019/$mes?formato=json");
			$jsonDecode = json_decode($verbas, true);
			
			
			foreach($jsonDecode as $chave => $valor){
						
				$tamanhoArray = count($valor);
						
				for($j = 0; $j < $tamanhoArray; $j++){
					$valorIndenizado += $valor[$j]["valor"];
				}
			}
			
			if ($idDeputado == 4458){
				$dadosVerbas[] = array('id' => $idDeputado, 'valor' => $valorIndenizado, 'partido' => "PT", 'nome' => 'Marquinho Lemos');			
			}else{
				$dadosVerbas[] = array('id' => $idDeputado, 'valor' => $valorIndenizado, 'partido' => "", 'nome' => '');			

			}
			//Coloca todos os resultados em uma array data
		
		}


		// Ordena o array por maior valor
		$valorVerba  = array_column($dadosVerbas, 'valor');
		$idVerba = array_column($dadosVerbas, 'id');

		array_multisort($valorVerba, SORT_DESC, $idVerba, SORT_ASC, $dadosVerbas);
		
		

		

	}
	
	
	// Calcula os 5 deputados com maior gasto em verbas indenizatórias
	$sqlDeputado = "SELECT * FROM info_deputados";
	$result = mysqli_query($conexao, $sqlDeputado); 
	
	$data = mysqli_fetch_array($result);
	
	while($data = mysqli_fetch_array($result)){
		$idDeputado = $data['id'];
		$nomeDeputado = $data['nome'];
		$partidoPolitico = $data['partido'];
			
		for($j=0; $j<5; $j++){
			if($idDeputado == $dadosVerbas[$j]["id"]){
				$dadosVerbas[$j]["nome"] = $nomeDeputado;
				$dadosVerbas[$j]["partido"] = $partidoPolitico;
			} 
		}
			
	}
	
	//Mostra na tela os dados dos 5 deputados que mais tiveram gastos em verbas indenizatórias
	for($i =0;$i < 5; $i++){
		echo "<p>Nome do deputado: ".$dadosVerbas[$i]["nome"]." - Verbas indenizatórias: R$".$dadosVerbas[$i]["valor"]. " - Partido político: ".$dadosVerbas[$i]["partido"]."<p>";
	}
	
	
	//Mostra as redes sociais mais usadas pelos deputados da Assembleia
	
	$listaTel = file_get_contents("https://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json");
	$listaDecode = json_decode($listaTel, true);
	
	echo "<h3> Redes mais usadas pelos deputados: </h3>";
	$listaRedes = array(
		"Facebook" => 0,
		"Twitter" => 0,
		"LinkedIn" => 0,
		"WhatsApp" => 0,
		"Instagram" => 0,
		"Youtube" => 0,
		"Flickr" => 0,
		"Telegram" => 0,
		"TikTok" => 0,
		"SoundCloud" => 0,
	);
	
	
	foreach($listaDecode as $key => $value){
		$tamanhoArray = count($value);
						
		for($i = 0; $i < $tamanhoArray; $i++){
			$redes = $value[$i]["redesSociais"];
			$numRedes = count($redes);
			
			for($j=0; $j<$numRedes; $j++){
				$nomeRede = $redes[$j]["redeSocial"]["nome"];
				$listaRedes[$nomeRede] += 1;
			}
		}
	}
	
	// Ordena a lista por onde de redes mais utilizadas
	asort($listaRedes);
	$reversed = array_reverse($listaRedes);
	
	$reversed = array_keys($reversed);
	for($i=0; $i < count($reversed); $i++ ){
		echo "<li>".$reversed[$i]."</li>";
	}

	
?>	
	
	</body>
</html>
