<?php
namespace Parser;


use Parser\Contracts\Lexeable;
use Parser\Helpers\Token;
use InvalidArgumentException;


/*
    Tokenize a numeric expression
    @author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/

/**
 * Class Lexer
 * @package Parser
 */
class Lexer implements Lexeable
{

    /**
     * @var string
     */
    protected $expression;


    /**
     * @var array
     */
    protected $tokens;

    /**
     * Lexer constructor.
     * @param $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->tokens     = [];
    }

    /**
     * Each time that function is called, returns the next token on the list(an instance of token)
     * @return mixed|Helpers\Token
     */
    public function getNextToken()
    {
        $ret = current($this->tokens);  // get the current value of the internal pointer

        if (!$ret) {
            return (new Helpers\Token('EOF'));
        }

        next($this->tokens); // increase the internal pointer, but don't return it.

        return $ret;
    }

    /**
     * Tokenize given expression and put into the tokens property
     * @throws InvalidArgumentException
     */
    public function tokenize(): void
    {
        if (empty($this->expression)) {
            throw new InvalidArgumentException('Cannot process an empty expression');
        }

        $expressionSize = strlen($this->expression);
        
        $i = 0;
        $number = '';

        while ($i < $expressionSize) {

            $currentChar = $this->expression[$i];

            if ($currentChar === ' ' || $currentChar === '\n' || $currentChar === '\t') {
                $i++;
                continue;
            }

            if (is_numeric($currentChar)) {

                while (is_numeric($currentChar)) {

                    $number .= $currentChar;
                    $i++;
                    if ($i >= $expressionSize) {
                        break;
                    }
                    $currentChar = $this->expression[$i];
                }

                // Checks if it is a flutuant point
                if ($currentChar === '.') {

                    $number .= $currentChar;
                    $i++;
                    $currentChar = $this->expression[$i];

                    if (is_numeric($currentChar)) {
                        while (is_numeric($currentChar))
                        {
                            $number .= $currentChar;
                            $i++;
                            if ($i >= $expressionSize) {
                                break;
                            }
                            $currentChar = $this->expression[$i];
                        }
                    }
                    else {
                        throw new InvalidArgumentException('Flutuant points shoud be like [0-9].[0-9][0-9]* ');
                    }
                }
                $token = new Token('Number',(float)$number);
                $i--;
                $number = '';
            }
            else if ($currentChar === '+') {
                $token = new Token('+');
            }
            else if ($currentChar === '-') {
                $token = new Token('-');
            }
            else if ($currentChar === '*') {
                $token = new Token('*');
            }
            else if ($currentChar === '/') {
                $token = new Token('/');
            }
            else if ($currentChar === '^') {
                $token = new Token('^');
            }
            else if ($currentChar === '(') {
                $token = new Token('(');
            }
            else if ($currentChar === ')') {
                $token = new Token(')');
            }
            else {
                throw new InvalidArgumentException("Invalid token {$currentChar} were given ");
            }

            $i++;
            $this->tokens[] = $token;
        }
    }
}