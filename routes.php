<?php

require_once 'controller/Controller.php';

$url = (!empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)) ? filter_input(INPUT_GET, 'url', FILTER_DEFAULT) : '');
$url = explode("/",$url);
$controller = new Controller();

if($url[0] != 'planos')
{
    echo "<p>Pagina não Existe...</p>"; 
    exit;
} 

switch($url[1])
{
    case 'historico':
        $controller->exibeHistorico();
        break;

    case 'cadastro':
        $controller->salvarDados();
        break;

    case 'teste':
        $controller->consultaPlanos();
        break;

    default:
        echo "<p>Pagina não Existe...</p>";

}
