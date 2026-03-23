<?php

namespace TypiCMS\Form\Tests;

trait InputContractTest
{
    abstract protected function newTestSubjectInstance($name);

    abstract protected function getTestSubjectType();

    protected function elementRegExp(string $attributes): string
    {
        return '/\A<input type="'.$this->getTestSubjectType().'" .*?'.$attributes.'( .*?|)>\z/';
    }

    public function test_text_can_be_created(): void
    {
        $this->assertNotNull($this->newTestSubjectInstance('email'));
    }

    public function test_required(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->required()->render();

        $message = 'required attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('required="required"'), $result, $message);
    }

    public function test_conditional_required(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->required(false)->render();

        $message = 'required attribute shouldnt be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('required="required"'), $result, $message);
    }

    public function test_autofocus(): void
    {
        $text = $this->newTestSubjectInstance('');
        $result = $text->autofocus()->render();

        $message = 'autofocus attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('autofocus="autofocus"'), $result, $message);
    }

    public function test_unfocus(): void
    {
        $pattern = 'autofocus="autofocus"';

        $text = $this->newTestSubjectInstance('');
        $result = $text->unfocus()->render();

        $message = 'autofocus attribute should not be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);

        $text = $this->newTestSubjectInstance('');
        $result = $text->autofocus()->unfocus()->render();

        $message = 'autofocus attribute should be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);
    }

    public function test_optional(): void
    {
        $pattern = 'required="required"';

        $text = $this->newTestSubjectInstance('email');
        $result = $text->optional()->render();

        $message = 'required attribute should not be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $result = $text->required()->optional()->render();

        $message = 'required attribute should be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);
    }

    public function test_disable(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->disable()->render();

        $message = 'disabled attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('disabled="disabled"'), $result, $message);
    }

    public function test_conditional_disable(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->required(false)->render();

        $message = 'disabled attribute shouldnt be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('disabled="disabled"'), $result, $message);
    }

    public function test_ready_only(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->readonly()->render();

        $message = 'readonly attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('readonly="readonly"'), $result, $message);
    }

    public function test_conditional_ready_only(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->required(false)->render();

        $message = 'readonly attribute shouldnt be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('readonly="readonly"'), $result, $message);
    }

    public function test_enable_disabled(): void
    {
        $pattern = 'disabled="disabled"';

        $text = $this->newTestSubjectInstance('email');
        $result = $text->enable()->render();

        $message = 'disabled attribute should not be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $result = $text->disable()->enable()->render();

        $message = 'disabled attribute should not be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('disabled="disabled"'), $result, $message);
    }

    public function test_enable_read_only(): void
    {
        $pattern = 'readonly="readonly"';

        $text = $this->newTestSubjectInstance('email');
        $result = $text->enable()->render();

        $message = 'readonly attribute should not be set';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp($pattern), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $result = $text->readonly()->enable()->render();

        $message = 'readonly attribute should not be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('readonly="readonly"'), $result, $message);
    }

    public function test_can_be_cast_to_string(): void
    {
        $text = $this->newTestSubjectInstance('email');

        $expected = $text->render();
        $result = (string) $text;
        $message = 'Casting input element to string should return the rendered element';
        $this->assertEquals($expected, $result, $message);
    }

    public function test_can_render_basic_form_control(): void
    {
        $text = $this->newTestSubjectInstance('email');

        $result = $text->render();
        $message = 'name attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('name="email"'), $result, $message);

        $text = $this->newTestSubjectInstance('first_name');

        $result = $text->render();
        $message = 'name attribute should be changed';
        $this->assertMatchesRegularExpression($this->elementRegExp('name="first_name"'), $result, $message);
    }

    public function test_can_render_with_id(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $text = $text->id('email_field');

        $result = $text->render();
        $message = 'id attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('id="email_field"'), $result, $message);

        $text = $this->newTestSubjectInstance('first_name');
        $text = $text->id('name_field');

        $result = $text->render();
        $message = 'id attribute should be changed';
        $this->assertMatchesRegularExpression($this->elementRegExp('id="name_field"'), $result, $message);
    }

