<?php

namespace spec\Bravesheep\MailerUrlBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MailerUrlResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bravesheep\MailerUrlBundle\MailerUrlResolver');
    }

    function it_should_resolve_a_basic_smtp_url()
    {
        $result = $this->resolve('smtp://user:pass@localhost:1234');
        $result['transport']->shouldBe('smtp');
        $result['user']->shouldBe('user');
        $result['password']->shouldBe('pass');
        $result['host']->shouldBe('localhost');
        $result['port']->shouldBe(1234);
        $result['encryption']->shouldBe(null);
        $result['auth_mode']->shouldBe(null);
    }

    function it_should_handle_urls_with_encryption()
    {
        $result = $this->resolve('smtp+tls://username:password@myhost');
        $result['transport']->shouldBe('smtp');
        $result['user']->shouldBe('username');
        $result['password']->shouldBe('password');
        $result['host']->shouldBe('myhost');
        $result['port']->shouldBe(false);
        $result['encryption']->shouldBe('tls');
        $result['auth_mode']->shouldBe(null);
    }

    function it_should_handle_emailaddress_usernames()
    {
        $result = $this->resolve('smtp+tls://user@example.com:password@host');

        $result['transport']->shouldBe('smtp');
        $result['user']->shouldBe('user@example.com');
        $result['password']->shouldBe('password');
        $result['host']->shouldBe('host');
        $result['port']->shouldBe(false);
        $result['encryption']->shouldBe('tls');
        $result['auth_mode']->shouldBe(null);
    }

    function it_should_not_override_parameters_with_query_parameters()
    {
        $result = $this->resolve('smtp+tls://user:pass@host:1234/?encryption=ssl&auth_mode=plain&password=overwritten');
        $result['transport']->shouldNotBe('ssl');
        $result['auth_mode']->shouldBe('plain');
        $result['password']->shouldNotBe('overwritten');
    }

    function it_should_not_work_with_invalid_auth_modes()
    {
        $this->shouldThrow('LogicException')->duringResolve('smtp://myhost:1234/?auth_mode=invalid_mode');
    }

    function it_should_not_work_with_invalid_encryption()
    {
        $this->shouldThrow('LogicException')->duringResolve('smtp+invalid://localhost');
        $this->shouldThrow('LogicException')->duringResolve('invalid+smtp://localhost');
    }

    function it_should_not_work_with_invalid_urls()
    {
        $this->shouldThrow('LogicException')->duringResolve('this_is_not_a_url');
    }

    function it_should_not_work_with_invalid_schemes()
    {
        $this->shouldThrow('LogicException')->duringResolve('http://www.example.com/');
    }

    function it_should_correctly_resolve_a_gmail_url()
    {
        $result = $this->resolve('gmail://user@gmail.com:mypassword');
        $result['user']->shouldBe('user@gmail.com');
        $result['password']->shouldBe('mypassword');
        $result['transport']->shouldBe('gmail');
    }

    function it_should_correctly_resolve_a_sendmail_url()
    {
        $result = $this->resolve('sendmail://');
        $result['transport']->shouldBe('sendmail');

        $result = $this->resolve('sendmail');
        $result['transport']->shouldBe('sendmail');
    }

    function it_should_correctly_resolve_a_mail_url()
    {
        $result = $this->resolve('mail://');
        $result['transport']->shouldBe('mail');

        $result = $this->resolve('mail');
        $result['transport']->shouldBe('mail');
    }
}
