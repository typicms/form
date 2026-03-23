<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Button;

/**
 * @internal
 *
 * @coversNothing
 */
class ButtonTest extends TestCase
{
    public function test_button_can_be_created(): void
    {
        $submit = new Button('Click Me', 'click-me');
        $this->assertNotNull($submit);
    }

    public function test_render_basic_button(): void
    {
        $button = new Button('Click Me', 'click-me');
        $expected = '<button type="button" name="click-me">Click Me</button>';
        $result = $button->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_change_value(): void
    {
        $button = new Button('Button');
        $button->value('Click Me');

        $expected = '<button type="button">Click Me</button>';
        $result = $button->render();

        $this->assertEquals($expected, $result);
    }
}
