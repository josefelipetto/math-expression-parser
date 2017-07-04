<?php


namespace Parser;

use Parser\Helpers\Token;

/*
	Generates the abstract syntactic tree(AST)

	@author José Henrique Medeiros Felipetto - jose.felipetto@pucpr.br
*/

class Syntactic {

	
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



	public function __construct($expression){
		$this->lexer = new Lexer($expression);
	}

	/*
		public interface with the world. Starts the whole process
		@throws RunTimeException
	*/
	public function parse(){

		$this->token = $this->lexer->getNextToken(); // get the first token
		$this->ast   = $this->exp();

		if($this->ast){
			$token = $this->read("EOF"); 
			if($token){
				return $this->ast;
			}else{
				throw new RunTimeException("Error Processing Request");
			}
		}

	}

	/*
		Implementation of: Primary ::= Number | (Exp)
		@return mixed[] collection to compose the ast.
		@throws RunTimeException
	*/
	private function primary(){
		
		if($this->token->getType() == "Number"){
			$numberToken = $this->read("Number");
			if($numberToken){
				return ["tag"=>"Number",$numberToken->getLexeme()];
			}else{
				throw new RunTimeException("Error processing a number on the tree");
			}
		}else if($this->token->getType() == "("){
			$bracketToken = $this->read("(");
			if($bracketToken){
				$expression = $this->exp();
				if($expression){
					$bracketToken = $this->read(")");
					if($bracketToken)
						return $expression;
					else
						throw new RunTimeException("Brackets should be balanced");
				}else{
					throw new RunTimeException("After a ( should have another expression");
				}
			}else{
				throw new RunTimeException("Error trying to read ( char");
			}
		}
	}


	/*
		If the token type matches the param, getNextToken and return the old one
		@param  String $type Type of the token you want to compare
		@return Token 		 Return the current token before we get the next
		@throws InvalidArgumentException
	*/
	private function read($type){

		if($this->token->getType() == $type){
			$oldToken = $this->token;
			$this->token = $this->lexer->getNextToken();
			return $oldToken;
		}else{
			throw new \InvalidArgumentException(
					"Cannot read operator {$type} - Current operator is " . $this->token->getType()
			);
			
		}
	}


}