<?php

namespace TypiCMS\Form\Tests;

trait TextSubclassContractTest
{
    use InputContractTest;

    public function test_default_value(): void
    {
        $text = $this->newTestSubjectInstance('email');

        $expected = '<input type="'.$this->getTestSubjectType().'" name="email" value="example@example.com">';
        $result = $text->defaultValue('example@example.com')->render();
        $this->assertEquals($expected, $result);

        $text = $this->newTestSubjectInstance('email');

        $expected = '<input type="'.$this->getTestSubjectType().'" name="email" value="test@test.com">';
        $result = $text->value('test@test.com')->defaultValue('example@example.com')->render();
        $this->assertEquals($expected, $result);

        $text = $this->newTestSubjectInstance('email');

        $expected = '<input type="'.$this->getTestSubjectType().'" name="email" value="test@test.com">';
        $result = $text->defaultValue('example@example.com')->value('test@test.com')->render();
        $this->assertEquals($expected, $result);
    }
}
