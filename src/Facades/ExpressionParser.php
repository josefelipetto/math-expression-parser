<?php


namespace Parser\Facades;


use Parser\Evaluator;
use Parser\Lexer;
use Parser\Syntactic;

/**
 * Class ExpressionParser
 * @package Parser\Facades
 */
class ExpressionParser
{
    /**
     * @param $expression
     * @return float|int|mixed
     */
    public static function parse($expression)
    {
        $evaluator = new Evaluator(
            new Syntactic(
                new Lexer($expression)
            )
        );

        return $evaluator->parse();
    }

}