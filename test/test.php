<?php

use Parser\Evaluator;

require 'vendor/autoload.php';


$evaluator = new Evaluator;

var_dump($evaluator('2-2'));

var_dump($evaluator('(2+0)^(2+3)/2*3^34*76/(9+123)'));

var_dump($evaluator('2*3^2'));
