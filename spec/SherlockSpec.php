<?php

namespace spec\Laravelista\Sherlock;

use Laravelista\Sherlock\Sherlock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SherlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Sherlock::class);
    }
}
