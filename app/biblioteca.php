<?php
// --------------------------------------------SORTEADO-----------------------------------------------


function salvarSorteado($sorteado,$idUsuario=NULL) //done, just need to think when it's going to be triggered - AddSelectedPrizeAsync
{
	$dbh = conectarBD();
	$dataAtual = date('Y-m-d H:i:s');
	if($idUsuario)
		$sql = "INSERT INTO logs_sorteados(idUsuario,sorteado,dataSorteado) VALUES ($idUsuario,$sorteado,'$dataAtual')";
	else
		$sql = "INSERT INTO logs_sorteados(sorteado,dataSorteado) VALUES ($sorteado,'$dataAtual')";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}


function salvarLogMudancaConfig(){ //done, just need to think when it's going to be triggered 
	$configs=consultarConfigsEstoque();
	$acelerometro=$configs[0]['valorConfig'];
	$tipoDistribuicao=$configs[4]['valorConfig'];
	$premiosEm10=$configs[5]['valorConfig'];

	$estoqueDivididoPorDia=consultarConfigDivididoPorDia();
	if($estoqueDivididoPorDia)
		$diaFinal=consultarDiaFinal();
	else
		$diaFinal=NULL;

	if(!$tipoDistribuicao) //acelerometro
		salvarAlteracoesConfig("acelerometro",$diaFinal);
	else
		salvarAlteracoesConfig("em10",$diaFinal);
}

// -------------------------------------------------ESTOQUE----------------------------------------------------------

function consultarEstoque($numeroEstoque=null){ // done, just need to think when it's going to be triggered - GetAllItems
	$dbh = conectarBD();
	$sql = "SELECT * FROM item";
	@$temp = $dbh->query($sql)->fetchAll();
	if($temp)
		return $temp;
	else
		return 0;
}

function consultarConfigDivididoPorDia(){ // done, just need to think when it's going to be triggered - GetConfigDvidedByDay
	$dbh = conectarBD();
	$sql = "SELECT valorConfig FROM config WHERE nomeConfig='porDia'";
	@$temp = $dbh->query($sql)->fetchAll();
	if($temp)
		return $temp[0][0];
	else
		return 0;
}

function consultarDiaFinal(){ // done, just need to think when it's going to be triggered - GetConfigLastDay
	$dbh = conectarBD();
	$sql = "SELECT valorConfig FROM config WHERE nomeConfig='diaFinal'";
	@$temp = $dbh->query($sql)->fetchAll();
	if($temp)
		return $temp[0][0];
	else
		return 0;
}

