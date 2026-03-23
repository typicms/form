<?php

namespace TypiCMS\Form\Elements;

class Number extends Input
{
    protected array $attributes = [
        'type' => 'number',
    ];

    public function placeholder(string $placeholder): self
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function max(int $max): self
    {
        $this->setAttribute('max', $max);

        return $this;
    }

    public function min(int $min): self
    {
        $this->setAttribute('min', $min);

        return $this;
    }

    public function step(float $step): self
    {
        $this->setAttribute('step', $step);

        return $this;
    }

    public function defaultValue(string $value): self
    {
        if (! $this->hasValue()) {
            $this->setValue($value);
        }

        return $this;
    }

    protected function hasValue(): bool
    {
        return isset($this->attributes['value']);
    }
}
