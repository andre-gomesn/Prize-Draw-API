<?php

function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
}
cors();
session_start();
include 'mvc.php';
include 'biblioteca.php';
date_default_timezone_set('America/Sao_Paulo');
$is_page = true;
$jogou =0;
// $_SESSION['frameRoleta'] = 2;
if(!isset($_SESSION['naoPremiado'])){
	$_SESSION['naoPremiado'] = 1;
}

if(!isset($_SESSION['som'])){
	$_SESSION['som'] = 1;
}

switch ($page) {
	case 'sortear-item-ajax':
		$sorteado=logica();
		if($sorteado==="novo"){
			$sorteado=logica();
			if (!isset($_POST['simulador'])){
				if (!isset($_POST['idUsuario']))
					salvarSorteado($sorteado);
				else
					salvarSorteado($sorteado,$_POST['idUsuario']);
			}
			if($sorteado!=0){
				$resultadoQuery=retirarItem($sorteado);
				if($resultadoQuery){
					atualizarPremiosSorteados();
				}else{
					$sorteado=0;
				}
			}
		}else{
			if (!isset($_POST['simulador'])){
				if (!isset($_POST['idUsuario']))
					salvarSorteado($sorteado);
				else
					salvarSorteado($sorteado,$_POST['idUsuario']);
			}
			if($sorteado!=0){
				$resultadoQuery=retirarItem($sorteado);
				if($resultadoQuery){
					atualizarPremiosSorteados();
				}else{
					$sorteado=0;
				}
			}
		}
		
		echo $sorteado;
		$is_page=false;
		break;
	case 'controle-estoque':
		$estoque=consultarEstoque();
		$configs=consultarConfigsEstoque();
		$tipoDistribuicao=$configs[4]['valorConfig'];
		break;

	case 'tempo':
		$tempoFinal=consultarTempoFinal();
		$tempoInicial=consultarTempoInicial();
		$estoqueDivididoPorDia=consultarConfigDivididoPorDia();
		if($estoqueDivididoPorDia)
			$diaFinal=consultarDiaFinal();
		else
			$diaFinal=date('m-d');
		break;

	case 'distribuicao':
		$configs=consultarConfigsEstoque();
		// $acelerometro=$configs[];
		$acelerometro=$configs[0]['valorConfig'];
		$tipoDistribuicao=$configs[4]['valorConfig'];
		$premiosEm10=$configs[5]['valorConfig'];
		if($premiosEm10==0)
			$premiosEm10=5;
		break;
	case 'simulador':
		$estoque='estoque1';
		$estoqueAtual = json_decode(consultarEstoqueCriado($estoque),TRUE);
		break;



	case 'confirmar-tempo-ajax':
		if(($_POST['tempoInicial']!="67103" && $_POST['tempoInicial']!="00:00" && $_POST['tempoInicial']!="00:00:00")&&($_POST['tempoFinal']!="67103" && $_POST['tempoFinal']!="00:00" && $_POST['tempoFinal']!="00:00:00")){
			atualizarTempos($_POST['tempoInicial'],$_POST['tempoFinal'],$_POST['dividoPorDia'],$_POST['diaFinal']);
			zerarEstoque();
			zerarVariaveisTempo();
			salvarLogMudancaConfig();
			
			echo true;
		}else{
			echo false;
		}
		$is_page=false;
		break;

	case 'atualizar-estoque-ajax':
		$nomes=json_decode($_GET['nomes']);
		$estoque=json_decode($_GET['estoque']);
		$ids=json_decode($_GET['ids']);
		for ($i=0; $i < count($estoque); $i++) { 
			salvarItens($nomes[$i],$estoque[$i],$ids[$i]);
		}
		$_SESSION['alterouEstoque']=1;
		zerarEstoque();
		zerarVariaveisTempo();
		salvarLogMudancaConfig();
		header("Location:../");
		$is_page=false;
		break;
	case 'alterar-configs-estoque-ajax':
		zerarEstoque();
		zerarVariaveisTempo();
		alterarConfigsEstoque($_POST['acelerometro'],$_POST['tempoEstoque']);
		salvarLogMudancaConfig();
		$is_page=false;
		break;
	case 'alterar-distribuicao-estoque-ajax':
		zerarEstoque();
		zerarVariaveisTempo();
		alterarConfigsEstoque($_POST['distribuicao'],$_POST['valorSelecionado']);
		salvarLogMudancaConfig();
		$is_page=false;
		break;
	case 'toggle-som-ajax':
		$_SESSION['som'] = $_POST['som'];
		$is_page=false;
		break;
	case 'get-name-item-ajax':
		$item=getItemByID($_GET['id']);
		echo $item['nomeItem'];
		$is_page=false;
		break;
	case 'get-qntd-items-ajax':
		echo getQntdItems();
		$is_page=false;
		break;
	case 'get-inventory-ajax':
		echo json_encode($estoque=consultarEstoque());
		break;
}

if ($is_page) {
	include 'core/header.php';
	include 'view/' . $page . '.php';
	include 'core/footer.php';
}