<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Number;

/**
 * @internal
 *
 * @coversNothing
 */
class NumberTest extends TestCase
{
    use TextSubclassContractTest;

    protected function newTestSubjectInstance($name): Number
    {
        return new Number($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'number';
    }

    public function test_default_value(): void
    {
        $number = new Number('number');
        $number->defaultValue(0);

        $expected = '<input type="number" name="number" value="0">';
        $this->assertSame($expected, $number->render());
    }

    public function test_min_value(): void
    {
        $number = new Number('number');
        $number->min(5);

        $expected = '<input type="number" name="number" min="5">';
        $this->assertSame($expected, $number->render());
    }

    public function test_max_value(): void
    {
        $number = new Number('number');
        $number->max(10);

        $expected = '<input type="number" name="number" max="10">';
        $this->assertSame($expected, $number->render());
    }

    public function test_step_value(): void
    {
        $number = new Number('number');
        $number->step(1);

        $expected = '<input type="number" name="number" step="1">';
        $this->assertSame($expected, $number->render());
    }

    public function test_placeholder_value(): void
    {
        $number = new Number('number');
        $number->placeholder('Number');

        $expected = '<input type="number" name="number" placeholder="Number">';
        $this->assertSame($expected, $number->render());
    }
}
