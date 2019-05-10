<?php

namespace Parser\Helpers;

/*
    Token class

    @author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/

class Token
{

    /*
        @var string $type Type of the token
    */
    protected $type;

    /*
        @var mixed $lexeme Lexeme of the token. Optional argument
    */
    protected $lexeme;

    public function __construct($type,$lexeme = '')
    {

        $this->type   = $type;
        $this->lexeme = $lexeme;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getLexeme() : ?string
    {
        return $this->lexeme;
    }
}
