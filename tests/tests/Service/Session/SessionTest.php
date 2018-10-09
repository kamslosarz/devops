<?php

namespace tests\Service\Session;

use Application\Service\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testShouldManageSession()
    {
        $session = new Session();
        $session->set('second', 2);
        $this->assertEquals(2, $session->get('second'));
        $session->save();
        $this->assertEquals(['second' => 2], $_SESSION);
        $session->set('third', 3);
        $session->clear('third');
        $this->assertNull($session->get('third'));
        $session->clear();
        $this->assertEmpty($_SESSION);
    }

    public function testShouldInitializeSession()
    {
        $session = new Session();
        $this->assertNotNull(session_id());
    }
}