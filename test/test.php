<?php 

use Parser\Evaluator;

$evaluator = new Evaluator();
$evaluator->parse('2+2');
var_dump($evaluator->getResult());

$evaluator->parse('(2+0)^(2+3)/2*3^34*76/(9+123)');
var_dump($evaluator->getResult());



$evaluator->parse('2*3^2');
var_dump($evaluator->getResult());


