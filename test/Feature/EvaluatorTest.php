<?php


use Parser\Evaluator;
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{

    private $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new Evaluator();
    }

    public function testNumber(): void
    {
        $this->assertEquals($this->evaluator->parse('2'), 2);
    }

    public function testPlus() : void
    {
        $this->assertEquals($this->evaluator->parse('2+2'), 4);
    }

    public function testMinus() : void
    {
        $this->assertEquals($this->evaluator->parse('2-2'), 0);
    }

    public function testTimes() : void
    {
        $this->assertEquals($this->evaluator->parse('2*2'), 4);
    }

    public function testDivision() : void
    {
        $this->assertEquals($this->evaluator->parse('2/2'), 1);
    }

    public function testPower() : void
    {
        $this->assertEquals($this->evaluator->parse('2^2'), 4);
    }

    public function testUnary() : void
    {
        $this->assertEquals($this->evaluator->parse('-2'), -2);
    }

    public function testWithParentesis() : void
    {
        $this->assertEquals($this->evaluator->parse('(2+2) * 2'), 8);
    }

    public function testWithComplexSentence() : void
    {
        $this->assertEquals($this->evaluator->parse('(2+2)^(2*2)/1+3+5'), 264);
    }



}
