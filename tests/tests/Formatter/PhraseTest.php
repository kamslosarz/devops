<?php

namespace tests\Formatter;

use Application\ParameterHolder\Formatter\Phrase;
use PHPUnit\Framework\TestCase;

class PhraseTest extends TestCase
{
    public function testShouldApplyVariables()
    {
        $phrase = new Phrase('This is test phrase with variables %test% %second% %third%');
        $this->assertEquals($phrase, 'This is test phrase with variables %test% %second% %third%');

        $phrase->setVariables([
            'test' => '"TEST VARIBLE"',
            'second' => '"SOME TEXT"',
            'third' => '"MORE VARIABLES THAT SHOULD BE APPLIED"',
        ]);
        $this->assertEquals('This is test phrase with variables "TEST VARIBLE" "SOME TEXT" "MORE VARIABLES THAT SHOULD BE APPLIED"', $phrase->__toString());

        $phrase->setVariables(['third' => '"test123"']);
        $this->assertEquals('This is test phrase with variables %test% %second% "test123"', $phrase->__toString());
    }
}