<?php

namespace Spec\TrustFully\Api;

use PhpSpec\ObjectBehavior;
use TrustFully\Api\ApiInterface;
use TrustFully\Api\ContactInterface;
use TrustFully\ClientInterface;

class ContactSpec extends ObjectBehavior
{
    public function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    public function it_is_an_api()
    {
        $this->shouldImplement(ApiInterface::class);
        $this->shouldImplement(ContactInterface::class);
    }
}
