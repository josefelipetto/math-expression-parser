<?php

namespace Parser;

use Token;

/*
    Generates the abstract syntactic tree(AST)

    @author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/

class Syntactic
{

    /*
        @var Token $token  Control the current token to be processed.
    */
    private $token;

    /*
        @var Lexer $lexer  Should contain the instance of the Lexer
    */
    private $lexer;

    /*
        @var mixed[] $ast  Should contain the ast generated
    */
    private $ast;

    /*
        Constructor. Initiate the lexer instance
    */
    public function __construct($expression)
    {
        $this->lexer = new Lexer($expression);
    }

    /*
        public interface with the world. Starts the whole process.
        Each ast peace it's composed by an associative array like this:
            [
                'tag' => 'Number|Plus|Minus|Times|Division|Power|Unary',
                left factor,
                optional right factor
            ]
        @throws \RunTimeException
    */
    public function parse()
    {

        $this->token = $this->lexer->getNextToken(); // get the first token

        try
        {
            $this->ast   = $this->exp();
        }catch(\RunTimeException $e)
        {
            echo $e->getMessage();
        }

        if($this->ast)
        {
            $token = $this->read("EOF");
            if($token)
            {
                return $this->ast;
            } else{
                throw new \RunTimeException("Error Processing Request");
            }
        }
    }

    /*
        Implementation of: Exp ::= Term { (+|-) Term }
        @return mixed[] colletion to compose the ast
        @throws \RunTimeException
    */
    private function exp()
    {

        $expression = $this->term();
        if($expression)
        {
            while($this->token->getType() === "+" || $this->token->getType() === "-")
            {
                $operator = $this->read($this->token->getType());
                if($operator)
                {
                    $expressionAux = $this->term();
                    if($expressionAux)
                    {
                        $expression = [
                            "tag" => $operator->getType() === "+" ? "Plus" : "Minus",
                            $expression,
                            $expressionAux
                        ];
                    } else
                    {
                        throw new \RunTimeException("After the operator {$operator->getType()} must have a expression");
                    }
                } else
                {
                    throw new \RunTimeException("Could not read the operator {$this->token->getType()} ");
                }
            }
            return $expression;
        } else
        {
            throw new \RunTimeException("A expression must be provided");
        }
    }

    /*
        Implementation of: Term ::= Factor { (*|/) Factor }
        @return mixed[] colletion to compose the ast
        @throws \RunTimeException
    */
    private function term()
    {

        $expression = $this->factor();
        if($expression)
        {
            while($this->token->getType() === "*" || $this->token->getType() === "/")
            {
                $operator = $this->read($this->token->getType());
                if($operator)
                {
                    $expressionAux = $this->factor();
                    if($expressionAux)
                    {
                        $expression = [
                            "tag" => $operator->getType() === "*" ? "Times" : "Division",
                             $expression,
                             $expressionAux
                        ];
                    } else
                    {
                        throw new \RunTimeException("After a {$operator->getType()} must have a expression");
                    }
                } else
                {
                    throw new \RunTimeException("Could not read the operator {$this->token->getType()} ");
                }
            }
            return $expression;
        } else
        {
            throw new \RunTimeException("A expression must be provided");
        }
    }

    /*
        Implementation of: Factor ::= - Factor | Power
        @return mixed[] colletion to compose the ast
        @throws \RunTimeException
    */
    private function factor()
    {
        if($this->token->getType() === "-")
        {
            $operator = $this->read("-");
            if($operator)
            {
                return ["tag" => "Unary", $this->factor()];
            } else
            {
                throw new \RunTimeException("After unary operand, should have a expression");
            }
        } else
        {
            return $this->power();
        }
    }

    /*
        Implementation of: Power ::= Primary ^ Power | Primary
        @return mixed[] colletion to compose the ast
        @throws \RunTimeException
    */
    private function power()
    {
        $expression = $this->primary();
        if($expression)
        {
            while($this->token->getType() === "^")
            {
                $operator = $this->read("^");
                if($operator)
                {
                    $expressionAux = $this->factor();
                    if($expressionAux)
                    {
                        $expression = ["tag"=>"Power",$expression,$expressionAux];
                    } else
                    {
                        throw new \RunTimeException("After a ^ should have a expression");
                    }
                } else
                {
                    throw new \RunTimeException("Could not read operator ^");
                }
            }
            return $expression;
        } else {
            throw new \RunTimeException("Expression must be provided");
        }
    }

    /*
        Implementation of: Primary ::= Number | (Exp)
        @return mixed[] collection to compose the ast.
        @throws \RunTimeException
    */
    private function primary()
    {

        if($this->token->getType() === "Number")
        {
            $numberToken = $this->read("Number");
            if($numberToken)
            {
                return ["tag"=>"Number",$numberToken->getLexeme()];
            } else
            {
                throw new \RunTimeException("Error processing a number on the tree");
            }
        } else if($this->token->getType() === "(")
        {
            $bracketToken = $this->read("(");
            if($bracketToken)
            {
                $expression = $this->exp();
                if($expression)
                {
                    $bracketToken = $this->read(")");
                    if($bracketToken){
                        return $expression;
                    } else{
                        throw new \RunTimeException("Brackets should be balanced");
                    }
                } else
                {
                    throw new \RunTimeException("After a ( should have another expression");
                }
            } else
            {
                throw new \RunTimeException("Error trying to read ( char");
            }
        }
    }

    /*
        If the token type matches the param, getNextToken and return the old one
        @param  String $type Type of the token you want to compare
        @return Token 		 Return the current token before we get the next
        @throws InvalidArgumentException
    */
    private function read($type)
    {

        if($this->token->getType() === $type)
        {
            $oldToken = $this->token;
            $this->token = $this->lexer->getNextToken();
            return $oldToken;
        } else
        {
            throw new \InvalidArgumentException(
                    "Cannot read operator {$type} - Current operator is " . $this->token->getType()
            );
        }
    }
}
