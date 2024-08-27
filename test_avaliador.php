<?php

require_once __DIR__ . "/vendor/autoload.php";

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Model\Avaliador;

$leilao = new Leilao("Leilão de um fiat uno");

$jhonattan = new Usuario("Jhonattan");
$lucas = new Usuario("Lucas");

$leilao->recebeLance(new Lance($jhonattan, 8000));
$leilao->recebeLance(new Lance($lucas, 8500));
$leilao->recebeLance(new Lance($jhonattan, 9000));

$avaliador = new Avaliador();

$avaliador->avalia($leilao);

echo "O maior lance é: {$avaliador->getMaiorValor()}";
