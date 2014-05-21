<?php

namespace Bravesheep\MailerUrlBundle;

use Symfony\Component\Form\Exception\LogicException;

class MailerUrlResolver
{
    private static $valid_schemes = [
        'smtp',
        'mail',
        'sendmail',
        'gmail'
    ];

    private static $valid_encryption = ['ssl', 'tls'];

    private static $valid_auth_modes = ['plain', 'login', 'cram-md5'];

    /**
     * Retrieve mailer options for the given url.
     * @param string $url
     * @return array
     */
    public function resolve($url)
    {
        $parts = parse_url($url);
        $parameters = [];
        $parameters['transport'] = isset($parts['scheme']) ? $parts['scheme'] : null;
        $parameters['encryption'] = null;

        // check for schemes such as 'ssl+smtp' and 'smtp+tls'
        if (strpos($parameters['transport'], '+') !== false) {
            $transports = explode('+', $parameters['transport']);
            if (count($transports) !== 2) {
                throw new \LogicException("Invalid transport in mailer url '$url'.");
            }

            if (in_array($transports[0], self::$valid_schemes)) {
                $parameters['transport'] = $transports[0];
                $parameters['encryption'] = $transports[1];
            } else {
                $parameters['transport'] = $transports[1];
                $parameters['encryption'] = $transports[0];
            }
        }

        $parameters['auth_mode'] = null;
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);

            // don't overwrite transport
            if (isset($query['transport'])) {
                unset($query['transport']);
            }

            // don't overwrite encryption if set
            if (isset($query['encryption']) && $parameters['encryption'] !== null) {
                unset($query['encryption']);
            }
            $parameters = array_merge($parameters, $query);
        }

        // extra parameters
        $parameters['host'] = isset($parts['host']) ? $parts['host'] : null;
        $parameters['port'] = isset($parts['port']) ? $parts['port'] : false;
        $parameters['user'] = isset($parts['user']) ? $parts['user'] : null;
        $parameters['password'] = isset($parts['pass']) ? $parts['pass'] : null;

        // some validation
        if ($parameters['transport'] !== null && !in_array($parameters['transport'], self::$valid_schemes)) {
            throw new \LogicException("Invalid transport scheme '{$parameters['transport']}'.");
        }

        if ($parameters['encryption'] !== null && !in_array($parameters['encryption'], self::$valid_encryption)) {
            throw new \LogicException("Invalid encryption method '{$parameters['encryption']}'.");
        }

        if ($parameters['auth_mode'] !== null && !in_array($parameters['auth_mode'], self::$valid_auth_modes)) {
            throw new \LogicException("Invalid auth_mode '{$parameters['auth_mode']}'.");
        }

        // some extra checks for specific cases
        if ($parameters['transport'] === null) {
            if ($url === 'sendmail://' || $url === 'sendmail') {
                $parameters['transport'] = 'sendmail';
            } else if ($url === 'mail://' || $url === 'mail') {
                $parameters['transport'] = 'mail';
            } else if (strpos($url, 'gmail://') === 0) {
                $userpass = substr($url, 8);
                $userpassparts = explode(':', $userpass, 2);
                if (count($userpassparts) === 2) {
                    $parameters['transport'] = 'gmail';
                    $parameters['user'] = $userpassparts[0];
                    $parameters['password'] = $userpassparts[1];
                }
            } else {
                throw new \LogicException("Invalid url specified '{$url}'");
            }
        }

        // disable transport if requested
        if ($parameters['transport'] === 'none' || $parameters['transport'] === 'null') {
            $parameters['transport'] = null;
        }

        return $parameters;
    }
} 
