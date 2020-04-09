# math-expression-parser
A Math expression parser built in PHP.

# Requirements
* PHP 7.1

# Installation
You can install the component using composer. 
Inside your project folder, type: composer require josefelipetto/math-expression-parser. This will update your
composer.json file to require the component. 

# How to use

You can use in two ways: 

* Using the ExpressionParser facade that already mount the dependecies of the Evaluator class and returns the result, like this: 
```php
use Parser\Facades\ExpressionParser;

$result = ExpressionParser::parse('(2+2)^(2*2)/1+3+5');

assert($result === 264); 
```

* Or you can actually use you own implementation of a Lexer and/or AST by providing the dependencies to the classes, like this:
```php
use Parser\Evaluator;
use Parser\Lexer; // Or your Lexer
use Parser\Syntactic; // Or your AST

$evaluator = new Evaluator(new Syntactic(new Lexer('(2+2)^(2*2)/1+3+5')));
    
assert($evaluator->parse() === 264);
```

# Grammar

The parser accepts the following operators: +  ; - ; . ; * ; / ; ^ ; ( ; )
