<?php

namespace TypiCMS\Form\OldInput;

use Illuminate\Session\Store as Session;

class IlluminateOldInputProvider implements OldInputInterface
{
    public function __construct(private readonly Session $session) {}

    public function hasOldInput(): bool
    {
        return (bool) $this->session->get('_old_input');
    }

    public function getOldInput(string $key): mixed
    {
        return $this->session->getOldInput($this->transformKey($key));
    }

    protected function transformKey(string $key): string
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }
}
