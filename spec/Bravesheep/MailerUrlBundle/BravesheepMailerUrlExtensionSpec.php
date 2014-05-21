<?php

namespace spec\Bravesheep\MailerUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BravesheepMailerUrlExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\MailerUrlBundle\BravesheepMailerUrlExtension');
    }

    function it_should_not_change_parameters_if_there_are_no_urls(ContainerBuilder $container)
    {
        $container->setParameter(Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->load([], $container);
    }

    function it_should_set_smtp_parameters_correctly(ContainerBuilder $container)
    {
        $container->setParameter('mailer_transport', 'smtp')->shouldBeCalled();
        $container->setParameter('mailer_host', 'localhost')->shouldBeCalled();
        $container->setParameter('mailer_user', 'user')->shouldBeCalled();
        $container->setParameter('mailer_password', 'pass')->shouldBeCalled();
        $container->setParameter('mailer_port', 1234)->shouldBeCalled();
        $container->setParameter('mailer_encryption', null)->shouldBeCalled();
        $container->setParameter('mailer_auth_mode', null)->shouldBeCalled();

        $config = ['url' => 'smtp://user:pass@localhost:1234', 'prefix' => 'mailer_'];
        $this->load(['bravesheep_mailer_url' => ['urls' => [$config]]], $container);
    }

    function it_should_set_multiple_url_parameters_correctly(ContainerBuilder $container)
    {
        $container->setParameter('mailer_transport', 'smtp')->shouldBeCalled();
        $container->setParameter('mailer_host', 'localhost')->shouldBeCalled();
        $container->setParameter('mailer_user', 'user')->shouldBeCalled();
        $container->setParameter('mailer_password', 'pass')->shouldBeCalled();
        $container->setParameter('mailer_port', 1234)->shouldBeCalled();
        $container->setParameter('mailer_encryption', 'ssl')->shouldBeCalled();
        $container->setParameter('mailer_auth_mode', null)->shouldBeCalled();

        $container->setParameter('gmail_transport', 'gmail')->shouldBeCalled();
        $container->setParameter('gmail_host', null)->shouldBeCalled();
        $container->setParameter('gmail_user', 'user@gmail.com')->shouldBeCalled();
        $container->setParameter('gmail_password', 'password')->shouldBeCalled();
        $container->setParameter('gmail_port', false)->shouldBeCalled();
        $container->setParameter('gmail_encryption', null)->shouldBeCalled();
        $container->setParameter('gmail_auth_mode', null)->shouldBeCalled();

        $first = ['url' => 'smtp+ssl://user:pass@localhost:1234', 'prefix' => 'mailer_'];
        $second = ['url' => 'gmail://user@gmail.com:password', 'prefix' => 'gmail_'];
        $this->load(['bravesheep_mailer_url' => ['urls' => [$first, $second]]], $container);
    }
}
