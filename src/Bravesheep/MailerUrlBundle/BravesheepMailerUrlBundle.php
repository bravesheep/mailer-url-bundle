<?php

namespace Bravesheep\MailerUrlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BravesheepMailerUrlBundle extends Bundle
{
    /**
     * @var null|BravesheepMailerUrlExtension
     */
    protected $extension;

    /**
     * @return BravesheepMailerUrlExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new BravesheepMailerUrlExtension();
        }
        return $this->extension;
    }
}
