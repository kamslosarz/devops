<?php

namespace tests\Service\Cookie;

use Application\Service\Cookie\Cookie;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{
    public function testShouldReadCookies()
    {
        $_COOKIE['test'] = 'test';
        $cookie = new Cookie();
        $this->assertEquals('test', $cookie->get('test'));
    }

    public function testShouldSaveCookies()
    {
        $_COOKIE = [];
        $cookie = new Cookie();
        $cookie->set('test', 123123);
        $cookie->save();
        $this->assertEquals(123123, $_COOKIE['test']);
    }

    public function testShouldClearCookies()
    {
        $_COOKIE['test'] = 'test123';
        $cookie = new Cookie();
        $cookie->clear();
        $this->assertEmpty($_COOKIE);
    }
}