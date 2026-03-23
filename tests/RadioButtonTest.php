<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\RadioButton;

/**
 * @internal
 *
 * @coversNothing
 */
class RadioButtonTest extends TestCase
{
    use InputContractTest;

    protected function newTestSubjectInstance($name): RadioButton
    {
        return new RadioButton($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'radio';
    }

    public function test_default_to_checked(): void
    {
        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18" checked="checked">';
        $result = $checkbox->defaultToChecked()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18">';
        $result = $checkbox->defaultToChecked()->uncheck()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18">';
        $result = $checkbox->uncheck()->defaultToChecked()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_default_to_unchecked(): void
    {
        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18">';
        $result = $checkbox->defaultToUnchecked()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18" checked="checked">';
        $result = $checkbox->defaultToUnchecked()->check()->render();

        $this->assertEquals($expected, $result);

        $checkbox = new RadioButton('above_18');
        $expected = '<input type="radio" name="above_18" value="above_18" checked="checked">';
        $result = $checkbox->check()->defaultToUnchecked()->render();

        $this->assertEquals($expected, $result);
    }
}
