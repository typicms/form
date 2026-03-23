<?php

namespace TypiCMS\Form\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\Form\Elements\Text;

/**
 * @internal
 *
 * @coversNothing
 */
class TextTest extends TestCase
{
    use TextSubclassContractTest;

    protected function newTestSubjectInstance($name): Text
    {
        return new Text($name);
    }

    protected function getTestSubjectType(): string
    {
        return 'text';
    }
}
