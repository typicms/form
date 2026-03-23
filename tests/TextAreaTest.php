<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\TextArea;

/**
 * @internal
 *
 * @coversNothing
 */
class TextAreaTest extends TestCase
{
    use InputContractTest;

    protected function newTestSubjectInstance($name): TextArea
    {
        return new TextArea($name);
    }

    protected function getTestSubjectType() {}

    protected function elementRegExp(string $attributes): string
    {
        return '/\A<textarea .*?'.$attributes.'( .*?|)><\/textarea>\z/';
    }

    public function test_render_basic_text_area(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50"></textarea>';
        $result = $textarea->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50"></textarea>';
        $result = $textarea->render();

        $this->assertEquals($expected, $result);
    }

    public function test_render_with_custom_rows(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="5" cols="50"></textarea>';
        $result = $textarea->rows(5)->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="3" cols="50"></textarea>';
        $result = $textarea->rows(3)->render();

        $this->assertEquals($expected, $result);
    }

    public function test_render_with_custom_cols(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="30"></textarea>';
        $result = $textarea->cols(30)->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="15"></textarea>';
        $result = $textarea->cols(15)->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_value(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50">Sample text</textarea>';
        $result = $textarea->value('Sample text')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50">Some more text</textarea>';
        $result = $textarea->value('Some more text')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_render_with_placeholder(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50" placeholder="Your bio"></textarea>';
        $result = $textarea->placeholder('Your bio')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50" placeholder="Product description"></textarea>';
        $result = $textarea->placeholder('Product description')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_default_value(): void
    {
        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50">My information</textarea>';
        $result = $textarea->defaultValue('My information')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50">Your information</textarea>';
        $result = $textarea->defaultValue('Your information')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50">Testing</textarea>';
        $result = $textarea->value('Testing')->defaultValue('My information')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50">Testing</textarea>';
        $result = $textarea->value('Testing')->defaultValue('Your information')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('bio');
        $expected = '<textarea name="bio" rows="10" cols="50">Testing</textarea>';
        $result = $textarea->defaultValue('My information')->value('Testing')->render();

        $this->assertEquals($expected, $result);

        $textarea = new TextArea('description');
        $expected = '<textarea name="description" rows="10" cols="50">Testing</textarea>';
        $result = $textarea->defaultValue('Your information')->value('Testing')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_can_add_attributes_through_magic_methods(): void
    {
        $text = new TextArea('bio');
        $text = $text->maxlength('5');

        $expected = '<textarea name="bio" rows="10" cols="50" maxlength="5"></textarea>';
        $result = $text->render();
        $this->assertEquals($expected, $result);
    }
}
