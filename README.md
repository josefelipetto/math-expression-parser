# math-expression-parser
A Math expression parser built in PHP.

# Requirements
* PHP 7.0 (still have to test on previous php versions)

# Installation
You can install the component using composer. 
Inside your project folder, type: composer require josefelipetto/math-expression-parser. This will update your
composer.json file to require the component. 

# How to use
```php
use Parser\Evaluator;

$evaluator = new Evaluator();
$evaluator->parse('(2+0)^(2+3)/2*3^34*76/(9+123)'); // you should pass a string as argument

echo $evaluator->getResult(); // Get the result of the given expression on parse function

```

# Grammar

The parser accepts the following operators: +  ; - ; . ; * ; / ; ^ ; ( ; )
