<?php

namespace League\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Provider\HisinoneResourceOwner;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class HisinoneResourceOwnerTest extends TestCase
{
    public function testUrlIsNullWithoutDomainOrNickname(): void
    {
        $user = new HisinoneResourceOwner();

        $url = $user->getUrl();

        $this->assertNull($url);
    }

    public function testUrlIsDomainWithoutNickname(): void
    {
        $domain = uniqid();
        $user = new HisinoneResourceOwner();
        $user->setDomain($domain);

        $url = $user->getUrl();

        $this->assertEquals($domain, $url);
    }

    public function testUrlIsNicknameWithoutDomain(): void
    {
        $nickname = uniqid();
        $user = new HisinoneResourceOwner(['login' => $nickname, 'sub' => $nickname ]);

        $url = $user->getUrl();

        $this->assertEquals($nickname, $url);
    }
}
