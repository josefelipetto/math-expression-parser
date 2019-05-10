<?php

namespace Parser;

class Evaluator
{

    /*
        @var $expression String Should have the expression to be evaluated.
    */
    protected $expression;

    /*
        @var $ast mixed[] The abstract syntactic tree
    */
    protected $ast;

    /*
        Initiate the whole process and set the result property.

    */
    public function __invoke(string $expression)
    {

        $this->expression = $expression;
        $syntactic = new Syntactic($this->expression);

        // try to generate the abstract syntactic tree (ast)
        try
        {
            $this->ast = $syntactic->parse();
            return $this->evaluate($this->ast);
        }
        catch(\RunTimeException $e){
            echo $e->getMessage();
        }

    }

    /*
        Evaluate recursively the ast.
        @return  Float
        @throws  RunTimeException

    */
    private function evaluate($ast)
    {
        if($ast['tag'] === 'Number')
        {
            return $ast[0];
        }

        if($ast['tag'] === 'Plus')
        {
            return $this->evaluate($ast[0]) + $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Minus')
        {
            return $this->evaluate($ast[0]) - $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Times')
        {
            return $this->evaluate($ast[0]) * $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Division')
        {
            if($ast[1] === 0)
            {
                throw new \RunTimeException('Division by 0');
            }

            return $this->evaluate($ast[0]) / $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Power')
        {
            return $this->evaluate($ast[0]) ** $this->evaluate($ast[1]);
        }

        if($ast['tag'] === 'Unary')
        {
            return (0 - $this->evaluate($ast[0]));
        }
    }
}
