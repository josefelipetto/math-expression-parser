<?php

namespace Parser;

use Parser\Helpers\Token;

/*
	Tokenize a numeric expression

	@author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/
class Lexer {

	
	/*
		@var string $expression  Should contain the expression to be tokenized
	*/
	protected $expression;

	/*
		@var array $tokens       A array with token instances
	*/
	protected $tokens;


	public function __construct($expression){

		$this->expression = $expression;
		$this->tokens     = [];

	}

	/*
		Tokenize given expression and put into the tokens property
		@throws InvalidArgumentException
	*/
	protected function tokenize(){

		if(empty($this->expression)){
			throw new InvalidArgumentException("Cannot process an empty expression");
		}

		$expressionSize = strlen($this->expression);
		$i = 0;
		$number = '';

		while($i < $expressionSize){

			$currentChar = $this->expression[$i];
			if($currentChar == ' ' || $currentChar == '\n' || $currentChar == '\t'){
				$i++;
				continue;
			}else if(is_numeric($currentChar)){
				while(is_numeric($currentChar)){
					$number .= $currentChar;
					$i++;
					$currentChar = $this->expression[$i];
				}
				// Checks if it is a flutuan point
				if($currentChar == '.'){
					$number .= $currentChar;
					$i++;
					$currentChar = $this->expession[$i];
					if(is_numeric($currentChar)){
						while(is_numeric($currentChar)){
							$number .= $currentChar;
							$i++;
							$currentChar = $this->expression[$i];
						}	
					}else{
						throw new InvalidArgumentException("Flutuant points shoud be like [0-9].[0-9][0-9]*");
					}

					$token = new Token('N',(float)$number);
					$--;
					$number = '';
				}else if($currentChar === '+'){
					$token = new Token('+');
				}else if($currentChar === '-'){
					$token = new Token('-');
				}else if($currentChar === '*'){
					$token = new Token('*');
				}else if($currentChar === '/'){
					$token = new Token('/');
				}else if($currentChar === '^'){
					$token = new Token('^');
				}else{
					throw new InvalidArgumentException("Invalid token were given");
				}
			}
			$i++;
			$this->tokens[] = $token;
		}
	}
}