function salvarItens($nome,$qntd,$id){  // done, just need to think when it's going to be triggered - UpdateItemAsync

	$dbh = conectarBD();
	$sql = "UPDATE item SET nomeItem = '$nome', quantidadeItem=$qntd WHERE idItem=$id";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function diminuirQuantidade($id){ // done, just need to think when it's going to be triggered - retirarItem - DecreaseItemQuantity
	$dbh = conectarBD();
	$sql = "SELECT quantidadeItem FROM item WHERE idItem=$id";
	@$quantidade = $dbh->query($sql)->fetchAll()[0][0];
	$quantidade--;
	$sql = "UPDATE item SET quantidadeItem=$quantidade WHERE idItem=$id";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function consultarTempoFinal(){ // done, just need to think when it's going to be triggered - GetConfigEndTime - GetEndTime
	$dbh = conectarBD();
	$sql = "SELECT valorConfig FROM config WHERE nomeConfig='tempoFinal'";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	if($temp)
		return $temp;
	else
		return 0;
}

function consultarTempoInicial(){ // done, just need to think when it's going to be triggered - GetConfigInitialTime - GetInitialTime
	$dbh = conectarBD();
	$sql = "SELECT valorConfig FROM config WHERE nomeConfig='tempoInicial'";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	if($temp)
		return $temp;
	else
		return 0;
}

function atualizarTempos($tempoInicial,$tempoFinal,$dividoPorDia,$diaFinal){ // done, just need to think when it's going to be triggered - UpdateTimesAsync
	$dbh = conectarBD();
	$sql = "UPDATE config SET valorConfig='$tempoFinal' WHERE nomeConfig='tempoFinal';";
	$sql=$sql."UPDATE config SET valorConfig='$tempoInicial' WHERE nomeConfig='tempoInicial';";
	$sql=$sql."UPDATE config SET valorConfig='$dividoPorDia' WHERE nomeConfig='porDia';";
	$sql=$sql."UPDATE config SET valorConfig='$diaFinal' WHERE nomeConfig='diaFinal';";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function alterarConfigsEstoque($distribuicao,$valor){ // eu nao sei
	$dbh = conectarBD();
	$sql="UPDATE config SET valorConfig=$distribuicao WHERE nomeConfig='estoquePor10';";
	if($distribuicao==1)
		$sql=$sql."UPDATE config SET valorConfig=$valor WHERE nomeConfig='premiosEm10';";
	else
		$sql=$sql."UPDATE config SET valorConfig=$valor WHERE nomeConfig='acelerometro';";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function consultarConfigsEstoque(){ // done, just need to think when it's going to be triggered - getAllConfigs
	// 2 = acelerometro
	// 3 = tempoEstoque
	// 5 = porDia
	// 6 = diaFinal
	// 7 = estoquePor10
	// 8 = premiosEm10
	$dbh = conectarBD();
	$sql = "SELECT * FROM config WHERE idConfig IN (2,3,5,6,7,8)";
	@$temp = $dbh->query($sql)->fetchAll();
	if($temp)
		return $temp;
	else
		return 0;
}

function consultarConfigsControlePremios(){ // done, just need to think when it's going to be triggered - GetPrizeControl
	$dbh = conectarBD();
	$sql = "SELECT * FROM controle_premios";
	@$temp = $dbh->query($sql)->fetchAll();
	if($temp)
		return $temp;
	else
		return 0;
}

function zerarEstoque(){ // done, just need to think when it's going to be triggered - ResetInventoryPrizeControl
	$dbh = conectarBD();
	$dataAtual = date('Y-m-d');
	$sql = "UPDATE controle_premios SET valorControle='false', dataUpdateControle='$dataAtual' WHERE nomeControle='estoque1';";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}
function zerarVariaveisTempo(){ // done, just need to think when it's going to be triggered - ResetTimesPrizeControl
	$dbh = conectarBD();
	$sql = "UPDATE controle_premios SET valorControle='',dataUpdateControle='' WHERE nomeControle='premiosDia';";

	$sql = $sql."UPDATE controle_premios SET valorControle='0',dataUpdateControle='' WHERE nomeControle='premiosSorteadoDia';";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function retirarItem($id){ // done, just need to think when it's going to be triggered - DecreaseItemQuantity
	$dbh = conectarBD();
	$sql = "SELECT quantidadeItem FROM item WHERE idItem=$id";
	@$quantidade = $dbh->query($sql)->fetchAll()[0][0];
	$quantidade--;
	try {
		$sql = "UPDATE item SET quantidadeItem=$quantidade WHERE idItem=$id AND quantidadeItem>0";
		$cod = $dbh->prepare($sql);
		$cod->execute();
		return true;
	} catch (Exception $e) {
		return false;
	}
	
}


function logica($numeroEstoque=1){
	//consulto as configuracoes para montar o estoque
	// 0 = acelerometro
	// 1 = tempoEstoque
	// 2 = porDia
	// 3 = diaFinal
	// 4 = estoquePor10
	// 5 = premiosEm10
	$configs=consultarConfigsEstoque();

	$qntdZeros=$configs[0]['valorConfig'];
	// por padrão, assumo que cada jogada duraria no minimo 1 minuto. De começo eu preencho essas jogadas na hora por 0, que é o não foi dessa vez

	$calculoDivididoPorDia=$configs[2]['valorConfig'];
	$tipoEstoque=$configs[4]['valorConfig'];
	// tipoEstoque é 1 quer dizer que é distribuicao em 10 jogadas, caso contrario é acelerometro


	$configsControlePremios=consultarConfigsControlePremios();

	$premiosSorteadoDia=$configsControlePremios[2]['valorControle'];
	// quantidade de prêmios que sairam no dia

	$maxPremiosDia=$configsControlePremios[0]['valorControle'];
	// o máximo de prêmios que pode sair no dia caso esteja dividio por dias
	
	$estoque['estoque1']=$configsControlePremios[1]['valorControle'];
	// o ultimo estoque gerado

	$dataCriacaoEstoque['estoque1']=$configsControlePremios[1]['dataUpdateControle'];

	//verifico se a quantidade de premios por dia ja foi criado, se não eu crio
	//caso nao seja dividio por dia, nao faz mal criar essa variavel e ficar atualizando ela
	
	//se o estoque for dividido por dia eu faço as verificações abaixo
	if($calculoDivididoPorDia && !$tipoEstoque){
		if($maxPremiosDia && verificarPremioPorDiaEDiaAtual()){
			//caso eu ja tenha passado por essa funcao e descoberto a quantidade de premios max por dia e a ultima vez que eu fiz essa verificacao foi hoje
			// eu verifico se o limite de premios por dia bateu com os premios dados, se sim eu nao sorteio mais
			if($premiosSorteadoDia>=$maxPremiosDia)
				return 0;
		}
	}

	if(!$tipoEstoque){
		$tempoAtual=date('H:i');
		$horaAtual = explode(':', $tempoAtual, 2)[0];
		$minAtual = explode(':', $tempoAtual, 2)[1];

		$tempoFinal = consultarTempoFinal();
		$horaFinal = explode(':', $tempoFinal, 2)[0];
		$minFinal = explode(':', $tempoFinal, 2)[1];

		$tempoInicial = consultarTempoInicial();
		$horaInicial = explode(':', $tempoInicial, 2)[0];
		$minInicial = explode(':', $tempoInicial, 2)[1];

		$diffTempo=0;	
		if($horaFinal>=$horaAtual || $minFinal>=$minAtual){
			$diffHoras=$horaFinal-$horaAtual;
			$diffMins=$minFinal-$minAtual;
			$diffTempo = ($diffHoras*60)+$diffMins;
			// isso é em minutos ou seja: horas * 60 é quantos minutos tem em horas menos os minutos da hora atual
		}
	}


	$estoqueTotal=0;
	$itens=consultarEstoque($numeroEstoque);
	$idsItens=array();
	$indexUniversal=0;
	$nomeEstoque='estoque'.$numeroEstoque;

	foreach($itens as $item){
		for ($i=0; $i < $item['quantidadeItem']; $i++) { 
			$idsItens[$indexUniversal]=$item['idItem'];
			$indexUniversal++;
		}
		$estoqueTotal=$estoqueTotal+$item['quantidadeItem'];
	}

	if(!$tipoEstoque){
		if($estoqueTotal<=0 || $diffTempo<=0 || $horaInicial>$horaAtual || ($horaInicial==$horaAtual && $minInicial>$minAtual)){
			return 0;
		}
	}

	$diaAtual = new DateTime(date("Y-m-d H:i:s"));
	$diaFinal = new DateTime($dataCriacaoEstoque[$nomeEstoque]);
	$interval = $diaAtual->diff($diaFinal);
	// echo $interval->m;
	// var_dump($interval);
	$tempoAtual=date('H:i');
	$horaAtual = explode(':', $tempoAtual, 2)[0];

	$tempoEstoqueCriado = substr($dataCriacaoEstoque[$nomeEstoque],-8,-3);
	$horaEstoqueCriado = explode(':', $tempoEstoqueCriado, 2)[0];

	if(($interval->h>=1 || $interval->d>=1 || $horaAtual>$horaEstoqueCriado)&& !$tipoEstoque)
		$estoque[$nomeEstoque]=false;
	
	if(!$estoque[$nomeEstoque]){
		$estoque[$nomeEstoque]=false;
	}

	//se o calculo for dividido por dia eu faço as verificações abaixo
	if($calculoDivididoPorDia && !$tipoEstoque){
		$diaFinal=$configs[3]['valorConfig'];
		$diaAtual=date('m-d');
		//ano nao importa
		if(intval(explode('-', $diaFinal, 2)[0])==intval(explode('-', $diaAtual, 2)[0]) && intval(explode('-', $diaFinal, 2)[1])<intval(explode('-', $diaAtual, 2)[1]) || intval(explode('-', $diaFinal, 2)[0])<intval(explode('-', $diaAtual, 2)[0])){
			return 0;
		}
		$diaAtual = new DateTime('2023-'.$diaAtual);
		$diaFinal = new DateTime('2023-'.$diaFinal);
		$interval = $diaAtual->diff($diaFinal);

		$qntdDias=$interval->days+1;

		setcookie('qntdDias',$qntdDias,time()+3600,'/');
		// premios divido por hora e por dia

		$estoqueTotalRoletas=0;
		$itensTotalRoletas=consultarEstoque();
		foreach($itensTotalRoletas as $item){
			$estoqueTotalRoletas=$estoqueTotalRoletas+$item['quantidadeItem'];
		}
		$estoqueTotalRoletas=ceil($estoqueTotalRoletas/$qntdDias);
		salvarPremiosPorDia($estoqueTotalRoletas);

		$estoqueTotal=ceil($estoqueTotal/$qntdDias);
	}

	if(!$tipoEstoque){
		if($diffHoras!=0 && $qntdZeros!=0)
			$premioPorHora=ceil($estoqueTotal/$diffHoras);
		else
			$premioPorHora=$estoqueTotal;
	}else{
		$premiosEm10=$configs[5]['valorConfig'];
		if ($estoqueTotal<$premiosEm10)
			$premiosEm10=$estoqueTotal;
	}


	// echo json_decode($estoque[$nomeEstoque]); 
	if(!json_decode($estoque[$nomeEstoque])){
		$estoque=array();
		// echo $diffTempo;
		if(!$tipoEstoque){
			if($qntdZeros!=0){
				if($diffTempo>=60){
					for ($i=0; $i < $qntdZeros; $i++) { 
						array_push($estoque,0);
					}
					shuffle($idsItens);
					for ($i=0; $i < $premioPorHora; $i++) { 
						array_shift($estoque);
						array_push($estoque,$idsItens[$i]);
					}
				}else{
					for ($i=0; $i < $qntdZeros; $i++) { 
						array_push($estoque,0);
					}
					for ($i=0; $i < $estoqueTotal; $i++) { 
						array_shift($estoque);
						array_push($estoque,$idsItens[$i]);
					}
				}
			}else{
				for ($i=0; $i < ($estoqueTotal-($estoqueTotal-1)); $i++) {
					shuffle($idsItens);
					array_push($estoque,$idsItens[$i]);
				}
			}
		}else{
			for ($i=0; $i < 10; $i++) { 
				array_push($estoque,0);
			}
			shuffle($idsItens);
			for ($i=0; $i < $premiosEm10; $i++) { 
				array_shift($estoque);
				array_push($estoque,$idsItens[$i]);
			}
		}
		shuffle($estoque);
		// echo "criou novo"."\n";
		$estoque=json_encode($estoque,TRUE);
		$dataCriacaoEstoque = date('Y-m-d H:i:s');
		$dbh = conectarBD();
		$sql = "UPDATE controle_premios SET valorControle = '$estoque', dataUpdateControle='$dataCriacaoEstoque' WHERE nomeControle='$nomeEstoque';";
		$sql = $sql."INSERT INTO logs_estoque(estoque,dataLog) VALUES ('$estoque','$dataCriacaoEstoque');";
		$cod = $dbh->prepare($sql);
		$cod->execute();
		return "novo";
	}else{
		$dbh = conectarBD();
		$estoque=json_decode($estoque[$nomeEstoque]);
		$sorteado=array_pop($estoque);
		$estoque=json_encode($estoque,TRUE);
		$sql = "UPDATE controle_premios SET valorControle = '$estoque' WHERE nomeControle='$nomeEstoque'";
		$cod = $dbh->prepare($sql);
		$cod->execute();
		return $sorteado;
	}
}

function consultarEstoqueCriado($estoque){
	$dbh = conectarBD();
	$sql = "SELECT valorControle FROM controle_premios WHERE nomeControle='$estoque'";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	if($temp){
		return $temp;
	}else{
		return false;
	}
}

function atualizarPremiosSorteados(){ // done, just need to think when it's going to be triggered - UpdatePrizesAsync
	$dbh = conectarBD();

	$sql = "SELECT valorControle FROM controle_premios WHERE nomeControle='premiosSorteadoDia'";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	$valor=$temp+1;
	$sql = "UPDATE controle_premios SET valorControle='$valor' WHERE nomeControle='premiosSorteadoDia';";
	$cod = $dbh->prepare($sql);
	$cod->execute();
}

function verificarPremioPorDiaEDiaAtual(){
	$dbh = conectarBD();
	$dataAtual = date('Y-m-d');
	$sql = "SELECT dataUpdateControle FROM controle_premios WHERE nomeControle='premiosDia'";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	if($temp==$dataAtual){
		return true;
	}
	else{
		$sql = "UPDATE controle_premios SET valorControle='0', dataUpdateControle='$dataAtual' WHERE nomeControle='premiosDia';";
		$sql = $sql."UPDATE controle_premios SET valorControle='0', dataUpdateControle='$dataAtual' WHERE nomeControle='premiosSorteadoDia';";
		$cod = $dbh->prepare($sql);
		$cod->execute();
		return false;
	}
}

function salvarPremiosPorDia($valor){ // done, just need to think when it's going to be triggered - SavePrizeByDay
	$dbh = conectarBD();
	$dataAtual = date('Y-m-d');
	$sql = "SELECT * FROM controle_premios WHERE nomeControle='premiosDia'";
	@$temp = $dbh->query($sql)->fetchAll()[0];
	if($temp['dataUpdateControle']!=$dataAtual){
		$sql = "UPDATE controle_premios SET valorControle='$valor', dataUpdateControle='$dataAtual' WHERE nomeControle='premiosDia';";
		$sql = $sql."UPDATE controle_premios SET valorControle='0', dataUpdateControle='$dataAtual' WHERE nomeControle='premiosSorteadoDia';";
		$cod = $dbh->prepare($sql);
		$cod->execute();
	}
}

function getItemByID($id){ // done, just need to think when it's going to be triggered - GetItemByIDAsync
	$dbh = conectarBD();
	$sql = "SELECT * FROM item WHERE idItem=$id";
	@$temp = $dbh->query($sql)->fetchAll()[0];
	if($temp)
		return $temp;
	else
		return 0;
}

function getQntdItems(){ // done, just need to think when it's going to be triggered - GetNumberItens
	$dbh = conectarBD();
	$sql = "SELECT COUNT(idItem) FROM item";
	@$temp = $dbh->query($sql)->fetchAll()[0][0];
	if($temp)
		return $temp;
	else
		return 0;
}