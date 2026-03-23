<?php

namespace TypiCMS\Form\Elements;

class FormOpen extends Element
{
    protected array $attributes = [
        'method' => 'POST',
        'action' => '',
    ];

    protected ?Hidden $token = null;

    protected ?Hidden $hiddenMethod = null;

    public function render(): string
    {
        $tags = [sprintf('<form%s>', $this->renderAttributes())];

        if ($this->hasToken() && ($this->attributes['method'] !== 'GET')) {
            $tags[] = $this->token->render();
        }

        if ($this->hasHiddenMethod()) {
            $tags[] = $this->hiddenMethod->render();
        }

        return implode('', $tags);
    }

    protected function hasToken(): bool
    {
        return $this->token instanceof Hidden;
    }

    protected function hasHiddenMethod(): bool
    {
        return $this->hiddenMethod instanceof Hidden;
    }

    public function post(): self
    {
        $this->setMethod('POST');

        return $this;
    }

    public function get(): self
    {
        $this->setMethod('GET');

        return $this;
    }

    public function put(): self
    {
        return $this->setHiddenMethod('PUT');
    }

    public function patch(): self
    {
        return $this->setHiddenMethod('PATCH');
    }

    public function delete(): self
    {
        return $this->setHiddenMethod('DELETE');
    }

    public function token(string $token): self
    {
        $this->token = new Hidden('_token');
        $this->token->value($token);

        return $this;
    }

    protected function setHiddenMethod(string $method): self
    {
        $this->setMethod('POST');
        $this->hiddenMethod = new Hidden('_method');
        $this->hiddenMethod->value($method);

        return $this;
    }

    public function setMethod(string $method): self
    {
        $this->setAttribute('method', $method);

        return $this;
    }

    public function action(string $action): self
    {
        $this->setAttribute('action', $action);

        return $this;
    }

    public function encodingType(string $type): self
    {
        $this->setAttribute('enctype', $type);

        return $this;
    }

    public function multipart(): self
    {
        return $this->encodingType('multipart/form-data');
    }
}
