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
        @var $result Float Where the result will be stored
    */
    protected $result;

    /*
        Constructor. Generate the ast and try to evaluate.
    */
    public function __construct()
    {
        // Constructor Left Blank
    }

    /*
        Initiate the whole process and set the result property.

    */
    public function parse(string $expression)
    {
        $this->expression = $expression;
        $syntactic = new Syntactic($this->expression);

        // try to generate the abstract syntactic tree (ast)
        try
        {
            $this->ast = $syntactic->parse();
        }catch(\RunTimeException $e){
            echo $e->getMessage();
        }

        // try to evaluate the expression
        try
        {
            $this->result = $this->evaluate($this->ast);
        }catch(\RunTimeException $e)
        {
            echo $e->getMessage();
        }
    }

    /*
        public interface to get the result
        @return Float
    */
    public function getResult()
    {
        return $this->result;
    }

    /*
        Evaluate recursively the ast.
        @return  Float
        @throws  RunTimeException

    */
    private function evaluate($ast)
    {
        if($ast['tag'] == 'Number')
        {
            return $ast[0];
        } else if($ast['tag'] == 'Plus')
        {
            return $this->evaluate($ast[0]) + $this->evaluate($ast[1]);
        } else if($ast['tag'] == 'Minus')
        {
            return $this->evaluate($ast[0] - $ast[1]);
        } else if($ast['tag'] == 'Times')
        {
            return $this->evaluate($ast[0]) * $this->evaluate($ast[1]);
        } else if($ast['tag'] == 'Division')
        {
            if($ast[1] == 0)
                throw new RunTimeException("Division by 0");
            return $this->evaluate($ast[0]) / $this->evaluate($ast[1]);
        } else if($ast['tag'] == 'Power')
        {
            return pow($this->evaluate($ast[0]),$this->evaluate($ast[1]));
        } else if($ast['tag'] == 'Unary')
        {
            return (0 - $this->evaluate($ast[0]));
        }
    }
}
