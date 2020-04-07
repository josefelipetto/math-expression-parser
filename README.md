# math-expression-parser
A Math expression parser built in PHP.

# Requirements
* PHP 7.1

# Installation
You can install the component using composer. 
Inside your project folder, type: composer require josefelipetto/math-expression-parser. This will update your
composer.json file to require the component. 

# How to use
```php
use Parser\Evaluator;

$evaluator = new Evaluator;
$res = $evaluator->parse('(2+2)^(2*2)/1+3+5'); // you should pass a string as argument

assert($res === 264); 
```

# Grammar

The parser accepts the following operators: +  ; - ; . ; * ; / ; ^ ; ( ; )
