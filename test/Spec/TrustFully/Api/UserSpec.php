<?php

namespace Spec\TrustFully\Api;

use PhpSpec\ObjectBehavior;
use TrustFully\Api\ApiInterface;
use TrustFully\Api\UserInterface;
use TrustFully\ClientInterface;

class UserSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_an_api()
    {
        $this->shouldImplement(ApiInterface::class);
        $this->shouldImplement(UserInterface::class);
    }
}
