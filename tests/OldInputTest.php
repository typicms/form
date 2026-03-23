<?php

namespace TypiCMS\Form\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\FormBuilder;
use TypiCMS\Form\OldInput\OldInputInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class OldInputTest extends TestCase
{
    protected FormBuilder $form;

    protected function setUp(): void
    {
        $this->form = new FormBuilder;
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_render_text_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('title')->andReturn('Hello "quotes"');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="text" name="title" value="Hello &quot;quotes&quot;">';
        $result = (string) $this->form->text('title');
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('terms')->andReturn('agree');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="terms" value="agree" checked="checked">';
        $result = (string) $this->form->checkbox('terms', 'agree');
        $this->assertEquals($expected, $result);

        $expected = '<input type="checkbox" name="terms" value="agree" checked="checked">';
        $result = (string) $this->form->checkbox('terms')->value('agree');
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_array_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('favourite_foods[]')->andReturn(['fish', 'chips']);

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="favourite_foods[]" value="fish" checked="checked">';
        $result = (string) $this->form->checkbox('favourite_foods[]', 'fish');
        $this->assertEquals($expected, $result);

        $expected = '<input type="checkbox" name="favourite_foods[]" value="tofu">';
        $result = (string) $this->form->checkbox('favourite_foods[]', 'tofu');
        $this->assertEquals($expected, $result);

        $expected = '<input type="checkbox" name="favourite_foods[]" value="chips" checked="checked">';
        $result = (string) $this->form->checkbox('favourite_foods[]', 'chips');
        $this->assertEquals($expected, $result);
    }

    public function test_render_radio_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('color')->andReturn('green');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="radio" name="color" value="green" checked="checked">';
        $result = (string) $this->form->radio('color', 'green');
        $this->assertEquals($expected, $result);

        $expected = '<input type="radio" name="color" value="green" checked="checked">';
        $result = (string) $this->form->radio('color')->value('green');
        $this->assertEquals($expected, $result);
    }

    public function test_render_select_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('color')->andReturn('blue');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<select name="color"><option value="red">Red</option><option value="blue" selected>Blue</option></select>';
        $result = (string) $this->form->select('color', ['red' => 'Red', 'blue' => 'Blue']);
        $this->assertEquals($expected, $result);

        $expected = '<select name="color"><option value="red">Red</option><option value="blue" selected>Blue</option></select>';
        $result = (string) $this->form->select('color')->options(['red' => 'Red', 'blue' => 'Blue']);
        $this->assertEquals($expected, $result);
    }

    public function test_render_multiple_select_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('favourite_foods')->andReturn(['fish', 'chips']);

        $this->form->setOldInputProvider($oldInput);

        $expected = '<select name="favourite_foods[]" multiple="multiple">';
        $expected .= '<option value="fish" selected>Fish</option>';
        $expected .= '<option value="tofu">Tofu</option>';
        $expected .= '<option value="chips" selected>Chips</option>';
        $expected .= '</select>';
        $result = (string) $this->form->select('favourite_foods', ['fish' => 'Fish', 'tofu' => 'Tofu', 'chips' => 'Chips'])->multiple();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_area_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('bio')->andReturn('This is my bio');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<textarea name="bio" rows="10" cols="50">This is my bio</textarea>';
        $result = (string) $this->form->textarea('bio');
        $this->assertEquals($expected, $result);
    }

    public function test_render_date_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('date_of_birth')->andReturn('1999-04-06');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="date" name="date_of_birth" value="1999-04-06">';
        $result = (string) $this->form->date('date_of_birth');
        $this->assertEquals($expected, $result);
    }

    public function test_render_date_time_local_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('date_and_time_of_birth')->andReturn('1985-05-06T16:39');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="datetime-local" name="date_and_time_of_birth" value="1985-05-06T16:39">';
        $result = (string) $this->form->dateTimeLocal('date_and_time_of_birth');
        $this->assertEquals($expected, $result);
    }

    public function test_render_email_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('email')->andReturn('example@example.com');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="email" name="email" value="example@example.com">';
        $result = (string) $this->form->email('email');
        $this->assertEquals($expected, $result);
    }

    public function test_render_hidden_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('secret')->andReturn('my-secret-string');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="hidden" name="secret" value="my-secret-string">';
        $result = (string) $this->form->hidden('secret');
        $this->assertEquals($expected, $result);
    }

    public function test_rendering_text_area_with_old_input_escapes_dangerous_characters(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('bio')->andReturn('<script>alert("xss!");</script>');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<textarea name="bio" rows="10" cols="50">&lt;script&gt;alert(&quot;xss!&quot;);&lt;/script&gt;</textarea>';
        $result = (string) $this->form->textarea('bio');
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_against_binary_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('agree_to_terms')->andReturn('1');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="agree_to_terms" value="1" checked="checked">';
        $result = (string) $this->form->checkbox('agree_to_terms', 1);
        $this->assertEquals($expected, $result);
    }

    public function test_old_input_on_checkbox_takes_precedence_over_default_to_checked(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('published')->andReturn('0');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="published" value="1">';
        $result = (string) $this->form->checkbox('published', 1)->defaultToChecked();
        $this->assertEquals($expected, $result);
    }

    public function test_old_input_on_checkbox_takes_precedence_over_default_to_unchecked(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('published')->andReturn('1');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="published" value="1" checked="checked">';
        $result = (string) $this->form->checkbox('published', 1)->defaultToUnchecked();
        $this->assertEquals($expected, $result);
    }

    public function test_old_input_on_radio_takes_precedence_over_default_to_checked(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('published')->andReturn('0');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="radio" name="published" value="1">';
        $result = (string) $this->form->radio('published', 1)->defaultToChecked();
        $this->assertEquals($expected, $result);
    }

    public function test_old_input_on_radio_takes_precedence_over_default_to_unchecked(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('published')->andReturn('1');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="radio" name="published" value="1" checked="checked">';
        $result = (string) $this->form->radio('published', 1)->defaultToUnchecked();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_uncheck_on_checkbox_takes_precedence_over_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('terms')->andReturn('agree');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="terms" value="agree">';
        $result = (string) $this->form->checkbox('terms', 'agree')->uncheck();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_uncheck_on_radio_takes_precedence_over_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('color')->andReturn('green');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="radio" name="color" value="green">';
        $result = (string) $this->form->radio('color', 'green')->uncheck();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_check_on_checkbox_takes_precedence_over_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('terms')->andReturn('agree');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="checkbox" name="terms" value="agree" checked="checked">';
        $result = (string) $this->form->checkbox('terms', 'agree')->check();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_check_on_radio_takes_precedence_over_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('color')->andReturn('green');

        $this->form->setOldInputProvider($oldInput);

        $expected = '<input type="radio" name="color" value="green" checked="checked">';
        $result = (string) $this->form->radio('color', 'green')->check();
        $this->assertEquals($expected, $result);
    }
}
