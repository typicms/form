<?php

namespace TypiCMS\Form\Elements;

class Checkbox extends Input
{
    protected array $attributes = [
        'type' => 'checkbox',
    ];

    protected ?bool $checked = null;

    protected mixed $oldValue = null;

    public function __construct(string $name, mixed $value = 1)
    {
        parent::__construct($name);

        $this->setValue($value);
    }

    public function setOldValue(mixed $oldValue): void
    {
        $this->oldValue = $oldValue;
    }

    public function unsetOldValue(): void
    {
        $this->oldValue = null;
    }

    public function defaultToChecked(): self
    {
        if ($this->checked === null && is_null($this->oldValue)) {
            $this->check();
        }

        return $this;
    }

    public function defaultToUnchecked(): self
    {
        if ($this->checked === null && is_null($this->oldValue)) {
            $this->uncheck();
        }

        return $this;
    }

    public function defaultCheckedState(bool $state): self
    {
        $state ? $this->defaultToChecked() : $this->defaultToUnchecked();

        return $this;
    }

    public function check(): self
    {
        $this->unsetOldValue();
        $this->setChecked(true);

        return $this;
    }

    public function uncheck(): self
    {
        $this->unsetOldValue();
        $this->setChecked(false);

        return $this;
    }

    protected function setChecked(bool $checked = true): void
    {
        $this->checked = $checked;
        $this->removeAttribute('checked');

        if ($checked) {
            $this->setAttribute('checked', 'checked');
        }
    }

    protected function checkBinding(): ?Checkbox
    {
        $currentValue = $this->getAttribute('value');

        $oldValue = $this->oldValue;
        $oldValue = is_array($oldValue) ? $oldValue : [$oldValue];
        $oldValue = array_map(strval(...), $oldValue);

        if (in_array($currentValue, $oldValue)) {
            return $this->check();
        }

        return null;
    }

    public function render(): string
    {
        $this->checkBinding();

        return parent::render();
    }
}
