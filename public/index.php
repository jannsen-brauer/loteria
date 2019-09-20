<?php
require __DIR__.'/../vendor/autoload.php';

use App\Loteria;

$quantidadeDezenas = \rand(6,10);
$totalJogos        = \rand(1,5);

$loteria = new Loteria($quantidadeDezenas, $totalJogos);
try
{
    $loteria->gerarJogos()
            ->realizaSorteio();
    echo $loteria->confereJogos();
}
catch (\Exception $e)
{
    die($e->getMessage());
}
