<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Checkbox;

/**
 * @internal
 *
 * @coversNothing
 */
class CheckboxTest extends TestCase
{
    use InputContractTest;

    protected function newTestSubjectInstance($name): Checkbox
    {
        return new Checkbox($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'checkbox';
    }

    protected function getTestSubjectTag(): string
    {
        return 'input';
    }

    public function test_can_check_checkbox(): void
    {
        $checkbox = new Checkbox('terms');
        $expected = '<input type="checkbox" name="terms" value="1" checked="checked">';
        $result = $checkbox->check()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_uncheck_checkbox(): void
    {
        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->check()->uncheck()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_default_to_checked(): void
    {
        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1" checked="checked">';
        $result = $checkbox->defaultToChecked()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->defaultToChecked()->uncheck()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->uncheck()->defaultToChecked()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_default_to_unchecked(): void
    {
        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->defaultToUnchecked()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1" checked="checked">';
        $result = $checkbox->defaultToUnchecked()->check()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1" checked="checked">';
        $result = $checkbox->check()->defaultToUnchecked()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_default_checked_state(): void
    {
        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1" checked="checked">';
        $result = $checkbox->defaultCheckedState(true)->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->defaultCheckedState(false)->render();

        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1">';
        $result = $checkbox->uncheck()->defaultCheckedState(true)->render();
        $this->assertEquals($expected, $result);

        $checkbox = new Checkbox('above_18');
        $expected = '<input type="checkbox" name="above_18" value="1" checked="checked">';
        $result = $checkbox->check()->defaultCheckedState(false)->render();

        $this->assertEquals($expected, $result);
    }
}
