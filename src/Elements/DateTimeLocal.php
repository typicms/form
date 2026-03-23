<?php

namespace TypiCMS\Form\Elements;

use DateTime;

class DateTimeLocal extends Text
{
    protected array $attributes = [
        'type' => 'datetime-local',
    ];

    public function value(mixed $value): Input
    {
        if ($value instanceof DateTime) {
            $value = $value->format('Y-m-d\TH:i');
        }

        return parent::value($value);
    }

    public function defaultValue(mixed $value): self
    {
        if (! $this->hasValue()) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d\TH:i');
            }

            $this->setValue($value);
        }

        return $this;
    }
}
