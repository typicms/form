<?php

namespace TypiCMS\Form\Tests;

use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TypiCMS\Form\ErrorStore\ErrorStoreInterface;
use TypiCMS\Form\FormBuilder;

/**
 * @internal
 *
 * @coversNothing
 */
class FormBuilderTest extends TestCase
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

    public function test_form_builder_can_be_created(): void
    {
        $formBuilder = new FormBuilder;
        $this->assertNotNull($formBuilder);
    }

    public function test_form_open(): void
    {
        $expected = '<form method="POST" action="">';
        $result = (string) $this->form->open();
        $this->assertEquals($expected, $result);
    }

    public function test_can_close_form(): void
    {
        $expected = '</form>';
        $result = $this->form->close();
        $this->assertEquals($expected, $result);
    }

    public function test_text_box(): void
    {
        $expected = '<input type="text" name="email">';
        $result = (string) $this->form->text('email');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="first_name">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_number(): void
    {
        $expected = '<input type="number" name="number">';
        $result = (string) $this->form->number('number');
        $this->assertEquals($expected, $result);

        $expected = '<input type="number" name="age">';
        $result = (string) $this->form->number('age');
        $this->assertEquals($expected, $result);
    }

    public function test_password(): void
    {
        $expected = '<input type="password" name="password">';
        $result = (string) $this->form->password('password');
        $this->assertEquals($expected, $result);

        $expected = '<input type="password" name="password_confirmed">';
        $result = (string) $this->form->password('password_confirmed');
        $this->assertEquals($expected, $result);
    }

    public function test_checkbox(): void
    {
        $expected = '<input type="checkbox" name="terms" value="1">';
        $result = (string) $this->form->checkbox('terms');
        $this->assertEquals($expected, $result);

        $expected = '<input type="checkbox" name="terms" value="agree">';
        $result = (string) $this->form->checkbox('terms', 'agree');
        $this->assertEquals($expected, $result);

        $expected = '<input type="checkbox" name="terms" value="agree">';
        $result = (string) $this->form->checkbox('terms')->value('agree');
        $this->assertEquals($expected, $result);
    }

    public function test_radio(): void
    {
        $expected = '<input type="radio" name="terms" value="terms">';
        $result = (string) $this->form->radio('terms');
        $this->assertEquals($expected, $result);

        $expected = '<input type="radio" name="terms" value="agree">';
        $result = (string) $this->form->radio('terms', 'agree');
        $this->assertEquals($expected, $result);

        $expected = '<input type="radio" name="terms" value="agree">';
        $result = (string) $this->form->radio('terms')->value('agree');
        $this->assertEquals($expected, $result);
    }

    public function test_submit(): void
    {
        $expected = '<button type="submit">Sign In</button>';
        $result = (string) $this->form->submit('Sign In');
        $this->assertEquals($expected, $result);
    }

    public function test_reset(): void
    {
        $expected = '<button type="reset">Reset</button>';
        $result = (string) $this->form->reset('Reset');
        $this->assertEquals($expected, $result);
    }

    #[DataProvider('buttonProvider')]
    public function test_button(string $value, ?string $name, string $expected): void
    {
        $result = (string) $this->form->button($value, $name);
        $this->assertEquals($expected, $result);
    }

    public static function buttonProvider(): array
    {
        return [
            ['Click Me', 'click-me', '<button type="button" name="click-me">Click Me</button>'],
            ['Click Me', null, '<button type="button">Click Me</button>'],
        ];
    }

    public function test_select(): void
    {
        $expected = '<select name="color"><option value="red">Red</option><option value="blue">Blue</option></select>';
        $result = (string) $this->form->select('color', ['red' => 'Red', 'blue' => 'Blue']);
        $this->assertEquals($expected, $result);

        $expected = '<select name="fruit"><option value="apple">Granny Smith</option><option value="berry">Blueberry</option></select>';
        $result = (string) $this->form->select('fruit', ['apple' => 'Granny Smith', 'berry' => 'Blueberry']);
        $this->assertEquals($expected, $result);
    }

    public function test_text_area(): void
    {
        $expected = '<textarea name="bio" rows="10" cols="50"></textarea>';
        $result = (string) $this->form->textarea('bio');
        $this->assertEquals($expected, $result);

        $expected = '<textarea name="description" rows="10" cols="50"></textarea>';
        $result = (string) $this->form->textarea('description');
        $this->assertEquals($expected, $result);
    }

    public function test_label(): void
    {
        $expected = '<label>Email</label>';
        $result = (string) $this->form->label('Email');
        $this->assertEquals($expected, $result);

        $expected = '<label>First Name</label>';
        $result = (string) $this->form->label('First Name');
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_against_binary_zero(): void
    {
        $expected = '<input type="checkbox" name="boolean" value="0">';
        $result = (string) $this->form->checkbox('boolean', 0);
        $this->assertEquals($expected, $result);
    }

    public function test_render_radio_against_binary_zero(): void
    {
        $expected = '<input type="radio" name="boolean" value="0">';
        $result = (string) $this->form->radio('boolean', 0);
        $this->assertEquals($expected, $result);
    }

    public function test_no_error_store_returns_null(): void
    {
        $expected = '';
        $result = (string) $this->form->getError('email');
        $this->assertEquals($expected, $result);
    }

    public function test_can_check_for_error_message(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->with('email')->andReturn(true);

        $this->form->setErrorStore($errorStore);

        $result = $this->form->hasError('email');
        $this->assertTrue($result);

        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->with('email')->andReturn(false);

        $this->form->setErrorStore($errorStore);

        $result = $this->form->hasError('email');
        $this->assertFalse($result);
    }

    public function test_can_retrieve_error_message(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->with('email')->andReturn('The e-mail address is invalid.');

        $this->form->setErrorStore($errorStore);

        $expected = 'The e-mail address is invalid.';
        $result = $this->form->getError('email');
        $this->assertEquals($expected, $result);
    }

    public function test_can_retrieve_formatted_error_message(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->with('email')->andReturn('The e-mail address is invalid.');

        $this->form->setErrorStore($errorStore);

        $expected = '<span class="error">The e-mail address is invalid.</span>';
        $result = $this->form->getError('email', '<span class="error">:message</span>');
        $this->assertEquals($expected, $result);
    }

    public function test_formatted_error_message_returns_nothing_if_no_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->with('email')->andReturn(false);

        $this->form->setErrorStore($errorStore);

        $expected = '';
        $result = $this->form->getError('email', '<span class="error">:message</span>');
        $this->assertEquals($expected, $result);
    }

    public function test_hidden(): void
    {
        $expected = '<input type="hidden" name="secret">';
        $result = (string) $this->form->hidden('secret');
        $this->assertEquals($expected, $result);

        $expected = '<input type="hidden" name="token">';
        $result = (string) $this->form->hidden('token');
        $this->assertEquals($expected, $result);
    }

    public function test_file(): void
    {
        $expected = '<input type="file" name="photo">';
        $result = (string) $this->form->file('photo');
        $this->assertEquals($expected, $result);

        $expected = '<input type="file" name="document">';
        $result = (string) $this->form->file('document');
        $this->assertEquals($expected, $result);
    }

    public function test_date(): void
    {
        $expected = '<input type="date" name="date_of_birth">';
        $result = (string) $this->form->date('date_of_birth');
        $this->assertEquals($expected, $result);

        $expected = '<input type="date" name="start_date">';
        $result = (string) $this->form->date('start_date');
        $this->assertEquals($expected, $result);
    }

    public function test_date_time_local(): void
    {
        $expected = '<input type="datetime-local" name="date_and_time_of_birth">';
        $result = (string) $this->form->dateTimeLocal('date_and_time_of_birth');
        $this->assertEquals($expected, $result);

        $expected = '<input type="datetime-local" name="start_date_and_time">';
        $result = (string) $this->form->dateTimeLocal('start_date_and_time');
        $this->assertEquals($expected, $result);
    }

    public function test_email(): void
    {
        $expected = '<input type="email" name="email">';
        $result = (string) $this->form->email('email');
        $this->assertEquals($expected, $result);

        $expected = '<input type="email" name="alternate_email">';
        $result = (string) $this->form->email('alternate_email');
        $this->assertEquals($expected, $result);
    }

    public function test_can_set_csrf_token(): void
    {
        $this->form->setToken('12345');
        $expected = '<input type="hidden" name="_token" value="12345">';
        $this->assertEquals($expected, (string) $this->form->token());
    }

    public function test_can_render_csrf_token(): void
    {
        $this->form->setToken('12345');

        $expected = '<input type="hidden" name="_token" value="12345">';
        $result = (string) $this->form->token();
        $this->assertEquals($expected, $result);
    }

    public function test_token_is_rendered_automatically_on_open_if_set(): void
    {
        $this->form->setToken('12345');

        $expected = '<form method="POST" action=""><input type="hidden" name="_token" value="12345">';
        $result = (string) $this->form->open();
        $this->assertEquals($expected, $result);
    }

    public function test_token_is_not_rendered_automatically_on_open_form_with_get_method_if_set(): void
    {
        $this->form->setToken('12345');

        $expected = '<form method="GET" action="">';
        $result = (string) $this->form->open()->get();
        $this->assertEquals($expected, $result);
    }

    public function test_select_month(): void
    {
        $expected = '<select name="month"><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>';
        $result = (string) $this->form->selectMonth('month');
        $this->assertEquals($expected, $result);
    }

    public function test_remove_class(): void
    {
        $expected = '<input type="text" name="food">';
        $result = (string) $this->form->text('food')->addClass('sandwich pizza')->removeClass('sandwich')->removeClass('pizza');
        $this->assertEquals($expected, $result);
    }

    public function test_get_type_attribute(): void
    {
        $expected = 'radio';
        $result = $this->form->radio('fm-transmission')->getAttribute('type');
        $this->assertEquals($expected, $result);
    }

    public function test_against_xss_attacks_in_attributes(): void
    {
        $expected = '<input type="text" name="meme" lol="catz&quot;&gt;&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;">';
        $result = $this->form->text('meme')->lol('catz"><script>alert("xss")</script>');
        $this->assertEquals($expected, $result);
    }
}
