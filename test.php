<?php 

use Parser\Evaluator;

$evaluator = new Evaluator('2.1');
$evaluator->parse();

echo $evaluator->getResult();

