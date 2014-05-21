<?php

namespace spec\Bravesheep\MailerUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BravesheepMailerUrlBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\MailerUrlBundle\BravesheepMailerUrlBundle');
    }

    function it_should_return_the_correct_extension()
    {
        $this->getContainerExtension()->shouldHaveType('Bravesheep\\MailerUrlBundle\\BravesheepMailerUrlExtension');
    }
}
