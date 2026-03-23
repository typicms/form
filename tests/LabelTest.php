<?php

namespace TypiCMS\Form\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Element;
use TypiCMS\Form\Elements\Label;

/**
 * @internal
 *
 * @coversNothing
 */
class LabelTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_label_can_be_created(): void
    {
        $label = new Label('Email');
        $this->assertNotNull($label);
    }

    public function test_render_basic_label(): void
    {
        $label = new Label('Email');
        $expected = '<label>Email</label>';
        $result = $label->render();

        $this->assertEquals($expected, $result);

        $label = new Label('Password');
        $expected = '<label>Password</label>';
        $result = $label->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_render_for_id(): void
    {
        $label = new Label('Email');

        $expected = '<label for="email">Email</label>';
        $result = $label->forId('email')->render();

        $this->assertEquals($expected, $result);

        $label = new Label('Password');

        $expected = '<label for="pass">Password</label>';
        $result = $label->forId('pass')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_wrap_before_element(): void
    {
        $element = Mockery::mock(Element::class);
        $element->shouldReceive('render')->once()->andReturn('<input>');
        $label = new Label('Email');

        $expected = '<label>Email<input></label>';
        $result = $label->before($element)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_wrap_after_element(): void
    {
        $element = Mockery::mock(Element::class);
        $element->shouldReceive('render')->once()->andReturn('<input>');
        $label = new Label('Email');

        $expected = '<label><input>Email</label>';
        $result = $label->after($element)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_retrieve_element(): void
    {
        $element = Mockery::mock(Element::class);
        $label = new Label('Email');
        $result = $label->after($element)->getControl();
        $this->assertEquals($element, $result);
    }
}
