<?php

namespace TypiCMS\Form\Elements;

class Select extends FormControl
{
    protected array $options;

    protected mixed $selected = null;

    public function __construct(string $name, array $options = [])
    {
        $this->setName($name);
        $this->setOptions($options);
    }

    public function select(mixed $option): self
    {
        $this->selected = $option;

        return $this;
    }

    protected function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function options(array $options): self
    {
        $this->setOptions($options);

        return $this;
    }

    public function render(): string
    {
        return implode('', [
            sprintf('<select%s>', $this->renderAttributes()),
            $this->renderOptions(),
            '</select>',
        ]);
    }

    protected function renderOptions(): string
    {
        [$values, $labels] = $this->splitKeysAndValues($this->options);

        $tags = array_map(function (string $value, $label): string {
            if (is_array($label)) {
                return $this->renderOptGroup($value, $label);
            }

            return $this->renderOption($value, $label);
        }, $values, $labels);

        return implode('', $tags);
    }

    protected function renderOptGroup(string $label, array $options): string
    {
        [$values, $labels] = $this->splitKeysAndValues($options);

        $options = array_map(fn (string $value, string $label): string => $this->renderOption($value, $label), $values, $labels);

        return implode('', [
            sprintf('<optgroup label="%s">', $label),
            implode('', $options),
            '</optgroup>',
        ]);
    }

    protected function renderOption(string $value, string $label): string
    {
        return vsprintf('<option value="%s"%s>%s</option>', [
            $this->escape($value),
            $this->isSelected($value) ? ' selected' : '',
            $this->escape($label),
        ]);
    }

    protected function isSelected(string $value): bool
    {
        return in_array($value, (array) $this->selected);
    }

    public function addOption(string $value, string $label): self
    {
        $this->options[$value] = $label;

        return $this;
    }

    public function defaultValue(string|array $value): self
    {
        if ($this->selected !== null) {
            return $this;
        }

        $this->select($value);

        return $this;
    }

    public function multiple(): self
    {
        $name = $this->attributes['name'];
        if (mb_substr((string) $name, -2) !== '[]') {
            $name .= '[]';
        }

        $this->setName($name);
        $this->setAttribute('multiple', 'multiple');

        return $this;
    }
}