    public function test_can_render_with_value(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $text = $text->value('example@example.com');

        $result = $text->render();
        $message = 'value attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('value="example@example.com"'), $result, $message);

        $text = $this->newTestSubjectInstance('first_name');
        $text = $text->value('test@test.com');

        $result = $text->render();
        $message = 'value attribute should be changed';
        $this->assertMatchesRegularExpression($this->elementRegExp('value="test@test.com"'), $result, $message);

        $text = $this->newTestSubjectInstance('first_name');
        $text = $text->value(null);

        $result = $text->render();
        $message = 'value attribute should be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('value="test@test.com"'), $result, $message);
    }

    public function test_can_render_with_class(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $text = $text->addClass('error');

        $result = $text->render();
        $message = 'class attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('class="error"'), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $text = $text->addClass('success');

        $result = $text->render();
        $message = 'class attribute should be changed';
        $this->assertMatchesRegularExpression($this->elementRegExp('class="success"'), $result, $message);
    }

    public function test_can_render_with_placeholder(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $text = $text->placeholder('error');

        $result = $text->render();
        $message = 'placeholder attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('placeholder="error"'), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $text = $text->placeholder('success');

        $result = $text->render();
        $message = 'placeholder attribute should be removed';
        $this->assertMatchesRegularExpression($this->elementRegExp('placeholder="success"'), $result, $message);
    }

    public function test_custom_attribute(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->attribute('custom', 'test-value')->render();

        $message = 'custom attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('custom="test-value"'), $result, $message);
        $result = $text->clear('custom')->render();

        $message = 'custom attribute should be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('custom="test-value"'), $result, $message);
    }

    public function test_data_attribute(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->data('sample', 'test-value')->render();

        $message = 'data-sample attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('data-sample="test-value"'), $result, $message);

        $text = $this->newTestSubjectInstance('email');
        $result = $text->data('custom', 'another-value')->render();

        $message = 'data-custom attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('data-custom="another-value"'), $result, $message);
    }

    public function test_array_of_data_attributes(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->data(['custom' => 'value', 'other' => 'value2'])->render();

        $message = 'data-custom attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('data-custom="value"'), $result, $message);
        $message = 'data-other attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('data-other="value2"'), $result, $message);
    }

    public function test_can_remove_class(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $text = $text->addClass('error');

        $result = $text->render();
        $message = 'class attribute should be set';
        $this->assertMatchesRegularExpression($this->elementRegExp('class="error"'), $result, $message);

        $text = $text->addClass('large');

        $result = $text->render();
        $message = 'large class should be added to the class attribute';
        $this->assertMatchesRegularExpression($this->elementRegExp('class="error large"'), $result, $message);

        $text = $text->removeClass('error');

        $result = $text->render();
        $message = 'error class should be removed from the class attribute';
        $this->assertMatchesRegularExpression($this->elementRegExp('class="large"'), $result, $message);

        $text = $text->removeClass('large');

        $result = $text->render();
        $message = 'class attribute should be removed';
        $this->assertDoesNotMatchRegularExpression($this->elementRegExp('class'), $result, $message);
    }

    public function test_can_add_attributes_through_magic_methods(): void
    {
        $text = $this->newTestSubjectInstance('email');
        $result = $text->maxlength('5')->render();

        $message = 'maxlength attribute should be set through magic method';
        $this->assertMatchesRegularExpression($this->elementRegExp('maxlength="5"'), $result, $message);
    }

    public function test_can_add_attributes_through_magic_methods_with_optional_parameter(): void
    {
        $text = $this->newTestSubjectInstance('cow');
        $result = $text->moo()->render();

        $message = 'moo attribute should be set through magic method without parameter';
        $this->assertMatchesRegularExpression($this->elementRegExp('moo="moo"'), $result, $message);
    }
}
