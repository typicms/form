<?php

namespace TypiCMS\Form;

use TypiCMS\Form\Binding\BoundData;
use TypiCMS\Form\Elements\Button;
use TypiCMS\Form\Elements\Checkbox;
use TypiCMS\Form\Elements\Date;
use TypiCMS\Form\Elements\DateTimeLocal;
use TypiCMS\Form\Elements\Email;
use TypiCMS\Form\Elements\File;
use TypiCMS\Form\Elements\FormOpen;
use TypiCMS\Form\Elements\Hidden;
use TypiCMS\Form\Elements\Label;
use TypiCMS\Form\Elements\Number;
use TypiCMS\Form\Elements\Password;
use TypiCMS\Form\Elements\RadioButton;
use TypiCMS\Form\Elements\Select;
use TypiCMS\Form\Elements\Text;
use TypiCMS\Form\Elements\TextArea;
use TypiCMS\Form\ErrorStore\ErrorStoreInterface;
use TypiCMS\Form\OldInput\OldInputInterface;

class FormBuilder
{
    protected ?OldInputInterface $oldInput = null;

    protected ?ErrorStoreInterface $errorStore = null;

    protected ?string $csrfToken = null;

    protected ?BoundData $boundData = null;

    public function setOldInputProvider(OldInputInterface $oldInputProvider): void
    {
        $this->oldInput = $oldInputProvider;
    }

    public function setErrorStore(ErrorStoreInterface $errorStore): void
    {
        $this->errorStore = $errorStore;
    }

    public function setToken(?string $token): void
    {
        $this->csrfToken = $token;
    }

    public function open(): FormOpen
    {
        $open = new FormOpen;

        if ($this->hasToken()) {
            $open->token($this->csrfToken);
        }

        return $open;
    }

    protected function hasToken(): bool
    {
        return $this->csrfToken !== null;
    }

    public function close(): string
    {
        $this->unbindData();

        return '</form>';
    }

    public function text(string $name): Text
    {
        $text = new Text($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $text->value($value);
        }

        return $text;
    }

    public function number(string $name): Number
    {
        $number = new Number($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $number->value($value);
        }

        return $number;
    }

    public function date(string $name): Date
    {
        $date = new Date($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    public function dateTimeLocal(string $name): DateTimeLocal
    {
        $date = new DateTimeLocal($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $date->value($value);
        }

        return $date;
    }

    public function email(string $name): Email
    {
        $email = new Email($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $email->value($value);
        }

        return $email;
    }

    public function hidden(string $name): Hidden
    {
        $hidden = new Hidden($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $hidden->value($value);
        }

        return $hidden;
    }

    public function textarea(string $name): TextArea
    {
        $textarea = new TextArea($name);

        if (! is_null($value = $this->getValueFor($name))) {
            $textarea->value($value);
        }

        return $textarea;
    }

    public function password(string $name): Password
    {
        return new Password($name);
    }

    public function checkbox(string $name, int|string $value = 1): Checkbox
    {
        $checkbox = new Checkbox($name, $value);

        $oldValue = $this->getValueFor($name);
        $checkbox->setOldValue($oldValue);

        return $checkbox;
    }

    public function radio(string $name, int|string|null $value = null): RadioButton
    {
        $radio = new RadioButton($name, $value);

        $oldValue = $this->getValueFor($name);
        $radio->setOldValue($oldValue);

        return $radio;
    }

    public function button(string $value, ?string $name = null): Button
    {
        return new Button($value, $name);
    }

    public function reset(string $value = 'Reset'): Button
    {
        $reset = new Button($value);
        $reset->attribute('type', 'reset');

        return $reset;
    }

    public function submit(string $value = 'Submit'): Button
    {
        $submit = new Button($value);
        $submit->attribute('type', 'submit');

        return $submit;
    }

    public function select(string $name, array $options = []): Select
    {
        $select = new Select($name, $options);

        $selected = $this->getValueFor($name);
        $select->select($selected);

        return $select;
    }

    public function label(string $label): Label
    {
        return new Label($label);
    }

    public function file(string $name): File
    {
        return new File($name);
    }

    public function token(): Hidden
    {
        $token = $this->hidden('_token');

        if ($this->csrfToken !== null) {
            $token->value($this->csrfToken);
        }

        return $token;
    }

    public function hasError(string $name): bool
    {
        if (! $this->errorStore instanceof ErrorStoreInterface) {
            return false;
        }

        return $this->errorStore->hasError($name);
    }

    public function getError(string $name, ?string $format = null): ?string
    {
        if (! $this->errorStore instanceof ErrorStoreInterface) {
            return null;
        }

        if (! $this->hasError($name)) {
            return '';
        }

        $message = $this->errorStore->getError($name);

        if ($format) {
            return str_replace(':message', $message, $format);
        }

        return $message;
    }

    public function bind(mixed $data): void
    {
        $this->boundData = new BoundData($data);
    }

    public function getValueFor(string $name)
    {
        if ($this->hasOldInput()) {
            return $this->getOldInput($name);
        }

        if ($this->hasBoundData()) {
            return $this->getBoundValue($name, null);
        }
    }

    protected function hasOldInput(): bool
    {
        if (! $this->oldInput instanceof OldInputInterface) {
            return false;
        }

        return $this->oldInput->hasOldInput();
    }

    protected function getOldInput(string $name): mixed
    {
        return $this->oldInput->getOldInput($name);
    }

    protected function hasBoundData(): bool
    {
        return $this->boundData instanceof BoundData;
    }

    protected function getBoundValue(string $name, ?string $default): mixed
    {
        return $this->boundData->get($name, $default);
    }

    protected function unbindData(): void
    {
        $this->boundData = null;
    }

    public function selectMonth(string $name): Select
    {
        $options = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        return $this->select($name, $options);
    }
}
