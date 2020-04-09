<?php


use Parser\Evaluator;
use Parser\Facades\ExpressionParser;
use Parser\Lexer;
use Parser\Syntactic;
use PHPUnit\Framework\TestCase;

/**
 * Class EvaluatorTest
 */
class EvaluatorTest extends TestCase
{

    /**
     * test a single number
     */
    public function testNumber(): void
    {
        $this->assertEquals(ExpressionParser::parse('2'), 2);
    }

    /**
     * test sum
     */
    public function testPlus() : void
    {
        $this->assertEquals(ExpressionParser::parse('2+2'), 4);
    }

    /**
     * Test minus
     */
    public function testMinus() : void
    {
        $this->assertEquals(ExpressionParser::parse('2-2'), 0);
    }

    /**
     * Test multiplication
     */
    public function testTimes() : void
    {
        $this->assertEquals(ExpressionParser::parse('2*2'), 4);
    }

    /**
     * Test division
     */
    public function testDivision() : void
    {
        $this->assertEquals(ExpressionParser::parse('2/2'), 1);
    }

    /**
     * Test power functions
     */
    public function testPower() : void
    {
        $this->assertEquals(ExpressionParser::parse('2^2'), 4);
    }

    /**
     * Test unary values like -2
     */
    public function testUnary() : void
    {
        $this->assertEquals(ExpressionParser::parse('-2'), -2);
    }

    /**
     * Test parentesis precedence
     */
    public function testWithParentesis() : void
    {
        $this->assertEquals(ExpressionParser::parse('(2+2) * 2'), 8);
    }

    /**
     * Test a complex sentence
     */
    public function testWithComplexSentence() : void
    {
        $this->assertEquals(ExpressionParser::parse('(2+2)^(2*2)/1+3+5'), 264);
    }

    /**
     * Test if the parser works without the facade
     */
    public function testResultWithoutFacade(): void
    {
        $evaluator = new Evaluator(new Syntactic(new Lexer('(2+2)^(2*2)/1+3+5')));

        $this->assertEquals($evaluator->parse(), 264);
    }



}
