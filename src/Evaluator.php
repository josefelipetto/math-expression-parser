<?php

namespace Parser;

use Parser\Contracts\ASTInterface;
use Parser\Contracts\Evaluatable;
use RunTimeException;

/**
 * Class Evaluator
 * @package Parser
 */
class Evaluator implements Evaluatable
{

    /**
     * @var ASTInterface
     */
    private $astParser;

    /**
     * @param ASTInterface $astParser
     */
    public function __construct(ASTInterface $astParser)
    {
        $this->astParser = $astParser;
    }

    /**
     * @return float|int|mixed
     */
    public function parse()
    {
        return $this->evaluate($this->astParser->parse());
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
