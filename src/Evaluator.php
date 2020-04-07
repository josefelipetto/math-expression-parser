<?php

namespace Parser;

use RunTimeException;

/**
 * Class Evaluator
 * @package Parser
 */
class Evaluator
{

    /**
     * @var
     */
    protected $expression;

    /**
     * @var
     */
    protected $ast;

    /**
     * @param string $expression
     * @return float|int|mixed
     * @throws RuntimeException
     */
    public function parse(string $expression)
    {

        $this->expression = $expression;

        $syntactic = new Syntactic($this->expression);

        $this->ast = $syntactic->parse();

        return $this->evaluate($this->ast);

    }

    /**
     * @param $ast
     * @return float|int|mixed
     */
    private function evaluate($ast)
    {
        if($ast['tag'] === 'Number') {
            return $ast[0];
        }

        if($ast['tag'] === 'Plus') {
            return $this->evaluate($ast[0]) + $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Minus') {
            return $this->evaluate($ast[0]) - $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Times') {
            return $this->evaluate($ast[0]) * $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Division') {

            if($ast[1] === 0) {
                throw new RunTimeException('Division by 0');
            }

            return $this->evaluate($ast[0]) / $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Power') {
            return $this->evaluate($ast[0]) ** $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Unary') {
            return (0 - $this->evaluate($ast[0]));
        }
    }
}
