<?php

namespace TypiCMS\Form\Tests;

use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypiCMS\Form\FormBuilder;
use TypiCMS\Form\OldInput\OldInputInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class BindingTest extends TestCase
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

    public function test_can_bind_object(): void
    {
        $this->assertTrue(method_exists($this->form, 'bind'));
    }

    public function test_bind_email(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="email" name="email" value="johndoe@example.com">';
        $result = (string) $this->form->email('email');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_text(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="text" name="first_name" value="John">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_text_with_integer_zero(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="text" name="number" value="0">';
        $result = (string) $this->form->text('number');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_number(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="number" name="number" value="0">';
        $result = (string) $this->form->number('number');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_date(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="date" name="date_of_birth" value="1985-05-06">';
        $result = (string) $this->form->date('date_of_birth');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_date_time_local(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="datetime-local" name="date_and_time_of_birth" value="1985-05-06T16:39">';
        $result = (string) $this->form->dateTimeLocal('date_and_time_of_birth');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_select(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<select name="gender"><option value="male" selected>Male</option><option value="female">Female</option></select>';
        $result = (string) $this->form->select('gender', ['male' => 'Male', 'female' => 'Female']);
        $this->assertEquals($expected, $result);
    }

    public function test_bind_multiple_select(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<select name="favourite_foods[]" multiple="multiple">';
        $expected .= '<option value="fish" selected>Fish</option>';
        $expected .= '<option value="tofu">Tofu</option>';
        $expected .= '<option value="chips" selected>Chips</option>';
        $expected .= '</select>';
        $result = (string) $this->form->select('favourite_foods', ['fish' => 'Fish', 'tofu' => 'Tofu', 'chips' => 'Chips'])->multiple();
        $this->assertEquals($expected, $result);
    }

    public function test_bind_hidden(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="hidden" name="last_name" value="Doe">';
        $result = (string) $this->form->hidden('last_name');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_checkbox(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="terms" value="agree" checked="checked">';
        $result = (string) $this->form->checkbox('terms', 'agree');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_checkbox_array(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

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

    public function test_bind_unset_property(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="text" name="not_set">';
        $result = (string) $this->form->text('not_set');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_magic_property(): void
    {
        $object = new MagicGetter;
        $this->form->bind($object);

        $expected = '<input type="text" name="not_magic" value="foo">';
        $result = (string) $this->form->text('not_magic');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="magic" value="bar">';
        $result = (string) $this->form->text('magic');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_array(): void
    {
        $array = ['first_name' => 'John'];
        $this->form->bind($array);

        $expected = '<input type="text" name="first_name" value="John">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_array_with_missing_key(): void
    {
        $array = ['first_name' => 'John'];
        $this->form->bind($array);

        $expected = '<input type="text" name="last_name">';
        $result = (string) $this->form->text('last_name');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_nested_array(): void
    {
        $array = [
            'address' => [
                'city' => 'Roswell',
                'tree' => [
                    'has' => [
                        'nested' => 'Bird',
                    ],
                ],
            ],
        ];
        $this->form->bind($array);

        $expected = '<input type="text" name="address[city]" value="Roswell">';
        $result = (string) $this->form->text('address[city]');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="address[tree][has][nested]" value="Bird">';
        $result = (string) $this->form->text('address[tree][has][nested]');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_nested_array_with_missing_key(): void
    {
        $array = [
            'address' => [
                'tree' => [
                    'nested' => 'Bird',
                ],
            ],
        ];

        $this->form->bind($array);

        $expected = '<input type="text" name="address[notSet]">';
        $result = (string) $this->form->text('address[notSet]');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_array_with_zero_as_key(): void
    {
        $array = [
            'hotdog' => [
                0 => 'Tube',
                1 => 'Steak',
            ],
        ];

        $this->form->bind($array);

        $expected = '<input type="text" name="hotdog[0]" value="Tube">';
        $result = (string) $this->form->text('hotdog[0]');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="hotdog[1]" value="Steak">';
        $result = (string) $this->form->text('hotdog[1]');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_nested_object(): void
    {
        $object = json_decode(json_encode([
            'address' => [
                'city' => 'Roswell',
                'tree' => [
                    'has' => [
                        'nested' => 'Bird',
                    ],
                ],
            ],
        ]));
        $this->form->bind($object);

        $expected = '<input type="text" name="address[city]" value="Roswell">';
        $result = (string) $this->form->text('address[city]');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="address[tree][has][nested]" value="Bird">';
        $result = (string) $this->form->text('address[tree][has][nested]');
        $this->assertEquals($expected, $result);
    }

    public function test_bind_nested_mixed(): void
    {
        $object = [
            'address' => [
                'city' => 'Roswell',
                'tree' => json_decode(json_encode([
                    'has' => [
                        'nested' => 'Bird',
                    ],
                ])),
            ],
        ];
        $this->form->bind($object);

        $expected = '<input type="text" name="address[city]" value="Roswell">';
        $result = (string) $this->form->text('address[city]');
        $this->assertEquals($expected, $result);

        $expected = '<input type="text" name="address[tree][has][nested]" value="Bird">';
        $result = (string) $this->form->text('address[tree][has][nested]');
        $this->assertEquals($expected, $result);
    }

    public function test_close_unbinds_data(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);
        $this->form->close();

        $expected = '<input type="text" name="first_name">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_against_xss_attacks_in_bound_data(): void
    {
        $object = $this->getStubObject();
        $object->first_name = '" onmouseover="alert(\'xss\')';

        $this->form->bind($object);

        $expected = '<input type="text" name="first_name" value="&quot; onmouseover=&quot;alert(&#039;xss&#039;)">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_value_takes_precedence_over_binding(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="text" name="first_name" value="Mike">';
        $result = (string) $this->form->text('first_name')->value('Mike');
        $this->assertEquals($expected, $result);
    }

    public function test_binding_on_checkbox_takes_precedence_over_default_to_checked(): void
    {
        $object = (object) ['published' => 1];
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="published[]" value="1" checked="checked">';
        $result = (string) $this->form->checkbox('published[]', 1);
        $this->assertEquals($expected, $result);

        $object = (object) ['published' => 0];
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="published[]" value="1">';
        $result = (string) $this->form->checkbox('published[]', 1)->defaultToChecked();
        $this->assertEquals($expected, $result);

        $object = (object) ['published' => true];
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="published[]" value="1" checked="checked">';
        $result = (string) $this->form->checkbox('published[]', 1);
        $this->assertEquals($expected, $result);

        $object = (object) ['published' => false];
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="published[]" value="1">';
        $result = (string) $this->form->checkbox('published[]', 1)->defaultToChecked();
        $this->assertEquals($expected, $result);
    }

    public function test_binding_on_checkbox_takes_precedence_over_default_to_unchecked(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="checkbox" name="published[]" value="1" checked="checked">';
        $expected .= '<input type="checkbox" name="published[]" value="0">';
        $result = (string) $this->form->checkbox('published[]', 1)->defaultToUnchecked();
        $result .= (string) $this->form->checkbox('published[]', 0);
        $this->assertEquals($expected, $result);
    }

    public function test_binding_on_radio_takes_precedence_over_default_to_checked(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="published[]" value="1" checked="checked">';
        $expected .= '<input type="radio" name="published[]" value="0">';
        $result = (string) $this->form->radio('published[]', 1);
        $result .= (string) $this->form->radio('published[]', 0)->defaultToChecked();
        $this->assertEquals($expected, $result);
    }

    public function test_binding_on_radio_takes_precedence_over_default_to_unchecked(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="published[]" value="1" checked="checked">';
        $expected .= '<input type="radio" name="published[]" value="0">';
        $result = (string) $this->form->radio('published[]', 1)->defaultToUnchecked();
        $result .= (string) $this->form->radio('published[]', 0);
        $this->assertEquals($expected, $result);
    }

    public function test_old_input_takes_precedence_over_binding(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->with('first_name')->andReturn('Steve');
        $this->form->setOldInputProvider($oldInput);

        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="text" name="first_name" value="Steve">';
        $result = (string) $this->form->text('first_name');
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_uncheck_on_checkbox_takes_precedence_over_binding(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="terms" value="agree">';
        $result = (string) $this->form->radio('terms', 'agree')->uncheck();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_uncheck_on_radio_takes_precedence_over_binding(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="color" value="green">';
        $result = (string) $this->form->radio('color', 'green')->uncheck();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_check_on_checkbox_takes_precedence_over_binding(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="terms" value="agree" checked="checked">';
        $result = (string) $this->form->radio('terms', 'agree')->check();
        $this->assertEquals($expected, $result);
    }

    public function test_explicit_check_on_radio_takes_precedence_over_binding(): void
    {
        $object = $this->getStubObject();
        $this->form->bind($object);

        $expected = '<input type="radio" name="color" value="green" checked="checked">';
        $result = (string) $this->form->radio('color', 'green')->check();
        $this->assertEquals($expected, $result);
    }

    private function getStubObject(): stdClass
    {
        $obj = new stdClass;

        $obj->email = 'johndoe@example.com';
        $obj->first_name = 'John';
        $obj->last_name = 'Doe';
        $obj->date_of_birth = new DateTime('1985-05-06');
        $obj->date_and_time_of_birth = new DateTime('1985-05-06 16:39');
        $obj->gender = 'male';
        $obj->terms = 'agree';
        $obj->color = 'green';
        $obj->number = '0';
        $obj->favourite_foods = ['fish', 'chips'];
        $obj->published = '1';
        $obj->private = false;

        return $obj;
    }
}

class MagicGetter
{
    public $not_magic = 'foo';

    public function __get(string $key): mixed
    {
        return 'bar';
    }
}
