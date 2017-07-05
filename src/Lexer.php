<?php
namespace Parser;


use Helpers\Token;


/*
    Tokenize a numeric expression
    @author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/
class Lexer
{


    /*
        @var string $expression  Should contain the expression to be tokenized
    */
    protected $expression;

    /*
        @var array $tokens       A array with token instances
    */
    protected $tokens;

    /*
        Constructor. Try to tokenize the given expression.
    */
    public function __construct($expression)
    {
        $this->expression = $expression;
        $this->tokens     = [];
        try
        {
            $this->tokenize();
        }catch(InvalidArgumentException $e)
        {
            echo $e->getMessage();
        }
    }
    /*
        Each time that function is called, returns the next token on the list(an instance of token)
        @return Token
    */
    public function getNextToken()
    {
        $ret = current($this->tokens);  // get the current value of the internal pointer
        if(!$ret)
            return (new Helpers\Token('EOF'));
        $aux = next($this->tokens); // increase the internal pointer, but don't return it.
        return $ret;
    }
    /*
        Tokenize given expression and put into the tokens property
        @throws InvalidArgumentException
    */
    protected function tokenize()
    {
        if(empty($this->expression))
        {
            throw new \InvalidArgumentException("Cannot process an empty expression");
        }
        $expressionSize = strlen($this->expression);
        
        $i = 0;
        $number = '';
        $currentChar = '';
        while($i < $expressionSize)
        {
            $currentChar = $this->expression[$i];
            if($currentChar === ' ' || $currentChar === '\n' || $currentChar === '\t')
            {
                $i++;
                continue;
            } else if(is_numeric($currentChar))
            {
                while(is_numeric($currentChar))
                {
                    $number .= $currentChar;
                    $i++;
                    if($i >= $expressionSize) break;
                    $currentChar = $this->expression[$i];
                }
                // Checks if it is a flutuan point
                if($currentChar === '.')
                {
                    $number .= $currentChar;
                    $i++;
                    $currentChar = $this->expression[$i];
                    if(is_numeric($currentChar))
                    {
                        while(is_numeric($currentChar))
                        {
                            $number .= $currentChar;
                            $i++;
                            if($i >= $expressionSize) break;
                            $currentChar = $this->expression[$i];
                        }
                    } else
                    {
                        throw new \InvalidArgumentException("Flutuant points shoud be like [0-9].[0-9][0-9]* ");
                    }
                }
                $token = new Helpers\Token('Number',(float)$number);
                $i--;
                $number = '';
            } else if($currentChar === '+')
            {
                $token = new Helpers\Token('+');
            } else if($currentChar === '-')
            {
                $token = new Helpers\Token('-');
            } else if($currentChar === '*')
            {
                $token = new Helpers\Token('*');
            } else if($currentChar === '/')
            {
                $token = new Helpers\Token('/');
            } else if($currentChar === '^')
            {
                $token = new Helpers\Token('^');
            } else if($currentChar === "(")
            {
                $token = new Helpers\Token('(');
            } else if($currentChar === ")")
            {
                $token = new Helpers\Token(')');
            } else
            {
                throw new \InvalidArgumentException("Invalid token {$currentChar} were given ");
            }
            $i++;
            $this->tokens[] = $token;
        }
    }
}