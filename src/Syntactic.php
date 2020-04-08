<?php

namespace Parser;

use InvalidArgumentException;
use Parser\Contracts\ASTInterface;
use Parser\Contracts\Lexeable;
use Parser\Helpers\Token;
use RuntimeException;

/*
    Generates the abstract syntactic tree(AST)

    @author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/

/**
 * Class Syntactic
 * @package Parser
 */
class Syntactic implements ASTInterface
{

    /**
     * @var Token
     */
    private $token;


    /**
     * @var Lexer
     */
    private $lexer;


    /**
     * @var
     */
    private $ast;


    /**
     * Syntactic constructor.
     * @param Lexeable $lexeable
     */
    public function __construct(Lexeable $lexeable)
    {
        $this->lexer = $lexeable;
    }

    /**
     *  public interface with the world. Starts the whole process.
        Each ast peace it's composed by an associative array like this:
        [
            'tag' => 'Number|Plus|Minus|Times|Division|Power|Unary',
            left factor,
            optional right factor
        ]
     * @throws RuntimeException
     * @return array|null
     */
    public function parse() : ?array
    {
        $this->lexer->tokenize();

        $this->token = $this->lexer->getNextToken(); // get the first token

        $this->ast = $this->exp();

        if (!$this->ast) {
            throw new RuntimeException('Couldnt generate the AST');
        }

        $token = $this->read('EOF');

        if (!$token) {
            throw new RuntimeException('Error Processing Request');
        }

        return $this->ast;
    }

    /**
     * Implementation of: Exp ::= Term { (+|-) Term }
     * @return mixed[] colletion to compose the ast
     * @throws RuntimeException
     */
    private function exp() : ?array
    {
        $expression = $this->term();

        if (!$expression) {
            throw new RuntimeException('A expression must be provided');
        }

        while ($this->token->getType() === '+' || $this->token->getType() === '-') {

            $operator = $this->read($this->token->getType());

            if (!$operator) {
                throw new RuntimeException("Could not read the operator {$this->token->getType()} ");
            }

            $expressionAux = $this->term();

            if (!$expressionAux) {
                throw new RuntimeException("After the operator {$operator->getType()} must have a expression");
            }

            $expression = [
                'tag' => $operator->getType() === '+' ? 'Plus' : 'Minus',
                $expression,
                $expressionAux
            ];

        }

        return $expression;
    }

    /**
     * Implementation of: Term ::= Factor { (*|/) Factor }
     * @return mixed[] colletion to compose the ast
     * @throws RuntimeException
     */
    private function term() : ?array
    {

        $expression = $this->factor();

        if (!$expression) {
            throw new RuntimeException('A expression must be provided');
        }

        while ($this->token->getType() === '*' || $this->token->getType() === '/') {

            $operator = $this->read($this->token->getType());

            if (!$operator) {
                throw new RuntimeException("Could not read the operator {$this->token->getType()} ");
            }

            $expressionAux = $this->factor();

            if (!$expressionAux) {
                throw new RuntimeException("After a {$operator->getType()} must have a expression");
            }

            $expression = [
                'tag' => $operator->getType() === '*' ? 'Times' : 'Division',
                $expression,
                $expressionAux
            ];
        }

        return $expression;
    }

    /**
     * Implementation of: Factor ::= - Factor | Power
     *  @return mixed[] colletion to compose the ast
     *  @throws RuntimeException
     */
    private function factor(): array
    {
        if ($this->token->getType() === '-') {

            $operator = $this->read('-');

            if (!$operator) {
                throw new RuntimeException('After unary operand, should have a expression');
            }

            return [
                'tag' => 'Unary',
                $this->factor()
            ];
        }

        return $this->power();
    }

    /**
     * Implementation of: Power ::= Primary ^ Power | Primary
     * @return mixed[] colletion to compose the ast
     * @throws RuntimeException
     */
    private function power() : ?array
    {
        $expression = $this->primary();

        if (!$expression) {
            throw new RuntimeException('Expression must be provided');
        }

        while ($this->token->getType() === '^') {

            $operator = $this->read('^');

            if (!$operator) {
                throw new RuntimeException('Could not read operator ^');
            }

            $expressionAux = $this->factor();

            if (!$expressionAux) {
                throw new RuntimeException('After a ^ should have a expression');
            }

            $expression = ['tag'=>'Power',$expression,$expressionAux];
        }

        return $expression;
    }

    /**
     * Implementation of: Primary ::= Number | (Exp)
     * @return mixed[] collection to compose the ast.
     * @throws RuntimeException
     */
    private function primary(): array
    {

        $errorMessages = [];

        if ($this->token->getType() === 'Number') {

            $numberToken = $this->read('Number');

            if (!$numberToken) {
                throw new RuntimeException('Error processing a number on the tree');
            }

            return [
                'tag'=>'Number',
                $numberToken->getLexeme()
            ];

        }

        if ($this->token->getType() === '(') {

            $bracketToken = $this->read('(');

            if ($bracketToken) {

                $expression = $this->exp();

                if ($expression) {

                    $bracketToken = $this->read(')');

                    if ($bracketToken) {
                        return $expression;
                    }

                    $errorMessages[] = 'Brackets should be balanced';
                }

                $errorMessages[] = 'After a ( should have another expression';
            }

            $errorMessages[] = 'Error trying to read ( char';
        }

        throw new RuntimeException(implode('|', $errorMessages));
    }

    /**
     * If the token type matches the param, getNextToken and return the old one
     * @param  String $type Type of the token you want to compare
     * @return Token 		 Return the current token before we get the next
     * @throws InvalidArgumentException
     */
    private function read($type)
    {

        if ($this->token->getType() !== $type) {
            throw new InvalidArgumentException(
                "Cannot read operator {$type} - Current operator is " . $this->token->getType()
            );
        }

        $oldToken = $this->token;
        $this->token = $this->lexer->getNextToken();

        return $oldToken;
    }
}
